<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
  @page { background-color:#f3e9e9; size:A4 portrait; }
  html, body {
    background-color:#f3e9e9;
    margin:0;
    padding:18px 20px;
    font-family:'DejaVu Serif', serif;
    font-size:7.5pt;
    color:#2d5a43;
  }

  /* ── Header ────────────────────────────────────────────── */
  table.hdr {
    width:100%;
    border-collapse:collapse;
    margin-bottom:14px;
    border-bottom:3px solid #2f5d50;
    padding-bottom:8px;
  }
  td.hdr-title { vertical-align:middle; background-color:#f3e9e9; }
  td.hdr-right { vertical-align:middle; text-align:right; width:200px; background-color:#f3e9e9; }

  .title-main { font-size:26pt; font-weight:bold; color:#2f5d50; letter-spacing:3px; line-height:.85; }
  .title-sub  { font-size:14pt; font-weight:normal; color:#2f5d50; display:block; }

  .hdr-patient { font-size:9pt; font-weight:bold; color:#2f5d50; border-bottom:1.5px solid #2f5d50; padding-bottom:2px; margin-bottom:3px; }
  .hdr-week    { font-size:7pt; color:#2f5d50; }

  /* ── Grid ──────────────────────────────────────────────── */
  table.wgrid {
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
  }
  table.wgrid td {
    vertical-align:top;
    padding:0 4px 8px 0;
    background-color:#f3e9e9;
    width:50%;
  }
  table.wgrid td:last-child { padding-right:0; }

  /* ── Day box ────────────────────────────────────────────── */
  .day-box {
    border:2px solid #1a1a1a;
    padding:8px 8px 6px;
    background-color:#f3e9e9;
    min-height:110px;
  }
  .day-header {
    display:block;
    border-bottom:1.5px solid #1a1a1a;
    margin-bottom:6px;
    padding-bottom:4px;
  }
  .day-name { font-size:8.5pt; font-weight:bold; color:#2f5d50; text-transform:uppercase; letter-spacing:.06em; }
  .day-date { font-size:6.5pt; color:#555; font-weight:bold; float:right; }

  /* ── Meal rows ──────────────────────────────────────────── */
  .meal-label { font-size:6.5pt; font-weight:bold; color:#2d5a43; margin-bottom:1px; }
  .meal-line  {
    border-bottom:1.5px dashed #2d5a43;
    min-height:14px;
    padding-bottom:1px;
    margin-bottom:5px;
    font-size:6.5pt;
    color:#2d5a43;
    line-height:1.35;
  }

  /* ── Rating circles ─────────────────────────────────────── */
  table.rate-row { width:100%; border-collapse:collapse; margin-top:6px; border-top:1px solid #aaa; padding-top:4px; }
  td.rate-label  { font-size:6pt; font-weight:bold; color:#2f5d50; vertical-align:middle; background-color:#f3e9e9; }
  td.rate-circles{ text-align:right; vertical-align:middle; background-color:#f3e9e9; }
  .circle-filled { display:inline-block; width:10px; height:10px; border:1.5px solid #1a1a1a; border-radius:50%; background:#2f5d50; margin-left:2px; }
  .circle-empty  { display:inline-block; width:10px; height:10px; border:1.5px solid #1a1a1a; border-radius:50%; background:transparent; margin-left:2px; }

  /* ── Notes box ──────────────────────────────────────────── */
  .notes-box {
    border-top:2px solid #2d5a43;
    padding-top:8px;
    min-height:110px;
  }
  .notes-title { font-size:9pt; font-weight:bold; color:#2f5d50; text-transform:uppercase; letter-spacing:.08em; margin-bottom:6px; }
  .notes-line  { border-bottom:1.5px dashed #333; margin:9px 0; }

  /* ── Footer ─────────────────────────────────────────────── */
  .pdf-footer {
    margin-top:8px;
    font-size:6.5pt;
    color:#5a8a70;
    text-align:right;
    border-top:1px solid #b8ccc4;
    padding-top:4px;
  }
</style>
</head>
<body>

@if(!empty($letterhead))
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;border-bottom:1.5px solid #b8ccc4;padding-bottom:10px">
  <tr>
    <td style="text-align:center;background-color:#f3e9e9">
      <img src="{{ $letterhead }}" style="width:100%;height:auto;display:block">
    </td>
  </tr>
</table>
@endif

@php
    $weekEnd = $weekStart->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

    // Build pairs for 2-column table layout
    $pairs = [];
    $days  = $weekDays;
    for ($i = 0; $i < count($days); $i += 2) {
        $pairs[] = [ $days[$i], $days[$i+1] ?? null ];
    }
    // Last pair: day 6 (index 6) + notes. Days 0-5 are rows 0-2, day 6 + notes = row 3.
    // We actually have 7 days so pairs will be [0,1],[2,3],[4,5],[6,null].
    // The notes replaces the null slot.
@endphp

{{-- ── Header ──────────────────────────────────────────── --}}
<table class="hdr" cellpadding="0" cellspacing="0">
  <tr>
    <td class="hdr-title">
      <span class="title-sub">Weekly food</span>
      <span class="title-main">DIARY</span>
    </td>
    <td class="hdr-right">
      <div class="hdr-patient">{{ $patient->name }} {{ $patient->surname }}</div>
      <div class="hdr-week">{{ $weekStart->format('d M Y') }} &ndash; {{ $weekEnd->format('d M Y') }}</div>
      <div class="hdr-week" style="margin-top:2px">Generated {{ now()->format('d M Y') }}</div>
    </td>
  </tr>
</table>

{{-- ── Day grid ─────────────────────────────────────────── --}}
<table class="wgrid" cellpadding="0" cellspacing="0">
  @foreach($pairs as $pairIndex => $pair)
  <tr>
    @foreach([0, 1] as $col)
    @php
      $isLastPair = $pairIndex === count($pairs) - 1;
      $isSecondCol = $col === 1;
      $renderNotes = $isLastPair && $isSecondCol;
      $dayItem = $pair[$col] ?? null;
    @endphp
    <td>
      @if($renderNotes)
        {{-- Notes box in the last slot --}}
        @php
            $notesText = collect($weekDays)
                ->filter(fn($d) => $d['diary']?->improvement)
                ->map(fn($d) => $d['date']->format('D') . ': ' . $d['diary']->improvement)
                ->implode("\n");
        @endphp
        <div class="notes-box">
          <div class="notes-title">Notes:</div>
          @if($notesText)
            <div style="font-size:6.5pt;line-height:1.5;color:#1a3d2b;white-space:pre-wrap">{{ $notesText }}</div>
          @else
            <div class="notes-line"></div>
            <div class="notes-line"></div>
            <div class="notes-line"></div>
            <div class="notes-line"></div>
          @endif
        </div>
      @elseif($dayItem)
        @php
          $diary  = $dayItem['diary'];
          $date   = $dayItem['date'];
          $snacks = collect(['snack1','snack2','snack3'])
                      ->map(fn($s) => $diary?->{$s})
                      ->filter()
                      ->implode(' · ');
          $rating = $diary?->rating ?? 0;
        @endphp
        <div class="day-box">
          <div class="day-header">
            <span class="day-date">{{ $date->format('d M') }}</span>
            <span class="day-name">{{ $date->format('l') }}</span>
          </div>

          <div class="meal-label">Breakfast</div>
          <div class="meal-line">{{ $diary?->breakfast ?: '' }}</div>

          <div class="meal-label">Lunch</div>
          <div class="meal-line">{{ $diary?->lunch ?: '' }}</div>

          <div class="meal-label">Dinner</div>
          <div class="meal-line">{{ $diary?->supper ?: '' }}</div>

          <div class="meal-label">Snacks</div>
          <div class="meal-line">{{ $snacks ?: '' }}</div>

          <table class="rate-row" cellpadding="0" cellspacing="0" style="margin-top:4px;border-top:1px solid #aaa">
            <tr>
              <td class="rate-label">Rate your day</td>
              <td class="rate-circles">
                @for($i = 1; $i <= 5; $i++)
                  @if($rating && $i <= $rating)
                    <span class="circle-filled"></span>
                  @else
                    <span class="circle-empty"></span>
                  @endif
                @endfor
              </td>
            </tr>
          </table>
        </div>
      @else
        {{-- Empty slot (shouldn't happen for 7 days) --}}
        <div class="day-box" style="opacity:.4"></div>
      @endif
    </td>
    @endforeach
  </tr>
  @endforeach
</table>

<div class="pdf-footer">
  {{ config('app.name') }} &middot; Confidential &middot; For clinical use only
</div>

</body>
</html>
