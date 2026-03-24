<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Grocery List</title>
<style>
  body { margin:0; padding:0; background:#f4f4f0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; color:#1e293b; }
  .wrapper { max-width:560px; margin:32px auto; padding:0 16px 48px; }
  .card { background:#fffef9; border:1px solid #e5e3d8; border-radius:14px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.06); }
  .card-header { background:#16a34a; padding:24px 28px 20px; }
  .card-header h1 { margin:0 0 4px; font-size:1.25rem; font-weight:800; color:#fff; }
  .card-header p  { margin:0; font-size:.82rem; color:rgba(255,255,255,.8); }
  .section-label {
    display:block; padding:9px 20px 6px;
    font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em;
    color:#94a3b8; background:#f8f7f3; border-bottom:1px solid #ede9e0;
    border-top:1px solid #ede9e0;
  }
  .item-row { display:flex; align-items:center; gap:12px; padding:10px 20px; border-bottom:1px solid #f1ede4; }
  .item-row:last-child { border-bottom:none; }
  .item-row.checked { opacity:.5; }
  .cb { width:18px; height:18px; border-radius:4px; flex-shrink:0; border:2px solid #94a3b8; display:inline-block; }
  .cb.done { background:#16a34a; border-color:#16a34a; }
  .item-name { font-size:.9rem; font-weight:600; line-height:1.3; }
  .item-name.crossed { text-decoration:line-through; color:#94a3b8; font-weight:400; }
  .footer { margin-top:28px; text-align:center; font-size:.75rem; color:#94a3b8; line-height:1.6; }
  .progress-bar-wrap { height:4px; background:#d1fae5; }
  .progress-bar-fill { height:4px; background:#16a34a; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="card">
    <div class="card-header">
      <h1>🛒 {{ $groceryList->name ?: 'Grocery List #' . $groceryList->id }}</h1>
      @php
        $total   = $groceryList->items->count();
        $checked = $groceryList->items->where('checked', true)->count();
        $pct     = $total > 0 ? round($checked / $total * 100) : 0;
      @endphp
      <p>
        @if($groceryList->patient) For {{ $groceryList->patient->name }} &nbsp;·&nbsp; @endif
        {{ $total }} item{{ $total === 1 ? '' : 's' }}
        @if($checked > 0) · {{ $checked }} checked off @endif
      </p>
    </div>
    @if($total > 0)
    <div class="progress-bar-wrap">
      <div class="progress-bar-fill" style="width:{{ $pct }}%"></div>
    </div>
    @endif

    @php
      $categoryLabels = \App\Models\GroceryList::CATEGORY_LABELS;
    @endphp

    @forelse($byCategory as $cat => $items)
      <span class="section-label">{{ $categoryLabels[$cat] ?? ucfirst($cat) }}</span>
      @foreach($items as $item)
      <div class="item-row {{ $item->checked ? 'checked' : '' }}">
        <span class="cb {{ $item->checked ? 'done' : '' }}"></span>
        <span class="item-name {{ $item->checked ? 'crossed' : '' }}">{{ $item->item }}</span>
      </div>
      @endforeach
    @empty
      <div style="padding:32px 20px;text-align:center;color:#94a3b8;font-size:.875rem">No items on this list.</div>
    @endforelse
  </div>

  <div class="footer">
    Sent from {{ config('app.name') }}<br>
    {{ now()->format('d M Y') }}
  </div>
</div>
</body>
</html>
