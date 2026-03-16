<?php

namespace App\Http\Controllers;

use App\Models\MealItem;
use Braunson\FatSecret\FatSecret;
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

    /**
     * AJAX: search DB first, fall back to FatSecret if < 3 results found.
     * Returns JSON: { db: [...], fs: [...] }
     */
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if ($q === '') {
            return response()->json(['db' => [], 'fs' => []]);
        }

        // ── 1. Search own DB ──────────────────────────────────────────────
        $dbItems = MealItem::visibleTo(auth()->id())
            ->where('name', 'like', "%{$q}%")
            ->orderByRaw('is_system ASC') // custom first
            ->orderBy('name')
            ->limit(30)
            ->get()
            ->map(fn($i) => [
                'id'      => $i->id,
                'name'    => $i->name,
                'serving' => $i->serving_size,
                'kcal'    => $i->energy_kcal ? round($i->energy_kcal) : null,
                'kj'      => $i->energy_kj  ? round($i->energy_kj)   : null,
                'fat'     => $i->fat_g,
                'carbs'   => $i->cho_g,
                'protein' => $i->protein_g,
                'fiber'   => $i->fiber_g,
                'category'=> $i->category,
                'source'  => $i->is_system ? 'system' : 'custom',
            ])->values()->all();

        // ── 2. Fall back to FatSecret if DB returned fewer than 3 hits ────
        $fsItems = [];
        if (count($dbItems) < 3) {
            try {
                $fs   = app(FatSecret::class);
                $raw  = $fs->searchIngredients($q, 0, 20);
                $foods = $raw['foods']['food'] ?? [];
                if (isset($foods['food_id'])) $foods = [$foods];

                // Collect names already in DB (case-insensitive) to avoid duplicates
                $existingNames = array_map('strtolower', array_column($dbItems, 'name'));

                foreach ($foods as $food) {
                    if (in_array(strtolower($food['food_name']), $existingNames)) continue;

                    $desc    = $food['food_description'] ?? '';
                    $kcal = $fat = $carbs = $protein = $fiber = $serving = null;
                    if (preg_match('/Calories:\s*([\d.]+)kcal/i', $desc, $m)) $kcal    = (float)$m[1];
                    if (preg_match('/Fat:\s*([\d.]+)g/i',          $desc, $m)) $fat     = (float)$m[1];
                    if (preg_match('/Carbs:\s*([\d.]+)g/i',        $desc, $m)) $carbs   = (float)$m[1];
                    if (preg_match('/Protein:\s*([\d.]+)g/i',      $desc, $m)) $protein = (float)$m[1];
                    if (preg_match('/Fiber:\s*([\d.]+)g/i',        $desc, $m)) $fiber   = (float)$m[1];
                    if (preg_match('/^Per\s+(.+?)\s+-/i',          $desc, $m)) $serving = trim($m[1]);

                    $fsItems[] = [
                        'id'      => 'fs_' . $food['food_id'],
                        'name'    => $food['food_name'],
                        'serving' => $serving,
                        'kcal'    => $kcal,
                        'kj'      => $kcal ? round($kcal * 4.184) : null,
                        'fat'     => $fat,
                        'carbs'   => $carbs,
                        'protein' => $protein,
                        'fiber'   => $fiber,
                        'source'  => 'fatsecret',
                    ];
                }
            } catch (\Throwable $e) {
                \Log::error('MealItems FatSecret search error: ' . $e->getMessage());
            }
        }

        return response()->json(['db' => $dbItems, 'fs' => $fsItems]);
    }

    /**
     * AJAX POST: save a FatSecret food into the user's meal-item library.
     */
    public function importFatSecret(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:200',
            'serving'    => 'nullable|string|max:200',
            'kcal'       => 'nullable|numeric|min:0',
            'kj'         => 'nullable|numeric|min:0',
            'fat'        => 'nullable|numeric|min:0',
            'carbs'      => 'nullable|numeric|min:0',
            'protein'    => 'nullable|numeric|min:0',
            'fiber'      => 'nullable|numeric|min:0',
            'category'   => 'nullable|string|max:100',
        ]);

        // Check if already saved by this user
        $existing = MealItem::where('created_by', auth()->id())
            ->where('name', $data['name'])
            ->first();

        if ($existing) {
            return response()->json(['id' => $existing->id, 'already_existed' => true]);
        }

        $item = MealItem::create([
            'name'         => $data['name'],
            'serving_size' => $data['serving'] ?? null,
            'energy_kcal'  => isset($data['kcal']) ? round($data['kcal'], 1) : null,
            'energy_kj'    => isset($data['kj'])   ? round($data['kj'],   1) : null,
            'fat_g'        => $data['fat']     ?? null,
            'cho_g'        => $data['carbs']   ?? null,
            'protein_g'    => $data['protein'] ?? null,
            'fiber_g'      => $data['fiber']   ?? null,
            'category'     => $data['category'] ?? 'Protein',
            'is_system'    => false,
            'created_by'   => auth()->id(),
        ]);

        return response()->json(['id' => $item->id, 'already_existed' => false]);
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

    /**
     * Bulk action: delete or re-categorise selected items.
     * Only items owned by the authenticated user may be modified.
     */
    public function bulkAction(Request $request)
    {
        $data = $request->validate([
            'action'   => 'required|in:delete,recategorise',
            'ids'      => 'required|array|min:1',
            'ids.*'    => 'integer',
            'category' => 'required_if:action,recategorise|nullable|string|max:100',
        ]);

        // Scope to items the user can actually modify
        $items = MealItem::visibleTo(auth()->id())
            ->whereIn('id', $data['ids'])
            ->get();

        $count = $items->count();

        if ($data['action'] === 'delete') {
            foreach ($items as $item) {
                $item->delete();
            }
            return redirect()->route('meal-items.index')
                ->with('success', $count . ' item' . ($count === 1 ? '' : 's') . ' deleted.');
        }

        // recategorise
        foreach ($items as $item) {
            $item->update(['category' => $data['category']]);
        }
        return redirect()->route('meal-items.index')
            ->with('success', $count . ' item' . ($count === 1 ? '' : 's') . ' moved to "' . $data['category'] . '".');
    }
}
