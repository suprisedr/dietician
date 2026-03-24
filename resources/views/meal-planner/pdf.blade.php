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
  .pdf-title { font-size: 14pt; font-weight: bold; color: #0f172a; }
  .pdf-subtitle { font-size: 9pt; color: #475569; margin-top: 2px; }
  .pdf-meta   { font-size: 7.5pt; color: #64748b; }

  /* ── Main grid table ─────────────────────────────────────── */
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

  /* Column widths: slot label col + 7 day cols */
  col.slot-col { width: 70px; }
  col.day-col  { width: auto; }

  /* Day header row */
  thead tr.day-header th {
    text-align: center;
    font-size: 8pt;
    font-weight: bold;
    background: #1e293b;
    color: #f8fafc;
    padding: 5px 3px;
  }
  thead tr.day-header th.slot-label-th {
    background: #0f172a;
  }

  /* Slot label cells */
  td.slot-label {
    font-weight: bold;
    font-size: 7.5pt;
    vertical-align: middle;
    text-align: center;
    border-right: 2px solid #94a3b8;
  }

  /* Meal item text inside each cell */
  .meal-entry {
    margin-bottom: 2px;
    line-height: 1.35;
  }
  .meal-entry:last-child { margin-bottom: 0; }

  /* Slot colour bands */
  tr.slot-breakfast td.meal-cell  { background: #fff7ed; }
  tr.slot-breakfast td.slot-label { background: #fed7aa; color: #7c2d12; }
  tr.slot-snack1    td.meal-cell  { background: #f0fdf4; }
  tr.slot-snack1    td.slot-label { background: #bbf7d0; color: #14532d; }
  tr.slot-lunch     td.meal-cell  { background: #eff6ff; }
  tr.slot-lunch     td.slot-label { background: #bfdbfe; color: #1e3a8a; }
  tr.slot-snack2    td.meal-cell  { background: #f0fdf4; }
  tr.slot-snack2    td.slot-label { background: #bbf7d0; color: #14532d; }
  tr.slot-dinner    td.meal-cell  { background: #faf5ff; }
  tr.slot-dinner    td.slot-label { background: #e9d5ff; color: #4c1d95; }
  tr.slot-snack3    td.meal-cell  { background: #f0fdf4; }
  tr.slot-snack3    td.slot-label { background: #bbf7d0; color: #14532d; }

  /* kJ row */
  tr.kj-row td {
    background: #f8fafc;
    font-size: 7pt;
    color: #475569;
    text-align: center;
    padding: 3px 4px;
    border-top: 2px solid #cbd5e1;
  }
  tr.kj-row td.slot-label {
    font-weight: bold;
    color: #334155;
    background: #e2e8f0;
    border-right: 2px solid #94a3b8;
  }

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

{{-- ── Header ──────────────────────────────────────────────── --}}
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

{{-- ── Grid table ───────────────────────────────────────────── --}}
<table class="plan-grid">
  <colgroup>
    <col class="slot-col">
    @foreach($days as $day)
      <col class="day-col">
    @endforeach
  </colgroup>

  <thead>
    <tr class="day-header">
      <th class="slot-label-th">Meal</th>
      @foreach($days as $day)
        <th>{{ $day }}</th>
      @endforeach
    </tr>
  </thead>

  <tbody>
    @foreach($slots as $slot)
      <tr class="slot-{{ $slot }}">
        <td class="slot-label">{{ $slotLabels[$slot] ?? $slot }}</td>
        @foreach($days as $di => $day)
          <td class="meal-cell">
            @foreach($grid[$di][$slot] as $entry)
              <div class="meal-entry">{{ $entry->meal_text }}</div>
            @endforeach
          </td>
        @endforeach
      </tr>
    @endforeach

    {{-- kJ totals row --}}
    <tr class="kj-row">
      <td class="slot-label">Total kJ</td>
      @foreach($days as $di => $day)
        <td>
          @php $total = array_sum(array_column(array_map(fn($s) => ['kj' => $cellKj[$di][$s]], $slots), 'kj')); @endphp
          {{ $total > 0 ? number_format($total) . ' kJ' : '—' }}
        </td>
      @endforeach
    </tr>
  </tbody>
</table>

<div class="pdf-footer">
  {{ config('app.name') }} &bull; Confidential &bull; For clinical use only
</div>

</body>
</html>
