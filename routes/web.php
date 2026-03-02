<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $patients = \App\Models\Patient::where('user_id', auth()->id())->get();
    return view('dashboard', compact('patients'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('patients', \App\Http\Controllers\PatientController::class);
    Route::patch('patients/{patient}/macronutrients', [\App\Http\Controllers\PatientController::class, 'updateMacronutrients'])->name('patients.macronutrients.update');
    Route::patch('patients/{patient}/exchange-items/{item}/nu', [\App\Http\Controllers\PatientController::class, 'updateExchangeItemNu'])->name('patients.exchange-items.nu');
    Route::post('patients/{patient}/exchange-template', [\App\Http\Controllers\PatientController::class, 'createExchangeTemplate'])->name('patients.exchange-template.create');
    Route::patch('patients/{patient}/meal-plan', [\App\Http\Controllers\PatientController::class, 'saveMealPlan'])->name('patients.meal-plan.save');
    Route::resource('meal-items', \App\Http\Controllers\MealItemController::class)->except(['show']);

    // ── Meal Planner ─────────────────────────────────────────────────────────
    Route::resource('meal-planner', \App\Http\Controllers\MealPlannerController::class)
        ->except(['edit', 'update']);
    Route::patch('meal-planner/{mealPlanner}/entries', [\App\Http\Controllers\MealPlannerController::class, 'saveEntries'])
        ->name('meal-planner.save-entries');

    // ── Recipes ───────────────────────────────────────────────────────────────
    Route::resource('recipes', \App\Http\Controllers\RecipeController::class);

    // ── Pantry / Freezer Inventory ────────────────────────────────────────────
    Route::get('pantry', [\App\Http\Controllers\PantryController::class, 'index'])->name('pantry.index');
    Route::post('pantry', [\App\Http\Controllers\PantryController::class, 'store'])->name('pantry.store');
    Route::patch('pantry/{pantryItem}', [\App\Http\Controllers\PantryController::class, 'update'])->name('pantry.update');
    Route::delete('pantry/{pantryItem}', [\App\Http\Controllers\PantryController::class, 'destroy'])->name('pantry.destroy');

    // ── Grocery Lists ─────────────────────────────────────────────────────────
    Route::resource('grocery-lists', \App\Http\Controllers\GroceryListController::class)
        ->except(['edit', 'update']);
    Route::post('grocery-lists/{groceryList}/items', [\App\Http\Controllers\GroceryListController::class, 'addItem'])
        ->name('grocery-lists.items.add');
    Route::patch('grocery-lists/{groceryList}/items/{item}/toggle', [\App\Http\Controllers\GroceryListController::class, 'toggleItem'])
        ->name('grocery-lists.items.toggle');
    Route::delete('grocery-lists/{groceryList}/items/{item}', [\App\Http\Controllers\GroceryListController::class, 'removeItem'])
        ->name('grocery-lists.items.remove');
});
// public pricing page
Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

require __DIR__.'/auth.php';
