<?php

namespace App\Http\Controllers;

use App\Models\MealPlannerWeek;
use App\Models\MealPlannerEntry;
use App\Models\MealItem;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MealPlannerController extends Controller
{
    // ── List all planner weeks for the authenticated user ─────────────────────
    public function index(Request $request)
    {
        $weeks = MealPlannerWeek::where('user_id', auth()->id())
            ->with('patient')
            ->orderByDesc('week_start')
            ->paginate(20);

        $patients = Patient::where('user_id', auth()->id())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('meal-planner.index', compact('weeks', 'patients'));
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

        $mealPlanner->load(['entries.mealItem', 'patient']);

        $grid = $mealPlanner->grid; // day x slot matrix

        // All meal items grouped by category for the search-and-select UI
        $mealItemsByCategory = MealItem::orderBy('category')->orderBy('name')
            ->get(['id', 'category', 'name'])
            ->groupBy('category');

        // ── Meal plan distribution from the patient's exchange template ──────
        // slotDistribution['breakfast'] = [['name'=>'Starchy Foods','qty'=>2,'item_id'=>5], ...]
        // The 'supper' slot in the DB maps to 'dinner' in the planner
        $slotDistribution = array_fill_keys(
            \App\Models\MealPlannerWeek::MEAL_SLOTS, []
        );

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
            'mealPlanner', 'grid', 'mealItemsByCategory', 'slotDistribution'
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
                    $itemId = isset($item['id']) && $item['id'] ? (int) $item['id'] : null;
                    $text   = trim($item['text'] ?? '');

                    if ($itemId) {
                        $mi   = MealItem::find($itemId);
                        $text = $mi ? $mi->name : $text;
                    }

                    if ($text === '' && !$itemId) continue;

                    MealPlannerEntry::create([
                        'week_id'      => $mealPlanner->id,
                        'day_of_week'  => (int) $day,
                        'meal_slot'    => $slot,
                        'sort_order'   => (int) $order,
                        'meal_text'    => $text,
                        'meal_item_id' => $itemId,
                    ]);
                }
            }
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
}
