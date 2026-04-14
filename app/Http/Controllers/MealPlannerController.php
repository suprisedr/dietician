<?php

namespace App\Http\Controllers;

use App\Models\MealPlannerWeek;
use App\Models\MealPlannerEntry;
use App\Models\MealItem;
use App\Models\Patient;
use Braunson\FatSecret\FatSecret;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MealPlannerController extends Controller
{
    // ── FatSecret food search (AJAX) ─────────────────────────────────────────
    public function foodSearch(Request $request)
    {
        $q = trim($request->input('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        try {
            $fs       = app(FatSecret::class);
            $raw      = $fs->searchIngredients($q, 0, 25);
            $foods    = $raw['foods']['food'] ?? [];

            // Single result comes back as an associative array, not a list
            if (isset($foods['food_id'])) {
                $foods = [$foods];
            }

            $results = array_map(function ($food) {
                $desc    = $food['food_description'] ?? '';
                // "Per 100g - Calories: 23kcal | Fat: 0.37g | Carbs: 3.63g | Protein: 2.86g"
                // "Per 1 serving (85g) - Calories: ..."
                $kcal    = null;
                $fat     = null;
                $carbs   = null;
                $protein = null;
                $fiber   = null;
                $serving = null;
                if (preg_match('/Calories:\s*([\d.]+)kcal/i',  $desc, $m)) $kcal    = (float) $m[1];
                if (preg_match('/Fat:\s*([\d.]+)g/i',           $desc, $m)) $fat     = (float) $m[1];
                if (preg_match('/Carbs:\s*([\d.]+)g/i',         $desc, $m)) $carbs   = (float) $m[1];
                if (preg_match('/Protein:\s*([\d.]+)g/i',       $desc, $m)) $protein = (float) $m[1];
                if (preg_match('/Fiber:\s*([\d.]+)g/i',         $desc, $m)) $fiber   = (float) $m[1];
                // Parse "Per 100g", "Per 1 cup (240ml)", "Per 1 serving (85g)" etc.
                if (preg_match('/^Per\s+(.+?)\s+-/i', $desc, $m)) $serving = trim($m[1]);

                return [
                    'id'          => 'fs_' . $food['food_id'],
                    'name'        => $food['food_name'],
                    'description' => $desc,
                    'serving'     => $serving,
                    'kcal'        => $kcal,
                    'kj'          => $kcal ? round($kcal * 4.184) : null,
                    'fat'         => $fat,
                    'carbs'       => $carbs,
                    'protein'     => $protein,
                    'fiber'       => $fiber,
                    'source'      => 'fatsecret',
                ];
            }, $foods);

            return response()->json($results);
        } catch (\Throwable $e) {
            \Log::error('FatSecret search error: ' . $e->getMessage());
            return response()->json(['error' => 'Search unavailable'], 500);
        }
    }

    // ── List all planner weeks for the authenticated user ─────────────────────
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));

        $query = MealPlannerWeek::where('user_id', auth()->id())
            ->with(['patient', 'entries'])
            ->orderByDesc('week_start');

        // Filter by patient name when a search term is given
        if ($search !== '') {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $allWeeks = $query->get();

        // Group by patient name, sorted alphabetically
        $grouped = $allWeeks->groupBy(function ($w) {
            return $w->patient ? $w->patient->name : '— No Patient —';
        })->sortKeys();

        // Paginate the patient groups (10 patients per page)
        $perPage     = 10;
        $currentPage = (int) $request->input('page', 1);
        $patientKeys = $grouped->keys();
        $pageKeys    = $patientKeys->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $pagedGrouped = $pageKeys->mapWithKeys(fn($k) => [$k => $grouped[$k]]);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedGrouped,
            $patientKeys->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('meal-planner.index', [
            'pagedGrouped' => $pagedGrouped,
            'paginator'    => $paginator,
            'search'       => $search,
            'totalPatients'=> $patientKeys->count(),
        ]);
    }

    // ── Create a new week ─────────────────────────────────────────────────────
    public function create(Request $request)
    {
        $patients = Patient::where('user_id', auth()->id())
            ->orderBy('name')
            ->get(['id', 'name']);

        // Default to coming Monday
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        return view('meal-planner.create', compact('patients', 'weekStart'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'week_start' => 'required|date',
            'label'      => 'nullable|string|max:100',
        ]);

        // Normalise to Monday
        $data['week_start'] = Carbon::parse($data['week_start'])
            ->startOfWeek(Carbon::MONDAY)
            ->toDateString();
        $data['user_id'] = auth()->id();

        // Ensure the referenced patient belongs to this user
        if ($data['patient_id']) {
            $patient = Patient::where('id', $data['patient_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();
        }

        $week = MealPlannerWeek::firstOrCreate(
            [
                'user_id'    => $data['user_id'],
                'patient_id' => $data['patient_id'] ?? null,
                'week_start' => $data['week_start'],
            ],
            ['label' => $data['label'] ?? null]
        );

        return redirect()->route('meal-planner.show', [$week->patient_id ?? 0, $week])
            ->with('success', 'Meal planner week created.');
    }

    // ── Show / edit a weekly planner ──────────────────────────────────────────
    public function show(string $patient, MealPlannerWeek $mealPlanner)
    {
        abort_if($mealPlanner->user_id !== auth()->id(), 403);
        // $patient segment is the patient id, or 0 for plans with no patient
        $expectedPatient = $mealPlanner->patient_id ?? 0;
        abort_if((int) $patient !== (int) $expectedPatient, 404);

        $mealPlanner->load(['entries.mealItem', 'patient', 'groceryList']);

        $grid = $mealPlanner->grid; // day x slot matrix

        // All meal items grouped by category for the search-and-select UI
        $mealItemsByCategory = MealItem::orderBy('category')->orderBy('name')
            ->get(['id', 'category', 'name', 'energy_kcal', 'energy_kj', 'serving_size', 'cho_g', 'protein_g', 'fat_g', 'fiber_g'])
            ->groupBy('category');

        // ── Meal plan distribution from the patient's exchange template ──────
        // slotDistribution['breakfast'] = [['name'=>'Starchy Foods','qty'=>2,'item_id'=>5], ...]
        // The 'supper' slot in the DB maps to 'dinner' in the planner
        $slotDistribution = array_fill_keys(
            \App\Models\MealPlannerWeek::MEAL_SLOTS, []
        );
        $slotLimits = array_fill_keys(\App\Models\MealPlannerWeek::MEAL_SLOTS, null);

        if ($mealPlanner->patient_id) {
            $patientModel = $mealPlanner->patient;
            if ($patientModel && $patientModel->exchange_template_id) {
                $patientModel->load('exchangeTemplate.items');
                $slotMap = [
                    'breakfast' => 'slot_breakfast',
                    'snack1'    => 'slot_snack1',
                    'lunch'     => 'slot_lunch',
                    'snack2'    => 'slot_snack2',
                    'dinner'    => 'slot_supper',   // supper in DB = dinner in planner
                    'snack3'    => 'slot_snack3',
                ];
                // First pass: sum raw quantities for the limit (0.5 counts as 1 whole item)
                $rawSums = array_fill_keys(\App\Models\MealPlannerWeek::MEAL_SLOTS, 0.0);
                foreach ($patientModel->exchangeTemplate->items as $item) {
                    foreach ($slotMap as $plannerSlot => $dbCol) {
                        $rawSums[$plannerSlot] += (float) ($item->{$dbCol} ?? 0);
                    }
                }
                foreach ($rawSums as $plannerSlot => $sum) {
                    if ($sum > 0) {
                        $slotLimits[$plannerSlot] = (int) ceil($sum);
                    }
                }
                // Second pass: build distribution entries
                foreach ($patientModel->exchangeTemplate->items as $item) {
                    foreach ($slotMap as $plannerSlot => $dbCol) {
                        $qty = (float) ($item->{$dbCol} ?? 0);
                        if ($qty > 0) {
                            for ($i = 0; $i < $qty; $i++) {
                                $slotDistribution[$plannerSlot][] = [
                                    'name'    => $item->name,
                                    'item_id' => $item->id,
                                    'row_idx' => count($slotDistribution[$plannerSlot]),
                                ];
                            }
                        }
                    }
                }
            }
        }

        return view('meal-planner.show', compact(
            'mealPlanner', 'grid', 'mealItemsByCategory', 'slotDistribution', 'slotLimits'
        ));
    }

    // ── Save all entries for a week (mass-save from the grid form) ────────────
    public function saveEntries(Request $request, string $patient, MealPlannerWeek $mealPlanner)
    {
        abort_if($mealPlanner->user_id !== auth()->id(), 403);
        abort_if((int) $patient !== (int) ($mealPlanner->patient_id ?? 0), 404);

        // cells[day][slot] = array of {item_id, text} objects (JSON-encoded per cell)
        $cells = $request->input('cells', []);

        foreach ($cells as $day => $slots) {
            foreach ($slots as $slot => $json) {
                // Always delete existing entries for this cell first
                MealPlannerEntry::where('week_id', $mealPlanner->id)
                    ->where('day_of_week', (int) $day)
                    ->where('meal_slot', $slot)
                    ->delete();

                $items = json_decode($json, true);
                if (!is_array($items)) continue;

                foreach ($items as $order => $item) {
                    $rawId  = $item['id'] ?? null;
                    $text   = trim($item['text'] ?? '');

                    $isFreeText   = $rawId && str_starts_with((string) $rawId, '_free_');
                    $isFatSecret  = $rawId && str_starts_with((string) $rawId, 'fs_');

                    $itemId = null;

                    if ($isFatSecret) {
                        // Auto-create or reuse a MealItem for this FatSecret food
                        $existing = MealItem::where('name', $text)
                            ->where(function ($q) {
                                $q->where('is_system', true)
                                  ->orWhere('created_by', auth()->id());
                            })
                            ->first();

                        if ($existing) {
                            $itemId = $existing->id;
                        } else {
                            $fsData  = $item['fsData'] ?? [];
                            $kcal    = isset($fsData['kcal'])    ? (float) $fsData['kcal']    : null;
                            $fat     = isset($fsData['fat'])     ? (float) $fsData['fat']     : null;
                            $carbs   = isset($fsData['carbs'])   ? (float) $fsData['carbs']   : null;
                            $protein = isset($fsData['protein']) ? (float) $fsData['protein'] : null;
                            $fiber   = isset($fsData['fiber'])   ? (float) $fsData['fiber']   : null;
                            $serving = $fsData['serving'] ?? null;

                            $newItem = MealItem::create([
                                'name'         => $text,
                                'category'     => 'Other',
                                'serving_size' => $serving,
                                'energy_kcal'  => $kcal,
                                'energy_kj'    => $kcal ? round($kcal * 4.184) : null,
                                'fat_g'        => $fat,
                                'cho_g'        => $carbs,
                                'protein_g'    => $protein,
                                'fiber_g'      => $fiber,
                                'is_system'    => false,
                                'created_by'   => auth()->id(),
                            ]);
                            $itemId = $newItem->id;
                        }
                    } elseif (!$isFreeText && $rawId) {
                        $itemId = (int) $rawId;
                        $mi   = MealItem::find($itemId);
                        $text = $mi ? $mi->name : $text;
                    }

                    if ($text === '' && !$itemId) continue;

                    MealPlannerEntry::create([
                        'week_id'           => $mealPlanner->id,
                        'day_of_week'       => (int) $day,
                        'meal_slot'         => $slot,
                        'exchange_category' => $item['exchCat'] ?? null,
                        'sort_order'        => (int) $order,
                        'qty'               => max(1, (int) ($item['qty'] ?? 1)),
                        'meal_text'         => $text,
                        'meal_item_id'      => $itemId,
                    ]);
                }
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Meal plan saved.');
    }

    // ── Delete a week ─────────────────────────────────────────────────────────
    public function destroy(string $patient, MealPlannerWeek $mealPlanner)
    {
        abort_if($mealPlanner->user_id !== auth()->id(), 403);
        abort_if((int) $patient !== (int) ($mealPlanner->patient_id ?? 0), 404);
        $mealPlanner->delete();
        return redirect()->route('meal-planner.index')
            ->with('success', 'Planner week deleted.');
    }

    public function pdf(string $patient, MealPlannerWeek $mealPlanner)
    {
        abort_if($mealPlanner->user_id !== auth()->id(), 403);
        abort_if((int) $patient !== (int) ($mealPlanner->patient_id ?? 0), 404);

        $mealPlanner->load(['entries.mealItem', 'patient']);

        $days      = \App\Models\MealPlannerWeek::DAYS;
        $slots     = \App\Models\MealPlannerWeek::MEAL_SLOTS;
        $slotLabels = \App\Models\MealPlannerWeek::SLOT_LABELS;
        $grid      = $mealPlanner->grid;

        // Compute per-day kJ totals and per-cell kJ totals
        $dayKj  = array_fill(0, 7, 0);
        $cellKj = [];
        foreach (range(0, 6) as $d) {
            foreach ($slots as $slot) {
                $kj = 0;
                foreach ($grid[$d][$slot] as $entry) {
                    $kj += $entry->mealItem?->energy_kj ?? 0;
                }
                $cellKj[$d][$slot] = $kj;
                $dayKj[$d] += $kj;
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('meal-planner.pdf', compact(
            'mealPlanner', 'days', 'slots', 'slotLabels', 'grid', 'dayKj', 'cellKj'
        ))->setPaper('a4', 'landscape');

        $label    = $mealPlanner->label ?: $mealPlanner->week_start->format('Y-m-d');
        $patient  = $mealPlanner->patient;
        $nameSlug = $patient ? \Illuminate\Support\Str::slug($patient->name) : 'plan';
        $filename = 'meal-plan-' . $nameSlug . '-' . \Illuminate\Support\Str::slug($label) . '.pdf';

        return $pdf->download($filename);
    }
}
