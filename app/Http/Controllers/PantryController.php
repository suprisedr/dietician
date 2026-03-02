<?php

namespace App\Http\Controllers;

use App\Models\PantryItem;
use Illuminate\Http\Request;

class PantryController extends Controller
{
    public function index(Request $request)
    {
        $type   = $request->get('type', 'pantry');
        $items  = PantryItem::where('user_id', auth()->id())
            ->where('storage_type', $type)
            ->orderBy('item')
            ->paginate(50);

        return view('pantry.index', compact('items', 'type'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'storage_type' => 'required|in:pantry,freezer',
            'item'         => 'required|string|max:200',
            'quantity'     => 'nullable|string|max:100',
            'date_added'   => 'nullable|date',
            'expiry_date'  => 'nullable|date',
            'notes'        => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();
        PantryItem::create($data);

        return back()->with('success', ucfirst($data['storage_type']) . ' item added.');
    }

    public function update(Request $request, PantryItem $pantryItem)
    {
        abort_if($pantryItem->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'item'        => 'required|string|max:200',
            'quantity'    => 'nullable|string|max:100',
            'date_added'  => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes'       => 'nullable|string',
        ]);

        $pantryItem->update($data);

        return back()->with('success', 'Item updated.');
    }

    public function destroy(PantryItem $pantryItem)
    {
        abort_if($pantryItem->user_id !== auth()->id(), 403);
        $pantryItem->delete();
        return back()->with('success', 'Item removed.');
    }
}
