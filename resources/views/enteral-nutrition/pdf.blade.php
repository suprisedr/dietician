<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
* { box-sizing: border-box; }
body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 9pt;
    color: #1e293b;
    background: #fff;
    margin: 0;
    padding: 22px 26px 26px;
}

/* ── Letterhead ── */
.hdr-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
.hdr-logo  { width: 90px; vertical-align: middle; }
.hdr-logo img { max-width: 80px; max-height: 60px; }
.hdr-text  { vertical-align: middle; padding-left: 12px; }
.hdr-brand { font-size: 14pt; font-weight: bold; color: #15803d; letter-spacing: .5px; }
.hdr-sub   { font-size: 7.5pt; color: #64748b; margin-top: 2px; }
.hdr-meta  { vertical-align: middle; text-align: right; font-size: 7.5pt; color: #64748b; }
.hdr-meta strong { color: #1e293b; }
.divider { border: none; border-top: 2px solid #15803d; margin: 8px 0 10px; }

/* ── Page title ── */
.page-title { font-size: 13pt; font-weight: bold; color: #0f172a; margin: 0 0 2px; }
.page-sub   { font-size: 8pt; color: #64748b; margin: 0 0 10px; }

/* ── Patient box ── */
.patient-box {
    background: #f0fdf4; border: 1px solid #bbf7d0;
    border-radius: 4px; padding: 7px 10px; margin-bottom: 12px;
}
.patient-grid { width: 100%; border-collapse: collapse; }
.patient-grid td { font-size: 7.5pt; padding: 2px 8px 2px 0; vertical-align: top; }
.pat-lbl { color: #64748b; font-weight: bold; text-transform: uppercase;
           letter-spacing: .04em; font-size: 6pt; width: 70px; }
.pat-val { color: #0f172a; font-weight: bold; }

/* ── Calculation block ── */
.calc-block { margin-bottom: 18px; }

/* ── Section header bar ── */
.sec-bar {
    background: #15803d; color: #fff;
    font-size: 7.5pt; font-weight: bold;
    text-transform: uppercase; letter-spacing: .07em;
    padding: 4px 8px; margin-bottom: 0;
}
.sec-bar-meta { font-weight: normal; font-size: 6.5pt; letter-spacing: 0; float: right; }

/* ── Summary banner ── */
.banner {
    background: #f0fdf4; border: 1px solid #bbf7d0;
    border-left: 3px solid #15803d;
    padding: 7px 10px; margin-bottom: 0;
}
.banner-formula { font-size: 9.5pt; font-weight: bold; color: #15803d; margin-bottom: 3px; }
.banner-detail  { font-size: 7.5pt; color: #166534; margin-bottom: 1px; }

/* ── 3-col results grid ── */
.grid-table { width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0; }
.grid-table td { vertical-align: top; padding: 8px 10px; border-right: 1px solid #e2e8f0; }
.grid-table td:last-child { border-right: none; }
.col-head {
    font-size: 6pt; font-weight: bold; text-transform: uppercase;
    letter-spacing: .08em; color: #64748b; margin-bottom: 7px;
    padding-bottom: 4px; border-bottom: 1px solid #e2e8f0;
}
.row-lbl  { font-size: 7pt; color: #64748b; margin-bottom: 1px; }
.row-val  { font-size: 9.5pt; font-weight: bold; color: #0f172a; margin-bottom: 1px; }
.row-val.green { color: #15803d; }
.row-sub  { font-size: 6.5pt; color: #94a3b8; margin-bottom: 6px; }

/* ── Startup protocol ── */
.startup {
    background: #f8fafc; border: 1px solid #e2e8f0;
    font-size: 7.5pt; color: #475569;
    padding: 5px 8px; margin-top: 0;
}
.startup strong { color: #0f172a; }

/* ── Adequacy badge ── */
.badge-ok  { background: #dcfce7; color: #15803d; padding: 1px 5px; border-radius: 2px; font-size: 6.5pt; font-weight: bold; }
.badge-err { background: #fee2e2; color: #b91c1c; padding: 1px 5px; border-radius: 2px; font-size: 6.5pt; font-weight: bold; }

/* ── Notes ── */
.notes-box { background: #fffbeb; border: 1px solid #fde68a; font-size: 7.5pt; color: #1e293b; padding: 4px 8px; margin-top: 0; }

/* ── Disclaimer ── */
.disclaimer { font-size: 6pt; color: #94a3b8; line-height: 1.5;
              border-top: 1px solid #e2e8f0; margin-top: 16px; padding-top: 6px; }

/* ── Empty ── */
.empty { font-size: 9pt; color: #94a3b8; padding: 20px 0; text-align: center; }
</style>
</head>
<body>

{{-- ── Letterhead ────────────────────────────────────────────────── --}}
<table class="hdr-table">
    <tr>
        @if($letterhead)
        <td class="hdr-logo"><img src="{{ $letterhead }}" alt=""></td>
        @endif
        <td class="hdr-text">
            <div class="hdr-brand">MindfulNutrico</div>
            <div class="hdr-sub">Enteral Nutrition &mdash; Tube Feed Recommendations &mdash; SASPEN / ESPEN</div>
        </td>
        <td class="hdr-meta">
            <strong>{{ now()->format('d F Y') }}</strong><br>
            {{ auth()->user()->name }}
        </td>
    </tr>
</table>
<hr class="divider">

<div class="page-title">Tube Feed Prescription</div>
<div class="page-sub">Enteral Nutrition Calculation Report</div>

{{-- ── Patient demographics ──────────────────────────────────────── --}}
<div class="patient-box">
    <table class="patient-grid">
        <tr>
            <td class="pat-lbl">Patient</td>
            <td class="pat-val">{{ $patient->full_name }}</td>
            <td class="pat-lbl">Gender</td>
            <td class="pat-val">{{ ucfirst($patient->gender ?? '—') }}</td>
            <td class="pat-lbl">Age</td>
            <td class="pat-val">{{ $patient->age ? $patient->age.' yrs' : '—' }}</td>
            <td class="pat-lbl">Weight</td>
            <td class="pat-val">{{ $patient->weight ? $patient->weight.' kg' : '—' }}</td>
            <td class="pat-lbl">Height</td>
            <td class="pat-val">{{ $patient->height ? $patient->height.' cm' : '—' }}</td>
            <td class="pat-lbl">BMI</td>
            <td class="pat-val">{{ $patient->bmi ? number_format($patient->bmi,1).' kg/m<sup>2</sup>' : '—' }}</td>
        </tr>
    </table>
</div>

{{-- ── Calculations ──────────────────────────────────────────────── --}}
@if($calculations->isEmpty())
    <div class="empty">No saved calculations for this patient.</div>
@else

@php
$formulaDb = [
    '1.0' => ['proteinGL' => 40.0,  'carbsGL' => 127.0, 'fatGL' => 35.4, 'freeWater' => 0.85],
    '1.2' => ['proteinGL' => 55.5,  'carbsGL' => 169.4, 'fatGL' => 39.3, 'freeWater' => 0.80],
    '1.5' => ['proteinGL' => 62.0,  'carbsGL' => 200.0, 'fatGL' => 50.0, 'freeWater' => 0.70],
];

$heightCm = (float)($patient->height ?? 0);
$ageYrs   = (int)($patient->age ?? 0);
$isMale   = strtolower($patient->gender ?? 'male') !== 'female';
$actualWt = (float)($patient->weight ?? 0);
$heightIn = $heightCm / 2.54;
$devineIbw = $heightCm > 0
    ? max(0, round(($isMale ? 50.0 : 45.5) + 2.3 * max(0, $heightIn - 60), 1))
    : null;
$bmi = (float)($patient->bmi ?? 0);
$bmiClass = $bmi >= 40 ? 'Obese class III' : ($bmi >= 35 ? 'Obese class II' : ($bmi >= 30 ? 'Obese class I' : ($bmi >= 25 ? 'Overweight' : ($bmi >= 18.5 ? 'Normal weight' : 'Underweight'))));
@endphp

@foreach($calculations as $i => $calc)
@php
    $density      = (string) number_format((float)$calc->formula_density, 1);
    $formula      = $formulaDb[$density] ?? $formulaDb['1.0'];
    $volumeL      = $calc->daily_volume_ml / 1000;
    $fmlPro       = round($volumeL * $formula['proteinGL'], 1);
    $fmlCarbs     = round($volumeL * $formula['carbsGL'], 1);
    $fmlFat       = round($volumeL * $formula['fatGL'], 1);
    $fwMl         = round($calc->daily_volume_ml * $formula['freeWater']);
    $totalFluidMl = $fwMl + (float)$calc->additional_water_ml;
    $adequate     = $fmlPro >= $calc->protein_target_g;

    $flushFreqHours = (int) filter_var($calc->water_flush_frequency ?? '6-hourly', FILTER_SANITIZE_NUMBER_INT);
    $flushFreqHours = max(1, $flushFreqHours ?: 6);
    $flushesPerDay  = (int) round(24 / $flushFreqHours);
    $flushVolMl     = (int)($calc->water_flush_ml ?? 30);
    $flushTotalMl   = $flushVolMl * $flushesPerDay;

    $wtLabels = ['actual' => 'Actual body weight', 'ibw' => 'IBW (Devine)', 'abw' => 'Adjusted (NDW)'];
    $wtLabel  = $wtLabels[$calc->weight_type] ?? strtoupper($calc->weight_type);
@endphp

<div class="calc-block">

    {{-- Section header bar --}}
    <div class="sec-bar">
        Calculation #{{ $i + 1 }}@if($calc->label) &mdash; {{ $calc->label }}@endif
        <span class="sec-bar-meta">{{ $calc->created_at->format('d M Y H:i') }}</span>
    </div>

    {{-- Summary banner (mirrors UI banner) --}}
    <div class="banner">
        <div class="banner-formula">
            Formula {{ number_format((float)$calc->formula_density,1) }} kcal/mL &mdash;
            Goal rate: {{ number_format($calc->rate_ml_per_hour,1) }} mL/hr
            over {{ $calc->feeding_hours_per_day }}h
        </div>
        <div class="banner-detail">
            &#x25B8; Water flush: {{ $flushVolMl }} mL every {{ $calc->water_flush_frequency ?? '6-hourly' }}
            ({{ $flushesPerDay }}&times;/day &mdash; {{ number_format($flushTotalMl) }} mL/day)
        </div>
    </div>

    {{-- 3-column grid (Macronutrients | Fluid | Anthropometrics) --}}
    <table class="grid-table">
        <tr>

            {{-- Macronutrients --}}
            <td width="33%">
                <div class="col-head">Macronutrients</div>

                <div class="row-lbl">Feed Calories</div>
                <div class="row-val">{{ number_format($calc->energy_target_kcal,0) }} kcal</div>
                <div class="row-sub">({{ $calc->energy_kcal_per_kg }} kcal/kg &times; {{ number_format($calc->weight_kg,1) }}&thinsp;kg)</div>

                <div class="row-lbl">Total Protein</div>
                <div class="row-val">{{ number_format($calc->protein_target_g,1) }} g</div>
                <div class="row-sub">({{ $calc->protein_g_per_kg }} g/kg &times; {{ number_format($calc->weight_kg,1) }}&thinsp;kg)</div>

                <div class="row-lbl">Total Carbohydrates</div>
                <div class="row-val">{{ $fmlCarbs }} g</div>
                <div class="row-sub">from {{ number_format($calc->daily_volume_ml,0) }} mL formula</div>

                <div class="row-lbl">Total Fat</div>
                <div class="row-val">{{ $fmlFat }} g</div>
            </td>

            {{-- Fluid --}}
            <td width="33%">
                <div class="col-head">Fluid</div>

                <div class="row-lbl">Total Fluids</div>
                <div class="row-val green">{{ number_format($totalFluidMl,0) }} mL</div>
                <div class="row-sub">({{ number_format($totalFluidMl / max(1,(float)$calc->weight_kg),1) }} mL/kg/day)</div>

                <div class="row-lbl">Daily Needs (35 mL/kg)</div>
                <div class="row-val">{{ number_format($calc->fluid_requirement_ml,0) }} mL</div>
                <div class="row-sub">35 mL/kg/day</div>

                <div class="row-lbl">Water Flush</div>
                <div class="row-val">{{ $flushVolMl }} mL every {{ $calc->water_flush_frequency ?? '6-hourly' }}</div>
                <div class="row-sub">{{ $flushesPerDay }}&times;/day &mdash; {{ number_format($flushTotalMl,0) }} mL/day total</div>
            </td>

            {{-- Anthropometrics --}}
            <td width="34%">
                <div class="col-head">Anthropometry</div>

                <div class="row-lbl">Ideal Body Weight (Devine)</div>
                <div class="row-val">{{ $devineIbw !== null ? number_format($devineIbw,1).' kg' : '—' }}</div>
                <div class="row-sub">&nbsp;</div>

                <div class="row-lbl">Actual Body Weight</div>
                <div class="row-val">{{ $actualWt > 0 ? number_format($actualWt,1).' kg' : '—' }}</div>
                <div class="row-sub">&nbsp;</div>

                <div class="row-lbl">Nutritional Weight Used</div>
                <div class="row-val green">{{ number_format($calc->weight_kg,1) }} kg</div>
                <div class="row-sub">{{ $wtLabel }}</div>

                <div class="row-lbl">BMI</div>
                <div class="row-val">{{ $bmi > 0 ? number_format($bmi,1).' kg/m<sup>2</sup>' : '—' }}</div>
                <div class="row-sub">{{ $bmiClass }}</div>
            </td>

        </tr>
    </table>

    {{-- Start-up protocol --}}
    <div class="startup">
        <strong>Start-up:</strong>
        Start at 20 mL/hr, titrate by 10&ndash;20 mL/hr every 4 hours to goal rate of
        <strong>{{ number_format($calc->rate_ml_per_hour,1) }} mL/hr</strong>.
        &nbsp;
        <strong>Water flush:</strong> {{ $flushVolMl }} mL every {{ $calc->water_flush_frequency ?? '6-hourly' }}
        ({{ $flushesPerDay }}&times;/day &mdash; {{ number_format($flushTotalMl,0) }} mL/day).
    </div>

    @if($calc->notes)
    <div class="notes-box"><strong>Notes:</strong> {{ $calc->notes }}</div>
    @endif

</div>
@endforeach

@endif

<div class="disclaimer">
    This report is generated from MindfulNutrico and is intended for use by registered dieticians only.
    Calculations are based on SASPEN/ESPEN guidelines and generic enteral formula data.
    Verify all values against current product datasheets and clinical status before prescribing.
</div>

</body>
</html>
