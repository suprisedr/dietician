<?php

namespace App\Http\Controllers;

use App\Events\PatientUpdated;
use App\Mail\PatientConsentMail;
use App\Models\ExchangeTemplateItem;
use App\Models\Macronutrient;
use App\Models\MealPlannerWeek;
use App\Models\Patient;
use App\Models\ExchangeTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Patient::where('user_id', auth()->id());

        // Search by name, surname, or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('surname', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Gender filter
        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }

        // BMI category filter (computed from weight/height columns)
        if ($bmiCat = $request->input('bmi_category')) {
            $query->whereRaw('height > 0 AND weight > 0');
            match ($bmiCat) {
                'underweight' => $query->whereRaw('(weight / POW(height / 100, 2)) < 18.5'),
                'normal'      => $query->whereRaw('(weight / POW(height / 100, 2)) >= 18.5 AND (weight / POW(height / 100, 2)) < 25'),
                'overweight'  => $query->whereRaw('(weight / POW(height / 100, 2)) >= 25 AND (weight / POW(height / 100, 2)) < 30'),
                'obese'       => $query->whereRaw('(weight / POW(height / 100, 2)) >= 30'),
                default       => null,
            };
        }

        // Consent status filter
        if ($consent = $request->input('consent')) {
            $query->where('consent_status', $consent);
        }

        // Age range filter
        if ($ageMin = $request->input('age_min')) {
            $query->where('age', '>=', (int) $ageMin);
        }
        if ($ageMax = $request->input('age_max')) {
            $query->where('age', '<=', (int) $ageMax);
        }

        // Stats from full filtered result set (before pagination)
        $allForStats = (clone $query)->get(['gender', 'weight', 'height']);
        $stats = [
            'total'   => $allForStats->count(),
            'males'   => $allForStats->where('gender', 'male')->count(),
            'females' => $allForStats->where('gender', 'female')->count(),
            'avg_bmi' => $allForStats->filter(fn ($p) => $p->bmi)->avg(fn ($p) => $p->bmi),
        ];

        $sortable  = ['name', 'age', 'weight', 'height', 'activity_factor', 'bmi', 'consent_status', 'created_at'];
        $sort      = in_array($request->input('sort'), $sortable) ? $request->input('sort') : 'created_at';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        if ($sort === 'bmi') {
            $query->orderByRaw('(weight / NULLIF(POW(height / 100, 2), 0)) ' . $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        $patients = $query->paginate(15)->withQueryString();

        return view('patients.index', compact('patients', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'                  => 'nullable|string|max:10',
            'name'                  => 'required|string|max:255',
            'surname'               => 'nullable|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'id_type'               => 'nullable|in:sa_id,passport',
            'id_number'             => 'nullable|string|max:50',
            'date_of_birth'         => 'nullable|date',
            'address'               => 'nullable|string|max:500',
            'reason_for_assessment' => 'nullable|string|max:1000',
            'allergies'             => 'nullable|string|max:1000',
            'medical_history'       => 'nullable|string|max:2000',
            'medications'           => 'nullable|string|max:1000',
            'dietary_history'       => 'nullable|string|max:1000',
            'appetite'              => 'nullable|in:good,fair,poor',
            'referred_by'           => 'nullable|string|max:255',
            'age'                   => 'required|integer|min:0|max:150',
            'gender'                => 'required|in:male,female',
            'weight'                => 'required|numeric|min:0',
            'height'                => 'required|numeric|min:0',
            'activity_factor'       => 'required|numeric|min:0',
            'ibw_bmi_target'        => 'nullable|integer|in:22,25,30',
            'oedema'                => 'nullable|boolean',
        ]);

        $oedema = (bool) $request->input('oedema', false);

        $patient = Patient::create([
            'user_id'               => auth()->id(),
            'title'                 => $request->title,
            'name'                  => $request->name,
            'surname'               => $request->surname,
            'email'                 => $request->email,
            'id_type'               => $request->id_type,
            'id_number'             => $request->id_number,
            'date_of_birth'         => $request->date_of_birth,
            'address'               => $request->address,
            'reason_for_assessment' => $request->reason_for_assessment,
            'allergies'             => $request->allergies,
            'medical_history'       => $request->medical_history,
            'medications'           => $request->medications,
            'dietary_history'       => $request->dietary_history,
            'appetite'              => $request->appetite,
            'referred_by'           => $request->referred_by,
            'age'                   => $request->age,
            'gender'                => $request->gender,
            'weight'                => $request->weight,
            'height'                => $request->height,
            'activity_factor'       => $request->activity_factor,
            'ibw_bmi_target'        => $request->ibw_bmi_target ?? 22,
            'use_ibw_weight'        => true,
            'oedema'                => $oedema,
            'oedema_changed_at'     => now(),
        ]);

        // Create default macronutrients
        $macronutrients = [
            ['type' => 'carbohydrates', 'range_min' => 35, 'range_max' => 65, 'selected_percentage' => 50],
            ['type' => 'protein', 'range_min' => 10, 'range_max' => 35, 'selected_percentage' => 30],
            ['type' => 'fats', 'range_min' => 20, 'range_max' => 35, 'selected_percentage' => 20],
        ];

        foreach ($macronutrients as $macro) {
            // compute initial kj/grams from patient's TEE using KJ = selected% * TEE(kJ), grams = KJ / 17 (CHO & protein) or KJ / 38 (fat)
            $teeKj = $patient->tee ?? 0; // TEE already in kJ/day
            $initialKj = ($macro['selected_percentage'] / 100) * $teeKj;
            $divisor = in_array($macro['type'], ['fat', 'fats']) ? 38 : 17;
            $initialGrams = $initialKj > 0 ? ($initialKj / $divisor) : 0;

            Macronutrient::create([
                'patient_id' => $patient->id,
                'type' => $macro['type'],
                'range_min' => $macro['range_min'],
                'range_max' => $macro['range_max'],
                'selected_percentage' => $macro['selected_percentage'],
                'kj' => round($initialKj, 2),
                'grams' => round($initialGrams, 0),
            ]);
        }

        // Log the initial capture as the first visit
        $patient->visits()->create([
            'visited_at' => now()->toDateString(),
            'weight'     => $patient->weight,
            'height'     => $patient->height,
            'notes'      => 'Initial assessment',
        ]);

        // Send POPIA consent email with a 72-hour action link
        if ($patient->email) {
            $token = $patient->issueConsentToken();
            $link  = route('patient-consent.show', $token);
            Mail::to($patient->email)->send(new PatientConsentMail($patient, auth()->user(), $link));
        } else {
            // No email — treat as consented (verbal/in-person consent assumed)
            $patient->update(['consent_status' => 'consented', 'consented_at' => now()]);
        }

        return redirect()->route('patients.index')->with('success', 'Patient added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patient::with(['macronutrients', 'exchangeTemplate.items', 'visits'])->where('user_id', auth()->id())->findOrFail($id);

        // if patient has no linked template, fall back to the database "Customer Template"
        if (! $patient->exchangeTemplate) {
            $default = ExchangeTemplate::where('name', 'Customer Template')->with('items')->first();
            if ($default) {
                $patient->setRelation('exchangeTemplate', $default);
            }
        }

        return view('patients.show', compact('patient'));
    }

    /**
     * Resend the POPIA consent email (regenerates a fresh 72-hour token).
     */
    public function resendConsent(string $id)
    {
        $patient = Patient::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        abort_if(empty($patient->email), 422, 'This patient has no email address on file.');
        abort_if($patient->hasConsented(), 422, 'This patient has already consented.');

        $token = $patient->issueConsentToken();
        $link  = route('patient-consent.show', $token);
        Mail::to($patient->email)->send(new PatientConsentMail($patient, auth()->user(), $link));

        return back()->with('consent_success', 'Consent email resent to ' . $patient->email . '.');
    }

    /**
     * Toggle weekly meal plan reminder emails for a patient.
     */
    public function toggleWeeklyReminder(Request $request, string $id)
    {
        $patient = Patient::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        abort_if(empty($patient->email), 422, 'This patient has no email address on file.');

        $enabled = ! $patient->weekly_reminder_enabled;
        $patient->update(['weekly_reminder_enabled' => $enabled]);

        $msg = $enabled
            ? "Weekly meal plan reminders enabled for {$patient->full_name}."
            : "Weekly meal plan reminders disabled for {$patient->full_name}.";

        if ($request->wantsJson()) {
            return response()->json(['enabled' => $enabled, 'message' => $msg]);
        }

        return back()->with('reminder_success', $msg);
    }

    /**
     * Generate a downloadable/printable report for a patient.
     */
    public function report(Request $request, string $id)
    {
        $patient = Patient::with(['macronutrients', 'exchangeTemplate.items'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if (! $patient->exchangeTemplate) {
            $default = ExchangeTemplate::where('name', 'Customer Template')->with('items')->first();
            if ($default) {
                $patient->setRelation('exchangeTemplate', $default);
            }
        }

        $teeKj      = $patient->tee ?? 0;
        $teeKcal    = $patient->tee ? round($patient->tee / 4.184) : null;
        $bmrKj      = $patient->bmr ? round($patient->bmr * 4.184) : null;
        $isObese    = ($patient->bmi ?? 0) >= 30;

        $macroByType = $patient->macronutrients->keyBy('type');
        $recCho_g = $recPro_g = $recFat_g = null;
        $recCho_kj = $recPro_kj = $recFat_kj = null;
        $choPct = $proPct = $fatPct = null;

        if ($teeKj > 0 && $patient->macronutrients->count()) {
            $choPct     = $macroByType->get('carbohydrates')?->selected_percentage ?? 0;
            $proPct     = $macroByType->get('protein')?->selected_percentage       ?? 0;
            $fatPct     = $macroByType->get('fats')?->selected_percentage          ?? 0;
            $recCho_kj  = round($teeKj * $choPct / 100);
            $recPro_kj  = round($teeKj * $proPct / 100);
            $recFat_kj  = round($teeKj * $fatPct / 100);
            $recCho_g   = $recCho_kj > 0 ? round($recCho_kj / 17) : 0;
            $recPro_g   = $recPro_kj > 0 ? round($recPro_kj / 17) : 0;
            $recFat_g   = $recFat_kj > 0 ? round($recFat_kj / 38) : 0;
        }

        // Exchange template totals
        $etTotCho = $etTotPro = $etTotFat = $etTotKj = 0;
        if ($patient->exchangeTemplate) {
            foreach ($patient->exchangeTemplate->items as $item) {
                $nu = $item->nu;
                $etTotCho += $item->cho_g          !== null ? $nu * $item->cho_g          : 0;
                $etTotPro += $item->protein_min_g  !== null ? $nu * $item->protein_min_g  : 0;
                $etTotFat += $item->fat_min_g      !== null ? $nu * $item->fat_min_g      : 0;
                $etTotKj  += $item->kj             !== null ? $nu * $item->kj             : 0;
            }
        }

        $asOf    = $request->input('as_of');
        $enteral = \App\Models\EnteralNutritionCalculation::where('patient_id', $patient->id)
            ->where('user_id', auth()->id())
            ->when($asOf, fn($q) => $q->whereDate('created_at', '<=', $asOf))
            ->latest()
            ->first();

        $isPackage3 = auth()->user()->canAccessPlan('package_3');
        $letterhead = auth()->user()->letterheadBase64();

        return view('patients.report', compact(
            'patient', 'teeKj', 'teeKcal', 'bmrKj', 'isObese',
            'choPct', 'proPct', 'fatPct',
            'recCho_g', 'recPro_g', 'recFat_g',
            'recCho_kj', 'recPro_kj', 'recFat_kj',
            'etTotCho', 'etTotPro', 'etTotFat', 'etTotKj',
            'enteral', 'isPackage3', 'asOf', 'letterhead'
        ));
    }

    /**
     * Download a PDF version of the patient report.
     */
    public function reportPdf(Request $request, string $id)
    {
        $patient = Patient::with(['macronutrients', 'exchangeTemplate.items'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        if (! $patient->exchangeTemplate) {
            $default = ExchangeTemplate::where('name', 'Customer Template')->with('items')->first();
            if ($default) {
                $patient->setRelation('exchangeTemplate', $default);
            }
        }

        $teeKj      = $patient->tee ?? 0;
        $teeKcal    = $patient->tee ? round($patient->tee / 4.184) : null;
        $bmrKj      = $patient->bmr ? round($patient->bmr * 4.184) : null;
        $isObese    = ($patient->bmi ?? 0) >= 30;

        $macroByType = $patient->macronutrients->keyBy('type');
        $recCho_g = $recPro_g = $recFat_g = null;
        $recCho_kj = $recPro_kj = $recFat_kj = null;
        $choPct = $proPct = $fatPct = null;

        if ($teeKj > 0 && $patient->macronutrients->count()) {
            $choPct     = $macroByType->get('carbohydrates')?->selected_percentage ?? 0;
            $proPct     = $macroByType->get('protein')?->selected_percentage       ?? 0;
            $fatPct     = $macroByType->get('fats')?->selected_percentage          ?? 0;
            $recCho_kj  = round($teeKj * $choPct / 100);
            $recPro_kj  = round($teeKj * $proPct / 100);
            $recFat_kj  = round($teeKj * $fatPct / 100);
            $recCho_g   = $recCho_kj > 0 ? round($recCho_kj / 17) : 0;
            $recPro_g   = $recPro_kj > 0 ? round($recPro_kj / 17) : 0;
            $recFat_g   = $recFat_kj > 0 ? round($recFat_kj / 38) : 0;
        }

        $etTotCho = $etTotPro = $etTotFat = $etTotKj = 0;
        if ($patient->exchangeTemplate) {
            foreach ($patient->exchangeTemplate->items as $item) {
                $nu = $item->nu;
                $etTotCho += $item->cho_g         !== null ? $nu * $item->cho_g         : 0;
                $etTotPro += $item->protein_min_g !== null ? $nu * $item->protein_min_g : 0;
                $etTotFat += $item->fat_min_g     !== null ? $nu * $item->fat_min_g     : 0;
                $etTotKj  += $item->kj            !== null ? $nu * $item->kj            : 0;
            }
        }

        // Enteral nutrition calculation for section C/D (optionally filtered by date)
        $asOf    = $request->input('as_of');
        $enteral = \App\Models\EnteralNutritionCalculation::where('patient_id', $patient->id)
            ->where('user_id', auth()->id())
            ->when($asOf, fn($q) => $q->whereDate('created_at', '<=', $asOf))
            ->latest()
            ->first();

        $isPackage3 = auth()->user()->canAccessPlan('package_3');
        $letterhead = auth()->user()->letterheadBase64();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('patients.report-pdf', compact(
            'patient', 'teeKj', 'teeKcal', 'bmrKj', 'isObese',
            'choPct', 'proPct', 'fatPct',
            'recCho_g', 'recPro_g', 'recFat_g',
            'recCho_kj', 'recPro_kj', 'recFat_kj',
            'etTotCho', 'etTotPro', 'etTotFat', 'etTotKj',
            'enteral', 'isPackage3', 'letterhead'
        ))->setPaper('a4', 'portrait');

        $filename = 'report-' . \Illuminate\Support\Str::slug($patient->name) . '-' . now()->format('Y-m-d') . '.pdf';

        if ($request->boolean('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title'                  => 'nullable|string|max:10',
            'name'                  => 'required|string|max:255',
            'surname'               => 'nullable|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'id_type'               => 'nullable|in:sa_id,passport',
            'id_number'             => 'nullable|string|max:50',
            'date_of_birth'         => 'nullable|date',
            'address'               => 'nullable|string|max:500',
            'reason_for_assessment' => 'nullable|string|max:1000',
            'allergies'             => 'nullable|string|max:1000',
            'medical_history'       => 'nullable|string|max:2000',
            'medications'           => 'nullable|string|max:1000',
            'dietary_history'       => 'nullable|string|max:1000',
            'appetite'              => 'nullable|in:good,fair,poor',
            'referred_by'           => 'nullable|string|max:255',
            'age'                   => 'required|integer|min:0|max:150',
            'gender'                => 'required|in:male,female',
            'weight'                => 'required|numeric|min:0',
            'height'                => 'required|numeric|min:0',
            'activity_factor'       => 'required|numeric|min:0',
            'ibw_bmi_target'        => 'nullable|integer|in:22,25,30',
        ]);

        $updateData = $request->only([
            'title', 'name', 'surname', 'email', 'id_type', 'id_number',
            'date_of_birth', 'address', 'reason_for_assessment',
            'allergies', 'medical_history', 'medications', 'dietary_history', 'appetite', 'referred_by',
            'age', 'gender', 'weight', 'height', 'activity_factor', 'ibw_bmi_target',
        ]);
        // Any BMI target (22, 25 or 30) means IBW is used as the calculation weight.
        $updateData['use_ibw_weight'] = isset($updateData['ibw_bmi_target']) && in_array((int) $updateData['ibw_bmi_target'], [22, 25, 30]);
        $patient->update($updateData);

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient deleted.');
    }

    /**
     * PATCH patients/{patient}/ibw-target — save the dietitian's chosen BMI target for IBW.
     * Returns JSON so the show page can update the hero card live without a full reload.
     */
    public function updateIbwTarget(Request $request, string $id)
    {
        $patient = Patient::with('macronutrients')->where('user_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'ibw_bmi_target' => 'required|integer|in:22,25,30',
        ]);

        // Any selected BMI target (22, 25 or 30) means IBW is used as the calculation weight.
        $data['use_ibw_weight'] = true;

        $patient->update($data);
        $patient->refresh();

        // Recalculate and persist macro kJ/grams using the new TEE so that
        // all downstream calculations (RMR, TEE, macros, exchange totals)
        // immediately reflect the weight change — no manual re-save needed.
        $teeKj = $patient->tee ?? 0;
        foreach ($patient->macronutrients as $macro) {
            $kj      = ($macro->selected_percentage / 100) * $teeKj;
            $divisor = in_array($macro->type, ['fat', 'fats']) ? 38 : 17;
            $grams   = $kj > 0 ? ($kj / $divisor) : 0;
            $macro->update([
                'kj'    => round($kj, 2),
                'grams' => round($grams, 0),
            ]);
        }

        $patient->refresh();
        try {
            broadcast(new PatientUpdated($patient))->toOthers();
        } catch (\Throwable $e) {
            \Log::warning('PatientUpdated broadcast failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'ibw_bmi_target' => $patient->ibw_bmi_target,
            'use_ibw_weight' => $patient->use_ibw_weight,
            'ibw'            => $patient->ibw,
            'abw'            => $patient->abw,
            'weight_for_bmr' => $patient->weight_for_bmr,
            'rmr_kcal'       => $patient->bmr ? round($patient->bmr) : null,
            'rmr_kj'         => $patient->bmr ? round($patient->bmr * 4.184) : null,
            'tee_kj'         => $teeKj ? round($teeKj) : null,
            'tee_kcal'       => $teeKj ? round($teeKj / 4.184) : null,
            'macros'         => $patient->macronutrients->map(fn($m) => [
                'id'    => $m->id,
                'type'  => $m->type,
                'kj'    => round(($m->selected_percentage / 100) * $teeKj, 1),
                'grams' => round(($m->selected_percentage / 100) * $teeKj / (in_array($m->type, ['fat','fats']) ? 38 : 17)),
            ])->values(),
        ]);
    }

    /**
     * Update only the clinical/subjective assessment fields from the show page.
     */
    public function updateClinicalAssessment(Request $request, string $id)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'reason_for_assessment' => 'nullable|string|max:1000',
            'referred_by'           => 'nullable|string|max:255',
            'medical_history'       => 'nullable|string|max:5000',
            'medications'           => 'nullable|string|max:2000',
            'allergies'             => 'nullable|string|max:1000',
            'dietary_history'       => 'nullable|string|max:2000',
            'appetite'              => 'nullable|in:good,fair,poor',
            'oedema'                => 'nullable|boolean',
        ]);

        $oedema = isset($data['oedema']) ? (bool) $data['oedema'] : $patient->oedema;
        unset($data['oedema']);

        $updateData = $data;
        if ($oedema !== (bool) $patient->oedema) {
            $updateData['oedema']            = $oedema;
            $updateData['oedema_changed_at'] = now();
        } else {
            $updateData['oedema'] = $oedema;
        }

        $patient->update($updateData);

        return redirect()->route('patients.edit', $patient->id)
            ->with('success', 'Clinical assessment updated.');
    }

    /**
     * Update macronutrient selected percentages for a patient.
     */
    public function updateMacronutrients(Request $request, string $id)
    {
        $patient = Patient::with('macronutrients')->where('user_id', auth()->id())->findOrFail($id);

        $input = $request->input('macronutrients', []);
        if (! is_array($input)) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Invalid input.'], 422)
                : back()->withErrors(['macronutrients' => 'Invalid input.']);
        }

        $totalSelected = array_reduce($input, fn($carry, $item) => $carry + (float) $item, 0.0);

        if (abs($totalSelected - 100.0) > 0.01) {
            return $request->expectsJson()
                ? response()->json(['error' => 'Total must equal 100%.'], 422)
                : back()->withErrors(['macronutrients_total' => 'The total of selected macronutrient percentages must equal 100%.'])->withInput();
        }

        foreach ($input as $macroId => $selectedPercentage) {
            $macro = $patient->macronutrients->firstWhere('id', (int) $macroId);
            if (! $macro) continue;

            $selected = (float) $selectedPercentage;
            if ($selected < $macro->range_min || $selected > $macro->range_max) {
                $msg = "Selected percentage for {$macro->type} must be between {$macro->range_min} and {$macro->range_max}.";
                return $request->expectsJson()
                    ? response()->json(['error' => $msg], 422)
                    : back()->withErrors(["macronutrients.{$macroId}" => $msg]);
            }

            $teeKj   = $patient->tee ?? 0;
            $kj      = ($selected / 100) * $teeKj;
            $divisor = in_array($macro->type, ['fat', 'fats']) ? 38 : 17;
            $grams   = $kj > 0 ? ($kj / $divisor) : 0;

            $macro->update([
                'selected_percentage' => $selected,
                'kj'    => round($kj, 2),
                'grams' => round($grams, 0),
            ]);
        }

        $event = new PatientUpdated($patient->fresh('macronutrients'));
        try {
            broadcast($event)->toOthers();
        } catch (\Throwable $e) {
            \Log::warning('PatientUpdated broadcast failed', ['error' => $e->getMessage()]);
        }

        if ($request->expectsJson()) {
            return response()->json($event->payload);
        }

        return back()->with('success', 'Macronutrients updated.');
    }

    /**
     * Create a new exchange template (with default items) and attach it to the patient.
     */
    /**
     * Apply a standard dietary preset to the patient's exchange template + meal plan slots.
     * Creates a fresh exchange template if one doesn't exist yet.
     * Returns JSON with the updated items so the page can refresh without a full reload.
     */
    public function applyPreset(Request $request, string $patientId)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($patientId);

        $presetKey = $request->input('preset');
        $dbPreset  = \App\Models\DietPreset::with('items')
                        ->where('key', $presetKey)
                        ->first();

        if (! $dbPreset) {
            return response()->json(['error' => 'Unknown preset'], 422);
        }

        // Ensure the patient has an exchange template
        $freshTemplate = false;
        if (! $patient->exchange_template_id) {
            $template = ExchangeTemplate::create(['name' => "Template for patient {$patient->id}"]);
            $patient->update(['exchange_template_id' => $template->id]);
            $patient->refresh();
            $freshTemplate = true;
        }

        // Record which preset is active on this patient
        $patient->update(['diet_preset_id' => $dbPreset->id]);

        $template = $patient->exchangeTemplate;

        // Ensure all 13 standard items exist on the template (fill gaps with defaults)
        $standardRows = [
            ['name' => 'Milk, full cream',    'nu' => 0, 'cho_g' => 12,   'protein_min_g' => 8,    'protein_max_g' => 8,    'fat_min_g' => 8,    'fat_max_g' => 8,    'kj' => 670],
            ['name' => 'Milk, low fat',       'nu' => 0, 'cho_g' => 12,   'protein_min_g' => 8,    'protein_max_g' => 8,    'fat_min_g' => 5,    'fat_max_g' => 5,    'kj' => 500],
            ['name' => 'Milk, fat free',      'nu' => 0, 'cho_g' => 12,   'protein_min_g' => 8,    'protein_max_g' => 8,    'fat_min_g' => 0,    'fat_max_g' => 3,    'kj' => 420],
            ['name' => 'Fruit',               'nu' => 0, 'cho_g' => 15,   'protein_min_g' => null, 'protein_max_g' => null, 'fat_min_g' => null, 'fat_max_g' => null, 'kj' => 250],
            ['name' => 'Veg, free veg',       'nu' => 0, 'cho_g' => 5,    'protein_min_g' => 2,    'protein_max_g' => 2,    'fat_min_g' => null, 'fat_max_g' => null, 'kj' => 105],
            ['name' => 'Starch',              'nu' => 0, 'cho_g' => 15,   'protein_min_g' => 0,    'protein_max_g' => 3,    'fat_min_g' => 0,    'fat_max_g' => 1,    'kj' => 335],
            ['name' => 'Sugar/sweets',        'nu' => 0, 'cho_g' => 5,    'protein_min_g' => null, 'protein_max_g' => null, 'fat_min_g' => null, 'fat_max_g' => null, 'kj' => 84],
            ['name' => 'Meat, lean fat',      'nu' => 0, 'cho_g' => null, 'protein_min_g' => 7,    'protein_max_g' => 7,    'fat_min_g' => 0,    'fat_max_g' => 3,    'kj' => 190],
            ['name' => 'Meat, medium fat',    'nu' => 0, 'cho_g' => null, 'protein_min_g' => 7,    'protein_max_g' => 7,    'fat_min_g' => 4,    'fat_max_g' => 7,    'kj' => 315],
            ['name' => 'Meat, high fat',      'nu' => 0, 'cho_g' => null, 'protein_min_g' => 7,    'protein_max_g' => 7,    'fat_min_g' => 8,    'fat_max_g' => 8,    'kj' => 420],
            ['name' => 'Plant-based protein', 'nu' => 0, 'cho_g' => 15,   'protein_min_g' => 7,    'protein_max_g' => 7,    'fat_min_g' => 0,    'fat_max_g' => 1,    'kj' => 380],
            ['name' => 'Fat',                 'nu' => 0, 'cho_g' => null, 'protein_min_g' => null, 'protein_max_g' => null, 'fat_min_g' => 5,    'fat_max_g' => 5,    'kj' => 190],
            ['name' => 'Alcohol',             'nu' => 0, 'cho_g' => 15,   'protein_min_g' => 0,    'protein_max_g' => 3,    'fat_min_g' => 0,    'fat_max_g' => 1,    'kj' => 420],
        ];

        $template->load('items');
        $existingNames = $template->items->map(fn($i) => strtolower(trim($i->name)))->flip();

        foreach ($standardRows as $row) {
            if (! $existingNames->has(strtolower($row['name']))) {
                ExchangeTemplateItem::create(array_merge($row, ['exchange_template_id' => $template->id]));
            }
        }

        // Reload items after ensuring all standard items exist
        $template->load('items');

        // Index existing items by name for quick lookup
        $existingByName = $template->items->keyBy(fn($i) => strtolower(trim($i->name)));

        $resultItems = [];

        foreach ($dbPreset->items as $def) {
            $key  = strtolower(trim($def->name));
            $item = $existingByName->get($key);

            $slotFields = [
                'slot_breakfast' => $def->slot_breakfast,
                'slot_snack1'    => $def->slot_snack1,
                'slot_lunch'     => $def->slot_lunch,
                'slot_snack2'    => $def->slot_snack2,
                'slot_supper'    => $def->slot_supper,
                'slot_snack3'    => $def->slot_snack3,
            ];

            if ($item) {
                // Update nu, slots, and nutrient values from the preset
                $item->update(array_merge([
                    'nu'            => $def->nu,
                    'cho_g'         => $def->cho_g,
                    'protein_min_g' => $def->protein_min_g,
                    'protein_max_g' => $def->protein_max_g,
                    'fat_min_g'     => $def->fat_min_g,
                    'fat_max_g'     => $def->fat_max_g,
                    'kj'            => $def->kj,
                ], $slotFields));
            } else {
                // Create brand-new item from preset data
                $item = ExchangeTemplateItem::create(array_merge([
                    'exchange_template_id' => $template->id,
                    'name'                 => $def->name,
                    'nu'                   => $def->nu,
                    'cho_g'                => $def->cho_g,
                    'protein_min_g'        => $def->protein_min_g,
                    'protein_max_g'        => $def->protein_max_g,
                    'fat_min_g'            => $def->fat_min_g,
                    'fat_max_g'            => $def->fat_max_g,
                    'kj'                   => $def->kj,
                ], $slotFields));
            }

            $resultItems[] = [
                'id'             => $item->id,
                'name'           => $item->name,
                'nu'             => $item->nu,
                'cho_g'          => $item->cho_g,
                'protein_min_g'  => $item->protein_min_g,
                'fat_min_g'      => $item->fat_min_g,
                'kj'             => $item->kj,
                'slot_breakfast' => $item->slot_breakfast,
                'slot_snack1'    => $item->slot_snack1,
                'slot_lunch'     => $item->slot_lunch,
                'slot_snack2'    => $item->slot_snack2,
                'slot_supper'    => $item->slot_supper,
                'slot_snack3'    => $item->slot_snack3,
            ];
        }

        // Zero out nu and all slots for items NOT included in this preset
        $presetNameKeys = $dbPreset->items->map(fn($i) => strtolower(trim($i->name)))->flip();

        $template->load('items');
        foreach ($template->items as $item) {
            if (! $presetNameKeys->has(strtolower(trim($item->name)))) {
                $item->update([
                    'nu'             => 0,
                    'slot_breakfast' => 0,
                    'slot_snack1'    => 0,
                    'slot_lunch'     => 0,
                    'slot_snack2'    => 0,
                    'slot_supper'    => 0,
                    'slot_snack3'    => 0,
                ]);
            }
        }

        return response()->json([
            'preset_name'    => $dbPreset->name,
            'kcal_target'    => $dbPreset->kcal_target,
            'items'          => $resultItems,
            'template_id'    => $template->id,
            'reload_required' => $freshTemplate,
        ]);
    }

    /**
     * Return a preset's items from the DB (for live preview before applying).
     */
    public function getPreset(string $presetKey)
    {
        $preset = \App\Models\DietPreset::with('items')
                    ->where('key', $presetKey)
                    ->firstOrFail();

        return response()->json([
            'key'         => $preset->key,
            'name'        => $preset->name,
            'description' => $preset->description,
            'kcal_target' => $preset->kcal_target,
            'items'       => $preset->items->map(fn($i) => [
                'name'           => $i->name,
                'nu'             => $i->nu,
                'cho_g'          => $i->cho_g,
                'protein_min_g'  => $i->protein_min_g,
                'fat_min_g'      => $i->fat_min_g,
                'kj'             => $i->kj,
                'slot_breakfast' => $i->slot_breakfast,
                'slot_snack1'    => $i->slot_snack1,
                'slot_lunch'     => $i->slot_lunch,
                'slot_snack2'    => $i->slot_snack2,
                'slot_supper'    => $i->slot_supper,
                'slot_snack3'    => $i->slot_snack3,
            ]),
        ]);
    }

    /**
     * Clear the active preset from the patient (sets diet_preset_id to null).
     */
    public function clearPreset(string $patientId)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($patientId);
        $patient->update(['diet_preset_id' => null]);

        return response()->json(['cleared' => true]);
    }

    public function createExchangeTemplate(string $patientId)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($patientId);

        // make sure we don't overwrite an existing template
        if ($patient->exchange_template_id) {
            return back()->with('success', 'Patient already has an exchange template.');
        }

        // build a fresh template identical to the seeder defaults
        $template = ExchangeTemplate::create(['name' => "Template for patient {$patient->id}"]);

        $rows = [
            ['name' => 'Milk, full cream',    'nu' => 1, 'cho_g' => 12,   'protein_min_g' => 8,    'protein_max_g' => 8,    'fat_min_g' => 8,    'fat_max_g' => 8,    'kj' => 670],
            ['name' => 'Milk, low fat',       'nu' => 1, 'cho_g' => 12,   'protein_min_g' => 8,    'protein_max_g' => 8,    'fat_min_g' => 5,    'fat_max_g' => 5,    'kj' => 500],
            ['name' => 'Milk, fat free',      'nu' => 1, 'cho_g' => 12,   'protein_min_g' => 8,    'protein_max_g' => 8,    'fat_min_g' => 0,    'fat_max_g' => 3,    'kj' => 420],
            ['name' => 'Fruit',               'nu' => 1, 'cho_g' => 15,   'protein_min_g' => null, 'protein_max_g' => null, 'fat_min_g' => null, 'fat_max_g' => null, 'kj' => 250],
            ['name' => 'Veg, free veg',       'nu' => 1, 'cho_g' => 5,    'protein_min_g' => 2,    'protein_max_g' => 2,    'fat_min_g' => null, 'fat_max_g' => null, 'kj' => 105],
            ['name' => 'Starch',              'nu' => 1, 'cho_g' => 15,   'protein_min_g' => 0,    'protein_max_g' => 3,    'fat_min_g' => 0,    'fat_max_g' => 1,    'kj' => 335],
            ['name' => 'Sugar/sweets',        'nu' => 1, 'cho_g' => 5,    'protein_min_g' => null, 'protein_max_g' => null, 'fat_min_g' => null, 'fat_max_g' => null, 'kj' => 84],
            ['name' => 'Meat, lean fat',      'nu' => 1, 'cho_g' => null, 'protein_min_g' => 7,    'protein_max_g' => 7,    'fat_min_g' => 0,    'fat_max_g' => 3,    'kj' => 190],
            ['name' => 'Meat, medium fat',    'nu' => 1, 'cho_g' => null, 'protein_min_g' => 7,    'protein_max_g' => 7,    'fat_min_g' => 4,    'fat_max_g' => 7,    'kj' => 315],
            ['name' => 'Meat, high fat',      'nu' => 1, 'cho_g' => null, 'protein_min_g' => 7,    'protein_max_g' => 7,    'fat_min_g' => 8,    'fat_max_g' => 8,    'kj' => 420],
            ['name' => 'Plant-based protein', 'nu' => 1, 'cho_g' => 15,   'protein_min_g' => 7,    'protein_max_g' => 7,    'fat_min_g' => 0,    'fat_max_g' => 1,    'kj' => 380],
            ['name' => 'Fat',                 'nu' => 1, 'cho_g' => null, 'protein_min_g' => null, 'protein_max_g' => null, 'fat_min_g' => 5,    'fat_max_g' => 5,    'kj' => 190],
            ['name' => 'Alcohol',             'nu' => 1, 'cho_g' => 15,   'protein_min_g' => 0,    'protein_max_g' => 3,    'fat_min_g' => 0,    'fat_max_g' => 1,    'kj' => 420],
        ];

        foreach ($rows as $r) {
            ExchangeTemplateItem::create(array_merge($r, ['exchange_template_id' => $template->id]));
        }

        $patient->update(['exchange_template_id' => $template->id]);

        return back()->with('success', 'Exchange template created and attached.');
    }

    /**
     * Save the user-entered meal plan slot distribution.
     */
    public function saveMealPlan(Request $request, string $patientId)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($patientId);

        // items is an array keyed by item id: ['123' => ['breakfast'=>1,'snack1'=>0.5,...]]
        $allItems = $request->input('items', []);

        foreach ($allItems as $itemId => $slots) {
            $item = ExchangeTemplateItem::findOrFail($itemId);
            $item->update([
                'slot_breakfast' => floatval($slots['breakfast'] ?? 0),
                'slot_snack1'    => floatval($slots['snack1']    ?? 0),
                'slot_lunch'     => floatval($slots['lunch']     ?? 0),
                'slot_snack2'    => floatval($slots['snack2']    ?? 0),
                'slot_supper'    => floatval($slots['supper']    ?? 0),
                'slot_snack3'    => floatval($slots['snack3']    ?? 0),
            ]);
        }

        // Auto-create (or find) a MealPlannerWeek for this patient starting this Monday
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $week = MealPlannerWeek::firstOrCreate(
            [
                'user_id'    => auth()->id(),
                'patient_id' => $patient->id,
                'week_start' => $weekStart,
            ],
            ['label' => null]
        );

        $redirectUrl = route('meal-planner.show', [$patient->id, $week->id]);

        // AJAX request from the JS fetch → return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message'      => 'Meal plan saved.',
                'redirect_url' => $redirectUrl,
            ]);
        }

        // Fallback for non-JS submit
        return redirect($redirectUrl)->with('success', 'Meal plan saved.');
    }

    /**
     * Increment or decrement the nu value of a single exchange template item.
     */
    public function updateExchangeItemNu(Request $request, string $patientId, string $itemId)
    {
        // Ensure patient belongs to current dietician
        Patient::where('user_id', auth()->id())->findOrFail($patientId);

        $item = ExchangeTemplateItem::findOrFail($itemId);

        if ($request->filled('nu')) {
            // absolute value provided
            $newNu = max(0, (int) $request->input('nu'));
        } else {
            $delta = (int) $request->input('delta', 0); // +1 or -1
            $newNu = max(0, $item->nu + $delta);
        }
        $item->update(['nu' => $newNu]);

        return back()->with('success', 'Exchange template updated.');
    }
}
