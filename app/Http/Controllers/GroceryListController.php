<?php

namespace App\Http\Controllers;

use App\Mail\GroceryListMail;
use App\Models\GroceryList;
use App\Models\GroceryListItem;
use App\Models\MealPlannerWeek;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GroceryListController extends Controller
{
    // ── Category mapping: MealItem category → GroceryList category ───────────
    const MEAL_ITEM_CATEGORY_MAP = [
        'Starch'            => 'bakery',
        'Protein - Animal'  => 'meat',
        'Protein - Plant'   => 'pantry',
        'Fruit'             => 'produce',
        'Vegetable'         => 'produce',
        'Dairy'             => 'dairy',
        'Fat'               => 'pantry',
        'Other/Limit'       => 'pantry',
        'FatSecret'         => 'pantry',
        'Online'            => 'pantry',
    ];

    public function index()
    {
        $lists = GroceryList::where('user_id', auth()->id())
            ->with(['patient', 'items', 'week'])
            ->orderByDesc('created_at')
            ->paginate(20);

        // Weeks that don't yet have a grocery list — for the generate dropdown
        $availableWeeks = \App\Models\MealPlannerWeek::where('user_id', auth()->id())
            ->with('patient')
            ->whereDoesntHave('groceryList')
            ->orderByDesc('week_start')
            ->get();

        return view('grocery-lists.index', compact('lists', 'availableWeeks'));
    }

    public function create()
    {
        $patients   = Patient::where('user_id', auth()->id())->orderBy('name')->get(['id', 'name']);
        $categories = GroceryList::CATEGORIES;
        return view('grocery-lists.create', compact('patients', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'nullable|string|max:100',
            'patient_id' => 'nullable|exists:patients,id',
        ]);

        $data['user_id'] = auth()->id();
        $list = GroceryList::create($data);

        return redirect()->route('grocery-lists.show', $list)
            ->with('success', 'Grocery list created.');
    }

    // ── Generate a grocery list from a MealPlannerWeek ────────────────────────
    public function generateFromPlan(MealPlannerWeek $week)
    {
        abort_if($week->user_id !== auth()->id(), 403);

        $week->load('entries.mealItem', 'patient');

        // If a list already exists for this week, redirect to it
        $existing = GroceryList::where('week_id', $week->id)
            ->where('user_id', auth()->id())
            ->first();
        if ($existing) {
            return redirect()->route('grocery-lists.show', $existing)
                ->with('success', 'Grocery list for this plan already exists.');
        }

        // Build the name from week label / dates
        $weekLabel = $week->label
            ?: 'Week of ' . $week->week_start->format('d M Y');
        $listName  = ($week->patient ? $week->patient->name . ' — ' : '') . $weekLabel;

        $list = GroceryList::create([
            'user_id'    => auth()->id(),
            'patient_id' => $week->patient_id,
            'week_id'    => $week->id,
            'name'       => $listName,
        ]);

        // Collect items from the plan, deduplicating by lower-cased name
        $seen  = [];
        $items = [];

        foreach ($week->entries as $entry) {
            // Resolve the display name: prefer meal_item name, fallback to meal_text
            $name = trim($entry->mealItem?->name ?? $entry->meal_text ?? '');
            if ($name === '') continue;

            $key = strtolower($name);
            if (isset($seen[$key])) continue;
            $seen[$key] = true;

            // Map to grocery category
            $mealCat     = $entry->mealItem?->category ?? '';
            $groceryCat  = self::MEAL_ITEM_CATEGORY_MAP[$mealCat] ?? 'pantry';

            $items[] = [
                'grocery_list_id' => $list->id,
                'category'        => $groceryCat,
                'item'            => $name,
                'checked'         => false,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        if (!empty($items)) {
            GroceryListItem::insert($items);
        }

        return redirect()->route('grocery-lists.show', $list)
            ->with('success', 'Grocery list generated from meal plan (' . count($items) . ' items).');
    }

    public function show(GroceryList $groceryList)
    {
        abort_if($groceryList->user_id !== auth()->id(), 403);
        $groceryList->load(['items', 'patient', 'week']);

        return view('grocery-lists.show', compact('groceryList'));
    }

    public function destroy(GroceryList $groceryList)
    {
        abort_if($groceryList->user_id !== auth()->id(), 403);
        $groceryList->delete();
        return redirect()->route('grocery-lists.index')
            ->with('success', 'Grocery list deleted.');
    }

    // ── Items ─────────────────────────────────────────────────────────────────

    public function addItem(Request $request, GroceryList $groceryList)
    {
        abort_if($groceryList->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'category' => 'required|in:' . implode(',', GroceryList::CATEGORIES),
            'item'     => 'required|string|max:200',
        ]);

        $data['grocery_list_id'] = $groceryList->id;
        GroceryListItem::create($data);

        return back()->with('success', 'Item added.');
    }

    public function toggleItem(GroceryList $groceryList, GroceryListItem $item)
    {
        abort_if($groceryList->user_id !== auth()->id(), 403);
        $item->update(['checked' => !$item->checked]);
        return back();
    }

    public function removeItem(GroceryList $groceryList, GroceryListItem $item)
    {
        abort_if($groceryList->user_id !== auth()->id(), 403);
        $item->delete();
        return back()->with('success', 'Item removed.');
    }

    public function sendEmail(GroceryList $groceryList)
    {
        abort_if($groceryList->user_id !== auth()->id(), 403);

        $groceryList->load(['items', 'patient', 'week']);

        $email = $groceryList->patient?->email;
        abort_if(empty($email), 422, 'This patient has no email address on file.');

        Mail::to($email, $groceryList->patient->name)
            ->send(new GroceryListMail($groceryList));

        return back()->with('success', 'Grocery list emailed to ' . $email . '.');
    }
}
