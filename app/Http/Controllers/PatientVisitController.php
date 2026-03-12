<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PatientVisitController extends Controller
{
    public function store(Request $request, Patient $patient): RedirectResponse
    {
        // Ensure the patient belongs to the authenticated user
        abort_unless($patient->user_id === auth()->id(), 403);

        $data = $request->validate([
            'visited_at' => ['required', 'date'],
            'weight'     => ['required', 'numeric', 'min:1', 'max:500'],
            'height'     => ['nullable', 'numeric', 'min:50', 'max:250'],
            'notes'      => ['nullable', 'string', 'max:1000'],
        ]);

        $patient->visits()->create($data);

        return back()->with('visit_success', 'Visit recorded successfully.');
    }

    public function destroy(Patient $patient, PatientVisit $visit): RedirectResponse
    {
        abort_unless($patient->user_id === auth()->id(), 403);
        abort_unless($visit->patient_id === $patient->id, 403);

        $visit->delete();

        return back()->with('visit_success', 'Visit deleted.');
    }
}
