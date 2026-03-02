<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');
        $search   = $request->get('search');

        $query = Recipe::where('user_id', auth()->id());

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $recipes    = $query->orderBy('name')->paginate(30);
        $categories = Recipe::CATEGORIES;

        return view('recipes.index', compact('recipes', 'categories', 'category', 'search'));
    }

    public function create()
    {
        $categories = Recipe::CATEGORIES;
        return view('recipes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:200',
            'category'      => 'nullable|string|max:50',
            'ingredients'   => 'nullable|string',
            'directions'    => 'nullable|string',
            'servings'      => 'nullable|integer|min:1',
            'prep_time_min' => 'nullable|integer|min:0',
            'cook_time_min' => 'nullable|integer|min:0',
            'notes'         => 'nullable|string',
        ]);

        $data['user_id']   = auth()->id();
        $data['is_system'] = false;

        Recipe::create($data);

        return redirect()->route('recipes.index')
            ->with('success', 'Recipe saved.');
    }

    public function edit(Recipe $recipe)
    {
        abort_if($recipe->is_system || $recipe->user_id !== auth()->id(), 403);
        $categories = Recipe::CATEGORIES;
        return view('recipes.edit', compact('recipe', 'categories'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        abort_if($recipe->is_system || $recipe->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'name'          => 'required|string|max:200',
            'category'      => 'nullable|string|max:50',
            'ingredients'   => 'nullable|string',
            'directions'    => 'nullable|string',
            'servings'      => 'nullable|integer|min:1',
            'prep_time_min' => 'nullable|integer|min:0',
            'cook_time_min' => 'nullable|integer|min:0',
            'notes'         => 'nullable|string',
        ]);

        $recipe->update($data);

        return redirect()->route('recipes.index')
            ->with('success', 'Recipe updated.');
    }

    public function show(Recipe $recipe)
    {
        abort_if($recipe->user_id !== auth()->id(), 403);
        return view('recipes.show', compact('recipe'));
    }

    public function destroy(Recipe $recipe)
    {
        abort_if($recipe->is_system || $recipe->user_id !== auth()->id(), 403);
        $recipe->delete();
        return back()->with('success', 'Recipe deleted.');
    }
}
