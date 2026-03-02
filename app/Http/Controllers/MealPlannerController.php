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

        return redirect()->route('meal-planner.show', $week)
            ->with('success', 'Meal planner week created.');
    }

    // ── Show / edit a weekly planner ──────────────────────────────────────────
    public function show(MealPlannerWeek $mealPlanner)
    {
        abort_if($mealPlanner->user_id !== auth()->id(), 403);

        $mealPlanner->load(['entries.mealItem', 'patient']);

        $grid = $mealPlanner->grid; // day x slot matrix

        // All meal items grouped by category for the search-and-select UI
        $mealItemsByCategory = MealItem::orderBy('category')->orderBy('name')
            ->get(['id', 'category', 'name'])
            ->groupBy('category');

        return view('meal-planner.show', compact('mealPlanner', 'grid', 'mealItemsByCategory'));
    }

    // ── Save all entries for a week (mass-save from the grid form) ────────────
    public function saveEntries(Request $request, MealPlannerWeek $mealPlanner)
    {
        abort_if($mealPlanner->user_id !== auth()->id(), 403);

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
    public function destroy(MealPlannerWeek $mealPlanner)
    {
        abort_if($mealPlanner->user_id !== auth()->id(), 403);
        $mealPlanner->delete();
        return redirect()->route('meal-planner.index')
            ->with('success', 'Planner week deleted.');
    }
}
