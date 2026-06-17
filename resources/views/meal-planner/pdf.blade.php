<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
  @page {
    size: A4 portrait;
    background-color: #f3e9e9;
  }
  html, body {
    background-color: #f3e9e9;
  }
  body {
    font-family: 'DejaVu Serif', serif;
    font-size: 7.5pt;
    color: #2d5a43;
    background-color: #f3e9e9;
    margin: 0;
    padding: 14px 16px;
  }

  /* ── Header layout table ─────────────────────────────── */
  table.hdr {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    border-bottom: 2px solid #2d5a43;
    padding-bottom: 5px;
  }
  td.hdr-left  { vertical-align: middle; background-color: #f3e9e9; }
  td.hdr-right { vertical-align: middle; text-align: right; width: 230px; background-color: #f3e9e9; }

  .plan-title-em  { font-size: 28pt; font-style: italic; color: #2d5a43; font-weight: normal; }
  .plan-title-str { font-size: 28pt; font-weight: bold;  color: #2d5a43; letter-spacing: 4px; }

  .hdr-patient {
    font-size: 10pt;
    font-weight: bold;
    color: #2d5a43;
    border-bottom: 2px solid #2d5a43;
    padding-bottom: 3px;
    margin-bottom: 4px;
  }
  .hdr-week { font-size: 8pt; color: #2d5a43; }
  .hdr-gen  { font-size: 7pt;  color: #5a8a70; margin-top: 2px; }

  /* ── Plan grid ───────────────────────────────────────── */
  table.plan-grid {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
  }

  table.plan-grid th {
    color: #2d5a43;
    padding: 5px 2px;
    text-align: center;
    font-size: 6.5pt;
    font-weight: bold;
    border-bottom: 2px solid #2d5a43;
    background-color: #f3e9e9;
  }

  table.plan-grid td {
    padding: 4px 3px;
    vertical-align: top;
    border: 1px solid #c8ddd6;
    border-bottom: 2px solid #2d5a43;
    height: 44px;
    background-color: #f3e9e9;
  }

  td.day-col, th.day-col {
    width: 28px;
    text-align: center;
    vertical-align: middle;
    font-weight: bold;
    font-size: 7pt;
    border-right: 2px solid #2d5a43;
    border-left: none;
    color: #2d5a43;
  }

  td.total-col, th.total-col {
    width: 36px;
    text-align: center;
    vertical-align: middle;
    font-size: 6.5pt;
    font-weight: bold;
    color: #2d5a43;
    border-right: none;
  }

  td.fluids-col, th.fluids-col { width: 36px; }

  /* Per-item layout */
  .meal-entry  { margin-bottom: 1px; line-height: 1.2; }
  .entry-name  { font-size: 6pt; font-weight: bold; color: #1a3d2b; }
  .entry-serv  { font-size: 5.5pt; color: #4a7a60; }
  .entry-mac   { font-size: 5.5pt; color: #6b9c80; margin-top: 0; }

  /* Footer */
  .pdf-footer {
    margin-top: 8px;
    font-size: 7pt;
    color: #5a8a70;
    text-align: right;
    border-top: 1px solid #c8ddd6;
    padding-top: 4px;
  }
</style>
</head>
<body>

@if(!empty($letterhead))
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;border-bottom:1.5px solid #c8ddd6;padding-bottom:10px">
  <tr>
    <td style="text-align:center;background-color:#f3e9e9">
      <img src="{{ $letterhead }}" style="width:100%;height:auto;display:block">
    </td>
  </tr>
</table>
@endif

{{-- ── Header ────────────────────────────────────────────── --}}
<table class="hdr" cellpadding="0" cellspacing="0">
  <tr>
    <td class="hdr-left">
      <span class="plan-title-em">Meal</span>
      <span class="plan-title-str"> PLAN</span>
    </td>
    <td class="hdr-right">
      <div class="hdr-patient">{{ $mealPlanner->patient?->name ?? 'Patient' }}</div>
      <div class="hdr-week">Week: {{ $mealPlanner->week_start->format('d M Y') }} &#8211; {{ $mealPlanner->week_start->copy()->addDays(6)->format('d M Y') }}</div>
      <div class="hdr-gen">Generated {{ now()->format('d M Y') }}</div>
    </td>
  </tr>
</table>

{{-- ── Grid ────────────────────────────────────────────── --}}
<table class="plan-grid" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th class="day-col">DAY</th>
      @foreach($slots as $slot)
        <th>{{ strtoupper($slotLabels[$slot] ?? $slot) }}</th>
      @endforeach
      <th class="fluids-col">FLUIDS</th>
      <th class="total-col">TOTAL kJ</th>
    </tr>
  </thead>
  <tbody>
    @foreach($days as $di => $dayName)
      <tr>
        <td class="day-col">{{ strtoupper(substr($dayName, 0, 3)) }}</td>
        @foreach($slots as $slot)
          <td>
            @foreach($grid[$di][$slot] as $entry)
              @php
                $mi      = $entry->mealItem;
                $qty     = max(0.25, (float)($entry->qty ?? 1));
                $qtyLbl  = rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.');
                $serving = $mi?->serving_size;
                $kj  = $mi?->energy_kj  ? round($mi->energy_kj  * $qty) : null;
                $cho = $mi?->cho_g      ? round($mi->cho_g      * $qty, 1) : null;
                $pro = $mi?->protein_g  ? round($mi->protein_g  * $qty, 1) : null;
                $fat = $mi?->fat_g      ? round($mi->fat_g      * $qty, 1) : null;
                $fib = ($mi?->fiber_g && $mi->fiber_g > 0) ? round($mi->fiber_g * $qty, 1) : null;
                $macroParts = array_filter([
                    $kj  ? $kj.'kJ'  : null,
                    $cho ? $cho.'g C' : null,
                    $pro ? $pro.'g P' : null,
                    $fat ? $fat.'g F' : null,
                    $fib ? $fib.'g Fb': null,
                ]);
              @endphp
              <div class="meal-entry">
                <span class="entry-name">@if($qty != 1){{ $qtyLbl }}x @endif{{ $entry->meal_text }}</span>@if($serving)<span class="entry-serv"> ({{ $serving }})</span>@endif
                @if($macroParts)
                  <div class="entry-mac">{!! implode(' · ', $macroParts) !!}</div>
                @endif
              </div>
            @endforeach
          </td>
        @endforeach
        <td class="fluids-col"></td>
        <td class="total-col">{{ $dayKj[$di] > 0 ? number_format($dayKj[$di]) : '—' }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<div class="pdf-footer">
  {{ config('app.name') }} &#183; Confidential &#183; For clinical use only
</div>

</body>
</html>
