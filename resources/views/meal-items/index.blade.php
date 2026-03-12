<x-app-layout>
@php
    $catColors = [
        'Fruit & Vegetables'         => ['bg'=>'#dcfce7','text'=>'#15803d','dot'=>'#22c55e'],
        'Starchy Foods'              => ['bg'=>'#fef9c3','text'=>'#854d0e','dot'=>'#eab308'],
        'Protein'                    => ['bg'=>'#e0e7ff','text'=>'#3730a3','dot'=>'#6366f1'],
        'Milk & Dairy'               => ['bg'=>'#e0f2fe','text'=>'#0369a1','dot'=>'#0ea5e9'],
        'Spreading Fat, Oil & Sauce' => ['bg'=>'#ffedd5','text'=>'#c2410c','dot'=>'#f97316'],
    ];
@endphp

<style>
.mi-page { max-width:1280px; margin:0 auto; padding:1.75rem 1.25rem 3rem; }

/* ── Header ── */
.mi-header {
    display:flex; align-items:flex-start; justify-content:space-between;
    flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;
}
.mi-title    { font-size:1.35rem; font-weight:800; color:var(--text-primary); margin:0 0 .2rem; }
.mi-subtitle { font-size:.82rem; color:var(--text-muted); margin:0; }

.mi-add-btn {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.5rem 1.2rem; background:var(--primary); color:#fff;
    font-weight:700; font-size:.83rem; border-radius:8px;
    text-decoration:none; transition:filter .15s; white-space:nowrap;
}
.mi-add-btn:hover { filter:brightness(.92); }

/* ── Flash ── */
.mi-flash {
    padding:.65rem 1rem; background:#dcfce7; color:#15803d;
    border-radius:8px; font-size:.82rem; font-weight:600;
    margin-bottom:1.1rem; border:1px solid #86efac;
}

/* ── Filters ── */
.mi-filters { display:flex; gap:.6rem; flex-wrap:wrap; align-items:center; margin-bottom:1rem; }
.mi-filters input[type=text],
.mi-filters select {
    padding:.44rem .75rem; border:1.5px solid var(--border); border-radius:8px;
    font-size:.83rem; background:#fff; outline:none; transition:border-color .15s;
}
.mi-filters input[type=text] { flex:1; min-width:180px; }
.mi-filters input[type=text]:focus,
.mi-filters select:focus { border-color:var(--primary); }
.mi-filter-btn {
    padding:.44rem 1.1rem; background:var(--primary); color:#fff;
    font-weight:700; font-size:.83rem; border:none; border-radius:8px; cursor:pointer; transition:filter .15s;
}
.mi-filter-btn:hover { filter:brightness(.92); }
.mi-clear-link { font-size:.81rem; color:var(--text-muted); text-decoration:none; padding:.44rem .4rem; }
.mi-clear-link:hover { color:var(--primary); }

/* ── Category pills ── */
.mi-cat-pills { display:flex; flex-wrap:wrap; gap:.4rem; margin-bottom:1rem; }
.mi-cat-pill {
    display:inline-flex; align-items:center; gap:.3rem;
    font-size:.71rem; font-weight:700; padding:.22rem .65rem;
    border-radius:999px; cursor:pointer; text-decoration:none; transition:filter .15s;
}
.mi-cat-pill:hover { filter:brightness(.93); }
.mi-cat-pill-dot { width:.42rem; height:.42rem; border-radius:50%; flex-shrink:0; }

/* ── Card ── */
.mi-card {
    background:#fff; border:1px solid var(--border); border-radius:12px;
    overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.05);
}

