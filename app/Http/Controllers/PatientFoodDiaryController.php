<?php

namespace App\Http\Controllers;

use App\Models\FoodDiary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientFoodDiaryController extends Controller
{
    public function show(string $token)
    {
        $diary = FoodDiary::where('patient_token', $token)->firstOrFail();

        // Already submitted — show confirmation
        if ($diary->submitted_at) {
            return view('food-diary.patient-submitted', compact('diary'));
        }

        $diary->load('patient');
        return view('food-diary.patient-form', compact('diary', 'token'));
    }

    public function submit(Request $request, string $token)
    {
        $diary = FoodDiary::where('patient_token', $token)->firstOrFail();

        // Prevent re-submission
        if ($diary->submitted_at) {
            return redirect()->route('food-diary.patient-show', $token);
        }

        $data = $request->validate([
            'diary_date'  => 'required|date',
            'breakfast'   => 'nullable|string|max:2000',
            'snack1'      => 'nullable|string|max:2000',
            'lunch'       => 'nullable|string|max:2000',
            'snack2'      => 'nullable|string|max:2000',
            'supper'      => 'nullable|string|max:2000',
            'snack3'      => 'nullable|string|max:2000',
            'rating'      => 'nullable|integer|min:1|max:5',
            'improvement' => 'nullable|string|max:3000',
        ]);

        $diary->update(array_merge($data, ['submitted_at' => now()]));

        return redirect()->route('food-diary.patient-show', $token);
    }
}
