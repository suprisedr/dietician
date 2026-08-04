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
        .letterhead-banner { text-align: center; margin-bottom: 14px; }
        .letterhead-banner img { width: 100%; max-height: 120px; height: auto; display: block; }

        .report-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 14px;
        }
        .report-title-row h1 {
            font-size: 20px;
            font-weight: 800;
            color: #002d58;
            text-transform: uppercase;
            letter-spacing: -.01em;
            margin-bottom: 2px;
        }
        .report-title-row .doc-sub {
            font-size: 11px;
            font-weight: 600;
            color: #555;
        }
        .draft-badge {
            display: inline-block;
            border: 2px solid #6b7280;
            border-radius: 4px;
            padding: 2px 10px;
            font-size: 10px;
            font-weight: 800;
            color: #6b7280;
            letter-spacing: .08em;
        }
        .report-id {
            font-size: 9px;
            color: #888;
            margin-top: 4px;
            text-align: right;
        }

        hr.divider { border: none; border-top: 2px solid #002d58; margin: 10px 0 14px; }

        /* ── INFO GRID ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 15px;
            font-size: 11px;
            margin-bottom: 16px;
        }
        .info-section-title {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 800;
            color: #002d58;
            margin-bottom: 8px;
        }
        .info-section-title svg { width: 14px; height: 14px; color: #006442; flex-shrink: 0; }
        .field {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 4px;
            padding-bottom: 3px;
            gap: 4px;
            font-size: 11px;
        }
        .field label { font-weight: 700; white-space: nowrap; margin-right: 3px; color: #374151; }
        .field span { flex-grow: 1; color: #0d1f0c; }
        .field .gender-icon { font-size: 13px; margin-right: 2px; }

        .abcd-box {
            border: 1px solid #006442;
            border-radius: 6px;
            padding: 10px;
            font-size: 10px;
            background: #f0fff4;
            line-height: 1.8;
        }
        .abcd-box .abcd-title {
            font-weight: 800;
            color: #002d58;
            font-size: 11px;
            margin-bottom: 4px;
        }
        .abcd-box .abcd-item { color: #374151; }
        .abcd-box .abcd-item strong { color: #002d58; }

        /* ── SECTION HEADERS ── */
        .section-header {
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 800;
            display: flex;
            align-items: center;
            border-radius: 4px 4px 0 0;
            margin-bottom: 0;
            text-transform: uppercase;
            letter-spacing: .03em;
        }
        .circle-label {
            background: white;
            width: 20px; height: 20px;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin-right: 10px;
            font-weight: 900;
            font-size: 11px;
            flex-shrink: 0;
        }
        .bg-a { background-color: #002d58; }
        .bg-b { background-color: #1d3557; }
        .bg-c { background-color: #006442; }
        .bg-pes { background-color: #006442; }
        .bg-d { background-color: #5b21b6; }
        .circle-a { color: #002d58; }
        .circle-b { color: #1d3557; }
        .circle-c { color: #006442; }
        .circle-pes { color: #006442; }
        .circle-d { color: #5b21b6; }

        .content-box {
            border: 1px solid #d1d8e0;
            border-top: none;
            padding: 10px 12px;
            margin-bottom: 14px;
            font-size: 11px;
        }

        .sub-header {
            color: #2d6a4f;
            font-weight: 800;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 1px solid #eee;
            margin: 8px 0 5px;
            padding-bottom: 2px;
        }
        .sub-header:first-child { margin-top: 0; }

        /* ── MAIN TWO-COL ── */
        .main-two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* ── TABLES ── */
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { border: 1px solid #ddd; padding: 4px 6px; text-align: left; }
        th { background: #f2f2f2; font-size: 9px; font-weight: 700; }
        td.r, th.r { text-align: right; }

        /* ── BMI BADGE ── */
        .bmi-badge {
            display: inline-block;
            padding: 1px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
        }
        .bmi-underweight { background: #fef9c3; color: #92400e; }
        .bmi-normal      { background: #dcfce7; color: #15803d; }
        .bmi-overweight  { background: #ffedd5; color: #c2410c; }
        .bmi-obese       { background: #fecaca; color: #b91c1c; }

        /* ── RISK BADGE ── */
        .risk-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
        }
        .risk-low    { background: #dcfce7; color: #15803d; }
        .risk-medium { background: #ffedd5; color: #c2410c; }
        .risk-high   { background: #fecaca; color: #b91c1c; }

        /* ── LAB ARROWS ── */
        .lab-high { color: #dc2626; font-weight: 700; }
        .lab-low  { color: #dc2626; font-weight: 700; }
        .lab-arrow { font-size: 10px; margin-left: 2px; }

        /* ── CLINICAL FINDINGS GRID ── */
        .findings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px 8px;
            font-size: 10px;
        }
        .finding-item {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 1px 0;
        }
        .finding-check {
            width: 13px; height: 13px;
            border: 1.5px solid #9ca3af;
            border-radius: 2px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: white;
            flex-shrink: 0;
        }
        .finding-check.checked {
            background: #006442;
            border-color: #006442;
        }

        /* ── APPETITE DOT ── */
        .appetite-dot {
            display: inline-block;
            width: 8px; height: 8px;
            border-radius: 50%;
            margin-right: 3px;
        }
        .appetite-good { background: #22c55e; }
        .appetite-fair { background: #f59e0b; }
        .appetite-poor { background: #ef4444; }

        /* ── NUTRITION IMPACT ICONS ── */
        .nis-row {
            display: flex;
            gap: 16px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        .nis-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            font-size: 9px;
            color: #6b7280;
        }
        .nis-icon {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .nis-icon.active { background: #dcfce7; }

        /* ── PES TABLE ── */
        .pes-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .pes-table td { padding: 6px 8px; border: 1px solid #d1d8e0; vertical-align: top; }
        .pes-table td:first-child { font-weight: 700; color: #002d58; white-space: nowrap; width: 130px; }
        .pes-table td:first-child span { font-size: 9px; font-weight: 400; color: #888; }

        /* ── PRIORITY LIST ── */
        .priority-list { list-style: none; padding: 0; margin: 6px 0 0; }
        .priority-list li {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 3px 0;
            font-size: 10px;
        }
        .priority-num {
            width: 20px; height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 10px;
            flex-shrink: 0;
        }
        .priority-num.p1 { background: #002d58; color: #fff; }
        .priority-num.p2 { background: #e5e7eb; color: #374151; }
        .priority-num.p3 { background: #e5e7eb; color: #374151; }

        /* ── PRESCRIPTION GRID ── */
        .rx-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }
        .rx-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            font-size: 10px;
        }
        .rx-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .rx-dot.green  { background: #22c55e; }
        .rx-dot.orange { background: #f59e0b; }
        .rx-dot.purple { background: #8b5cf6; }
        .rx-dot.blue   { background: #3b82f6; }
        .rx-check {
            width: 14px; height: 14px;
            border: 1.5px solid #d1d5db;
            border-radius: 3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: white;
            flex-shrink: 0;
            margin-left: auto;
        }
        .rx-check.checked { background: #006442; border-color: #006442; }

        /* ── INTERVENTION DETAILS ── */
        .intervention-grid {
            font-size: 11px;
        }
        .intervention-grid .field { border-bottom: none; margin-bottom: 3px; padding-bottom: 0; }
        .intervention-grid .field label { min-width: 140px; color: #002d58; font-weight: 800; font-size: 10px; }
        .intervention-grid ul { margin: 2px 0 6px 16px; padding: 0; font-size: 10px; }
        .intervention-grid li { margin-bottom: 1px; }

        /* ── FOOTER ── */
        .report-footer {
            margin-top: 16px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 30px;
            align-items: end;
            font-size: 11px;
        }
        .sig-section .field { border-bottom: 1px solid #e5e7eb; }

        .stamp-box {
            border: 2px solid #002d58;
            border-radius: 8px;
            padding: 12px 16px;
            text-align: center;
            width: 200px;
            position: relative;
            overflow: hidden;
        }
        .stamp-name { font-size: 13px; font-weight: 800; color: #002d58; }
        .stamp-role { font-size: 10px; font-weight: 700; color: #006442; margin-top: 2px; }
        .stamp-reg  { font-size: 9px; color: #555; margin-top: 2px; }
        .stamp-line { border-top: 1px solid #002d58; margin: 8px 0 6px; }
        .stamp-date { font-size: 9px; color: #555; margin-top: 6px; }

        .confidential-bar {
            text-align: center;
            font-size: 8px;
            color: #888;
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .confidential-bar .conf-left { display: flex; align-items: center; gap: 4px; }
        .confidential-bar svg { width: 10px; height: 10px; }

        /* ── TUBE FEEDING (Package 3) ── */
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
    $reportId  = 'MN-' . now()->format('Y-m-d') . '-' . str_pad($patient->id, 3, '0', STR_PAD_LEFT);
    $cf        = $patient->clinical_findings ?? [];
    $nis       = $patient->nutrition_impact_symptoms ?? [];
    $rxList    = $patient->nutrition_prescription ?? [];
    $mustRisk  = strtolower($patient->nutrition_risk_must ?? '');
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

    {{-- LETTERHEAD --}}
    @if(!empty($letterhead))
    <div class="letterhead-banner">
        <img src="{{ $letterhead }}">
    </div>
    @endif

    {{-- TITLE ROW --}}
    <div class="report-title-row">
        <div>
            <h1>Dietetic Assessment &amp; Care Plan</h1>
            <div class="doc-sub">South African Nutrition Care Process (ABCD)</div>
        </div>
        <div style="text-align:right">
            <span class="draft-badge">DRAFT</span>
            <div class="report-id">Report ID: {{ $reportId }}</div>
        </div>
    </div>

    <hr class="divider">

    {{-- PATIENT + DIETITIAN INFO + ABCD FRAMEWORK --}}
    <div class="info-grid">
        {{-- Patient Information --}}
        <div>
            <div class="info-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                PATIENT INFORMATION
            </div>
            <div class="field"><label>Patient Name:</label><span>{{ $patient->name }}</span></div>
            <div class="field"><label>Folder Number:</label><span>{{ $patient->folder_number ?? $patient->id_number ?? '—' }}</span></div>
            <div class="field">
                <label>Date of Birth:</label>
                <span>{{ $patient->date_of_birth?->format('d M Y') ?? '—' }}</span>
                &nbsp;&nbsp;<label>Age:</label>
                <span>{{ $patient->age ?? '—' }} Y</span>
            </div>
            <div class="field">
                <label>Gender:</label>
                <span>
                    @if($patient->gender === 'female')
                        <span class="gender-icon">&#9792;</span> Female
                    @elseif($patient->gender === 'male')
                        <span class="gender-icon">&#9794;</span> Male
                    @else
                        —
                    @endif
                </span>
            </div>
            <div class="field"><label>Ward / Clinic:</label><span>{{ $patient->ward_clinic ?? '—' }}</span></div>
            <div class="field"><label>Contact Number:</label><span>{{ $patient->contact_number ?? '—' }}</span></div>
            <div class="field"><label>Medical Diagnosis:</label><span>{{ $patient->medical_diagnosis ?? $patient->medical_history ?? '—' }}</span></div>
            <div class="field"><label>Referred By:</label><span>{{ $patient->referred_by ?? '—' }}</span></div>
        </div>

        {{-- Dietitian Information --}}
        <div>
            <div class="info-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                DIETITIAN INFORMATION
            </div>
            <div class="field"><label>Dietitian Name:</label><span>{{ $user->name }}</span></div>
            @if($user->dietician_number)
            <div class="field"><label>HPCSA Registration No.:</label><span>{{ $user->dietician_number }}</span></div>
            @endif
            <div class="field"><label>Email Address:</label><span>{{ $user->email }}</span></div>
            <div class="field">
                <label>Date of Assessment:</label>
                <span>{{ $lastVisit?->visited_at->format('d M Y') ?? '—' }}</span>
            </div>
            <div class="field"><label>Report Date:</label><span>{{ $genDate }}</span></div>
        </div>

        {{-- ABCD Framework --}}
        <div>
            <div class="abcd-box">
                <div class="abcd-title">ABCD FRAMEWORK</div>
                <div class="abcd-item"><strong>A</strong> — Anthropometric Assessment</div>
                <div class="abcd-item"><strong>B</strong> — Biochemical &amp; Clinical Assessment</div>
                <div class="abcd-item"><strong>C</strong> — Clinical Complaints</div>
                <div class="abcd-item"><strong>D</strong> — Dietetic Intervention</div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION A + B — Two columns                            --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="main-two-col">

        {{-- SECTION A — Anthropometric Assessment --}}
        <div>
            <div class="section-header bg-a">
                <span class="circle-label circle-a">A</span> Anthropometric Assessment
            </div>
            <div class="content-box">
                <table>
                    <thead>
                        <tr><th>Parameter</th><th class="r">Result</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Weight</td>
                            <td class="r">{{ $patient->weight ? number_format($patient->weight, 2) . ' kg' : '—' }}</td>
                        </tr>
                        <tr>
                            <td>Height</td>
                            <td class="r">{{ $patient->height ? number_format($patient->height, 2) . ' cm' : '—' }}</td>
                        </tr>
                        <tr>
                            <td>BMI</td>
                            <td class="r">
                                {{ $patient->bmi ? number_format($patient->bmi, 1) . ' kg/m²' : '—' }}
                                @if($patient->bmi_category && $patient->bmi_category !== 'N/A')
                                    <span class="bmi-badge bmi-{{ $bmiCat }}">{{ $patient->bmi_category }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>MUAC</td>
                            <td class="r">{{ $patient->muac ? $patient->muac . ' cm' : '—' }}</td>
                        </tr>
                        <tr>
                            <td>Weight History (3 months)</td>
                            <td class="r">
                                @if($patient->weight_history_3m !== null)
                                    @if($patient->weight_history_3m < 0)
                                        <span style="color:#dc2626">&darr; {{ abs($patient->weight_history_3m) }} kg</span>
                                    @elseif($patient->weight_history_3m > 0)
                                        <span style="color:#15803d">&uarr; {{ $patient->weight_history_3m }} kg</span>
                                    @else
                                        0 kg
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Nutrition Risk (MUST)</td>
                            <td class="r">
                                @if($mustRisk)
                                    <span class="risk-badge risk-{{ $mustRisk }}">{{ ucfirst($mustRisk) }} Risk</span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SECTION B — Biochemical & Clinical Assessment --}}
        <div>
            <div class="section-header bg-b">
                <span class="circle-label circle-b">B</span> Biochemical &amp; Clinical Assessment
            </div>
            <div class="content-box">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    {{-- Biochemistry --}}
                    <div>
                        <div class="sub-header">Biochemistry</div>
                        <table>
                            <tbody>
                                <tr>
                                    <td><strong>BP</strong></td>
                                    <td class="r">{{ $patient->bp ?? '—' }}{{ $patient->bp ? ' mmHg' : '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Blood Glucose</strong></td>
                                    <td class="r">{{ $patient->blood_glucose !== null ? $patient->blood_glucose . ' mmol/L' : '—' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>HbA1c</strong></td>
                                    <td class="r">{{ $patient->hba1c !== null ? $patient->hba1c . ' %' : '—' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Hb</strong></td>
                                    <td class="r">{{ $patient->hb !== null ? $patient->hb . ' g/dL' : '—' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Albumin</strong></td>
                                    <td class="r">
                                        {{ $patient->albumin !== null ? $patient->albumin . ' g/L' : '—' }}
                                        @if($patient->albumin !== null && $patient->albumin < 35)
                                            <span class="lab-arrow lab-low">&darr;</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Creatinine</strong></td>
                                    <td class="r">
                                        {{ $patient->creatinine !== null ? $patient->creatinine . ' µmol/L' : '—' }}
                                        @if($patient->creatinine !== null && $patient->creatinine > 115)
                                            <span class="lab-arrow lab-high">&uarr;</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Urea</strong></td>
                                    <td class="r">
                                        {{ $patient->urea !== null ? $patient->urea . ' mmol/L' : '—' }}
                                        @if($patient->urea !== null && $patient->urea > 7.1)
                                            <span class="lab-arrow lab-high">&uarr;</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Sodium (Na)</strong></td>
                                    <td class="r">{{ $patient->sodium_na !== null ? $patient->sodium_na . ' mmol/L' : '—' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Potassium (K)</strong></td>
                                    <td class="r">{{ $patient->potassium_k !== null ? $patient->potassium_k . ' mmol/L' : '—' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cholesterol</strong></td>
                                    <td class="r">{{ $patient->cholesterol !== null ? $patient->cholesterol . ' mmol/L' : '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Clinical Findings --}}
                    <div>
                        <div class="sub-header">Clinical Findings</div>
                        <div class="findings-grid">
                            @php
                                $findingsList = [
                                    'Oedema', 'Dehydration', 'Muscle Wasting', 'Fat Loss',
                                    'Ascites', 'Pressure Sores', 'Poor Dentition', 'Dysphagia',
                                    'Nausea', 'Vomiting', 'Diarrhoea', 'Constipation',
                                ];
                            @endphp
                            @foreach($findingsList as $finding)
                                @php $isChecked = in_array($finding, $cf) || ($finding === 'Oedema' && $patient->oedema); @endphp
                                <div class="finding-item">
                                    <span class="finding-check {{ $isChecked ? 'checked' : '' }}">{{ $isChecked ? '✓' : '' }}</span>
                                    {{ $finding }}
                                </div>
                            @endforeach
                        </div>
                        @if($patient->clinical_findings_other)
                            <div style="margin-top:4px;font-size:10px;color:#555">
                                Other: {{ $patient->clinical_findings_other }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- end A+B two-col --}}

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION C + PES — Two columns                          --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="main-two-col">

        {{-- SECTION C — Clinical Complaints / Subjective Assessment --}}
        <div>
            <div class="section-header bg-c">
                <span class="circle-label circle-c">C</span> Clinical Complaints / Subjective Assessment
            </div>
            <div class="content-box">
                <div class="field"><label>Chief Complaint:</label><span>{{ $patient->reason_for_assessment ?? '—' }}</span></div>
                <div class="field"><label>Medical History:</label><span>{{ $patient->medical_history ?? '—' }}</span></div>
                <div class="field"><label>Medications:</label><span>{{ $patient->medications ?? '—' }}</span></div>
                <div class="field">
                    <label>Allergies:</label>
                    <span @if($patient->allergies) style="color:#b91c1c;font-weight:600" @endif>{{ $patient->allergies ?? 'No known allergies' }}</span>
                </div>
                <div class="field">
                    <label>Appetite:</label>
                    <span>
                        @if($ap)
                            <span class="appetite-dot appetite-{{ $ap }}"></span> {{ ucfirst($ap) }}
                        @else
                            —
                        @endif
                    </span>
                </div>
                <div class="field"><label>GI Symptoms:</label><span>{{ $patient->gi_symptoms ?? '—' }}</span></div>
                <div class="field"><label>Dietary History:</label><span>{{ $patient->dietary_history ?? '—' }}</span></div>
                <div class="field" style="border:none"><label>Lifestyle:</label><span>{{ $patient->lifestyle ?? '—' }}</span></div>

                {{-- Nutrition Impact Symptoms --}}
                <div class="sub-header" style="margin-top:10px">Nutrition Impact Symptoms</div>
                <div class="nis-row">
                    @php
                        $nisIcons = [
                            'Poor Intake'   => '🍽️',
                            'Nausea'        => '🤢',
                            'Early Satiety' => '😖',
                            'Bloating'      => '🫄',
                            'Constipation'  => '💩',
                            'Other'         => '📋',
                        ];
                    @endphp
                    @foreach($nisIcons as $nisLabel => $nisEmoji)
                        @php $nisActive = in_array($nisLabel, $nis); @endphp
                        <div class="nis-item">
                            <div class="nis-icon {{ $nisActive ? 'active' : '' }}">{{ $nisEmoji }}</div>
                            {{ $nisLabel }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- PES — Nutrition Diagnosis --}}
        <div>
            <div class="section-header bg-pes">
                <span class="circle-label circle-pes" style="font-size:8px;font-weight:800">PES</span>
                Nutrition Diagnosis (PES Statement)
            </div>
            <div class="content-box">
                <table class="pes-table">
                    <tbody>
                        <tr>
                            <td>Problem (P)</td>
                            <td>{{ $patient->pes_problem ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td>Etiology (E)</td>
                            <td>{{ $patient->pes_etiology ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td>Signs &amp; Symptoms (S)</td>
                            <td>{{ $patient->pes_signs_symptoms ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="sub-header" style="margin-top:10px">Nutrition Diagnosis Priority</div>
                <ul class="priority-list">
                    <li>
                        <span class="priority-num p1">1</span>
                        {{ $patient->nutrition_diagnosis_priority_1 ?? '—' }}
                    </li>
                    @if($patient->nutrition_diagnosis_priority_2)
                    <li>
                        <span class="priority-num p2">2</span>
                        {{ $patient->nutrition_diagnosis_priority_2 }}
                    </li>
                    @endif
                    @if($patient->nutrition_diagnosis_priority_3)
                    <li>
                        <span class="priority-num p3">3</span>
                        {{ $patient->nutrition_diagnosis_priority_3 }}
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>{{-- end C+PES two-col --}}

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION D — Dietetic Intervention & Care Plan          --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div style="margin-top:2px">
        <div class="section-header bg-d">
            <span class="circle-label circle-d">D</span> Dietetic Intervention &amp; Care Plan
        </div>
        <div class="content-box">
            <div class="main-two-col" style="gap:16px">
                {{-- Nutrition Prescription --}}
                <div>
                    <div class="sub-header" style="margin-top:0">Nutrition Prescription</div>
                    @php
                        $rxOptions = [
                            ['label' => 'Renal Diet',                  'dot' => 'green'],
                            ['label' => 'Diabetic Diet',              'dot' => 'green'],
                            ['label' => 'Cardiac Diet',               'dot' => 'green'],
                            ['label' => 'High Protein',               'dot' => 'orange'],
                            ['label' => 'High Energy',                'dot' => 'orange'],
                            ['label' => 'Low Sodium',                 'dot' => 'orange'],
                            ['label' => 'Texture Modified',           'dot' => 'purple'],
                            ['label' => 'Enteral Feeding',            'dot' => 'purple'],
                            ['label' => 'Parenteral Nutrition',       'dot' => 'blue'],
                            ['label' => 'Oral Nutrition Supplements', 'dot' => 'blue'],
                        ];
                    @endphp
                    <div class="rx-grid">
                        @foreach($rxOptions as $rx)
                            @php $rxChecked = in_array($rx['label'], $rxList); @endphp
                            <div class="rx-item">
                                <span class="rx-dot {{ $rx['dot'] }}"></span>
                                {{ $rx['label'] }}
                                <span class="rx-check {{ $rxChecked ? 'checked' : '' }}">{{ $rxChecked ? '✓' : '' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Intervention Details --}}
                <div>
                    <div class="sub-header" style="margin-top:0">Intervention Details</div>
                    <div class="intervention-grid">
                        <div class="field"><label>Nutrition Intervention:</label><span>{{ $patient->nutrition_intervention ?? '—' }}</span></div>
                        <div class="field"><label>Meal Plan:</label><span>{{ $patient->meal_plan_details ?? '—' }}</span></div>
                        <div class="field"><label>Oral Supplements:</label><span>{{ $patient->oral_supplements ?? '—' }}</span></div>
                        <div class="field"><label>Nutrition Education:</label><span>{{ $patient->nutrition_education ?? '—' }}</span></div>
                        <div class="field" style="border:none;flex-direction:column">
                            <label>Goals:</label>
                            @if($patient->intervention_goals)
                                <ul>
                                    @foreach(explode("\n", $patient->intervention_goals) as $goal)
                                        @if(trim($goal))
                                        <li>{{ trim($goal) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <span>—</span>
                            @endif
                        </div>
                        <div class="field"><label>Monitoring Plan:</label><span>{{ $patient->monitoring_plan ?? '—' }}</span></div>
                        <div class="field" style="border:none"><label>Follow-up Plan:</label><span>{{ $patient->follow_up_plan ?? '—' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION D (cont.) — Tube Feeding (Package 3 only)      --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
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

        $dMacros = [
            '1.0' => ['cho_pct'=>54,'pro_pct'=>16,'fat_pct'=>30],
            '1.2' => ['cho_pct'=>52,'pro_pct'=>17,'fat_pct'=>31],
            '1.5' => ['cho_pct'=>50,'pro_pct'=>18,'fat_pct'=>32],
        ];
        $dm = $dMacros[(string)number_format($dDensity,1)] ?? $dMacros['1.0'];
        $dChoG   = round($dVol * $dDensity * $dm['cho_pct'] / 100 / 4, 1);
        $dProG   = round($dVol * $dDensity * $dm['pro_pct'] / 100 / 4, 1);
        $dFatG   = round($dVol * $dDensity * $dm['fat_pct'] / 100 / 9, 1);

        $dFwFrac = match(true) { $dDensity >= 1.45 => 0.76, $dDensity >= 1.15 => 0.82, default => 0.85 };
        $dFwMl      = round($dVol * $dFwFrac);
        $dAddWater  = (int)$enteral->additional_water_ml;
        $dFluidReq  = (int)$enteral->fluid_requirement_ml;
        $dTotalFluid = $dFwMl + $dAddWater;

        $dFlushFreqStr = $enteral->water_flush_frequency ?? '6-hourly';
        $dFlushHours   = max(1, (int)filter_var($dFlushFreqStr, FILTER_SANITIZE_NUMBER_INT) ?: 6);
        $dFlushPerDay  = (int)round(24 / $dFlushHours);
        $dFlushMl      = (int)($enteral->water_flush_ml ?? 30);
        $dFlushTotal   = $dFlushMl * $dFlushPerDay;

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
        $dProAdequate = $dProG > 0 && $dProteinG > 0 ? ($dProG >= $dProteinG * 0.95) : null;
        $dCalcDate = $enteral->created_at?->format('d M Y') ?? '';
    @endphp
    <div style="margin-top:14px">
        <div class="section-header bg-d">
            <span class="circle-label circle-d">D</span> TUBE FEEDING RECOMMENDATIONS
            @if($dCalcDate)<span style="font-size:9px;font-weight:400;opacity:.8;margin-left:8px">Calculated {{ $dCalcDate }}</span>@endif
        </div>
        <div class="content-box" style="font-size:11px">
            <div class="tube-banner">
                <span>Condition: <strong>{{ $dCond }}</strong></span>
                <span>Formula: <strong>{{ $dDensity }} kcal/mL</strong></span>
                <span>Goal rate: <strong>{{ $dRate }} mL/hr &times; {{ $dHours }}h</strong></span>
                <span>Protein: <strong>{{ $dProG }}g</strong> delivered / <strong>{{ $dProteinG }}g</strong> target</span>
                <span>Flush: <strong>{{ $dFlushMl }}mL</strong> {{ $dFlushFreqStr }}</span>
            </div>
            <div class="tube-grid">
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
                <div class="tube-col">
                    <div class="tube-col-title">Fluid Management</div>
                    <div class="tube-row"><span class="tube-label">Total Fluids</span><span class="tube-val">{{ number_format($dTotalFluid) }} mL</span></div>
                    <div class="tube-row"><span class="tube-label">Daily Needs (35mL/kg)</span><span class="tube-val">{{ number_format($dFluidReq) }} mL</span></div>
                    <div class="tube-row"><span class="tube-label">Free Water (formula)</span><span class="tube-val">{{ number_format($dFwMl) }} mL</span></div>
                    <div class="tube-row"><span class="tube-label">Additional Water</span><span class="tube-val">{{ number_format($dAddWater) }} mL</span></div>
                    <div class="tube-row"><span class="tube-label">Water Flush</span><span class="tube-val">{{ $dFlushMl }}mL &times; {{ $dFlushPerDay }}/day = {{ number_format($dFlushTotal) }}mL</span></div>
                    <div style="margin-top:5px;font-size:9px;color:#6b7280">Flush {{ $dFlushMl }}mL {{ $dFlushFreqStr }}</div>
                </div>
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
            <span style="font-size:9px;display:block;margin-top:4px">Use the Enteral Feed Calculator for this patient to populate this section.</span>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- FOOTER                                                 --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="report-footer">
        <div class="sig-section">
            <div style="font-weight:800;font-size:11px;color:#002d58;margin-bottom:6px;text-transform:uppercase">Dietitian Signature</div>
            <div class="field"><label>Full Name:</label><span>{{ $user->name }}</span></div>
            @if($user->dietician_number)
            <div class="field"><label>HPCSA Registration No.:</label><span>{{ $user->dietician_number }}</span></div>
            @endif
            <div class="field"><label>Date:</label><span>{{ $genDate }}</span></div>
            <div class="field" style="border:none"><label>Signature:</label><span>____________________</span></div>
        </div>

        <div class="stamp-box">
            <div class="stamp-name">{{ $user->name }}</div>
            <div class="stamp-role">Registered Dietitian</div>
            @if($user->dietician_number)
            <div class="stamp-reg">HPCSA: {{ $user->dietician_number }}</div>
            @endif
            <div class="stamp-line"></div>
            <div class="stamp-date">{{ $genDate }} &bull; {{ $genTime }}</div>
        </div>
    </div>

    <div class="confidential-bar">
        <div class="conf-left">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <strong>CONFIDENTIAL:</strong> This report is confidential and intended for the patient and authorised healthcare professionals only.
        </div>
        <div style="font-style:italic">Generated by <strong style="color:#006442">Mindful Nutri Co.</strong> Nutrition Management System</div>
    </div>

</div>{{-- end page --}}

</body>
</html>
