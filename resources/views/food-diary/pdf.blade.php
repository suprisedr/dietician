<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
  @page {
    background-color: #f3e9e9;
  }
  html, body {
    background-color: #f3e9e9;
  }
  body {
    font-family: 'DejaVu Serif', serif;
    font-size: 9pt;
    color: #2d5a43;
    background-color: #f3e9e9;
    margin: 0;
    padding: 24px 28px;
  }

  /* ── Header ─────────────────────────────────── */
  table.hdr {
    width: 100%;
    border-collapse: collapse;
    border-bottom: 2px solid #2d5a43;
    margin-bottom: 18px;
    padding-bottom: 10px;
  }
  td.hdr-title { vertical-align: middle; background-color: #f3e9e9; }
  td.hdr-meta  { vertical-align: middle; text-align: right; width: 200px; background-color: #f3e9e9; }

  .title-sub { font-size: 22pt; font-style: italic; font-weight: normal; color: #2d5a43; display: block; line-height: 1; }
  .title-main { font-size: 26pt; font-weight: bold; color: #2d5a43; letter-spacing: 3px; }

  .meta-field { font-size: 8pt; font-weight: bold; color: #2d5a43; margin-bottom: 4px; }
  .meta-val   { border-bottom: 1px solid #2d5a43; padding-bottom: 1px; }

  /* ── Meal grid ───────────────────────────────── */
  table.meal-grid {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
  }
  table.meal-grid td {
    width: 50%;
    vertical-align: top;
    padding: 4px 5px;
    background-color: #f3e9e9;
  }
  .meal-box {
    border: 1px solid #2d5a43;
  }
  .meal-label {
    background-color: #2d5a43;
    color: #fff;
    padding: 4px 10px;
    font-size: 7.5pt;
    font-weight: bold;
    letter-spacing: 1px;
  }
  .meal-content {
    min-height: 58px;
    padding: 7px 9px;
    font-size: 8pt;
    color: #1a3d2b;
    line-height: 1.5;
    background-color: #faf0f0;
  }

  /* ── Reflection ─────────────────────────────── */
  .reflection-label { font-weight: bold; font-size: 8.5pt; color: #2d5a43; margin-bottom: 5px; }
  .stars { font-size: 16pt; color: #2d5a43; letter-spacing: 5px; }
  .star-off { color: #d1d5db; }
  .improvement-box {
    width: 100%;
    min-height: 70px;
    border: 1.5px solid #2d5a43;
    padding: 7px 9px;
    font-size: 8pt;
    color: #1a3d2b;
    line-height: 1.5;
    background-color: #faf0f0;
  }

  /* ── Footer ─────────────────────────────────── */
  .pdf-footer {
    margin-top: 10px;
    font-size: 7pt;
    color: #5a8a70;
    text-align: right;
    border-top: 1px solid #c8ddd6;
    padding-top: 4px;
  }
</style>
</head>
<body>

{{-- ── Header ────────────────────────────────── --}}
<table class="hdr" cellpadding="0" cellspacing="0">
  <tr>
    <td class="hdr-title">
      <span class="title-sub">Daily food</span>
      <span class="title-main">DIARY</span>
    </td>
    <td class="hdr-meta">
      <div class="meta-field">Date: <span class="meta-val">{{ $foodDiary->diary_date->format('d M Y') }}</span></div>
      <div class="meta-field">Day: <span class="meta-val">{{ $foodDiary->diary_date->format('l') }}</span></div>
      @if($foodDiary->patient)
        <div class="meta-field" style="margin-top:5px">{{ $foodDiary->patient->name }}</div>
      @endif
    </td>
  </tr>
</table>

{{-- ── Meal grid ───────────────────────────────── --}}
@php
  $slots = [
    ['breakfast', 'Breakfast'],
    ['snack1',    'Snack (Morning)'],
    ['lunch',     'Lunch'],
    ['snack2',    'Snack (Afternoon)'],
    ['supper',    'Supper'],
    ['snack3',    'Snack (Evening)'],
  ];
  $rows = array_chunk($slots, 2);
@endphp

<table class="meal-grid" cellpadding="0" cellspacing="0">
  @foreach($rows as $row)
  <tr>
    @foreach($row as [$slot, $label])
    <td>
      <div class="meal-box">
        <div class="meal-label">{{ strtoupper($label) }}</div>
        <div class="meal-content">{{ $foodDiary->{$slot} ?: '' }}</div>
      </div>
    </td>
    @endforeach
    @if(count($row) === 1)<td></td>@endif
  </tr>
  @endforeach
</table>

{{-- ── Reflection ────────────────────────────── --}}
<div style="margin-bottom:12px">
  <div class="reflection-label">Rate your day:</div>
  <div class="stars">
    @for($i = 1; $i <= 5; $i++)
      @if($foodDiary->rating && $i <= $foodDiary->rating)
        &#9733;
      @else
        <span class="star-off">&#9733;</span>
      @endif
    @endfor
  </div>
</div>

<div>
  <div class="reflection-label">What can I improve on?</div>
  <div class="improvement-box">{{ $foodDiary->improvement ?: '' }}</div>
</div>

<div class="pdf-footer">
  {{ config('app.name') }} &#183; Confidential &#183; For clinical use only
</div>

</body>
</html>
