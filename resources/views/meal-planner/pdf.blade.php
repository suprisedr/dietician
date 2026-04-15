<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 8pt;
    color: #1e293b;
    padding: 12px 14px;
    background: #fff;
  }

  /* ── Header ─────────────────────────────────────────────── */
  .pdf-header {
    display: table;
    width: 100%;
    margin-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 6px;
  }
  .pdf-header-left  { display: table-cell; vertical-align: middle; }
  .pdf-header-right { display: table-cell; vertical-align: middle; text-align: right; }
  .pdf-title    { font-size: 14pt; font-weight: bold; color: #0f172a; }
  .pdf-subtitle { font-size: 9pt;  color: #475569; margin-top: 2px; }
  .pdf-meta     { font-size: 7.5pt; color: #64748b; }

  /* ── Grid ───────────────────────────────────────────────── */
  table.plan-grid {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
  }
  table.plan-grid th,
  table.plan-grid td {
    border: 1px solid #cbd5e1;
    vertical-align: top;
    padding: 4px 5px;
    word-wrap: break-word;
  }

  /* Column widths: day label col | 6 slot cols | total col */
  th.day-th    { width: 48px; }
  th.total-th  { width: 44px; }

  /* Slot header row */
  thead th {
    text-align: center;
    font-size: 7.5pt;
    font-weight: bold;
    padding: 5px 3px;
  }
  thead th.day-th    { background: #0f172a; color: #f8fafc; }
  thead th.slot-breakfast { background: #fed7aa; color: #7c2d12; }
  thead th.slot-snack1    { background: #bbf7d0; color: #14532d; }
  thead th.slot-lunch     { background: #bfdbfe; color: #1e3a8a; }
  thead th.slot-snack2    { background: #bbf7d0; color: #14532d; }
  thead th.slot-dinner    { background: #e9d5ff; color: #4c1d95; }
  thead th.slot-snack3    { background: #bbf7d0; color: #14532d; }
  thead th.total-th       { background: #e2e8f0; color: #334155; }

  /* Day label cells */
  td.day-label {
    font-weight: bold;
    font-size: 7.5pt;
    vertical-align: middle;
    text-align: center;
    background: #1e293b;
    color: #f8fafc;
    border-right: 2px solid #94a3b8;
  }

  /* Meal cells per slot */
  td.cell-breakfast { background: #fff7ed; }
  td.cell-snack1    { background: #f0fdf4; }
  td.cell-lunch     { background: #eff6ff; }
  td.cell-snack2    { background: #f0fdf4; }
  td.cell-dinner    { background: #faf5ff; }
  td.cell-snack3    { background: #f0fdf4; }

  /* Total column */
  td.day-total {
    background: #f8fafc;
    font-size: 7pt;
    font-weight: bold;
    color: #475569;
    text-align: center;
    vertical-align: middle;
    border-left: 2px solid #94a3b8;
  }

  /* Per-item layout inside a cell */
  .meal-entry { margin-bottom: 3px; line-height: 1.3; }
  .meal-entry:last-child { margin-bottom: 0; }
  .entry-name    { font-size: 7pt; font-weight: bold; }
  .entry-serving { font-size: 6.5pt; color: #64748b; }
  .entry-macros  { font-size: 6pt; color: #94a3b8; margin-top: 1px; }

  /* Footer */
  .pdf-footer {
    margin-top: 8px;
    font-size: 7pt;
    color: #94a3b8;
    text-align: right;
    border-top: 1px solid #e2e8f0;
    padding-top: 4px;
  }
</style>
</head>
<body>

{{-- ── Header ────────────────────────────────────────────── --}}
<div class="pdf-header">
  <div class="pdf-header-left">
    <div class="pdf-title">Meal Planner — {{ $mealPlanner->label ?: 'Weekly Plan' }}</div>
    <div class="pdf-subtitle">
      Patient: <strong>{{ $mealPlanner->patient?->name ?? '—' }}</strong>
      &nbsp;|&nbsp;
      Week of {{ $mealPlanner->week_start->format('d M Y') }}
    </div>
  </div>
  <div class="pdf-header-right">
    <div class="pdf-meta">Generated {{ now()->format('d M Y, H:i') }}</div>
  </div>
</div>

{{-- ── Grid: days as rows, slots as columns ────────────── --}}
<table class="plan-grid">
  <thead>
    <tr>
      <th class="day-th">Day</th>
      @foreach($slots as $slot)
        <th class="slot-{{ $slot }}">{{ $slotLabels[$slot] ?? $slot }}</th>
      @endforeach
      <th class="total-th">Total kJ</th>
    </tr>
  </thead>
  <tbody>
    @foreach($days as $di => $dayName)
      <tr>
        <td class="day-label">{{ $dayName }}</td>
        @foreach($slots as $slot)
          <td class="cell-{{ $slot }}">
            @foreach($grid[$di][$slot] as $entry)
              @php
                $mi      = $entry->mealItem;
                $qty     = max(1, (int)($entry->qty ?? 1));
                $serving = $mi?->serving_size;
                $kj  = $mi?->energy_kj  ? round($mi->energy_kj  * $qty) : null;
                $cho = $mi?->cho_g      ? round($mi->cho_g      * $qty, 1) : null;
                $pro = $mi?->protein_g  ? round($mi->protein_g  * $qty, 1) : null;
                $fat = $mi?->fat_g      ? round($mi->fat_g      * $qty, 1) : null;
                $fib = ($mi?->fiber_g && $mi->fiber_g > 0) ? round($mi->fiber_g * $qty, 1) : null;
                $macroParts = array_filter([
                    $kj  ? $kj.'kJ'   : null,
                    $cho ? $cho.'g C'  : null,
                    $pro ? $pro.'g P'  : null,
                    $fat ? $fat.'g F'  : null,
                    $fib ? $fib.'g Fb' : null,
                ]);
              @endphp
              <div class="meal-entry">
                <span class="entry-name">{{ $qty > 1 ? $qty.'× ' : '' }}{{ $entry->meal_text }}</span>@if($serving)<span class="entry-serving"> ({{ $serving }})</span>@endif
                @if($macroParts)
                  <div class="entry-macros">{{ implode(' · ', $macroParts) }}</div>
                @endif
              </div>
            @endforeach
          </td>
        @endforeach
        <td class="day-total">
          {{ $dayKj[$di] > 0 ? number_format($dayKj[$di]).' kJ' : '—' }}
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<div class="pdf-footer">
  {{ config('app.name') }} &bull; Confidential &bull; For clinical use only
</div>

</body>
</html>
