<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient Report — {{ $patient->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 14px; }
        body {
            font-family: 'Figtree', sans-serif;
            background: #f5f7f5;
            color: #0d1f0c;
            line-height: 1.55;
        }

        /* ── SCREEN SHELL ── */
        .screen-shell {
            max-width: 900px;
            margin: 0 auto;
            padding: 1.5rem 1.5rem 3rem;
        }
        .screen-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: .75rem;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .82rem;
            color: #52705e;
            text-decoration: none;
        }
        .back-link:hover { color: #2e6e56; }
        .print-btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .5rem 1.25rem;
            background: linear-gradient(135deg, #679F5F, #429677);
            color: #fff;
            font-size: .875rem;
            font-weight: 700;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(103,159,95,.35);
        }
        .print-btn:hover { opacity: .88; }

        /* ── REPORT PAPER ── */
        .report-paper {
            background: #fff;
            border: 1px solid #d4e6d1;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(13,31,12,.08);
            overflow: hidden;
        }

        /* ── REPORT HEADER ── */
        .rpt-header {
            background: linear-gradient(135deg, #0d1f0c 0%, #2e6e56 55%, #679F5F 100%);
            padding: 2rem 2.25rem 1.75rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.5rem;
        }
        .rpt-header-left h1 {
            font-size: 1.55rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.03em;
            margin-bottom: .2rem;
        }
        .rpt-header-left .subtitle {
            font-size: .78rem;
            color: rgba(255,255,255,.55);
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 600;
        }
        .rpt-header-right {
            text-align: right;
        }
        .rpt-header-right .toolkit-name {
            font-size: .68rem;
            color: rgba(255,255,255,.45);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .2rem;
        }
        .rpt-header-right .report-date {
            font-size: .82rem;
            color: rgba(255,255,255,.65);
            font-weight: 600;
        }
        .rpt-header-badges {
            display: flex;
            gap: .5rem;
            margin-top: .85rem;
            flex-wrap: wrap;
        }
        .rpt-badge {
            padding: .2rem .65rem;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 700;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            color: #a8d5a2;
        }
        .rpt-badge.alert {
            background: rgba(210,30,51,.2);
            border-color: rgba(210,30,51,.4);
            color: #fca5a5;
        }

        /* ── SECTIONS ── */
        .rpt-body {
            padding: 1.75rem 2.25rem;
        }
        .rpt-section {
            margin-bottom: 2rem;
        }
        .rpt-section:last-child {
            margin-bottom: 0;
        }
        .rpt-section-title {
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #52705e;
            padding-bottom: .5rem;
            border-bottom: 2px solid #d4e6d1;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .rpt-section-title svg {
            width: .9rem;
            height: .9rem;
            color: #679F5F;
            flex-shrink: 0;
        }

        /* ── METRIC GRID ── */
        .metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: .75rem;
        }
        .metric-card {
            background: #f0f7ef;
            border: 1px solid #d4e6d1;
            border-radius: 7px;
            padding: .75rem 1rem;
        }
        .metric-card.highlight {
            background: linear-gradient(135deg, #f0f7ef, #e0f2ee);
            border-color: #9ecf9a;
        }
        .metric-label {
            font-size: .62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #52705e;
            margin-bottom: .2rem;
        }
        .metric-value {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0d1f0c;
            line-height: 1.1;
        }
        .metric-unit {
            font-size: .68rem;
            color: #52705e;
            font-weight: 600;
            margin-top: .1rem;
        }
        .bmi-pill {
            display: inline-block;
            padding: .15rem .55rem;
            border-radius: 999px;
            font-size: .65rem;
            font-weight: 700;
            margin-top: .25rem;
        }
        .bmi-pill.underweight { background: #fef9c3; color: #854d0e; }
        .bmi-pill.normal      { background: #dcfce7; color: #166534; }
        .bmi-pill.overweight  { background: #ffedd5; color: #9a3412; }
        .bmi-pill.obese       { background: #fee2e2; color: #991b1b; }

        /* ── INFO TABLE ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: .5rem .75rem;
            font-size: .875rem;
            border-bottom: 1px solid #edf5ec;
            vertical-align: top;
        }
        .info-table tr:last-child td { border-bottom: none; }
        .info-table td:first-child {
            font-weight: 700;
            color: #52705e;
            width: 42%;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        /* ── MACRO GRID ── */
        .macro-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .75rem;
        }
        .macro-card {
            border-radius: 7px;
            padding: .9rem 1rem;
            border: 1px solid;
        }
        .macro-card.cho { background: #fef9c3; border-color: #fde68a; }
        .macro-card.pro { background: #fce7f3; border-color: #f9a8d4; }
        .macro-card.fat { background: #e0f2fe; border-color: #7dd3fc; }
        .macro-card-label { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #374151; margin-bottom: .35rem; }
        .macro-card-pct   { font-size: 1.4rem; font-weight: 800; color: #0d1f0c; line-height: 1; }
        .macro-card-rows  { margin-top: .5rem; display: flex; flex-direction: column; gap: .2rem; }
        .macro-card-row   { display: flex; justify-content: space-between; font-size: .78rem; color: #374151; }
        .macro-card-row span:last-child { font-weight: 700; }

        /* ── EXCHANGE TABLE ── */
        .et-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .825rem;
        }
        .et-table thead th {
            background: #f0f7ef;
            padding: .55rem .75rem;
            text-align: left;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #52705e;
            border-bottom: 2px solid #d4e6d1;
        }
        .et-table thead th:not(:first-child):not(:nth-child(2)) { text-align: right; }
        .et-table tbody td {
            padding: .5rem .75rem;
            border-bottom: 1px solid #edf5ec;
            color: #0d1f0c;
        }
        .et-table tbody tr:last-child td { border-bottom: none; }
        .et-table tbody td:not(:first-child):not(:nth-child(2)) { text-align: right; }
        .et-table tfoot td {
            padding: .55rem .75rem;
            font-weight: 700;
            font-size: .8rem;
            background: #f0f7ef;
            border-top: 2px solid #d4e6d1;
        }
        .et-table tfoot td:not(:first-child):not(:nth-child(2)) { text-align: right; }
        .et-table .et-name { font-weight: 600; }
        .et-table .et-nu   { text-align: center !important; font-weight: 700; color: #2e6e56; }
        .et-table .et-kj   { color: #2e6e56; font-weight: 700; }

        /* ── DIVIDER ── */
        .rpt-divider {
            height: 1px;
            background: #d4e6d1;
            margin: 1.5rem 0;
        }

        /* ── FOOTER ── */
        .rpt-footer {
            background: #f0f7ef;
            border-top: 1px solid #d4e6d1;
            padding: 1rem 2.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .rpt-footer-note {
            font-size: .72rem;
            color: #52705e;
        }
        .rpt-footer-brand {
            font-size: .72rem;
            font-weight: 700;
            color: #2e6e56;
        }

        /* ── OBESITY NOTE ── */
        .obesity-note {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: .65rem .9rem;
            font-size: .78rem;
            color: #b91c1c;
            margin-top: .75rem;
        }

        /* ── PRINT ── */
        @media print {
            body { background: #fff; font-size: 11px; }
            .screen-shell { padding: 0; max-width: 100%; }
            .screen-toolbar { display: none; }
            .report-paper { border: none; box-shadow: none; border-radius: 0; }
            .rpt-body { padding: 1.25rem 1.5rem; }
            .rpt-header { padding: 1.25rem 1.5rem; }
            .rpt-footer { padding: .75rem 1.5rem; }
            .rpt-section { margin-bottom: 1.25rem; page-break-inside: avoid; }
            .et-table { font-size: .78rem; }
        }
    </style>
</head>
<body>

<div class="screen-shell">
    {{-- Toolbar (hidden when printing) --}}
    <div class="screen-toolbar">
        <a href="{{ route('patients.show', $patient->id) }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to {{ $patient->name }}
        </a>
        <div style="display:flex;gap:.65rem;flex-wrap:wrap">
            <a href="{{ route('patients.report.pdf', $patient->id) }}" class="print-btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                Download PDF
            </a>
            <button class="print-btn" style="background:linear-gradient(135deg,#4b6a7c,#2e4d5e)" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2m2 4h6a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2zm1-4h4v6H10v-6z"/></svg>
                Print
            </button>
        </div>
    </div>

    <div class="report-paper">

        {{-- Report Header --}}
        <div class="rpt-header">
            <div class="rpt-header-left">
                <div class="subtitle">Patient Clinical Report</div>
                <h1>{{ $patient->name }}</h1>
                <div class="rpt-header-badges">
                    <span class="rpt-badge">{{ ucfirst($patient->gender) }}</span>
                    <span class="rpt-badge">Age {{ $patient->age }} yrs</span>
                    <span class="rpt-badge">{{ $patient->weight }} kg &middot; {{ $patient->height }} cm</span>
                    @if($isObese)
                        <span class="rpt-badge alert">BMI &gt; 30 — Obesity adjustment applied</span>
                    @endif
                </div>
            </div>
            <div class="rpt-header-right">
                <div class="toolkit-name">Panamarex Outpatient Clinical Nutrition Toolkit</div>
                <div class="report-date">{{ now()->format('d M Y') }}</div>
            </div>
        </div>

        <div class="rpt-body">

            {{-- ── SECTION 1: Anthropometrics & Energy ── --}}
            <div class="rpt-section">
                <div class="rpt-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/></svg>
                    Anthropometrics &amp; Energy
                </div>
                <div class="metric-grid">
                    <div class="metric-card">
                        <div class="metric-label">BMI</div>
                        <div class="metric-value">{{ $patient->bmi ? number_format($patient->bmi, 1) : '—' }}</div>
                        <div class="metric-unit">kg/m²</div>
                        @php $bmiCat = strtolower($patient->bmi_category ?? 'normal'); @endphp
                        <span class="bmi-pill {{ $bmiCat }}">{{ $patient->bmi_category }}</span>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">IBW</div>
                        <div class="metric-value">{{ $patient->ibw ? number_format($patient->ibw, 1) : '—' }}</div>
                        <div class="metric-unit">kg</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">ABW</div>
                        <div class="metric-value">{{ $patient->abw ? number_format($patient->abw, 1) : '—' }}</div>
                        <div class="metric-unit">kg</div>
                    </div>
                    <div class="metric-card highlight">
                        <div class="metric-label">BMR</div>
                        <div class="metric-value">{{ $bmrKj ? number_format($bmrKj) : '—' }}</div>
                        <div class="metric-unit">kJ/day</div>
                    </div>
                    <div class="metric-card highlight">
                        <div class="metric-label">TEE</div>
                        <div class="metric-value">{{ $teeKj ? number_format(round($teeKj)) : '—' }}</div>
                        <div class="metric-unit">kJ/day</div>
                    </div>
                    <div class="metric-card highlight">
                        <div class="metric-label">TEE</div>
                        <div class="metric-value">{{ $teeKcal ? number_format($teeKcal) : '—' }}</div>
                        <div class="metric-unit">kcal/day</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Activity Factor</div>
                        <div class="metric-value">{{ $patient->activity_factor ?? '—' }}</div>
                        <div class="metric-unit">multiplier</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Weight</div>
                        <div class="metric-value">{{ $patient->weight }}</div>
                        <div class="metric-unit">kg (actual)</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-label">Height</div>
                        <div class="metric-value">{{ $patient->height }}</div>
                        <div class="metric-unit">cm</div>
                    </div>
                </div>

                @if($isObese)
                    <div class="obesity-note">
                        <strong>Obesity adjustment active (BMI {{ number_format($patient->bmi, 1) }} &gt; 30):</strong>
                        BMR calculated using adjusted body weight (IBW + 0.25 &times; (actual &minus; IBW) = {{ number_format($patient->weight_for_bmr, 1) }} kg) rather than actual weight to avoid overestimating energy needs.
                    </div>
                @endif
            </div>

            {{-- ── SECTION 2: Macronutrient Distribution ── --}}
            @if($patient->macronutrients->count())
            <div class="rpt-section">
                <div class="rpt-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2a10 10 0 0 1 0 20"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M2 12h20"/></svg>
                    Macronutrient Distribution
                </div>
                <div class="macro-grid">
                    @php
                        $macros = [
                            ['key'=>'carbohydrates','label'=>'Carbohydrates','class'=>'cho','g'=>$recCho_g,'kj'=>$recCho_kj,'pct'=>$choPct],
                            ['key'=>'protein',      'label'=>'Protein',      'class'=>'pro','g'=>$recPro_g,'kj'=>$recPro_kj,'pct'=>$proPct],
                            ['key'=>'fats',         'label'=>'Fat',          'class'=>'fat','g'=>$recFat_g,'kj'=>$recFat_kj,'pct'=>$fatPct],
                        ];
                    @endphp
                    @foreach($macros as $m)
                    @php $macro = $patient->macronutrients->firstWhere('type', $m['key']); @endphp
                    <div class="macro-card {{ $m['class'] }}">
                        <div class="macro-card-label">{{ $m['label'] }}</div>
                        <div class="macro-card-pct">{{ $m['pct'] !== null ? $m['pct'].'%' : '—' }}</div>
                        <div class="macro-card-rows">
                            <div class="macro-card-row"><span>Grams/day</span><span>{{ $m['g'] !== null ? $m['g'].'g' : '—' }}</span></div>
                            <div class="macro-card-row"><span>Energy</span><span>{{ $m['kj'] !== null ? number_format($m['kj']).' kJ' : '—' }}</span></div>
                            @if($macro)
                            <div class="macro-card-row"><span>Range</span><span>{{ (int)$macro->range_min }}–{{ (int)$macro->range_max }}%</span></div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top:.85rem;padding:.65rem .9rem;background:#f0f7ef;border:1px solid #d4e6d1;border-radius:6px;font-size:.8rem;color:#2e6e56;display:flex;gap:2rem;flex-wrap:wrap">
                    <span><strong>Total TEE:</strong> {{ $teeKj ? number_format(round($teeKj)).' kJ' : '—' }} &nbsp;/&nbsp; {{ $teeKcal ? number_format($teeKcal).' kcal' : '—' }}</span>
                    <span><strong>Distribution:</strong> CHO {{ $choPct ?? '—' }}% &middot; PRO {{ $proPct ?? '—' }}% &middot; FAT {{ $fatPct ?? '—' }}%</span>
                </div>
            </div>
            @endif

            {{-- ── SECTION 3: Exchange Food Item Package ── --}}
            @if($patient->exchangeTemplate)
            <div class="rpt-section">
                <div class="rpt-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Exchange Food Item Package
                    <span style="margin-left:auto;font-size:.65rem;font-weight:600;padding:.15rem .55rem;border-radius:999px;background:#fff7ed;color:#c2410c;text-transform:none;letter-spacing:0">
                        {{ $patient->exchangeTemplate->name }}
                    </span>
                </div>
                <table class="et-table">
                    <thead>
                        <tr>
                            <th style="min-width:150px">Exchange Item</th>
                            <th style="text-align:center;min-width:55px">Units</th>
                            <th>CHO (g)</th>
                            <th>Protein (g)</th>
                            <th>Fat (g)</th>
                            <th>kJ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->exchangeTemplate->items as $item)
                        @php $nu = $item->nu; @endphp
                        <tr>
                            <td class="et-name">{{ $item->name }}</td>
                            <td class="et-nu">{{ $nu }}</td>
                            <td>{{ $item->cho_g !== null ? number_format($nu * $item->cho_g, 1) : '—' }}</td>
                            <td>{{ $item->protein_min_g !== null ? number_format($nu * $item->protein_min_g, 1) : '—' }}</td>
                            <td>{{ $item->fat_min_g !== null ? number_format($nu * $item->fat_min_g, 1) : '—' }}</td>
                            <td class="et-kj">{{ $item->kj !== null ? number_format($nu * $item->kj) : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="color:#52705e;font-size:.7rem;text-transform:uppercase;letter-spacing:.06em">Total</td>
                            <td>{{ $etTotCho ? number_format($etTotCho, 1) : '—' }}</td>
                            <td>{{ $etTotPro ? number_format($etTotPro, 1) : '—' }}</td>
                            <td>{{ $etTotFat ? number_format($etTotFat, 1) : '—' }}</td>
                            <td style="color:#2e6e56">{{ $etTotKj ? number_format($etTotKj) : '—' }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif

            {{-- ── SECTION 4: Patient Summary ── --}}
            <div class="rpt-section">
                <div class="rpt-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/></svg>
                    Patient Details
                </div>
                <table class="info-table">
                    <tr><td>Full Name</td><td>{{ $patient->name }}</td></tr>
                    <tr><td>Gender</td><td>{{ ucfirst($patient->gender) }}</td></tr>
                    <tr><td>Age</td><td>{{ $patient->age }} years</td></tr>
                    <tr><td>Actual Weight</td><td>{{ $patient->weight }} kg</td></tr>
                    <tr><td>Height</td><td>{{ $patient->height }} cm</td></tr>
                    <tr><td>Activity Factor</td><td>{{ $patient->activity_factor }}</td></tr>
                    <tr><td>BMI</td><td>{{ $patient->bmi ? number_format($patient->bmi, 1).' kg/m²' : '—' }} — <strong>{{ $patient->bmi_category }}</strong></td></tr>
                    <tr><td>Ideal Body Weight (IBW)</td><td>{{ $patient->ibw ? number_format($patient->ibw, 1).' kg' : '—' }}</td></tr>
                    <tr><td>Adjusted Body Weight (ABW)</td><td>{{ $patient->abw ? number_format($patient->abw, 1).' kg' : '—' }}</td></tr>
                    <tr><td>BMR (Mifflin-St Jeor)</td><td>{{ $bmrKj ? number_format($bmrKj).' kJ/day' : '—' }}</td></tr>
                    <tr><td>TEE</td><td>{{ $teeKj ? number_format(round($teeKj)).' kJ/day' : '—' }}{{ $teeKcal ? ' ('.number_format($teeKcal).' kcal/day)' : '' }}</td></tr>
                </table>
            </div>

        </div>{{-- end rpt-body --}}

        <div class="rpt-footer">
            <div class="rpt-footer-note">Generated on {{ now()->format('d M Y \a\t H:i') }} &middot; Confidential patient record</div>
            <div class="rpt-footer-brand">Panamarex Outpatient Clinical Nutrition Toolkit</div>
        </div>

    </div>{{-- end report-paper --}}
</div>

</body>
</html>
