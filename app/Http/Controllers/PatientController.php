<?php

namespace App\Http\Controllers;

use App\Models\ExchangeTemplateItem;
use App\Models\Macronutrient;
use App\Models\Patient;
use App\Models\ExchangeTemplate;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::where('user_id', auth()->id())->get();
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
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            'gender' => 'required|in:male,female,other',
            'weight' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'activity_factor' => 'required|numeric|min:0',
        ]);

        $patient = Patient::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'weight' => $request->weight,
            'height' => $request->height,
            'activity_factor' => $request->activity_factor,
        ]);

        // Create default macronutrients
        $macronutrients = [
            ['type' => 'carbohydrates', 'range_min' => 35, 'range_max' => 65, 'selected_percentage' => 50],
            ['type' => 'protein', 'range_min' => 10, 'range_max' => 35, 'selected_percentage' => 30],
            ['type' => 'fats', 'range_min' => 20, 'range_max' => 35, 'selected_percentage' => 20],
        ];

        foreach ($macronutrients as $macro) {
            // compute initial kj/grams from patient's TEE using KJ = selected% * TEE(kJ), grams = KJ / 17
            $teeKj = ($patient->tee ?? 0) * 4.184;
            $initialKj = ($macro['selected_percentage'] / 100) * $teeKj;
            $initialGrams = $initialKj > 0 ? ($initialKj / 17) : 0;

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

        return redirect()->route('patients.index')->with('success', 'Patient added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patient::with(['macronutrients', 'exchangeTemplate.items'])->where('user_id', auth()->id())->findOrFail($id);

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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            'gender' => 'required|in:male,female,other',
            'weight' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'activity_factor' => 'required|numeric|min:0',
        ]);

        $patient->update($request->only(['name', 'age', 'gender', 'weight', 'height', 'activity_factor']));

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
            // - grams = KJ / 17
            $teeKj = ($patient->tee ?? 0) * 4.184; // convert stored TEE (kcal) to kJ
            $kj = ($selected / 100) * $teeKj;
            $grams = $kj > 0 ? ($kj / 17) : 0;

            $macro->update([
                'selected_percentage' => $selected,
                'kj' => round($kj, 2),
                'grams' => round($grams, 0),
            ]);
        }

        return back()->with('success', 'Macronutrients updated.');
    }

    /**
     * Create a new exchange template (with default items) and attach it to the patient.
     */
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
