<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientVisitController extends Controller
{
    public function index(Patient $patient): View
    {
        abort_unless($patient->user_id === auth()->id(), 403);

        $visits = $patient->visits()->latest('visited_at')->paginate(20)->withQueryString();

        return view('patients.visits', compact('patient', 'visits'));
    }

    public function pdf(Request $request, Patient $patient)
    {
        abort_unless($patient->user_id === auth()->id(), 403);

        $visits = $patient->visits()->latest('visited_at')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('patients.visits-pdf', compact('patient', 'visits'))
            ->setPaper('a4', 'landscape');

        $filename = 'visit-log-' . \Illuminate\Support\Str::slug($patient->full_name) . '-' . now()->format('Y-m-d') . '.pdf';

        if ($request->boolean('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    public function store(Request $request, Patient $patient): RedirectResponse
    {
        // Ensure the patient belongs to the authenticated user
        abort_unless($patient->user_id === auth()->id(), 403);

        $data = $request->validate([
            'weight'     => ['required', 'numeric', 'min:1', 'max:500'],
            'height'     => ['nullable', 'numeric', 'min:50', 'max:250'],
            'notes'      => ['nullable', 'string', 'max:1000'],
            'oedema'     => ['nullable', 'boolean'],
        ]);

        $data['visited_at'] = now();

        $oedema = (bool) $request->input('oedema', false);
        $prevVisit = $patient->visits()->latest('visited_at')->first();
        $oedemaChanged = !$prevVisit || ((bool) $prevVisit->oedema) !== $oedema;

        $data['oedema'] = $oedema;
        $data['oedema_changed_at'] = $oedemaChanged ? now() : ($prevVisit?->oedema_changed_at ?? now());

        // Also update the patient's current oedema status if it changed
        if ($oedemaChanged) {
            $patient->update(['oedema' => $oedema, 'oedema_changed_at' => now()]);
        }

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
