<?php

namespace App\Http\Controllers;

use App\Models\EnteralNutritionCalculation;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EnteralNutritionController extends Controller
{
    /**
     * Show the calculator & history for a patient.
     */
    public function index(Patient $patient)
    {
        abort_unless($patient->user_id === Auth::id(), 403);

        $calculations = $patient->enteralNutritionCalculations()
            ->latest()
            ->get();

        $conditions = EnteralNutritionCalculation::CONDITIONS;

        // Energy & protein range lookup arrays for JS (keyed by condition slug)
        $energyRanges  = [];
        $proteinRanges = [];
        foreach ($conditions as $slug => $label) {
            $energyRanges[$slug]  = EnteralNutritionCalculation::energyRangeFor($slug);
            $proteinRanges[$slug] = EnteralNutritionCalculation::proteinRangeFor($slug);
        }

        return view('enteral-nutrition.index', compact(
            'patient', 'calculations', 'conditions', 'energyRanges', 'proteinRanges'
        ));
    }

    /**
     * Save a new calculation.
     */
    public function store(Request $request, Patient $patient)
    {
        abort_unless($patient->user_id === Auth::id(), 403);

        $validConditions = implode(',', array_keys(EnteralNutritionCalculation::CONDITIONS));

        $data = $request->validate([
            'clinical_condition'     => 'required|in:' . $validConditions,
            'weight_type'            => 'required|in:actual,ibw,abw',
            'weight_kg'              => 'required|numeric|min:1|max:300',
            'energy_kcal_per_kg'     => 'required|numeric|min:15|max:40',
            'protein_g_per_kg'       => 'required|numeric|min:0.5|max:2.5',
            'formula_density'        => 'required|in:1.0,1.2,1.5',
            'feeding_hours_per_day'  => 'required|integer|min:1|max:24',
            'water_flush_ml'         => 'required|integer|min:10|max:100',
            'water_flush_frequency'  => 'required|in:4-hourly,6-hourly,8-hourly,12-hourly',
        ]);

        $weightKg       = (float) $data['weight_kg'];
        $kcalPerKg      = (float) $data['energy_kcal_per_kg'];
        $proteinPerKg   = (float) $data['protein_g_per_kg'];
        $density        = (float) $data['formula_density'];
        $feedingHours   = (int)   $data['feeding_hours_per_day'];

        $energyKcal      = round($weightKg * $kcalPerKg, 1);
        $proteinG        = round($weightKg * $proteinPerKg, 1);
        $dailyVolumeMl   = round($energyKcal / $density);
        $rateMlPerHr     = round($dailyVolumeMl / $feedingHours, 1);

        // Fluid requirements: 35 mL/kg/day (ClinCalc / ASPEN standard)
        $fluidReqMl       = round($weightKg * 35);
        $freeWaterFrac    = EnteralNutritionCalculation::freeWaterFractionFor($density);
        $freeWaterMl      = round($dailyVolumeMl * $freeWaterFrac);
        $additionalWaterMl = max(0, $fluidReqMl - $freeWaterMl);

        EnteralNutritionCalculation::create([
            'patient_id'                 => $patient->id,
            'user_id'                    => Auth::id(),
            'clinical_condition'         => $data['clinical_condition'],
            'weight_type'                => $data['weight_type'],
            'weight_kg'                  => $weightKg,
            'energy_kcal_per_kg'         => $kcalPerKg,
            'energy_target_kcal'         => $energyKcal,
            'protein_g_per_kg'           => $proteinPerKg,
            'protein_target_g'           => $proteinG,
            'formula_density'            => $density,
            'feeding_hours_per_day'      => $feedingHours,
            'daily_volume_ml'            => $dailyVolumeMl,
            'rate_ml_per_hour'           => $rateMlPerHr,
            'fluid_requirement_ml'       => $fluidReqMl,
            'free_water_from_formula_ml' => $freeWaterMl,
            'additional_water_ml'        => $additionalWaterMl,
            'water_flush_ml'             => (int) $data['water_flush_ml'],
            'water_flush_frequency'      => $data['water_flush_frequency'],
        ]);

        return redirect()
            ->route('patients.enteral-nutrition.index', $patient)
            ->with('success', 'Enteral nutrition calculation saved.');
    }

    /**
     * Stream or download a PDF of all saved calculations for a patient.
     * ?stream=1 returns inline for iframe preview.
     */
    public function pdf(Request $request, Patient $patient)
    {
        abort_unless($patient->user_id === Auth::id(), 403);

        $calcIds = array_filter(array_map('intval', explode(',', $request->input('calculations', ''))));

        $query = $patient->enteralNutritionCalculations()->latest();
        if (!empty($calcIds)) {
            $query->whereIn('id', $calcIds);
        }
        $calculations = $query->get();

        $letterhead = auth()->user()->letterheadBase64();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'enteral-nutrition.pdf',
            compact('patient', 'calculations', 'letterhead')
        )->setPaper('a4', 'portrait');

        $filename = 'enteral-nutrition-' . Str::slug($patient->full_name) . '-' . now()->format('Y-m-d') . '.pdf';

        if ($request->boolean('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Delete a saved calculation.
     */
    public function destroy(Patient $patient, EnteralNutritionCalculation $calculation)
    {
        abort_unless($patient->user_id === Auth::id(), 403);
        abort_unless($calculation->patient_id === $patient->id, 403);

        $calculation->delete();

        return redirect()
            ->route('patients.enteral-nutrition.index', $patient)
            ->with('success', 'Calculation deleted.');
    }
}
