<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Visit Log — {{ $patient->full_name }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9.5px;
        color: #0d1f0c;
        line-height: 1.5;
        background: #fff;
    }

    /* ── HEADER ── */
    .header {
        background-color: #1a4a36;
        color: #fff;
        padding: 16px 22px 12px;
        margin-bottom: 14px;
    }
    .header-top { width: 100%; margin-bottom: 6px; }
    .header-top td { vertical-align: top; }
    .header-title { font-size: 16px; font-weight: bold; color: #fff; }
    .header-sub { font-size: 9px; color: rgba(255,255,255,.7); margin-top: 2px; }
    .header-meta { text-align: right; font-size: 9px; color: rgba(255,255,255,.75); }
    .header-patient { margin-top: 8px; padding-top: 8px; border-top: 1px solid rgba(255,255,255,.25); }
    .header-patient td { vertical-align: top; }
    .pname { font-size: 13px; font-weight: bold; color: #fff; }
    .pdetail { font-size: 8.5px; color: rgba(255,255,255,.75); margin-top: 2px; }
    .chip {
        display: inline-block;
        padding: 1px 7px;
        border-radius: 10px;
        font-size: 7.5px;
        font-weight: bold;
        margin-right: 3px;
    }
    .chip-oedema { background: #fef3c7; color: #92400e; }
    .chip-no-oedema { background: #dcfce7; color: #15803d; }

    /* ── STATS BAR ── */
    .stats { width: 100%; margin-bottom: 12px; border-collapse: collapse; }
    .stats td {
        width: 25%;
        padding: 8px 10px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        text-align: center;
    }
    .stat-val { font-size: 13px; font-weight: bold; color: #1a4a36; }
    .stat-lbl { font-size: 7.5px; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; margin-top: 2px; }

    /* ── TABLE ── */
    .visit-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    .visit-table th {
        background: #1a4a36;
        color: #fff;
        padding: 6px 8px;
        text-align: left;
        font-size: 8px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .visit-table th.r { text-align: right; }
    .visit-table th.c { text-align: center; }
    .visit-table td {
        padding: 5px 8px;
        font-size: 9px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
    }
    .visit-table td.r { text-align: right; }
    .visit-table td.c { text-align: center; }
    .visit-table tr.even { background: #f9fafb; }
    .visit-table tr.odd  { background: #ffffff; }
    .wt { font-weight: bold; color: #0d1f0c; }
    .muted { color: #6b7280; }
    .gain { color: #b91c1c; font-weight: bold; }
    .loss { color: #15803d; font-weight: bold; }
    .time-sub { font-size: 7.5px; color: #9ca3af; display: block; }

    /* ── FOOTER ── */
    .footer {
        margin-top: 10px;
        padding-top: 8px;
        border-top: 1px solid #e5e7eb;
        font-size: 7.5px;
        color: #9ca3af;
        width: 100%;
    }
    .footer td { vertical-align: top; }
</style>
</head>
<body>

@php
    $user        = auth()->user();
    $letterhead  = $user->letterheadBase64();
    $totalVisits = $visits->count();

    // Overall weight change
    $firstV = $visits->last();
    $lastV  = $visits->first();
    $overallChange = ($firstV && $lastV && $firstV->id !== $lastV->id)
        ? round($lastV->weight - $firstV->weight, 1)
        : null;

    // Current oedema status
    $oedemaStatus = $patient->oedema ? 'Present' : 'No Oedema';
    $oedemaSince  = $patient->oedema_changed_at?->format('d M Y H:i') ?? '—';
@endphp

{{-- HEADER --}}
<div class="header">
    <table class="header-top">
        <tr>
            <td style="width:70%">
                @if($letterhead)
                    <img src="{{ $letterhead }}" style="max-height:45px;max-width:200px;margin-bottom:6px"><br>
                @endif
                <div class="header-title">Visit History &amp; Monitoring Log</div>
                <div class="header-sub">{{ $user->name }} &middot; Generated {{ now()->format('d M Y \a\t H:i') }}</div>
            </td>
            <td class="header-meta" style="width:30%">
                <div>CONFIDENTIAL</div>
                <div style="margin-top:4px">{{ now()->format('d M Y') }}</div>
            </td>
        </tr>
    </table>
    <div class="header-patient">
        <table style="width:100%">
            <tr>
                <td style="width:60%">
                    <div class="pname">{{ $patient->full_name }}</div>
                    <div class="pdetail">
                        {{ ucfirst($patient->gender) }} &middot; Age {{ $patient->age }}
                        @if($patient->date_of_birth) &middot; DOB {{ $patient->date_of_birth->format('d M Y') }} @endif
                        @if($patient->id_number) &middot; {{ $patient->id_type === 'passport' ? 'Passport' : 'SA ID' }}: {{ $patient->id_number }} @endif
                    </div>
                </td>
                <td style="text-align:right">
                    <span class="pdetail">Current Oedema:</span>
                    <span class="chip {{ $patient->oedema ? 'chip-oedema' : 'chip-no-oedema' }}">
                        {{ $oedemaStatus }}
                    </span>
                    <div class="pdetail" style="margin-top:3px">since {{ $oedemaSince }}</div>
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- STATS BAR --}}
<table class="stats">
    <tr>
        <td>
            <div class="stat-val">{{ $totalVisits }}</div>
            <div class="stat-lbl">Total Visits</div>
        </td>
        <td>
            <div class="stat-val">{{ $lastV ? number_format($lastV->weight, 1) . ' kg' : '—' }}</div>
            <div class="stat-lbl">Latest Weight</div>
        </td>
        <td>
            <div class="stat-val">{{ $lastV?->bmi ?? '—' }}</div>
            <div class="stat-lbl">Latest BMI</div>
        </td>
        <td>
            @if($overallChange !== null)
                <div class="stat-val {{ $overallChange <= 0 ? 'loss' : 'gain' }}">
                    {{ $overallChange > 0 ? '+' : '' }}{{ $overallChange }} kg
                </div>
            @else
                <div class="stat-val">—</div>
            @endif
            <div class="stat-lbl">Overall Change</div>
        </td>
    </tr>
</table>

{{-- VISIT TABLE --}}
@if($visits->isEmpty())
    <p style="text-align:center;color:#6b7280;padding:20px 0">No visits recorded.</p>
@else
<table class="visit-table">
    <thead>
        <tr>
            <th style="width:5%">#</th>
            <th style="width:16%">Date &amp; Time</th>
            <th class="r" style="width:10%">Weight (kg)</th>
            <th class="r" style="width:10%">Height (cm)</th>
            <th class="r" style="width:8%">BMI</th>
            <th class="r" style="width:10%">Change</th>
            <th class="c" style="width:10%">Oedema</th>
            <th style="width:31%">Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($visits as $i => $visit)
            @php
                $prev       = $visits->get($i + 1);
                $weightDiff = $prev ? round($visit->weight - $prev->weight, 1) : null;
                $bmi        = $visit->bmi;
                $rowClass   = $i % 2 === 0 ? 'even' : 'odd';
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="muted">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $visit->visited_at->format('d M Y') }}</strong>
                    <span class="time-sub">{{ $visit->visited_at->format('H:i') }}</span>
                </td>
                <td class="r wt">{{ number_format($visit->weight, 1) }}</td>
                <td class="r muted">{{ $visit->height ? number_format($visit->height, 1) : '—' }}</td>
                <td class="r muted">{{ $bmi ?? '—' }}</td>
                <td class="r">
                    @if($weightDiff !== null)
                        @if($weightDiff < 0)
                            <span class="loss">{{ $weightDiff }} kg</span>
                        @elseif($weightDiff > 0)
                            <span class="gain">+{{ $weightDiff }} kg</span>
                        @else
                            <span class="muted">no change</span>
                        @endif
                    @else
                        <span class="muted">—</span>
                    @endif
                </td>
                <td class="c">
                    @if($visit->oedema === null)
                        <span class="muted">—</span>
                    @elseif($visit->oedema)
                        <span class="chip chip-oedema">Yes</span>
                    @else
                        <span class="chip chip-no-oedema">No</span>
                    @endif
                </td>
                <td class="muted">{{ $visit->notes ?: '—' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- FOOTER --}}
<table class="footer">
    <tr>
        <td>{{ $patient->full_name }} — Visit Log</td>
        <td style="text-align:center">{{ $user->name }}</td>
        <td style="text-align:right">Generated {{ now()->format('d M Y H:i') }}</td>
    </tr>
</table>

</body>
</html>
