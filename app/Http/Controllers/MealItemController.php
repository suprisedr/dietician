<?php

namespace App\Http\Controllers;

use App\Models\MealItem;
use Illuminate\Http\Request;

class MealItemController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');
        $search   = $request->input('search');

        $items = MealItem::visibleTo(auth()->id())
            ->when($category, fn($q) => $q->where('category', $category))
            ->when($search,   fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $grouped    = $items->groupBy('category');
        $categories = MealItem::categories();
        $total      = $items->count();

        return view('meal-items.index', compact('grouped', 'categories', 'category', 'search', 'total'));
    }

    public function create()
    {
        $categories = MealItem::categories();
        return view('meal-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category'           => 'required|string|max:100',
            'name'               => 'required|string|max:200',
            'serving_size'       => 'nullable|string|max:200',
            'cho_g'              => 'nullable|numeric|min:0',
            'protein_g'          => 'nullable|numeric|min:0',
            'fat_g'              => 'nullable|numeric|min:0',
            'fiber_g'            => 'nullable|numeric|min:0',
            'energy_kj'          => 'nullable|numeric|min:0',
            'energy_kcal'        => 'nullable|numeric|min:0',
            'fruit_veg_portions' => 'nullable|integer|min:0|max:5',
        ]);

        $c = floatval($data['cho_g']     ?? 0);
        $p = floatval($data['protein_g'] ?? 0);
        $f = floatval($data['fat_g']     ?? 0);

        if (empty($data['energy_kj']) && ($c + $p + $f > 0)) {
            $data['energy_kj']   = round(($c * 17) + ($p * 17) + ($f * 37), 1);
        }
        if (empty($data['energy_kcal']) && ($c + $p + $f > 0)) {
            $data['energy_kcal'] = round(($c * 4) + ($p * 4) + ($f * 9), 1);
        }

        $data['is_system']  = false;
        $data['created_by'] = auth()->id();

        MealItem::create($data);

        return redirect()->route('meal-items.index')
            ->with('success', '"' . $data['name'] . '" added successfully.');
    }

    public function edit(MealItem $mealItem)
    {
        $categories = MealItem::categories();
        return view('meal-items.edit', compact('mealItem', 'categories'));
    }

    public function update(Request $request, MealItem $mealItem)
    {
        $data = $request->validate([
            'category'           => 'required|string|max:100',
            'name'               => 'required|string|max:200',
            'serving_size'       => 'nullable|string|max:200',
            'cho_g'              => 'nullable|numeric|min:0',
            'protein_g'          => 'nullable|numeric|min:0',
            'fat_g'              => 'nullable|numeric|min:0',
            'fiber_g'            => 'nullable|numeric|min:0',
            'energy_kj'          => 'nullable|numeric|min:0',
            'energy_kcal'        => 'nullable|numeric|min:0',
            'fruit_veg_portions' => 'nullable|integer|min:0|max:5',
        ]);

        $c = floatval($data['cho_g']     ?? 0);
        $p = floatval($data['protein_g'] ?? 0);
        $f = floatval($data['fat_g']     ?? 0);

        if (empty($data['energy_kj']) && ($c + $p + $f > 0)) {
            $data['energy_kj']   = round(($c * 17) + ($p * 17) + ($f * 37), 1);
        }
        if (empty($data['energy_kcal']) && ($c + $p + $f > 0)) {
            $data['energy_kcal'] = round(($c * 4) + ($p * 4) + ($f * 9), 1);
        }

        $mealItem->update($data);

        return redirect()->route('meal-items.index')
            ->with('success', '"' . $mealItem->name . '" updated.');
    }

    public function destroy(MealItem $mealItem)
    {
        $name = $mealItem->name;
        $mealItem->delete();

        return redirect()->route('meal-items.index')
            ->with('success', '"' . $name . '" deleted.');
    }
}
