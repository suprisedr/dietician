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
    public function index()
    {
        $patients = Patient::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('patients.index', compact('patients'));
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
            'age'                   => 'required|integer|min:0|max:150',
            'gender'                => 'required|in:male,female',
            'weight'                => 'required|numeric|min:0',
            'height'                => 'required|numeric|min:0',
            'activity_factor'       => 'required|numeric|min:0',
            'ibw_bmi_target'        => 'nullable|integer|in:22,25,30',
        ]);

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
            'age'                   => $request->age,
            'gender'                => $request->gender,
            'weight'                => $request->weight,
            'height'                => $request->height,
            'activity_factor'       => $request->activity_factor,
            'ibw_bmi_target'        => $request->ibw_bmi_target ?? 22,
            'use_ibw_weight'        => true,
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

        // Send POPIA consent notice to patient if an email address was provided
        if ($patient->email) {
            Mail::to($patient->email)->send(new PatientConsentMail($patient, auth()->user()));
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
     * Generate a downloadable/printable report for a patient.
     */
    public function report(string $id)
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

        return view('patients.report', compact(
            'patient', 'teeKj', 'teeKcal', 'bmrKj', 'isObese',
            'choPct', 'proPct', 'fatPct',
            'recCho_g', 'recPro_g', 'recFat_g',
            'recCho_kj', 'recPro_kj', 'recFat_kj',
            'etTotCho', 'etTotPro', 'etTotFat', 'etTotKj'
        ));
    }

    /**
     * Download a PDF version of the patient report.
     */
    public function reportPdf(string $id)
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

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('patients.report-pdf', compact(
            'patient', 'teeKj', 'teeKcal', 'bmrKj', 'isObese',
            'choPct', 'proPct', 'fatPct',
            'recCho_g', 'recPro_g', 'recFat_g',
            'recCho_kj', 'recPro_kj', 'recFat_kj',
            'etTotCho', 'etTotPro', 'etTotFat', 'etTotKj'
        ))->setPaper('a4', 'portrait');

        $filename = 'report-' . \Illuminate\Support\Str::slug($patient->name) . '-' . now()->format('Y-m-d') . '.pdf';

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
        broadcast(new PatientUpdated($patient))->toOthers();

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
     * Update macronutrient selected percentages for a patient.
     */
    public function updateMacronutrients(Request $request, string $id)
    {
        $patient = Patient::with('macronutrients')->where('user_id', auth()->id())->findOrFail($id);

        $input = $request->input('macronutrients', []);
        if (!is_array($input)) {
            return back()->withErrors(['macronutrients' => 'Invalid input.']);
        }

        // validate that total selected percentages equal 100
        $totalSelected = array_reduce($input, function ($carry, $item) {
            return $carry + (float) $item;
        }, 0.0);

        if (abs($totalSelected - 100.0) > 0.01) {
            return back()->withErrors(['macronutrients_total' => 'The total of selected macronutrient percentages must equal 100%.'])->withInput();
        }

        foreach ($input as $macroId => $selectedPercentage) {
            $macro = $patient->macronutrients->firstWhere('id', (int) $macroId);
            if (! $macro) {
                continue;
            }

            $selected = (float) $selectedPercentage;
            if ($selected < $macro->range_min || $selected > $macro->range_max) {
                return back()->withErrors(["macronutrients.{$macroId}" => "Selected percentage for {$macro->type} must be between {$macro->range_min} and {$macro->range_max}."]);
            }

            // Calculate KJ and grams using requested formulas:
            // - KJ = (selected_percentage / 100) * TEE_in_kJ
            // - grams = KJ / 17 (CHO & protein) or KJ / 38 (fat)
            $teeKj = $patient->tee ?? 0; // kJ/day
            $kj = ($selected / 100) * $teeKj;
            $divisor = in_array($macro->type, ['fat', 'fats']) ? 38 : 17;
            $grams = $kj > 0 ? ($kj / $divisor) : 0;

            $macro->update([
                'selected_percentage' => $selected,
                'kj' => round($kj, 2),
                'grams' => round($grams, 0),
            ]);
        }

        broadcast(new PatientUpdated($patient->fresh('macronutrients')))->toOthers();

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
        if (! $patient->exchange_template_id) {
            $template = ExchangeTemplate::create(['name' => "Template for patient {$patient->id}"]);
            $patient->update(['exchange_template_id' => $template->id]);
            $patient->refresh();
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
            'preset_name' => $dbPreset->name,
            'kcal_target' => $dbPreset->kcal_target,
            'items'       => $resultItems,
            'template_id' => $template->id,
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