/* ── Table ── */
.mi-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.mi-table thead th {
    padding:.58rem .85rem; font-size:.68rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.04em; color:var(--text-muted); background:#fff;
    white-space:nowrap; position:sticky; top:0; z-index:2;
    border-bottom:2px solid var(--border);
}
.mi-table tbody td {
    padding:.52rem .85rem; border-bottom:1px solid #f0f0f0;
    color:var(--text-primary); vertical-align:middle;
}
.mi-table tbody tr:last-child td { border-bottom:none; }
.mi-table tbody tr:hover td { background:#fef9f5; }
.mi-table .col-num           { text-align:right; font-size:.79rem; }
.mi-table tbody td.col-num   { color:var(--text-muted); }
.mi-table .col-center { text-align:center; }

/* ── Badges ── */
.cat-badge {
    display:inline-flex; align-items:center; gap:.28rem;
    font-size:.68rem; font-weight:700; padding:.18rem .52rem;
    border-radius:999px; white-space:nowrap;
}
.cat-badge-dot { width:.38rem; height:.38rem; border-radius:50%; flex-shrink:0; }
.badge-system { font-size:.67rem; font-weight:700; padding:.14rem .44rem; border-radius:999px; background:#e0f2fe; color:#0369a1; }
.badge-custom { font-size:.67rem; font-weight:700; padding:.14rem .44rem; border-radius:999px; background:#fef9c3; color:#854d0e; }
.badge-fv     { font-size:.67rem; font-weight:700; padding:.14rem .44rem; border-radius:999px; background:#dcfce7; color:#15803d; }

/* ── Row actions ── */
.mi-action-edit {
    font-size:.73rem; color:var(--primary); font-weight:600; text-decoration:none;
    margin-right:.3rem; padding:.18rem .42rem; border-radius:5px; transition:background .15s;
}
.mi-action-edit:hover { background:#fff7ed; }
.mi-action-del {
    font-size:.73rem; color:#b91c1c; font-weight:600; background:none; border:none;
    cursor:pointer; padding:.18rem .42rem; border-radius:5px; transition:background .15s;
}
.mi-action-del:hover { background:#fee2e2; }

/* ── Empty state ── */
.mi-empty { text-align:center; padding:3rem 1rem; color:var(--text-muted); font-size:.88rem; }
.mi-empty-icon { font-size:2.2rem; margin-bottom:.5rem; }

/* ── Pagination ── */
.mi-pagination { padding:.9rem 1.25rem; border-top:1px solid var(--border); }
</style>

<div class="mi-page">

    {{-- Header --}}
    <div class="mi-header">
        <div>
            <h1 class="mi-title">Meal Item Library</h1>
            <p class="mi-subtitle">
                {{ $total }} item{{ $total === 1 ? '' : 's' }}
                &mdash; system standard + your custom items
            </p>
        </div>
        <a href="{{ route('meal-items.create') }}" class="mi-add-btn">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Custom Item
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="mi-flash">✓ {{ session('success') }}</div>
    @endif

    {{-- Category quick-filter pills --}}
    <div class="mi-cat-pills">
        <a href="{{ route('meal-items.index', array_filter(['search'=>$search])) }}"
           class="mi-cat-pill"
           style="background:{{ $category ? '#f1f5f9' : 'var(--primary)' }};color:{{ $category ? '#475569' : '#fff' }}">
            All
        </a>
        @foreach($categories as $cat)
            @php $col = $catColors[$cat] ?? ['bg'=>'#f1f5f9','text'=>'#475569','dot'=>'#94a3b8']; @endphp
            <a href="{{ route('meal-items.index', array_filter(['category'=>$cat, 'search'=>$search])) }}"
               class="mi-cat-pill"
               style="background:{{ $col['bg'] }};color:{{ $col['text'] }};{{ $category===$cat ? 'outline:2px solid '.$col['dot'].';outline-offset:1px' : '' }}">
                <span class="mi-cat-pill-dot" style="background:{{ $col['dot'] }}"></span>
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Search filter --}}
    <form method="GET" action="{{ route('meal-items.index') }}" class="mi-filters">
        <input type="hidden" name="category" value="{{ $category }}">
        <input type="text"   name="search"   value="{{ $search }}" placeholder="Search by name…">
        <button type="submit" class="mi-filter-btn">Search</button>
        @if($search || $category)
            <a href="{{ route('meal-items.index') }}" class="mi-clear-link">✕ Clear filters</a>
        @endif
    </form>

    {{-- Table card --}}
    <div class="mi-card">
        <div style="overflow:auto;max-height:70vh;-webkit-overflow-scrolling:touch;position:relative">
            <table class="mi-table">
                <thead>
                    <tr>
                        <th style="min-width:170px">Name</th>
                        <th style="min-width:120px">Serving Size</th>
                        <th class="col-num" style="color:#b45309">kJ</th>
                        <th class="col-num" style="color:#0f766e">Fat (g)</th>
                        <th class="col-num" style="color:#c2410c">Carbs (g)</th>
                        <th class="col-num" style="color:#4338ca">Prot (g)</th>
                        <th class="col-num" style="color:#15803d">Fiber (g)</th>
                        <th style="width:90px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grouped as $catName => $catItems)
                        @php $col = $catColors[$catName] ?? ['bg'=>'#f1f5f9','text'=>'#475569','dot'=>'#94a3b8']; @endphp
                        {{-- Category divider row --}}
                        <tr>
                            <td colspan="8" style="padding:.45rem .85rem;background:{{ $col['bg'] }};border-bottom:1px solid {{ $col['dot'] }}20;border-top:2px solid {{ $col['dot'] }}40">
                                <span style="display:inline-flex;align-items:center;gap:.4rem;font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:{{ $col['text'] }}">
                                    <span style="width:.5rem;height:.5rem;border-radius:50%;background:{{ $col['dot'] }};flex-shrink:0;display:inline-block"></span>
                                    {{ $catName }}
                                    <span style="font-weight:500;text-transform:none;letter-spacing:0;opacity:.7">({{ $catItems->count() }})</span>
                                </span>
                            </td>
                        </tr>
                        {{-- Items in this category --}}
                        @foreach($catItems as $item)
                            <tr>
                                <td style="font-weight:700;padding-left:1.4rem;color:var(--text-primary)">{{ $item->name }}</td>
                                <td style="color:var(--text-muted);font-size:.79rem">{{ $item->serving_size ?? '—' }}</td>
                                <td class="col-num" style="color:#b45309;font-weight:600">
                                    {{ $item->energy_kj ? round($item->energy_kj) : ($item->energy_kcal ? round($item->energy_kcal * 4.184) : '—') }}
                                </td>
                                <td class="col-num" style="color:#0f766e;font-weight:600">{{ $item->fat_g ?? '—' }}</td>
                                <td class="col-num" style="color:#c2410c;font-weight:600">{{ $item->cho_g ?? '—' }}</td>
                                <td class="col-num" style="color:#4338ca;font-weight:600">{{ $item->protein_g ?? '—' }}</td>
                                <td class="col-num" style="color:#15803d;font-weight:600">{{ $item->fiber_g ?? '—' }}</td>
                                <td style="white-space:nowrap">
                                    <a href="{{ route('meal-items.edit', $item) }}" class="mi-action-edit">Edit</a>
                                    <form method="POST" action="{{ route('meal-items.destroy', $item) }}"
                                          style="display:inline"
                                          onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="mi-action-del">Del</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="mi-empty">
                                    <div class="mi-empty-icon">🍽️</div>
                                    No items found{{ $search ? ' for "'.e($search).'"' : '' }}{{ $category ? ' in '.$category : '' }}.
                                    @if($search || $category)
                                        <br><a href="{{ route('meal-items.index') }}" style="color:var(--primary);font-weight:600;text-decoration:none">Clear filters</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</x-app-layout>
