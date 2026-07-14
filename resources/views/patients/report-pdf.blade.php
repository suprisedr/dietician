<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Dietetic Assessment &amp; Care Plan — {{ $patient->name }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9.5px;
        color: #0d1f0c;
        line-height: 1.45;
        background: #fff;
    }

    .letterhead-banner { width: 100%; margin-bottom: 8px; border-collapse: collapse; }
    .letterhead-banner td { text-align: center; }
    .letterhead-banner img { width: 100%; max-height: 100px; height: auto; display: block; }

    .page-header { width: 100%; margin-bottom: 12px; border-collapse: collapse; }
    .page-header td { vertical-align: top; }
    .logo-cell { width: 80px; }
    .logo-cell img { max-width: 80px; max-height: 70px; }
    .title-cell { text-align: center; padding: 0 10px; }
    .doc-title {
        font-size: 16px; font-weight: bold; color: #002d58;
        text-transform: uppercase; letter-spacing: .5px;
    }
    .doc-subtitle { font-size: 8px; font-weight: bold; color: #555; margin-top: 3px; letter-spacing: .5px; }
    .contact-cell { text-align: right; font-size: 8px; line-height: 1.6; color: #333; }
    .contact-cell strong { font-size: 9px; }

    hr { border: none; border-top: 1.5px solid #002d58; margin: 6px 0 10px 0; }

    .pi-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .pi-table td { vertical-align: top; padding: 0 6px 0 0; font-size: 8.5px; }
    .field { display: block; border-bottom: 1px solid #ccc; margin-bottom: 4px; padding-bottom: 1px; }
    .field-label { font-weight: bold; font-size: 8px; }
    .field-value { color: #1a1a1a; }
    .abcd-box {
        border: 1px solid #006442; border-radius: 4px; padding: 6px 8px;
        font-size: 8px; background-color: #f0fff4; line-height: 1.7;
    }
    .abcd-box strong { color: #002d58; font-size: 8.5px; }

    .section-header {
        color: white; padding: 4px 8px; font-size: 9.5px; font-weight: bold;
        border-radius: 3px 3px 0 0; margin-bottom: 0;
    }
    .circle-label {
        display: inline-block; width: 16px; height: 16px; border-radius: 50%;
        background: white; text-align: center; line-height: 16px;
        font-weight: 900; font-size: 9px; margin-right: 6px;
    }
    .bg-a { background-color: #002d58; }
    .bg-b { background-color: #1d3557; }
    .bg-c { background-color: #006442; }
    .bg-d { background-color: #5b21b6; }
    .circle-a { color: #002d58; }
    .circle-b { color: #1d3557; }
    .circle-c { color: #006442; }
    .circle-d { color: #5b21b6; }

    .tube-banner { background: #5b21b6; color: #fff; border-radius: 4px; padding: 5px 8px; font-size: 7.5px; margin-bottom: 6px; }
    .tube-banner td { color: #fff; font-size: 7.5px; padding: 0 8px 0 0; white-space: nowrap; }
    .tube-3col { width: 100%; border-collapse: collapse; }
    .tube-3col > tbody > tr > td { width: 33.3%; vertical-align: top; padding: 0 4px 0 0; }
    .tube-3col > tbody > tr > td:last-child { padding-right: 0; }
    .tube-box { background: #f8f9fb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 5px 7px; }
    .tube-box-title { font-size: 7px; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; color: #374151; border-bottom: 1px solid #e5e7eb; padding-bottom: 3px; margin-bottom: 4px; }
    .tube-row { width: 100%; border-collapse: collapse; margin-bottom: 1px; }
    .tube-row td { font-size: 7.5px; padding: 1px 0; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
    .tube-lbl { color: #6b7280; width: 55%; }
    .tube-val { font-weight: bold; color: #0d1f0c; text-align: right; }
    .pro-badge { display: inline-block; padding: 1px 5px; border-radius: 8px; font-size: 6.5px; font-weight: bold; margin-top: 3px; }
    .pro-ok  { background: #dcfce7; color: #166534; }
    .pro-low { background: #fef9c3; color: #92400e; }
    .startup-box { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 4px; padding: 4px 7px; font-size: 7px; color: #166534; margin-top: 5px; }

    .content-box {
        border: 1px solid #d1d8e0; border-top: none;
        padding: 8px 9px; margin-bottom: 10px; font-size: 8.5px;
    }

    .sub-header {
        color: #2d6a4f; font-weight: bold; font-size: 8px;
        text-transform: uppercase; letter-spacing: .5px;
        border-bottom: 1px solid #eee; margin: 7px 0 4px 0; padding-bottom: 2px;
    }
    .sub-header:first-child { margin-top: 0; }

    .f-row-table { width: 100%; border-collapse: collapse; margin-bottom: 3px; }
    .f-row-table td { vertical-align: top; padding: 0; }
    .fl { font-weight: bold; width: 38%; color: #555; font-size: 8px; }
    .fv { color: #0d1f0c; }

    .data-table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 8px; }
    .data-table th {
        background-color: #f2f4f7; border: 1px solid #ddd; padding: 3px 5px;
        text-align: left; font-size: 7.5px; font-weight: bold;
    }
    .data-table td { border: 1px solid #ddd; padding: 3px 5px; }
    .data-table th.r, .data-table td.r { text-align: right; }
    .data-table th.c, .data-table td.c { text-align: center; }
    .data-table tr.tot td { background: #f2f4f7; font-weight: bold; border-top: 1.5px solid #aaa; }

    .metric-grid { width: 100%; border-collapse: collapse; }
    .metric-grid td { width: 33.3%; padding: 0 3px 3px 0; vertical-align: top; }
    .metric-grid td:last-child { padding-right: 0; }
    .m-box { background: #f0f4f8; border: 1px solid #c8d8ea; border-radius: 4px; padding: 5px 7px; }
    .m-box.hi { background: #e8f5e9; border-color: #a5d6a7; }
    .m-label { font-size: 6.5px; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; color: #445; margin-bottom: 1px; }
    .m-val { font-size: 13px; font-weight: bold; color: #0d1f0c; line-height: 1.1; }
    .m-unit { font-size: 6.5px; color: #555; margin-top: 1px; }
    .bmi-pill {
        display: inline-block; padding: 1px 5px; border-radius: 8px;
        font-size: 6.5px; font-weight: bold; margin-top: 2px;
    }
    .bmi-underweight { background: #fef9c3; color: #854d0e; }
    .bmi-normal      { background: #dcfce7; color: #166534; }
    .bmi-overweight  { background: #ffedd5; color: #9a3412; }
    .bmi-obese       { background: #fee2e2; color: #991b1b; }

    .macro-grid { width: 100%; border-collapse: collapse; }
    .macro-grid td { width: 33.3%; padding: 0 3px 0 0; vertical-align: top; }
    .macro-grid td:last-child { padding-right: 0; }
    .macro-box { border-radius: 4px; padding: 5px 7px; border: 1px solid; }
    .macro-cho { background: #fef9c3; border-color: #fde68a; }
    .macro-pro { background: #fce7f3; border-color: #f9a8d4; }
    .macro-fat { background: #e0f2fe; border-color: #7dd3fc; }
    .macro-label { font-size: 6.5px; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; color: #374151; margin-bottom: 2px; }
    .macro-pct { font-size: 15px; font-weight: bold; color: #0d1f0c; line-height: 1; }
    .macro-row { font-size: 7.5px; color: #374151; margin-top: 1px; }

    .en-grid { width: 100%; border-collapse: collapse; }
    .en-grid td { width: 50%; padding: 0 3px 3px 0; vertical-align: top; }
    .en-grid td:last-child { padding-right: 0; }
    .en-box { background: #f0fff4; border: 1px solid #86efac; border-radius: 4px; padding: 5px 7px; }
    .en-label { font-size: 6.5px; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; color: #166534; margin-bottom: 1px; }
    .en-val { font-size: 13px; font-weight: bold; color: #0d1f0c; line-height: 1.1; }
    .en-unit { font-size: 6.5px; color: #555; margin-top: 1px; }

    .obese-note {
        background: #fef2f2; border: 1px solid #fecaca; border-radius: 4px;
        padding: 4px 7px; font-size: 7.5px; color: #b91c1c; margin-top: 5px;
    }

    .two-col { width: 100%; border-collapse: collapse; }
    .two-col > tbody > tr > td { width: 50%; vertical-align: top; padding: 0; }
    .two-col > tbody > tr > td:first-child { padding-right: 8px; }

    .stamp {
        border: 2px solid #002d58; border-radius: 6px;
        padding: 8px 12px; text-align: center; font-size: 8px;
        color: #002d58; width: 180px;
    }
    .stamp-name { font-size: 10px; font-weight: bold; color: #002d58; margin-bottom: 3px; }
    .stamp-line { border-top: 1px solid #002d58; margin: 5px 0 4px; }
    .stamp-reg { font-size: 7.5px; color: #444; }
    .stamp-watermark {
        font-size: 18px; font-weight: bold; color: rgba(0,45,88,.07);
        letter-spacing: 3px; text-transform: uppercase;
        white-space: nowrap;
    }

    .footer-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .footer-table td { vertical-align: top; font-size: 8px; }
    .footer-note { color: #555; font-size: 7.5px; }
    .confidential-bar {
        text-align: center; font-size: 7.5px; color: #888;
        margin-top: 8px; padding-top: 6px; border-top: 1px solid #e5e7eb;
    }

    @page { margin: 12mm 14mm 12mm 14mm; size: A4 portrait; }
</style>
</head>
<body>

@php
    $user      = auth()->user();
    $genDate   = now()->format('d M Y');
    $genTime   = now()->format('H:i');
    $lastVisit = $patient->visits()->latest('visited_at')->first();
    $bmiCatClass = strtolower(str_replace([' ', '/', '(', ')'], ['_', '_', '', ''], $patient->bmi_category ?? 'normal'));
@endphp

{{-- LETTERHEAD --}}
@if(!empty($letterhead))
<table class="letterhead-banner" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <img src="{{ $letterhead }}">
    </td>
  </tr>
</table>
@endif

{{-- PAGE HEADER --}}
<table class="page-header" cellpadding="0" cellspacing="0">
    <tr>
        @if(empty($letterhead))
        <td class="logo-cell"></td>
        @endif
        <td class="title-cell">
            <div class="doc-title">Dietetic Assessment &amp; Care Plan</div>
            <div class="doc-subtitle">ABC Assessment Framework &mdash; Patient Report</div>
        </td>
        <td class="contact-cell">
            <strong>{{ $user->name }}</strong><br>
            @if($user->dietician_number)Dietitian Reg.: {{ $user->dietician_number }}<br>@endif
            {{ $user->email }}<br>
            <span style="color:#888">{{ $genDate }}</span>
        </td>
    </tr>
</table>

<hr>

{{-- PATIENT INFO GRID --}}
<table class="pi-table" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width:55%;padding-right:10px">
            <span class="field">
                <span class="field-label">Patient Name: </span>
                <span class="field-value">{{ $patient->name }}</span>
            </span>
            <span class="field">
                <span class="field-label">DOB: </span>
                <span class="field-value">{{ $patient->date_of_birth?->format('d M Y') ?? '—' }}</span>
                &nbsp;&nbsp;
                <span class="field-label">Age: </span>
                <span class="field-value">{{ $patient->age }}</span>
                &nbsp;&nbsp;
                <span class="field-label">Gender: </span>
                <span class="field-value">{{ ucfirst($patient->gender) }}</span>
            </span>
            <span class="field">
                <span class="field-label">{{ $patient->id_type === 'passport' ? 'Passport No.' : 'SA ID Number' }}: </span>
                <span class="field-value">{{ $patient->id_number ?? '—' }}</span>
            </span>
            <span class="field">
                <span class="field-label">Contact / Email: </span>
                <span class="field-value">{{ $patient->email ?? '—' }}</span>
            </span>
        </td>
        <td style="width:45%">
            <span class="field">
                <span class="field-label">Date of Assessment: </span>
                <span class="field-value">{{ $lastVisit?->visited_at->format('d M Y H:i') ?? '—' }}</span>
            </span>
            <span class="field">
                <span class="field-label">Report Date: </span>
                <span class="field-value">{{ $genDate }}</span>
            </span>
            <span class="field">
                <span class="field-label">Reason for Assessment: </span>
                <span class="field-value">{{ $patient->reason_for_assessment ?? '—' }}</span>
            </span>
            <div class="abcd-box" style="margin-top:6px">
                <strong>ABCD FRAMEWORK</strong><br>
                <strong>A</strong> &mdash; Assessment (Subjective + Patient Details)<br>
                <strong>B</strong> &mdash; Body Composition &amp; Energy Calculations<br>
                <strong>C</strong> &mdash; Calculation of Requirements<br>
                <strong>D</strong> &mdash; Tube Feeding Recommendations
            </div>
        </td>
    </tr>
</table>

{{-- TWO-COLUMN LAYOUT --}}
<table class="two-col" cellpadding="0" cellspacing="0">
<tbody><tr>

{{-- ══ LEFT COLUMN ══ --}}
<td>

    {{-- SECTION A --}}
    <div class="section-header bg-a">
        <span class="circle-label circle-a">A</span> ASSESSMENT
    </div>
    <div class="content-box">
        <div class="sub-header">Subjective Assessment</div>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="fl">Chief Complaint:</td>
                <td class="fv">{{ $patient->reason_for_assessment ?? '—' }}</td>
            </tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="fl">Medical History:</td>
                <td class="fv">{{ $patient->medical_history ?? '—' }}</td>
            </tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="fl">Medications:</td>
                <td class="fv">{{ $patient->medications ?? '—' }}</td>
            </tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="fl">Allergies:</td>
                <td class="fv" @if($patient->allergies) style="color:#b91c1c;font-weight:600" @endif>{{ $patient->allergies ?? '—' }}</td>
            </tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="fl">Dietary History:</td>
                <td class="fv">{{ $patient->dietary_history ?? '—' }}</td>
            </tr>
        </table>
        @php $ap = strtolower($patient->appetite ?? ''); @endphp
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="fl">Appetite:</td>
                <td class="fv">
                    {{ $ap === 'good' ? '☑' : '☐' }} Good &nbsp;
                    {{ $ap === 'fair' ? '☑' : '☐' }} Fair &nbsp;
                    {{ $ap === 'poor' ? '☑' : '☐' }} Poor
                </td>
            </tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="fl">Oedema:</td>
                <td class="fv">
                    @if($patient->oedema)
                        <strong style="color:#92400e">Present</strong>
                        @if($patient->oedema_changed_at)<span style="font-size:7px;color:#888"> (since {{ $patient->oedema_changed_at->format('d M Y') }})</span>@endif
                    @else
                        <strong style="color:#15803d">No Oedema</strong>
                        @if($patient->oedema_changed_at)<span style="font-size:7px;color:#888"> (since {{ $patient->oedema_changed_at->format('d M Y') }})</span>@endif
                    @endif
                </td>
            </tr>
        </table>

        <div class="sub-header">Patient Details</div>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Full Name:</td><td class="fv">{{ $patient->name }}</td></tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Date of Birth:</td><td class="fv">{{ $patient->date_of_birth?->format('d M Y') ?? '—' }}</td></tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Age / Gender:</td><td class="fv">{{ $patient->age }} years &middot; {{ ucfirst($patient->gender) }}</td></tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="fl">{{ $patient->id_type === 'passport' ? 'Passport No.' : 'SA ID Number' }}:</td>
                <td class="fv">{{ $patient->id_number ?? '—' }}</td>
            </tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Email / Contact:</td><td class="fv">{{ $patient->email ?? '—' }}</td></tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Address:</td><td class="fv">{{ $patient->address ?? '—' }}</td></tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Referred By:</td><td class="fv">{{ $patient->referred_by ?? '—' }}</td></tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Diagnosis:</td><td class="fv">{{ $patient->medical_history ?? '—' }}</td></tr>
        </table>

        @if($lastVisit)
        <div class="sub-header">Latest Visit</div>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Date:</td><td class="fv">{{ $lastVisit->visited_at->format('d M Y H:i') }}</td></tr>
        </table>
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Weight at Visit:</td><td class="fv">{{ number_format($lastVisit->weight, 1) }} kg</td></tr>
        </table>
        @if($lastVisit->notes)
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr><td class="fl">Notes:</td><td class="fv">{{ $lastVisit->notes }}</td></tr>
        </table>
        @endif
        @endif
    </div>

    {{-- SECTION B --}}
    <div class="section-header bg-b">
        <span class="circle-label circle-b">B</span> BODY COMPOSITION &amp; ENERGY
    </div>
    <div class="content-box">
        <div class="sub-header">Anthropometrics</div>
        <table class="metric-grid" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="m-box">
                        <div class="m-label">BMI</div>
                        <div class="m-val">{{ $patient->bmi ? number_format($patient->bmi, 1) : '—' }}</div>
                        <div class="m-unit">kg/m&sup2;</div>
                        @if($patient->bmi_category)
                        <span class="bmi-pill bmi-{{ $bmiCatClass }}">{{ $patient->bmi_category }}</span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="m-box">
                        <div class="m-label">Weight</div>
                        <div class="m-val">{{ $patient->weight ? number_format($patient->weight, 1) : '—' }}</div>
                        <div class="m-unit">kg (actual)</div>
                    </div>
                </td>
                <td>
                    <div class="m-box">
                        <div class="m-label">Height</div>
                        <div class="m-val">{{ $patient->height ? number_format($patient->height, 1) : '—' }}</div>
                        <div class="m-unit">cm</div>
                    </div>
                </td>
            </tr>
        </table>
        <table class="metric-grid" cellpadding="0" cellspacing="0" style="margin-top:3px">
            <tr>
                <td>
                    <div class="m-box">
                        <div class="m-label">IBW</div>
                        <div class="m-val">{{ $patient->ibw ? number_format($patient->ibw, 1) : '—' }}</div>
                        <div class="m-unit">kg</div>
                    </div>
                </td>
                <td>
                    <div class="m-box">
                        <div class="m-label">ABW</div>
                        <div class="m-val">{{ $patient->abw ? number_format($patient->abw, 1) : '—' }}</div>
                        <div class="m-unit">kg</div>
                    </div>
                </td>
                <td>
                    <div class="m-box">
                        <div class="m-label">Activity Factor</div>
                        <div class="m-val">{{ $patient->activity_factor ? number_format($patient->activity_factor, 2) : '—' }}</div>
                        <div class="m-unit">multiplier</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="sub-header" style="margin-top:7px">Energy Requirements</div>
        <table class="data-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th class="r">kJ/day</th>
                    <th class="r">kcal/day</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>BMR (Mifflin-St Jeor{{ $isObese ? ', ABW adj.' : '' }})</td>
                    <td class="r">{{ $bmrKj ? number_format($bmrKj) : '—' }}</td>
                    <td class="r">{{ $bmrKj ? number_format(round($bmrKj / 4.184)) : '—' }}</td>
                </tr>
                <tr class="tot">
                    <td>TEE (BMR &times; AF {{ $patient->activity_factor }})</td>
                    <td class="r">{{ $teeKj ? number_format(round($teeKj)) : '—' }}</td>
                    <td class="r">{{ $teeKcal ? number_format($teeKcal) : '—' }}</td>
                </tr>
            </tbody>
        </table>

        @if($isObese)
        <div class="obese-note">
            <strong>Obesity adjustment:</strong> BMR calculated using ABW
            (IBW + 0.4 &times; (actual &minus; IBW) = {{ number_format($patient->weight_for_bmr, 1) }} kg)
        </div>
        @endif

        @if($patient->macronutrients->count())
        <div class="sub-header" style="margin-top:7px">Macronutrient Distribution</div>
        @php
            $macros = [
                ['key'=>'carbohydrates','label'=>'CHO','cls'=>'cho','g'=>$recCho_g,'kj'=>$recCho_kj,'pct'=>$choPct],
                ['key'=>'protein',      'label'=>'PRO','cls'=>'pro','g'=>$recPro_g,'kj'=>$recPro_kj,'pct'=>$proPct],
                ['key'=>'fats',         'label'=>'FAT','cls'=>'fat','g'=>$recFat_g,'kj'=>$recFat_kj,'pct'=>$fatPct],
            ];
        @endphp
        <table class="macro-grid" cellpadding="0" cellspacing="4">
            <tr>
                @foreach($macros as $m)
                <td>
                    <div class="macro-box macro-{{ $m['cls'] }}">
                        <div class="macro-label">{{ $m['label'] }}</div>
                        <div class="macro-pct">{{ $m['pct'] !== null ? $m['pct'].'%' : '—' }}</div>
                        <div class="macro-row">Grams: <strong>{{ $m['g'] !== null ? $m['g'].'g' : '—' }}</strong></div>
                        <div class="macro-row">Energy: <strong>{{ $m['kj'] !== null ? number_format($m['kj']).' kJ' : '—' }}</strong></div>
                    </div>
                </td>
                @endforeach
            </tr>
        </table>
        @endif

        @if($patient->exchangeTemplate && $patient->exchangeTemplate->items->count())
        <div class="sub-header" style="margin-top:7px">Exchange Package &mdash; {{ $patient->exchangeTemplate->name }}</div>
        <table class="data-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="c">U</th>
                    <th class="r">CHO</th>
                    <th class="r">Prot</th>
                    <th class="r">Fat</th>
                    <th class="r">kJ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->exchangeTemplate->items as $item)
                @php $nu = $item->nu; @endphp
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="c" style="font-weight:bold;color:#006442">{{ $nu }}</td>
                    <td class="r">{{ $item->cho_g !== null ? number_format($nu*$item->cho_g,1) : '—' }}</td>
                    <td class="r">{{ $item->protein_min_g !== null ? number_format($nu*$item->protein_min_g,1) : '—' }}</td>
                    <td class="r">{{ $item->fat_min_g !== null ? number_format($nu*$item->fat_min_g,1) : '—' }}</td>
                    <td class="r" style="color:#006442;font-weight:bold">{{ $item->kj !== null ? number_format($nu*$item->kj) : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tbody>
                <tr class="tot">
                    <td colspan="2" style="font-size:7px;color:#555;text-transform:uppercase;letter-spacing:.4px">Total</td>
                    <td class="r">{{ $etTotCho ? number_format($etTotCho,1) : '—' }}</td>
                    <td class="r">{{ $etTotPro ? number_format($etTotPro,1) : '—' }}</td>
                    <td class="r">{{ $etTotFat ? number_format($etTotFat,1) : '—' }}</td>
                    <td class="r" style="color:#006442">{{ $etTotKj ? number_format($etTotKj) : '—' }}</td>
                </tr>
            </tbody>
        </table>
        @endif
    </div>

</td>

{{-- ══ RIGHT COLUMN ══ --}}
<td>

    {{-- SECTION C --}}
    <div class="section-header bg-c">
        <span class="circle-label circle-c">C</span> CALCULATION OF REQUIREMENTS
    </div>
    <div class="content-box">

        @if($enteral)
        <div class="sub-header">Enteral Feed Calculation &mdash; {{ $enteral->label ?: 'Latest Calculation' }}</div>

        @if($enteral->clinical_condition)
        <table class="f-row-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="fl">Clinical Condition:</td>
                <td class="fv">{{ \App\Models\EnteralNutritionCalculation::CONDITIONS[$enteral->clinical_condition] ?? $enteral->clinical_condition }}</td>
            </tr>
        </table>
        @endif

        <div class="sub-header" style="margin-top:6px">Energy &amp; Protein Targets</div>
        <table class="en-grid" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="en-box">
                        <div class="en-label">Energy Target</div>
                        <div class="en-val">{{ $enteral->energy_target_kcal ? number_format($enteral->energy_target_kcal) : '—' }}</div>
                        <div class="en-unit">kcal/day</div>
                    </div>
                </td>
                <td>
                    <div class="en-box">
                        <div class="en-label">Energy per kg</div>
                        <div class="en-val">{{ $enteral->energy_kcal_per_kg ?? '—' }}</div>
                        <div class="en-unit">kcal/kg/day</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="en-box">
                        <div class="en-label">Protein Target</div>
                        <div class="en-val">{{ $enteral->protein_target_g ? number_format($enteral->protein_target_g,1) : '—' }}</div>
                        <div class="en-unit">g/day</div>
                    </div>
                </td>
                <td>
                    <div class="en-box">
                        <div class="en-label">Protein per kg</div>
                        <div class="en-val">{{ $enteral->protein_g_per_kg ?? '—' }}</div>
                        <div class="en-unit">g/kg/day</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="sub-header" style="margin-top:6px">Feed Delivery</div>
        <table class="data-table" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td>Weight Used ({{ $enteral->weight_type ?? 'actual' }})</td>
                    <td class="r">{{ $enteral->weight_kg ? number_format($enteral->weight_kg,1).' kg' : '—' }}</td>
                </tr>
                <tr>
                    <td>Formula Density</td>
                    <td class="r">{{ $enteral->formula_density ? $enteral->formula_density.' kcal/mL' : '—' }}</td>
                </tr>
                <tr>
                    <td>Daily Volume</td>
                    <td class="r">{{ $enteral->daily_volume_ml ? number_format($enteral->daily_volume_ml).' mL/day' : '—' }}</td>
                </tr>
                <tr>
                    <td>Feeding Hours</td>
                    <td class="r">{{ $enteral->feeding_hours_per_day ? $enteral->feeding_hours_per_day.' hr/day' : '—' }}</td>
                </tr>
                <tr class="tot">
                    <td>Rate</td>
                    <td class="r">{{ $enteral->rate_ml_per_hour ? number_format($enteral->rate_ml_per_hour,1).' mL/hr' : '—' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="sub-header" style="margin-top:6px">Fluid Requirements</div>
        <table class="data-table" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td>Total Fluid Requirement</td>
                    <td class="r">{{ $enteral->fluid_requirement_ml ? number_format($enteral->fluid_requirement_ml).' mL' : '—' }}</td>
                </tr>
                <tr>
                    <td>Free Water from Formula</td>
                    <td class="r">{{ $enteral->free_water_from_formula_ml ? number_format($enteral->free_water_from_formula_ml).' mL' : '—' }}</td>
                </tr>
                <tr class="tot">
                    <td>Additional Water Needed</td>
                    <td class="r">{{ $enteral->additional_water_ml ? number_format($enteral->additional_water_ml).' mL' : '—' }}</td>
                </tr>
            </tbody>
        </table>

        @if($enteral->notes)
        <div class="sub-header" style="margin-top:6px">Clinical Notes</div>
        <p style="font-size:8px;color:#333;line-height:1.5">{{ $enteral->notes }}</p>
        @endif

        @else
        <p style="font-size:8.5px;color:#888;padding:10px 0;text-align:center">
            No enteral feed calculation recorded for this patient.<br>
            <span style="font-size:7.5px">Use the Enteral Feed Calculation tool to generate requirements.</span>
        </p>
        @endif

    </div>

    {{-- DIGITAL STAMP --}}
    <div style="margin-top:10px">
        <div style="font-size:7.5px;font-weight:bold;color:#555;text-transform:uppercase;letter-spacing:.5px;margin-bottom:5px">
            Dietitian Signature &amp; Stamp
        </div>
        <div class="stamp">
            <div class="stamp-name">{{ $user->name }}</div>
            @if($user->dietician_number)
            <div class="stamp-reg">Reg. No. {{ $user->dietician_number }}</div>
            @endif
            <div class="stamp-line"></div>
            <div style="font-size:7px;color:#006442;font-weight:bold">REGISTERED DIETITIAN</div>
            <div style="font-size:7px;color:#555;margin-top:2px">Date: {{ $genDate }}</div>
            <div style="font-size:6.5px;color:#888;margin-top:4px">&#x2713; Digitally Verified</div>
        </div>
        <div class="stamp-watermark" style="margin-top:4px">SIGNED</div>
    </div>

    {{-- CONSENT --}}
    <div style="margin-top:10px;padding:6px 8px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px;font-size:7.5px">
        <strong style="font-size:8px">POPIA Consent:</strong>
        @if($patient->hasConsented())
            <span style="color:#15803d;font-weight:bold">&#x2713; Consented</span>
            @if($patient->consented_at) on {{ $patient->consented_at->format('d M Y') }} @endif
        @elseif($patient->consentDeclined())
            <span style="color:#b91c1c;font-weight:bold">&#x2715; Declined</span>
        @else
            <span style="color:#d97706;font-weight:bold">Pending</span>
        @endif
        <br><span style="color:#888">Patient data protected under POPIA.</span>
    </div>

</td>
</tr></tbody>
</table>

{{-- SECTION D — Tube Feeding Recommendations (Package 3) --}}
@if(!empty($isPackage3) && $enteral)
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
    $dm    = $dMacros[(string)number_format($dDensity,1)] ?? $dMacros['1.0'];
    $dChoG = round($dVol * $dDensity * $dm['cho_pct'] / 100 / 4, 1);
    $dProG = round($dVol * $dDensity * $dm['pro_pct'] / 100 / 4, 1);
    $dFatG = round($dVol * $dDensity * $dm['fat_pct'] / 100 / 9, 1);

    $dFwFrac   = match(true) { $dDensity >= 1.45 => 0.76, $dDensity >= 1.15 => 0.82, default => 0.85 };
    $dFwMl     = round($dVol * $dFwFrac);
    $dAddWater = (int)$enteral->additional_water_ml;
    $dFluidReq = (int)$enteral->fluid_requirement_ml;
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
        $dBmi === null  => 'Normal',
        $dBmi < 18.5    => 'Underweight',
        $dBmi < 25.0    => 'Normal',
        $dBmi < 30.0    => 'Overweight',
        default         => 'Obese',
    };
    $dProAdequate = $dProG > 0 && $dProteinG > 0 ? ($dProG >= $dProteinG * 0.95) : null;
    $dCalcDate    = $enteral->created_at?->format('d M Y') ?? '';
@endphp

<div style="margin-top:10px">
    <div class="section-header bg-d">
        <span class="circle-label circle-d">D</span> TUBE FEEDING RECOMMENDATIONS
        @if($dCalcDate)<span style="font-size:7px;font-weight:400;opacity:.8;margin-left:8px">Calculated {{ $dCalcDate }}</span>@endif
    </div>
    <div class="content-box">
        {{-- Banner row --}}
        <table class="tube-banner" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td>Condition: <strong>{{ $dCond }}</strong></td>
                <td>Formula: <strong>{{ $dDensity }} kcal/mL</strong></td>
                <td>Goal: <strong>{{ $dRate }} mL/hr &times; {{ $dHours }}h</strong></td>
                <td>Protein: <strong>{{ $dProG }}g</strong> / <strong>{{ $dProteinG }}g</strong> target</td>
                <td>Flush: <strong>{{ $dFlushMl }}mL</strong> {{ $dFlushFreqStr }}</td>
            </tr>
        </table>

        {{-- 3-column grid --}}
        <table class="tube-3col" cellpadding="0" cellspacing="0">
            <tbody><tr>
                {{-- Macronutrients --}}
                <td>
                    <div class="tube-box">
                        <div class="tube-box-title">Macronutrients</div>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Feed Calories</td><td class="tube-val">{{ number_format($dEnergyKcal) }} kcal</td></tr>
                        </table>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Total Protein</td><td class="tube-val">{{ $dProteinG }}g/day</td></tr>
                        </table>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Carbohydrates</td><td class="tube-val">{{ $dChoG }}g</td></tr>
                        </table>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Fat</td><td class="tube-val">{{ $dFatG }}g</td></tr>
                        </table>
                        @if($dProAdequate !== null)
                        <span class="pro-badge {{ $dProAdequate ? 'pro-ok' : 'pro-low' }}">
                            {{ $dProAdequate ? 'Protein target met' : 'Below protein target' }}
                        </span>
                        @endif
                    </div>
                </td>
                {{-- Fluid --}}
                <td>
                    <div class="tube-box">
                        <div class="tube-box-title">Fluid Management</div>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Total Fluids</td><td class="tube-val">{{ number_format($dTotalFluid) }} mL</td></tr>
                        </table>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Daily Needs (35mL/kg)</td><td class="tube-val">{{ number_format($dFluidReq) }} mL</td></tr>
                        </table>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Free Water</td><td class="tube-val">{{ number_format($dFwMl) }} mL</td></tr>
                        </table>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Additional Water</td><td class="tube-val">{{ number_format($dAddWater) }} mL</td></tr>
                        </table>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Water Flush</td><td class="tube-val">{{ $dFlushMl }}mL &times;{{ $dFlushPerDay }}/d = {{ number_format($dFlushTotal) }}mL</td></tr>
                        </table>
                        <div style="font-size:6.5px;color:#6b7280;margin-top:2px">Flush {{ $dFlushMl }}mL {{ $dFlushFreqStr }}</div>
                    </div>
                </td>
                {{-- Anthropometrics --}}
                <td>
                    <div class="tube-box">
                        <div class="tube-box-title">Anthropometrics</div>
                        @if($dIbw !== null)
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">IBW (Devine)</td><td class="tube-val">{{ $dIbw }} kg</td></tr>
                        </table>
                        @endif
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Actual Body Wt</td><td class="tube-val">{{ $dActualBw }} kg</td></tr>
                        </table>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Weight Used ({{ $dWeightLabel }})</td><td class="tube-val">{{ $dWeightKg }} kg</td></tr>
                        </table>
                        @if($dBmi !== null)
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">BMI</td><td class="tube-val">{{ $dBmi }} &mdash; {{ $dBmiClass }}</td></tr>
                        </table>
                        @endif
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Energy Target</td><td class="tube-val">{{ $enteral->energy_kcal_per_kg }} kcal/kg</td></tr>
                        </table>
                        <table class="tube-row" cellpadding="0" cellspacing="0">
                            <tr><td class="tube-lbl">Protein Target</td><td class="tube-val">{{ $enteral->protein_g_per_kg }} g/kg</td></tr>
                        </table>
                    </div>
                </td>
            </tr></tbody>
        </table>

        <div class="startup-box">
            <strong>Startup Protocol:</strong> Start at 20&ndash;30 mL/hr. Advance by 10&ndash;20 mL/hr every 4&ndash;8 hours as tolerated until goal rate of {{ $dRate }} mL/hr is achieved.
        </div>
    </div>
</div>
@endif

{{-- FOOTER --}}
<table class="footer-table" cellpadding="0" cellspacing="0">
    <tr>
        <td class="footer-note">
            {{ $patient->name }} &middot; Dietetic Assessment &amp; Care Plan &middot; Generated {{ $genDate }} at {{ $genTime }}
        </td>
        <td style="text-align:right;font-size:7.5px;color:#888">
            Confidential &mdash; For authorised healthcare use only
        </td>
    </tr>
</table>
<div class="confidential-bar">
    This report is confidential and intended for the use of the patient and healthcare team only.
</div>

</body>
</html>
