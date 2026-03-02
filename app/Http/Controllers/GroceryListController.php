<?php

namespace App\Http\Controllers;

use App\Models\GroceryList;
use App\Models\GroceryListItem;
use App\Models\Patient;
use Illuminate\Http\Request;

class GroceryListController extends Controller
{
    public function index()
    {
        $lists = GroceryList::where('user_id', auth()->id())
            ->with(['patient', 'items'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('grocery-lists.index', compact('lists'));
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

    public function show(GroceryList $groceryList)
    {
        abort_if($groceryList->user_id !== auth()->id(), 403);
        $groceryList->load('items');

        $byCategory = [];
        foreach (GroceryList::CATEGORIES as $cat) {
            $byCategory[$cat] = $groceryList->items->where('category', $cat)->values();
        }

        $categories = GroceryList::CATEGORY_LABELS;

        return view('grocery-lists.show', compact('groceryList', 'byCategory', 'categories'));
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
}
