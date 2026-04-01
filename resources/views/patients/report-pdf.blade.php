<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Patient Report — {{ $patient->name }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        color: #0d1f0c;
        line-height: 1.5;
        background: #fff;
    }

    /* ── HEADER ── */
    .header {
        background-color: #1a4a36;
        color: #fff;
        padding: 18px 22px 14px;
        margin-bottom: 16px;
    }
    .header-top {
        width: 100%;
        margin-bottom: 8px;
    }
    .header-top td { vertical-align: top; }
    .header-title {
        font-size: 18px;
        font-weight: bold;
        color: #fff;
        letter-spacing: -0.3px;
    }
    .header-subtitle {
        font-size: 8px;
        color: rgba(255,255,255,0.6);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .header-right {
        text-align: right;
    }
    .header-right .brand {
        font-size: 7px;
        color: rgba(255,255,255,0.45);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    .header-right .date {
        font-size: 9px;
        color: rgba(255,255,255,0.7);
        margin-top: 3px;
    }
    .header-badges { margin-top: 6px; }
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 7.5px;
        font-weight: bold;
        background-color: rgba(255,255,255,0.15);
        color: #b0dba8;
        border: 1px solid rgba(255,255,255,0.2);
        margin-right: 4px;
    }
    .badge-alert {
        background-color: rgba(180,30,40,0.25);
        border-color: rgba(200,50,60,0.4);
        color: #fca5a5;
    }

    /* ── SECTION ── */
    .section { margin-bottom: 14px; }
    .section-title {
        font-size: 7.5px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.9px;
        color: #3a6b50;
        border-bottom: 1.5px solid #b8ddb4;
        padding-bottom: 4px;
        margin-bottom: 8px;
    }

    /* ── METRIC GRID ── */
    .metric-table { width: 100%; border-collapse: collapse; }
    .metric-table td { padding: 0 4px 0 0; vertical-align: top; width: 11.1%; }
    .metric-box {
        background-color: #f0f7ef;
        border: 1px solid #c8e0c4;
        border-radius: 4px;
        padding: 6px 7px;
        margin-bottom: 0;
    }
    .metric-box.hi {
        background-color: #e0f2ee;
        border-color: #8fcf8a;
    }
    .metric-label {
        font-size: 6.5px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #4a7060;
        margin-bottom: 2px;
    }
    .metric-value {
        font-size: 13px;
        font-weight: bold;
        color: #0d1f0c;
        line-height: 1.1;
    }
    .metric-unit {
        font-size: 6.5px;
        color: #4a7060;
        margin-top: 1px;
    }
    .bmi-pill {
        display: inline-block;
        padding: 1px 5px;
        border-radius: 8px;
        font-size: 6.5px;
        font-weight: bold;
        margin-top: 2px;
    }
    .bmi-underweight { background-color: #fef9c3; color: #854d0e; }
    .bmi-normal      { background-color: #dcfce7; color: #166534; }
    .bmi-overweight  { background-color: #ffedd5; color: #9a3412; }
    .bmi-obese       { background-color: #fee2e2; color: #991b1b; }

    /* ── OBESITY NOTE ── */
    .obese-note {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 4px;
        padding: 5px 8px;
        font-size: 8px;
        color: #b91c1c;
        margin-top: 6px;
    }

    /* ── MACRO GRID ── */
    .macro-table { width: 100%; border-collapse: collapse; }
    .macro-table td { width: 33.3%; padding: 0 4px 0 0; vertical-align: top; }
    .macro-box {
        border-radius: 4px;
        padding: 7px 8px;
        border: 1px solid;
    }
    .macro-cho { background-color: #fef9c3; border-color: #fde68a; }
    .macro-pro { background-color: #fce7f3; border-color: #f9a8d4; }
    .macro-fat { background-color: #e0f2fe; border-color: #7dd3fc; }
    .macro-label {
        font-size: 6.5px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #374151;
        margin-bottom: 3px;
    }
    .macro-pct {
        font-size: 16px;
        font-weight: bold;
        color: #0d1f0c;
        line-height: 1;
    }
    .macro-row {
        font-size: 7.5px;
        color: #374151;
        margin-top: 2px;
    }
    .macro-row strong { font-weight: bold; }

    /* ── TOTAL BAR ── */
    .tee-bar {
        background-color: #f0f7ef;
        border: 1px solid #b8ddb4;
        border-radius: 4px;
        padding: 5px 9px;
        font-size: 8px;
        color: #2e6e56;
        margin-top: 6px;
    }

    /* ── EXCHANGE TABLE ── */
    .et-table { width: 100%; border-collapse: collapse; font-size: 8px; }
    .et-table th {
        background-color: #f0f7ef;
        padding: 4px 6px;
        text-align: left;
        font-size: 7px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #4a7060;
        border-bottom: 1.5px solid #b8ddb4;
    }
    .et-table th.r, .et-table td.r { text-align: right; }
    .et-table td {
        padding: 4px 6px;
        border-bottom: 1px solid #edf5ec;
        color: #0d1f0c;
    }
    .et-table tr:last-child td { border-bottom: none; }
    .et-table .tot td {
        background-color: #f0f7ef;
        border-top: 1.5px solid #b8ddb4;
        font-weight: bold;
        font-size: 7.5px;
    }
    .et-name { font-weight: bold; }
    .et-nu   { text-align: center; font-weight: bold; color: #2e6e56; }
    .et-kj   { color: #2e6e56; font-weight: bold; }

    /* ── DETAILS TABLE ── */
    .details-table { width: 100%; border-collapse: collapse; }
    .details-table td {
        padding: 4px 7px;
        border-bottom: 1px solid #edf5ec;
        font-size: 8.5px;
        vertical-align: top;
    }
    .details-table tr:last-child td { border-bottom: none; }
    .details-table td:first-child {
        font-weight: bold;
        color: #4a7060;
        width: 40%;
        font-size: 7.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ── FOOTER ── */
    .footer {
        background-color: #f0f7ef;
        border-top: 1px solid #b8ddb4;
        padding: 7px 22px;
        margin-top: 14px;
        width: 100%;
    }
    .footer-table { width: 100%; }
    .footer-note { font-size: 7px; color: #4a7060; }
    .footer-brand { font-size: 7px; font-weight: bold; color: #2e6e56; text-align: right; }

    /* ── PAGE ── */
    @page { margin: 14mm 14mm 14mm 14mm; size: A4 portrait; }
</style>
</head>
<body>

{{-- ── HEADER ── --}}
<div class="header">
    <table class="header-top" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="header-subtitle">Patient Clinical Report</div>
                <div class="header-title">{{ $patient->name }}</div>
                <div class="header-badges">
                    <span class="badge">{{ ucfirst($patient->gender) }}</span>
                    <span class="badge">Age {{ $patient->age }} yrs</span>
                    <span class="badge">{{ $patient->weight }} kg &middot; {{ $patient->height }} cm</span>
                    <span class="badge">AF {{ $patient->activity_factor }}</span>
                    @if($isObese)<span class="badge badge-alert">BMI &ge; 30 &mdash; Obesity adj. applied</span>@endif
                </div>
            </td>
            <td class="header-right">
                <div class="brand">Panamarex Outpatient Clinical Nutrition Toolkit</div>
                <div class="date">{{ now()->format('d M Y') }}</div>
            </td>
        </tr>
    </table>
</div>

{{-- ── SECTION 1: Anthropometrics & Energy ── --}}
<div class="section">
    <div class="section-title">Anthropometry &amp; Energy</div>
    @php $bmiCat = strtolower($patient->bmi_category ?? 'normal'); @endphp
    <table class="metric-table" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="metric-box">
                    <div class="metric-label">BMI</div>
                    <div class="metric-value">{{ $patient->bmi ? number_format($patient->bmi, 2) : '—' }}</div>
                    <div class="metric-unit">kg/m&sup2;</div>
                    <span class="bmi-pill bmi-{{ $bmiCat }}">{{ $patient->bmi_category }}</span>
                </div>
            </td>
            <td>
                <div class="metric-box">
                    <div class="metric-label">Ideal Body Weight (IBW)</div>
                    <div class="metric-value">{{ $patient->ibw ? number_format($patient->ibw, 1) : '—' }}</div>
                    <div class="metric-unit">kg</div>
                </div>
            </td>
            <td>
                <div class="metric-box">
                    <div class="metric-label">ABW</div>
                    <div class="metric-value">{{ $patient->abw ? number_format($patient->abw, 1) : '—' }}</div>
                    <div class="metric-unit">kg</div>
                </div>
            </td>
            <td>
                <div class="metric-box">
                    <div class="metric-label">Weight</div>
                    <div class="metric-value">{{ $patient->weight }}</div>
                    <div class="metric-unit">kg (actual)</div>
                </div>
            </td>
            <td>
                <div class="metric-box">
                    <div class="metric-label">Height</div>
                    <div class="metric-value">{{ $patient->height }}</div>
                    <div class="metric-unit">cm</div>
                </div>
            </td>
            <td>
                <div class="metric-box">
                    <div class="metric-label">Act. Factor</div>
                    <div class="metric-value">{{ $patient->activity_factor ?? '—' }}</div>
                    <div class="metric-unit">multiplier</div>
                </div>
            </td>
            <td>
                <div class="metric-box hi">
                    <div class="metric-label">BMR</div>
                    <div class="metric-value">{{ $bmrKj ? number_format($bmrKj) : '—' }}</div>
                    <div class="metric-unit">kJ/day</div>
                </div>
            </td>
            <td>
                <div class="metric-box hi">
                    <div class="metric-label">TEE</div>
                    <div class="metric-value">{{ $teeKj ? number_format(round($teeKj)) : '—' }}</div>
                    <div class="metric-unit">kJ/day</div>
                </div>
            </td>
            <td>
                <div class="metric-box hi">
                    <div class="metric-label">TEE</div>
                    <div class="metric-value">{{ $teeKcal ? number_format($teeKcal) : '—' }}</div>
                    <div class="metric-unit">kcal/day</div>
                </div>
            </td>
        </tr>
    </table>
    @if($isObese)
    <div class="obese-note">
        <strong>Obesity adjustment active (BMI {{ number_format($patient->bmi, 2) }} &ge; 30):</strong>
        BMR calculated using adjusted body weight (IBW + 0.4 &times; (actual &minus; IBW) = {{ number_format($patient->weight_for_bmr, 1) }} kg) rather than actual weight to avoid overestimating energy needs.
    </div>
    @endif
</div>

{{-- ── SECTION 2: Macronutrient Distribution ── --}}
@if($patient->macronutrients->count())
<div class="section">
    <div class="section-title">Macronutrient Distribution</div>
    <table class="macro-table" cellpadding="0" cellspacing="4">
        <tr>
            @php
                $macros = [
                    ['key'=>'carbohydrates','label'=>'Carbohydrates','cls'=>'cho','g'=>$recCho_g,'kj'=>$recCho_kj,'pct'=>$choPct],
                    ['key'=>'protein',      'label'=>'Protein',      'cls'=>'pro','g'=>$recPro_g,'kj'=>$recPro_kj,'pct'=>$proPct],
                    ['key'=>'fats',         'label'=>'Fat',          'cls'=>'fat','g'=>$recFat_g,'kj'=>$recFat_kj,'pct'=>$fatPct],
                ];
            @endphp
            @foreach($macros as $m)
            @php $macro = $patient->macronutrients->firstWhere('type', $m['key']); @endphp
            <td>
                <div class="macro-box macro-{{ $m['cls'] }}">
                    <div class="macro-label">{{ $m['label'] }}</div>
                    <div class="macro-pct">{{ $m['pct'] !== null ? $m['pct'].'%' : '—' }}</div>
                    <div class="macro-row">Grams/day: <strong>{{ $m['g'] !== null ? $m['g'].'g' : '—' }}</strong></div>
                    <div class="macro-row">Energy: <strong>{{ $m['kj'] !== null ? number_format($m['kj']).' kJ' : '—' }}</strong></div>
                    @if($macro)<div class="macro-row">Range: <strong>{{ (int)$macro->range_min }}–{{ (int)$macro->range_max }}%</strong></div>@endif
                </div>
            </td>
            @endforeach
        </tr>
    </table>
    <div class="tee-bar">
        <strong>Total TEE:</strong> {{ $teeKj ? number_format(round($teeKj)).' kJ' : '—' }} &nbsp;/&nbsp; {{ $teeKcal ? number_format($teeKcal).' kcal' : '—' }}
        &nbsp;&nbsp;&nbsp;
        <strong>Distribution:</strong> CHO {{ $choPct ?? '—' }}% &middot; PRO {{ $proPct ?? '—' }}% &middot; FAT {{ $fatPct ?? '—' }}%
    </div>
</div>
@endif

{{-- ── SECTION 3: Exchange Food Item Package ── --}}
@if($patient->exchangeTemplate && $patient->exchangeTemplate->items->count())
<div class="section">
    <div class="section-title">Exchange Food Item Package &mdash; {{ $patient->exchangeTemplate->name }}</div>
    <table class="et-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="min-width:110px">Exchange Item</th>
                <th style="text-align:center;width:40px">Units</th>
                <th class="r">CHO (g)</th>
                <th class="r">Protein (g)</th>
                <th class="r">Fat (g)</th>
                <th class="r">kJ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patient->exchangeTemplate->items as $item)
            @php $nu = $item->nu; @endphp
            <tr>
                <td class="et-name">{{ $item->name }}</td>
                <td class="et-nu">{{ $nu }}</td>
                <td class="r">{{ $item->cho_g !== null ? number_format($nu * $item->cho_g, 1) : '—' }}</td>
                <td class="r">{{ $item->protein_min_g !== null ? number_format($nu * $item->protein_min_g, 1) : '—' }}</td>
                <td class="r">{{ $item->fat_min_g !== null ? number_format($nu * $item->fat_min_g, 1) : '—' }}</td>
                <td class="r et-kj">{{ $item->kj !== null ? number_format($nu * $item->kj) : '—' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tbody class="tot">
            <tr class="tot">
                <td colspan="2" style="color:#4a7060;font-size:7px;text-transform:uppercase;letter-spacing:.5px">Total</td>
                <td class="r">{{ $etTotCho ? number_format($etTotCho, 1) : '—' }}</td>
                <td class="r">{{ $etTotPro ? number_format($etTotPro, 1) : '—' }}</td>
                <td class="r">{{ $etTotFat ? number_format($etTotFat, 1) : '—' }}</td>
                <td class="r et-kj">{{ $etTotKj ? number_format($etTotKj) : '—' }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endif

{{-- ── SECTION 4: Patient Details ── --}}
<div class="section">
    <div class="section-title">Patient Details</div>
    <table class="details-table" cellpadding="0" cellspacing="0">
        <tr><td>Full Name</td><td>{{ $patient->name }}</td></tr>
        <tr><td>Gender</td><td>{{ ucfirst($patient->gender) }}</td></tr>
        <tr><td>Age</td><td>{{ $patient->age }} years</td></tr>
        <tr><td>Actual Weight</td><td>{{ $patient->weight }} kg</td></tr>
        <tr><td>Height</td><td>{{ $patient->height }} cm</td></tr>
        <tr><td>Activity Factor</td><td>{{ $patient->activity_factor }}</td></tr>
        <tr><td>BMI</td><td>{{ $patient->bmi ? number_format($patient->bmi, 2).' kg/m²' : '—' }} — {{ $patient->bmi_category }}</td></tr>
        <tr><td>Ideal Body Weight (IBW)</td><td>{{ $patient->ibw ? number_format($patient->ibw, 1).' kg' : '—' }}</td></tr>
        <tr><td>Adjusted Body Weight (ABW)</td><td>{{ $patient->abw ? number_format($patient->abw, 1).' kg' : '—' }}</td></tr>
        <tr><td>BMR (Mifflin-St Jeor)</td><td>{{ $bmrKj ? number_format($bmrKj).' kJ/day' : '—' }}</td></tr>
        <tr><td>Total Energy Expenditure (TEE)</td><td>{{ $teeKj ? number_format(round($teeKj)).' kJ/day' : '—' }}{{ $teeKcal ? ' ('.number_format($teeKcal).' kcal/day)' : '' }}</td></tr>
    </table>
</div>

{{-- ── FOOTER ── --}}
<div class="footer">
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="footer-note">Generated {{ now()->format('d M Y \a\t H:i') }} &middot; Confidential patient record</td>
            <td class="footer-brand">Panamarex Outpatient Clinical Nutrition Toolkit</td>
        </tr>
    </table>
</div>

</body>
</html>
