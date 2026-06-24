<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dietetic Assessment &amp; Care Plan — {{ $patient->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Figtree', 'Segoe UI', sans-serif;
            background: #525659;
            color: #0d1f0c;
            line-height: 1.5;
        }

        /* ── SCREEN TOOLBAR ── */
        .screen-toolbar {
            max-width: 900px;
            margin: 0 auto;
            padding: 1rem 1rem .5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .6rem;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .82rem;
            color: #d1e8d9;
            text-decoration: none;
        }
        .back-link:hover { color: #fff; }
        .toolbar-buttons { display: flex; gap: .5rem; flex-wrap: wrap; }
        .btn-toolbar {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .45rem 1.1rem;
            font-size: .82rem;
            font-weight: 700;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-toolbar svg { width: .9rem; height: .9rem; flex-shrink: 0; }
        .btn-pdf   { background: linear-gradient(135deg,#679F5F,#429677); color:#fff; box-shadow:0 3px 10px rgba(66,150,119,.35); }
        .btn-pdf:hover { opacity:.88; }

        /* ── PAGE ── */
        .page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            padding: 15mm;
            box-shadow: 0 0 10px rgba(0,0,0,.5);
            margin: 0 auto 2rem;
        }

        /* ── HEADER ── */
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .logo-placeholder {
            width: 80px;
            height: 80px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            color: #aaa;
            flex-shrink: 0;
        }
        .logo-placeholder img { width: 80px; height: auto; }
        .title-area { text-align: center; flex-grow: 1; padding: 0 12px; }
        .title-area h1 { color: #003366; font-size: 22px; font-weight: 800; text-transform: uppercase; letter-spacing: -.01em; margin-bottom: 4px; }
        .title-area .doc-sub { font-size: 11px; font-weight: 700; color: #555; letter-spacing: .03em; }
        .contact-info { font-size: 11px; text-align: right; line-height: 1.6; color: #333; }
        .contact-info strong { font-size: 12px; color: #003366; }

        hr.divider { border: none; border-top: 2px solid #002d58; margin: 10px 0 14px; }

        /* ── PATIENT INFO GRID ── */
        .patient-info-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 15px;
            font-size: 12px;
            margin-bottom: 20px;
        }
        .field {
            display: flex;
            border-bottom: 1px solid #ccc;
            margin-bottom: 5px;
            padding-bottom: 2px;
            gap: 4px;
        }
        .field label { font-weight: bold; white-space: nowrap; margin-right: 3px; }
        .field span { flex-grow: 1; }
        .abcd-box {
            border: 1px solid #006442;
            border-radius: 5px;
            padding: 8px;
            font-size: 10px;
            background: #f0fff4;
            line-height: 1.75;
            margin-top: 4px;
        }
        .abcd-box strong { color: #003366; }

        /* ── MAIN TWO-COL ── */
        .main-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        /* ── SECTION HEADERS ── */
        .section-header {
            color: white;
            padding: 5px 10px;
            font-size: 13px;
            font-weight: bold;
            display: flex;
            align-items: center;
            border-radius: 3px 3px 0 0;
            margin-bottom: 0;
        }
        .circle-label {
            background: white;
            width: 18px; height: 18px;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin-right: 10px;
            font-weight: 900;
            font-size: 10px;
            flex-shrink: 0;
        }
        .bg-a { background-color: #002d58; }
        .bg-b { background-color: #1d3557; }
        .bg-c { background-color: #006442; }
        .bg-d { background-color: #5b21b6; }
        .circle-a { color: #002d58; }
        .circle-b { color: #1d3557; }
        .circle-c { color: #006442; }
        .circle-d { color: #5b21b6; }

        /* Section D — Tube Recommendations */
        .tube-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 8px; }
        .tube-col { background: #f8f9fa; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 10px; font-size: 10px; }
        .tube-col-title { font-size: 10px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: .04em; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 6px; }
        .tube-row { display: flex; justify-content: space-between; padding: 2px 0; border-bottom: 1px solid #f3f4f6; }
        .tube-row:last-child { border-bottom: none; }
        .tube-label { color: #6b7280; font-size: 9px; }
        .tube-val { font-weight: 700; color: #0d1f0c; font-size: 10px; }
        .tube-banner { background: linear-gradient(135deg,#5b21b6,#7c3aed); color:#fff; border-radius:6px; padding:6px 10px; margin-bottom:8px; font-size:9px; display:flex; gap:12px; flex-wrap:wrap; }
        .tube-banner span { opacity:.9; }
        .tube-banner strong { opacity:1; }

        .content-box {
            border: 1px solid #d1d8e0;
            border-top: none;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .sub-header {
            color: #2d6a4f;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 1px solid #eee;
            margin: 8px 0 5px;
            padding-bottom: 2px;
        }
        .sub-header:first-child { margin-top: 0; }

        /* ── TABLES ── */
        table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 10px; }
        th, td { border: 1px solid #ddd; padding: 4px 6px; text-align: left; }
        th { background: #f2f2f2; font-size: 9px; font-weight: 700; }
        td.r, th.r { text-align: right; }
        tr.tot td { background: #f2f4f7; font-weight: 700; border-top: 2px solid #aaa; }

        /* ── STAMP ── */
        .stamp-box {
            border: 2px solid #002d58;
            border-radius: 8px;
            padding: 12px 16px;
            text-align: center;
            width: 160px;
            position: relative;
            overflow: hidden;
        }
        .stamp-name { font-size: 12px; font-weight: 800; color: #002d58; }
        .stamp-reg  { font-size: 10px; color: #444; margin-top: 2px; }
        .stamp-line { border-top: 1px solid #002d58; margin: 7px 0 5px; }
        .stamp-role { font-size: 9px; font-weight: 700; color: #006442; text-transform: uppercase; letter-spacing: .05em; }
        .stamp-date { font-size: 10px; color: #555; margin-top: 3px; }
        .stamp-verified { font-size: 9px; color: #15803d; margin-top: 4px; font-weight: 700; }
        .stamp-bg {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%,-50%) rotate(-30deg);
            font-size: 28px;
            font-weight: 900;
            color: rgba(0,45,88,.06);
            letter-spacing: 4px;
            text-transform: uppercase;
            white-space: nowrap;
            pointer-events: none;
        }

        /* ── FOOTER ── */
        footer {
            margin-top: 20px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 40px;
            align-items: start;
            font-size: 11px;
        }

        .confidential-bar {
            text-align: center;
            font-size: 9px;
            color: #888;
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }

        /* ── PRINT ── */
        @media print {
            body { background: white; }
            .screen-toolbar { display: none; }
            .page { box-shadow: none; padding: 10mm; margin: 0; width: 100%; min-height: auto; }
        }
    </style>
</head>
<body>

@php
    $user      = auth()->user();
    $genDate   = now()->format('d M Y');
    $genTime   = now()->format('H:i');
    $lastVisit = $patient->visits()->latest('visited_at')->first();
    $bmiCat    = strtolower($patient->bmi_category ?? 'normal');
    $ap        = $patient->appetite;
@endphp

{{-- SCREEN TOOLBAR --}}
<div class="screen-toolbar">
    <a href="{{ route('patients.show', $patient->id) }}" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Back to {{ $patient->name }}
    </a>
    <div class="toolbar-buttons" style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap">
        {{-- Date filter --}}
        <form method="GET" action="{{ route('patients.report', $patient->id) }}" id="report-date-form"
              style="display:flex;align-items:center;gap:.35rem">
            <label style="font-size:.75rem;font-weight:700;color:#d1e8d9;white-space:nowrap">As of</label>
            <input type="date" name="as_of" id="report-as-of"
                   value="{{ $asOf ?? '' }}"
                   style="padding:.3rem .5rem;border:none;border-radius:5px;font-size:.8rem;background:#fff;color:#0d1f0c;cursor:pointer"
                   onchange="document.getElementById('report-date-form').submit()">
            @if($asOf)
            <a href="{{ route('patients.report', $patient->id) }}"
               style="font-size:.72rem;color:#fbbf24;font-weight:700;text-decoration:none;white-space:nowrap">&#x2715; Clear</a>
            @endif
        </form>
        <a class="btn-toolbar btn-pdf" id="btn-pdf-report"
           href="{{ route('patients.report.pdf', $patient->id) }}{{ $asOf ? '?as_of=' . $asOf : '' }}"
           download>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download PDF
        </a>
    </div>
</div>

<div class="page">

    {{-- LETTERHEAD (full-width if set) --}}
    @if(!empty($letterhead))
    <div style="text-align:center;margin-bottom:14px">
        <img src="{{ $letterhead }}" style="width:100%;height:auto;display:block">
    </div>
    @endif

    {{-- HEADER --}}
    <header>
        <div class="logo-placeholder">
            @if(!empty($letterhead))
                <img src="{{ $letterhead }}" style="width:80px;height:auto">
            @else
                YOUR<br>LOGO
            @endif
        </div>
        <div class="title-area">
            <h1>Dietetic Assessment &amp; Care Plan</h1>
            <div class="doc-sub">ABCD GUIDELINES &mdash; PATIENT REPORT</div>
        </div>
        <div class="contact-info">
            <strong>{{ $user->name }}</strong><br>
            @if($user->dietician_number)Reg. No. {{ $user->dietician_number }}<br>@endif
            {{ $user->email }}<br>
            <span style="color:#888">{{ $genDate }}</span>
        </div>
    </header>

    <hr class="divider">

    {{-- PATIENT INFO GRID --}}
    <div class="patient-info-grid">
        <div>
            <div class="field"><label>Patient Name:</label><span>{{ $patient->name }}</span></div>
            <div class="field" style="gap:10px">
                <label>Date of Birth:</label><span style="min-width:70px">{{ $patient->date_of_birth?->format('d M Y') ?? '—' }}</span>
                <label>Age:</label><span style="min-width:30px">{{ $patient->age }}</span>
                <label>Gender:</label>
                <span>
                    @php $g = $patient->gender; @endphp
                    {{ $g === 'male' ? '☑' : '☐' }} M &nbsp; {{ $g === 'female' ? '☑' : '☐' }} F
                </span>
            </div>
            <div class="field">
                <label>{{ $patient->id_type === 'passport' ? 'Passport No.' : 'Patient ID' }}:</label>
                <span>{{ $patient->id_number ?? '—' }}</span>
            </div>
            <div class="field"><label>Contact:</label><span>{{ $patient->email ?? '—' }}</span></div>
        </div>
        <div>
            <div class="field">
                <label>Date of Assessment:</label>
                <span>{{ $lastVisit?->visited_at->format('d M Y') ?? '—' }}</span>
            </div>
            <div class="field"><label>Report Date:</label><span>{{ $genDate }}</span></div>
            <div class="field"><label>Referred By:</label><span>{{ $patient->referred_by ?? '—' }}</span></div>
            <div class="field"><label>Diagnosis:</label><span>{{ $patient->medical_history ?? '—' }}</span></div>
            <div class="abcd-box">
                <strong>ABCD FRAMEWORK</strong><br>
                A &mdash; Assessment &nbsp;|&nbsp; B &mdash; Body Composition<br>
                C &mdash; Calculation &nbsp;|&nbsp; D &mdash; Dietetic Intervention
            </div>
        </div>
    </div>

    {{-- MAIN TWO-COLUMN --}}
    <div class="main-container">

        {{-- LEFT COLUMN --}}
        <div>

            {{-- SECTION A --}}
            <div class="section-header bg-a">
                <span class="circle-label circle-a">A</span> ASSESSMENT
            </div>
            <div class="content-box">
                <div class="sub-header">Subjective Assessment</div>
                <div class="field"><label>Chief Complaint:</label><span>{{ $patient->reason_for_assessment ?? '—' }}</span></div>
                <div class="field"><label>Medical History:</label><span>{{ $patient->medical_history ?? '—' }}</span></div>
                <div class="field"><label>Medications:</label><span>{{ $patient->medications ?? '—' }}</span></div>
                <div class="field">
                    <label>Allergies:</label>
                    <span @if($patient->allergies) style="color:#b91c1c;font-weight:600" @endif>{{ $patient->allergies ?? '—' }}</span>
                </div>
                <div class="field"><label>Dietary History:</label><span>{{ $patient->dietary_history ?? '—' }}</span></div>
                <div class="field" style="border:none">
                    <label>Appetite:</label>
                    <span>
                        {{ $ap === 'good' ? '☑' : '☐' }} Good &nbsp;
                        {{ $ap === 'fair' ? '☑' : '☐' }} Fair &nbsp;
                        {{ $ap === 'poor' ? '☑' : '☐' }} Poor
                    </span>
                </div>
            </div>

            {{-- SECTION B --}}
            <div class="section-header bg-b">
                <span class="circle-label circle-b">B</span> BODY COMPOSITION &amp; CLINICAL
            </div>
            <div class="content-box">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div>
                        <div class="sub-header">Anthropometrics</div>
                        <div class="field"><label>Height:</label><span>{{ $patient->height ?? '—' }} cm</span></div>
                        <div class="field"><label>Weight:</label><span>{{ $patient->weight ?? '—' }} kg</span></div>
                        <div class="field">
                            <label>BMI:</label>
                            <span>
                                {{ $patient->bmi ? number_format($patient->bmi, 1) : '—' }} kg/m²
                                @if($patient->bmi_category) &nbsp;<em style="font-size:9px;color:#555">({{ $patient->bmi_category }})</em>@endif
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="sub-header">Clinical Data</div>
                        <div class="field"><label>BP:</label><span>— mmHg</span></div>
                        <div class="field"><label>HR:</label><span>— bpm</span></div>
                        <div class="field"><label>Labs:</label><span>—</span></div>
                    </div>
                </div>
            </div>

        </div>{{-- end left column --}}

        {{-- RIGHT COLUMN --}}
        <div>

            {{-- SECTION C --}}
            <div class="section-header bg-c">
                <span class="circle-label circle-c">C</span> CALCULATION OF REQUIREMENTS
            </div>
            <div class="content-box">

                <div class="sub-header">Energy Requirements</div>
                <table style="margin-bottom:10px">
                    <tbody>
                        <tr>
                            <td>EER (BMR &mdash; Mifflin-St Jeor)</td>
                            <td class="r">{{ $bmrKj ? number_format(round($bmrKj / 4.184)) : '—' }} kcal/day</td>
                        </tr>
                        <tr class="tot">
                            <td>Total Energy Requirement (TEE)</td>
                            <td class="r">{{ $teeKcal ? number_format($teeKcal) : '—' }} kcal/day</td>
                        </tr>
                    </tbody>
                </table>

                <div class="sub-header">Macronutrients</div>
                <table>
                    <thead>
                        <tr>
                            <th>Nutrient</th>
                            <th class="r">Grams</th>
                            <th class="r">% Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Carbohydrates</td>
                            <td class="r">{{ $recCho_g !== null ? $recCho_g.'g' : '—' }}</td>
                            <td class="r">{{ $choPct !== null ? $choPct.'%' : '—' }}</td>
                        </tr>
                        <tr>
                            <td>Protein</td>
                            <td class="r">{{ $recPro_g !== null ? $recPro_g.'g' : '—' }}</td>
                            <td class="r">{{ $proPct !== null ? $proPct.'%' : '—' }}</td>
                        </tr>
                        <tr>
                            <td>Fats</td>
                            <td class="r">{{ $recFat_g !== null ? $recFat_g.'g' : '—' }}</td>
                            <td class="r">{{ $fatPct !== null ? $fatPct.'%' : '—' }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>{{-- end right column --}}
    </div>{{-- end main-container --}}

    {{-- SECTION D — Tube Recommendations (Package 3 only) --}}
    @if($isPackage3 && $enteral)
    @php
        $dCond       = \App\Models\EnteralNutritionCalculation::CONDITIONS[$enteral->clinical_condition] ?? $enteral->clinical_condition;
        $dDensity    = (float)$enteral->formula_density;
        $dVol        = (int)$enteral->daily_volume_ml;
        $dRate       = (float)$enteral->rate_ml_per_hour;
        $dHours      = (int)$enteral->feeding_hours_per_day;
        $dEnergyKcal = (float)$enteral->energy_target_kcal;
        $dProteinG   = (float)$enteral->protein_target_g;
        $dWeightKg   = (float)$enteral->weight_kg;
        $dWeightType = $enteral->weight_type ?? 'actual';
        $dWeightLabel = match($dWeightType) { 'ibw' => 'IBW', 'abw' => 'ABW', default => 'Actual' };

        // Macros from formula density (approximate split)
        $dMacros = [
            '1.0' => ['cho_pct'=>54,'pro_pct'=>16,'fat_pct'=>30],
            '1.2' => ['cho_pct'=>52,'pro_pct'=>17,'fat_pct'=>31],
            '1.5' => ['cho_pct'=>50,'pro_pct'=>18,'fat_pct'=>32],
        ];
        $dm = $dMacros[(string)number_format($dDensity,1)] ?? $dMacros['1.0'];
        $dChoG   = round($dVol * $dDensity * $dm['cho_pct'] / 100 / 4, 1);
        $dProG   = round($dVol * $dDensity * $dm['pro_pct'] / 100 / 4, 1);
        $dFatG   = round($dVol * $dDensity * $dm['fat_pct'] / 100 / 9, 1);

        // Fluid
        $dFwFrac = match(true) { $dDensity >= 1.45 => 0.76, $dDensity >= 1.15 => 0.82, default => 0.85 };
        $dFwMl      = round($dVol * $dFwFrac);
        $dAddWater  = (int)$enteral->additional_water_ml;
        $dFluidReq  = (int)$enteral->fluid_requirement_ml;
        $dTotalFluid = $dFwMl + $dAddWater;

        // Flush
        $dFlushFreqStr = $enteral->water_flush_frequency ?? '6-hourly';
        $dFlushHours   = max(1, (int)filter_var($dFlushFreqStr, FILTER_SANITIZE_NUMBER_INT) ?: 6);
        $dFlushPerDay  = (int)round(24 / $dFlushHours);
        $dFlushMl      = (int)($enteral->water_flush_ml ?? 30);
        $dFlushTotal   = $dFlushMl * $dFlushPerDay;

        // Anthropometrics
        $dHeightCm = (float)($patient->height ?? 0);
        $dIsMale   = strtolower($patient->gender ?? '') === 'male';
        $dHeightIn = $dHeightCm / 2.54;
        $dIbw      = $dHeightCm > 0 ? max(0, round(($dIsMale ? 50.0 : 45.5) + 2.3 * max(0, $dHeightIn - 60), 1)) : null;
        $dActualBw = (float)($patient->weight ?? 0);
        $dBmi      = $dHeightCm > 0 && $dActualBw > 0 ? round($dActualBw / pow($dHeightCm/100, 2), 1) : null;
        $dBmiClass = match(true) {
            $dBmi === null         => 'Normal',
            $dBmi < 18.5           => 'Underweight',
            $dBmi < 25.0           => 'Normal',
            $dBmi < 30.0           => 'Overweight',
            default                => 'Obese',
        };

        // Protein adequacy
        $dProAdequate = $dProG > 0 && $dProteinG > 0 ? ($dProG >= $dProteinG * 0.95) : null;

        $dCalcDate = $enteral->created_at?->format('d M Y') ?? '';
    @endphp
    <div style="margin-top:14px">
        <div class="section-header bg-d">
            <span class="circle-label circle-d">D</span> TUBE FEEDING RECOMMENDATIONS
            @if($dCalcDate)<span style="font-size:9px;font-weight:400;opacity:.8;margin-left:8px">Calculated {{ $dCalcDate }}</span>@endif
            @if($asOf ?? null)<span style="font-size:9px;font-weight:400;opacity:.8;margin-left:4px">&mdash; as of {{ \Carbon\Carbon::parse($asOf)->format('d M Y') }}</span>@endif
        </div>
        <div class="content-box" style="font-size:11px">

            {{-- Banner --}}
            <div class="tube-banner">
                <span>Condition: <strong>{{ $dCond }}</strong></span>
                <span>Formula: <strong>{{ $dDensity }} kcal/mL</strong></span>
                <span>Goal rate: <strong>{{ $dRate }} mL/hr &times; {{ $dHours }}h</strong></span>
                <span>Protein: <strong>{{ $dProG }}g</strong> delivered / <strong>{{ $dProteinG }}g</strong> target</span>
                <span>Flush: <strong>{{ $dFlushMl }}mL</strong> {{ $dFlushFreqStr }}</span>
            </div>

            {{-- 3 columns --}}
            <div class="tube-grid">
                {{-- Macronutrients --}}
                <div class="tube-col">
                    <div class="tube-col-title">Macronutrients</div>
                    <div class="tube-row"><span class="tube-label">Feed Calories</span><span class="tube-val">{{ number_format($dEnergyKcal) }} kcal</span></div>
                    <div class="tube-row"><span class="tube-label">Total Protein</span><span class="tube-val">{{ $dProteinG }}g/day</span></div>
                    <div class="tube-row"><span class="tube-label">Carbohydrates</span><span class="tube-val">{{ $dChoG }}g</span></div>
                    <div class="tube-row"><span class="tube-label">Fat</span><span class="tube-val">{{ $dFatG }}g</span></div>
                    @if($dProAdequate !== null)
                    <div style="margin-top:5px;padding:2px 6px;border-radius:10px;font-size:9px;font-weight:700;display:inline-block;background:{{ $dProAdequate ? '#dcfce7' : '#fef9c3' }};color:{{ $dProAdequate ? '#15803d' : '#92400e' }}">
                        {{ $dProAdequate ? '&#x2713; Protein target met' : '&#x26A0; Below protein target' }}
                    </div>
                    @endif
                </div>
                {{-- Fluid --}}
                <div class="tube-col">
                    <div class="tube-col-title">Fluid Management</div>
                    <div class="tube-row"><span class="tube-label">Total Fluids</span><span class="tube-val">{{ number_format($dTotalFluid) }} mL</span></div>
                    <div class="tube-row"><span class="tube-label">Daily Needs (35mL/kg)</span><span class="tube-val">{{ number_format($dFluidReq) }} mL</span></div>
                    <div class="tube-row"><span class="tube-label">Free Water (formula)</span><span class="tube-val">{{ number_format($dFwMl) }} mL</span></div>
                    <div class="tube-row"><span class="tube-label">Additional Water</span><span class="tube-val">{{ number_format($dAddWater) }} mL</span></div>
                    <div class="tube-row"><span class="tube-label">Water Flush</span><span class="tube-val">{{ $dFlushMl }}mL &times; {{ $dFlushPerDay }}/day = {{ number_format($dFlushTotal) }}mL</span></div>
                    <div style="margin-top:5px;font-size:9px;color:#6b7280">Flush {{ $dFlushMl }}mL {{ $dFlushFreqStr }}</div>
                </div>
                {{-- Anthropometrics --}}
                <div class="tube-col">
                    <div class="tube-col-title">Anthropometrics</div>
                    @if($dIbw !== null)
                    <div class="tube-row"><span class="tube-label">IBW (Devine)</span><span class="tube-val">{{ $dIbw }} kg</span></div>
                    @endif
                    <div class="tube-row"><span class="tube-label">Actual Body Weight</span><span class="tube-val">{{ $dActualBw }} kg</span></div>
                    <div class="tube-row"><span class="tube-label">Weight Used ({{ $dWeightLabel }})</span><span class="tube-val">{{ $dWeightKg }} kg</span></div>
                    @if($dBmi !== null)
                    <div class="tube-row"><span class="tube-label">BMI</span><span class="tube-val">{{ $dBmi }} &mdash; {{ $dBmiClass }}</span></div>
                    @endif
                    <div class="tube-row"><span class="tube-label">Energy Target</span><span class="tube-val">{{ $enteral->energy_kcal_per_kg }} kcal/kg</span></div>
                    <div class="tube-row"><span class="tube-label">Protein Target</span><span class="tube-val">{{ $enteral->protein_g_per_kg }} g/kg</span></div>
                </div>
            </div>

            {{-- Startup protocol --}}
            <div style="margin-top:8px;background:#f0fff4;border:1px solid #bbf7d0;border-radius:5px;padding:6px 8px;font-size:9px;color:#166534">
                <strong>Startup Protocol:</strong> Start at 20&ndash;30 mL/hr. Advance by 10&ndash;20 mL/hr every 4&ndash;8 hours as tolerated until goal rate of {{ $dRate }} mL/hr is achieved.
            </div>

        </div>
    </div>
    @elseif($isPackage3 && !$enteral)
    <div style="margin-top:14px">
        <div class="section-header bg-d">
            <span class="circle-label circle-d">D</span> TUBE FEEDING RECOMMENDATIONS
        </div>
        <div class="content-box" style="font-size:11px;color:#888;text-align:center;padding:14px">
            No enteral nutrition calculation found{{ $asOf ? ' on or before ' . \Carbon\Carbon::parse($asOf)->format('d M Y') : '' }}.
            <span style="font-size:9px;display:block;margin-top:4px">Use the Enteral Feed Calculator for this patient to populate Section D.</span>
        </div>
    </div>
    @endif

    {{-- FOOTER --}}
    <footer>
        <div>
            <div class="field"><label>Dietitian Name:</label><span>{{ $user->name }}</span></div>
            @if($user->dietician_number)
            <div class="field"><label>Registration No.:</label><span>{{ $user->dietician_number }}</span></div>
            @endif
            <div class="field"><label>Date:</label><span>{{ $genDate }}</span></div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end">
            <div class="stamp-box">
                <div class="stamp-bg">SIGNED</div>
                <div class="stamp-name">{{ $user->name }}</div>
                @if($user->dietician_number)
                <div class="stamp-reg">Reg. No. {{ $user->dietician_number }}</div>
                @endif
                <div class="stamp-line"></div>
                <div class="stamp-role">Registered Dietitian</div>
                <div class="stamp-date">{{ $genDate }}</div>
                <div class="stamp-verified">&#x2713; Digitally Verified</div>
            </div>
        </div>
    </footer>

    <div class="confidential-bar">
        This report is confidential and intended for the use of the patient and healthcare team only.
        &mdash; Generated {{ $genDate }} at {{ $genTime }}
    </div>

</div>{{-- end page --}}

</body>
</html>
