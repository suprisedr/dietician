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
});
// public pricing page
Route::get('/pricing', function () {
    return view('pricing');
});

require __DIR__.'/auth.php';
