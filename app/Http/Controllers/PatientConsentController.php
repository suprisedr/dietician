<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Notifications\PatientConsented;
use Illuminate\Http\Request;

class PatientConsentController extends Controller
{
    /**
     * Show the consent decision page for the given token.
     */
    public function show(string $token)
    {
        $patient = Patient::where('consent_token', $token)->firstOrFail();

        // Already acted on
        if ($patient->hasConsented()) {
            return view('patients.consent-accepted', compact('patient'))->with('already', true);
        }
        if ($patient->consentDeclined()) {
            return view('patients.consent-declined', compact('patient'))->with('already', true);
        }

        // Token expired
        if ($patient->consentTokenExpired()) {
            return view('patients.consent-expired', compact('patient'));
        }

        return view('patients.consent-form', compact('patient', 'token'));
    }

    /**
     * Accept consent.
     */
    public function accept(string $token)
    {
        $patient = Patient::where('consent_token', $token)->firstOrFail();

        abort_if($patient->consentDeclined(), 422, 'Consent has already been declined.');
        abort_if($patient->consentTokenExpired(), 410, 'This consent link has expired.');

        $alreadyConsented = $patient->hasConsented();

        $patient->update([
            'consent_status' => 'consented',
            'consented_at'   => now(),
        ]);

        // Tell the dietitian — a re-submitted link must not notify twice.
        if (! $alreadyConsented) {
            $patient->user?->notify(new PatientConsented($patient));
        }

        return view('patients.consent-accepted', compact('patient'));
    }

    /**
     * Decline consent.
     */
    public function decline(string $token)
    {
        $patient = Patient::where('consent_token', $token)->firstOrFail();

        abort_if($patient->hasConsented(), 422, 'Consent has already been granted.');
        abort_if($patient->consentTokenExpired(), 410, 'This consent link has expired.');

        $patient->update([
            'consent_status' => 'declined',
        ]);

        return view('patients.consent-declined', compact('patient'));
    }
}
