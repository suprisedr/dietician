<x-app-layout>
@php
    $catColors = [
        'Fruit & Vegetables'         => ['bg'=>'#dcfce7','text'=>'#15803d','dot'=>'#22c55e'],
        'Starchy Foods'              => ['bg'=>'#fef9c3','text'=>'#854d0e','dot'=>'#eab308'],
        'Protein'                    => ['bg'=>'#e0e7ff','text'=>'#3730a3','dot'=>'#6366f1'],
        'Milk & Dairy'               => ['bg'=>'#e0f2fe','text'=>'#0369a1','dot'=>'#0ea5e9'],
        'Spreading Fat, Oil & Sauce' => ['bg'=>'#ffedd5','text'=>'#c2410c','dot'=>'#f97316'],
    ];
    $catList = \App\Models\MealItem::categories();
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

/* ── Live search box ── */
.mi-search-wrap { position:relative; margin-bottom:1.1rem; }
.mi-search-input {
    width:100%; padding:.55rem 2.6rem .55rem .85rem;
    border:1.5px solid var(--border); border-radius:8px;
    font-size:.9rem; outline:none; transition:border-color .15s;
    box-sizing:border-box; background:#fff;
}
.mi-search-input:focus { border-color:var(--primary); }
.mi-search-clear {
    position:absolute; right:.6rem; top:50%; transform:translateY(-50%);
    font-size:1rem; color:#94a3b8; cursor:pointer; display:none;
    background:none; border:none; line-height:1; padding:.15rem;
}

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

/* ── Search results panel ── */
#mi-search-panel { display:none; }
#mi-search-panel.active { display:block; }
.mis-section-title {
    font-size:.68rem; font-weight:800; text-transform:uppercase;
    letter-spacing:.07em; color:var(--text-muted);
    padding:.45rem .85rem; background:#f8fafc;
    border-bottom:1px solid var(--border);
}
.mis-row {
    display:grid;
    grid-template-columns: 1fr 80px repeat(4, 54px) auto;
    align-items:center; gap:.3rem .6rem;
    padding:.52rem .85rem; border-bottom:1px solid #f0f0f0;
    transition:background .12s;
}
.mis-row:last-child { border-bottom:none; }
.mis-row:hover { background:#fef9f5; }
.mis-name    { font-weight:700; font-size:.83rem; color:var(--text-primary); }
.mis-serving { font-size:.74rem; color:var(--text-muted); }
.mis-num     { font-size:.79rem; font-weight:600; text-align:right; }
.mis-badge   {
    font-size:.62rem; font-weight:700; padding:.12rem .42rem;
    border-radius:999px; white-space:nowrap; display:inline-block;
}
.mis-badge-db  { background:#dcfce7; color:#15803d; }
.mis-badge-fs  { background:#e0f2fe; color:#0369a1; }
.mis-badge-sys { background:#f1f5f9; color:#475569; }
.mis-import-btn {
    font-size:.72rem; font-weight:700; padding:.26rem .7rem;
    background:var(--primary); color:#fff; border:none; border-radius:6px;
    cursor:pointer; white-space:nowrap; transition:filter .15s;
}
.mis-import-btn:hover    { filter:brightness(.9); }
.mis-import-btn:disabled { opacity:.55; cursor:default; filter:none; }
.mis-import-btn.saved    { background:#64748b; }

/* ── Category selector modal ── */
#mi-cat-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.45);
    z-index:9000; align-items:center; justify-content:center;
}
#mi-cat-overlay.open { display:flex; }
#mi-cat-box {
    background:#fff; border-radius:14px; padding:1.5rem 1.75rem;
    min-width:280px; max-width:360px; box-shadow:0 8px 40px rgba(0,0,0,.18);
}
#mi-cat-box h3   { margin:0 0 .2rem; font-size:1rem; font-weight:800; color:var(--text-primary); }
#mi-cat-box p    { margin:0 0 .8rem; font-size:.79rem; color:var(--text-muted); }
#mi-cat-food-name{ margin:0 0 .9rem; font-size:.87rem; font-weight:700; color:var(--text-primary); }
#mi-cat-select {
    width:100%; padding:.5rem .75rem; border:1.5px solid var(--border);
    border-radius:8px; font-size:.88rem; margin-bottom:1rem; outline:none;
}
#mi-cat-select:focus { border-color:var(--primary); }
.mi-cat-btns { display:flex; gap:.6rem; justify-content:flex-end; }
.mi-cat-cancel {
    padding:.42rem 1rem; border:1.5px solid var(--border); background:#fff;
    border-radius:7px; font-size:.83rem; font-weight:600; cursor:pointer;
}
.mi-cat-confirm {
    padding:.42rem 1rem; background:var(--primary); color:#fff;
    border:none; border-radius:7px; font-size:.83rem; font-weight:700;
    cursor:pointer; transition:filter .15s;
}
.mi-cat-confirm:hover { filter:brightness(.9); }

/* ── Spinner ── */
.mis-spinner {
    padding:1.6rem; text-align:center; font-size:.82rem; color:var(--text-muted);
}
.mis-spinner::before {
    content:''; display:inline-block; width:.8rem; height:.8rem;
    border:2px solid var(--border); border-top-color:var(--primary);
    border-radius:50%; animation:mis-spin .6s linear infinite;
    margin-right:.45rem; vertical-align:middle;
}
@keyframes mis-spin { to { transform:rotate(360deg); } }

/* ── Row checkbox ── */
.mi-row-cb { width:1rem; height:1rem; cursor:pointer; accent-color:var(--primary); flex-shrink:0; }
.mi-table tbody tr.cb-selected td { background:#f0fdf4; }

/* ── Bulk action bar ── */
#mi-bulk-bar {
    display:none; position:sticky; bottom:1.2rem; z-index:100;
    margin:0 0 .6rem;
    background:var(--primary); color:#fff;
    border-radius:10px; padding:.65rem 1rem;
    align-items:center; gap:.75rem; flex-wrap:wrap;
    box-shadow:0 4px 18px rgba(103,159,95,.35);
}
#mi-bulk-bar.visible { display:flex; }
#mi-bulk-count { font-weight:700; font-size:.85rem; flex:1; white-space:nowrap; }
.mi-bulk-btn {
    padding:.38rem .95rem; border-radius:7px; font-size:.8rem; font-weight:700;
    cursor:pointer; border:none; transition:filter .15s; white-space:nowrap;
}
.mi-bulk-btn-del   { background:#fee2e2; color:#b91c1c; }
.mi-bulk-btn-del:hover   { filter:brightness(.95); }
.mi-bulk-btn-cat   { background:#fff; color:var(--primary); }
.mi-bulk-btn-cat:hover   { filter:brightness(.95); }
.mi-bulk-btn-clear { background:rgba(255,255,255,.18); color:#fff; }
.mi-bulk-btn-clear:hover { filter:brightness(.85); }

/* ── Recategorise modal ── */
#mi-recat-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.45);
    z-index:9100; align-items:center; justify-content:center;
}
#mi-recat-overlay.open { display:flex; }
#mi-recat-box {
    background:#fff; border-radius:14px; padding:1.5rem 1.75rem;
    min-width:280px; max-width:360px; box-shadow:0 8px 40px rgba(0,0,0,.18);
}
#mi-recat-box h3 { margin:0 0 .2rem; font-size:1rem; font-weight:800; color:var(--text-primary); }
#mi-recat-box p  { margin:0 0 .9rem; font-size:.8rem; color:var(--text-muted); }
#mi-recat-select {
    width:100%; padding:.5rem .75rem; border:1.5px solid var(--border);
    border-radius:8px; font-size:.88rem; margin-bottom:1rem; outline:none;
}
#mi-recat-select:focus { border-color:var(--primary); }
.mi-recat-btns { display:flex; gap:.6rem; justify-content:flex-end; }
.mi-recat-cancel {
    padding:.42rem 1rem; border:1.5px solid var(--border); background:#fff;
    border-radius:7px; font-size:.83rem; font-weight:600; cursor:pointer;
}
.mi-recat-confirm {
    padding:.42rem 1rem; background:var(--primary); color:#fff;
    border:none; border-radius:7px; font-size:.83rem; font-weight:700;
    cursor:pointer; transition:filter .15s;
}
.mi-recat-confirm:hover { filter:brightness(.9); }
</style>

<div class="mi-page">

    {{-- Header --}}
    <div class="mi-header">
        <div>
            <h1 class="mi-title">Meal Item Library</h1>
            <p class="mi-subtitle">
                {{ $total }} item{{ $total === 1 ? '' : 's' }} in your library &mdash; search to find more foods online
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

    {{-- Live search box --}}
    <div class="mi-search-wrap">
        <input type="text" id="mi-search-input" class="mi-search-input"
               placeholder="Search items… (checks your library first, then searches online)"
               autocomplete="off" spellcheck="false">
        <button type="button" class="mi-search-clear" id="mi-search-clear" title="Clear">✕</button>
    </div>

    {{-- Category pills (hidden while searching) --}}
    <div class="mi-cat-pills" id="mi-cat-pills">
        <a href="{{ route('meal-items.index') }}" class="mi-cat-pill"
           style="background:{{ $category ? '#f1f5f9' : 'var(--primary)' }};color:{{ $category ? '#475569' : '#fff' }}">
            All
        </a>
        @foreach($categories as $cat)
            @php $col = $catColors[$cat] ?? ['bg'=>'#f1f5f9','text'=>'#475569','dot'=>'#94a3b8']; @endphp
            <a href="{{ route('meal-items.index', ['category'=>$cat]) }}" class="mi-cat-pill"
               style="background:{{ $col['bg'] }};color:{{ $col['text'] }};{{ $category===$cat ? 'outline:2px solid '.$col['dot'].';outline-offset:1px' : '' }}">
                <span class="mi-cat-pill-dot" style="background:{{ $col['dot'] }}"></span>
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Live search results panel (shown while typing) --}}
    <div id="mi-search-panel" class="mi-card"></div>

    {{-- Bulk action bar --}}
    <div id="mi-bulk-bar">
        <span id="mi-bulk-count"></span>
        <button type="button" class="mi-bulk-btn mi-bulk-btn-cat" id="mi-bulk-recat-btn">⇄ Change Category</button>
        <button type="button" class="mi-bulk-btn mi-bulk-btn-del" id="mi-bulk-del-btn">🗑 Delete Selected</button>
        <button type="button" class="mi-bulk-btn mi-bulk-btn-clear" id="mi-bulk-clear-btn">✕ Clear</button>
    </div>

    {{-- Static library table (hidden while searching) --}}
    <div id="mi-library-table">
    <div class="mi-card">
        <div style="overflow:auto;max-height:70vh;-webkit-overflow-scrolling:touch;position:relative">
            <table class="mi-table">
                <thead>
                    <tr>
                        <th style="width:2.2rem;padding-left:.85rem">
                            <input type="checkbox" class="mi-row-cb" id="cb-all" title="Select all">
                        </th>
                        <th style="min-width:170px">Name</th>
                        <th style="min-width:120px">Serving Size</th>
                        <th class="col-num" style="color:#b45309">kJ</th>
                        <th class="col-num" style="color:#0f766e">Fat (g)</th>
                        <th class="col-num" style="color:#c2410c">Carbs (g)</th>
                        <th class="col-num" style="color:#4338ca">Prot (g)</th>
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
                            <tr data-id="{{ $item->id }}">
                                <td style="padding-left:.85rem">
                                    <input type="checkbox" class="mi-row-cb mi-cb-item" value="{{ $item->id }}">
                                </td>
                                <td style="font-weight:700;color:var(--text-primary)">{{ $item->name }}</td>
                                <td style="color:var(--text-muted);font-size:.79rem">{{ $item->serving_size ?? '—' }}</td>
                                <td class="col-num" style="color:#b45309;font-weight:600">
                                    {{ $item->energy_kj ? round($item->energy_kj) : ($item->energy_kcal ? round($item->energy_kcal * 4.184) : '—') }}
                                </td>
                                <td class="col-num" style="color:#0f766e;font-weight:600">{{ $item->fat_g ?? '—' }}</td>
                                <td class="col-num" style="color:#c2410c;font-weight:600">{{ $item->cho_g ?? '—' }}</td>
                                <td class="col-num" style="color:#4338ca;font-weight:600">{{ $item->protein_g ?? '—' }}</td>
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
    </div>{{-- /mi-library-table --}}

</div>

{{-- ── Category picker modal (FatSecret import) ────────────────────── --}}
<div id="mi-cat-overlay">
    <div id="mi-cat-box">
        <h3>Save to Library</h3>
        <p>Choose a category for this item:</p>
        <div id="mi-cat-food-name"></div>
        <select id="mi-cat-select">
            @foreach($catList as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
        <div class="mi-cat-btns">
            <button type="button" class="mi-cat-cancel" id="mi-cat-cancel">Cancel</button>
            <button type="button" class="mi-cat-confirm" id="mi-cat-confirm">Save to Library</button>
        </div>
    </div>
</div>

{{-- ── Recategorise modal ───────────────────────────────── --}}
<div id="mi-recat-overlay">
    <div id="mi-recat-box">
        <h3>Change Category</h3>
        <p id="mi-recat-desc">Move selected items to:</p>
        <select id="mi-recat-select">
            @foreach($catList as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
        <div class="mi-recat-btns">
            <button type="button" class="mi-recat-cancel" id="mi-recat-cancel">Cancel</button>
            <button type="button" class="mi-recat-confirm" id="mi-recat-confirm">Move Items</button>
        </div>
    </div>
</div>

{{-- ── Hidden bulk form ───────────────────────────────────────── --}}
<form id="mi-bulk-form" method="POST" action="{{ route('meal-items.bulk') }}" style="display:none">
    @csrf
    <input type="hidden" name="action"   id="mi-bulk-action">
    <input type="hidden" name="category" id="mi-bulk-category">
    <div id="mi-bulk-ids"></div>
</form>
<script>
(function () {
    const searchUrl = '{{ route("meal-items.search") }}';
    const importUrl = '{{ route("meal-items.import-fatsecret") }}';
    const csrfToken = '{{ csrf_token() }}';

    const inputEl  = document.getElementById('mi-search-input');
    const clearBtn = document.getElementById('mi-search-clear');
    const panel    = document.getElementById('mi-search-panel');
    const table    = document.getElementById('mi-library-table');
    const pills    = document.getElementById('mi-cat-pills');

    let _debounce = null;
    let _abort    = null;
    let _pending  = null; /* food awaiting category selection */

    function esc(s) {
        return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ── Search mode helpers ── */
    function enterSearch() {
        panel.classList.add('active');
        table.style.display  = 'none';
        pills.style.display  = 'none';
        clearBtn.style.display = 'block';
    }
    function exitSearch() {
        panel.classList.remove('active');
        panel.innerHTML = '';
        table.style.display  = '';
        pills.style.display  = '';
        clearBtn.style.display = 'none';
    }

    /* ── Build a result row ── */
    function makeRow(food) {
        let badge, action;
        if (food.source === 'system') {
            badge  = '<span class="mis-badge mis-badge-sys">System</span>';
            action = '<a href="/meal-items/' + food.id + '/edit" class="mi-action-edit" style="margin:0">Edit</a>';
        } else if (food.source === 'custom') {
            badge  = '<span class="mis-badge mis-badge-db">My Item</span>';
            action = '<a href="/meal-items/' + food.id + '/edit" class="mi-action-edit" style="margin:0">Edit</a>';
        } else {
            badge  = '<span class="mis-badge mis-badge-fs">Online</span>';
            action = '<button class="mis-import-btn" id="ib-' + esc(food.id) + '" '
                   + 'onclick="startImport(this)">+ Save</button>';
        }
        return '<div class="mis-row" '
            + 'data-food="' + esc(JSON.stringify(food)) + '">'
            + '<div><div class="mis-name">' + esc(food.name) + '</div>'
            + '<div class="mis-serving">' + esc(food.serving ?? '') + '</div></div>'
            + '<div>' + badge + '</div>'
            + '<div class="mis-num" style="color:#b45309">' + (food.kj    ?? '—') + '</div>'
            + '<div class="mis-num" style="color:#0f766e">' + (food.fat   ?? '—') + '</div>'
            + '<div class="mis-num" style="color:#c2410c">' + (food.carbs ?? '—') + '</div>'
            + '<div class="mis-num" style="color:#4338ca">' + (food.protein ?? '—') + '</div>'
            + '<div>' + action + '</div>'
            + '</div>';
    }

    /* ── Render results ── */
    const MIS_HDR = '<div class="mis-row" style="background:#f8fafc;font-size:.67rem;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em">'
        + '<div>Item</div><div></div>'
        + '<div style="text-align:right;color:#b45309">kJ</div>'
        + '<div style="text-align:right;color:#0f766e">Fat</div>'
        + '<div style="text-align:right;color:#c2410c">Carbs</div>'
        + '<div style="text-align:right;color:#4338ca">Protein</div>'
        + '<div></div></div>';

    function renderResults(q, db, fs) {
        panel.innerHTML = '';
        if (!db.length && !fs.length) {
            panel.innerHTML = '<div class="mi-empty"><div class="mi-empty-icon">🔍</div>'
                + 'No results for &ldquo;<strong>' + esc(q) + '</strong>&rdquo;<br>'
                + '<a href="{{ route("meal-items.create") }}" style="color:var(--primary);font-weight:600;text-decoration:none">+ Add manually</a></div>';
            return;
        }
        if (db.length) {
            panel.insertAdjacentHTML('beforeend',
                '<div class="mis-section-title">📋 Your Library (' + db.length + ')</div>');
            panel.insertAdjacentHTML('beforeend', MIS_HDR);
            db.forEach(function(f) { panel.insertAdjacentHTML('beforeend', makeRow(f)); });
        }
        if (fs.length) {
            panel.insertAdjacentHTML('beforeend',
                '<div class="mis-section-title">🔍 Online Results (' + fs.length + ') — click Save to add to your library</div>');
            panel.insertAdjacentHTML('beforeend', MIS_HDR);
            fs.forEach(function(f) { panel.insertAdjacentHTML('beforeend', makeRow(f)); });
        }
    }

    /* ── Run the search ── */
    function doSearch(q) {
        if (q.length < 2) { exitSearch(); return; }
        enterSearch();
        panel.innerHTML = '<div class="mis-spinner">Searching…</div>';
        if (_abort) _abort.abort();
        _abort = new AbortController();
        fetch(searchUrl + '?q=' + encodeURIComponent(q), {
            signal: _abort.signal,
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(function(d)  { renderResults(q, d.db || [], d.fs || []); })
        .catch(function(e) {
            if (e.name === 'AbortError') return;
            panel.innerHTML = '<div class="mi-empty" style="color:#b91c1c">Search failed. Please try again.</div>';
        });
    }

    /* ── Input + clear ── */
    inputEl.addEventListener('input', function() {
        const v = this.value;
        clearTimeout(_debounce);
        if (!v) { exitSearch(); return; }
        _debounce = setTimeout(function() { doSearch(v.trim()); }, 320);
    });
    clearBtn.addEventListener('click', function() {
        inputEl.value = ''; exitSearch(); inputEl.focus();
    });

    /* ── FatSecret import flow ── */
    window.startImport = function(btn) {
        const row  = btn.closest('.mis-row');
        _pending   = JSON.parse(row.dataset.food);
        document.getElementById('mi-cat-food-name').textContent = _pending.name;
        document.getElementById('mi-cat-overlay').classList.add('open');
    };

    function closeCatModal() {
        _pending = null;
        document.getElementById('mi-cat-overlay').classList.remove('open');
    }

    document.getElementById('mi-cat-cancel').addEventListener('click', closeCatModal);
    document.getElementById('mi-cat-overlay').addEventListener('click', function(e) {
        if (e.target === this) closeCatModal();
    });

    /* ──────────────────────────────────────────────────────
     * Bulk selection
     * ───────────────────────────────────────────────────── */
    const bulkBar    = document.getElementById('mi-bulk-bar');
    const bulkCount  = document.getElementById('mi-bulk-count');
    const cbAll      = document.getElementById('cb-all');
    const bulkForm   = document.getElementById('mi-bulk-form');
    const bulkAction = document.getElementById('mi-bulk-action');
    const bulkCatEl  = document.getElementById('mi-bulk-category');
    const bulkIdsEl  = document.getElementById('mi-bulk-ids');

    function getChecked() {
        return Array.from(document.querySelectorAll('.mi-cb-item:checked'));
    }

    function updateBulkBar() {
        const checked = getChecked();
        const n = checked.length;
        if (n > 0) {
            bulkBar.classList.add('visible');
            bulkCount.textContent = n + ' item' + (n === 1 ? '' : 's') + ' selected';
        } else {
            bulkBar.classList.remove('visible');
        }
        /* Update select-all state */
        const all = document.querySelectorAll('.mi-cb-item');
        cbAll.indeterminate = n > 0 && n < all.length;
        cbAll.checked = n > 0 && n === all.length;
        /* Highlight rows */
        document.querySelectorAll('.mi-cb-item').forEach(function(cb) {
            cb.closest('tr').classList.toggle('cb-selected', cb.checked);
        });
    }

    /* Select-all toggle */
    if (cbAll) {
        cbAll.addEventListener('change', function() {
            document.querySelectorAll('.mi-cb-item').forEach(function(cb) {
                cb.checked = cbAll.checked;
            });
            updateBulkBar();
        });
    }

    /* Individual row checkboxes (delegated) */
    document.getElementById('mi-library-table').addEventListener('change', function(e) {
        if (e.target.classList.contains('mi-cb-item')) updateBulkBar();
    });

    /* Clear selection */
    document.getElementById('mi-bulk-clear-btn').addEventListener('click', function() {
        document.querySelectorAll('.mi-cb-item').forEach(function(cb) { cb.checked = false; });
        if (cbAll) cbAll.checked = false;
        updateBulkBar();
    });

    /* Submit bulk form helper */
    function submitBulk(action, category) {
        const ids = getChecked().map(function(cb) { return cb.value; });
        if (!ids.length) return;
        bulkAction.value   = action;
        bulkCatEl.value    = category || '';
        bulkIdsEl.innerHTML = ids.map(function(id) {
            return '<input type="hidden" name="ids[]" value="' + id + '">';
        }).join('');
        bulkForm.submit();
    }

    /* Delete selected */
    document.getElementById('mi-bulk-del-btn').addEventListener('click', function() {
        const n = getChecked().length;
        if (!n) return;
        if (!confirm('Delete ' + n + ' selected item' + (n === 1 ? '' : 's') + '? This cannot be undone.')) return;
        submitBulk('delete');
    });

    /* Recategorise — open modal */
    document.getElementById('mi-bulk-recat-btn').addEventListener('click', function() {
        const n = getChecked().length;
        if (!n) return;
        document.getElementById('mi-recat-desc').textContent =
            'Move ' + n + ' selected item' + (n === 1 ? '' : 's') + ' to:';
        document.getElementById('mi-recat-overlay').classList.add('open');
    });

    document.getElementById('mi-recat-cancel').addEventListener('click', function() {
        document.getElementById('mi-recat-overlay').classList.remove('open');
    });
    document.getElementById('mi-recat-overlay').addEventListener('click', function(e) {
        if (e.target === this) document.getElementById('mi-recat-overlay').classList.remove('open');
    });
    document.getElementById('mi-recat-confirm').addEventListener('click', function() {
        const cat = document.getElementById('mi-recat-select').value;
        document.getElementById('mi-recat-overlay').classList.remove('open');
        submitBulk('recategorise', cat);
    });

    /* ──────────────────────────────────────────────────────
     * FatSecret import flow
     * ───────────────────────────────────────────────────── */
    document.getElementById('mi-cat-confirm').addEventListener('click', function() {
        if (!_pending) return;
        const food     = _pending;
        const category = document.getElementById('mi-cat-select').value;
        const btn      = document.getElementById('ib-' + food.id);
        closeCatModal();
        if (btn) { btn.disabled = true; btn.textContent = 'Saving…'; }

        fetch(importUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: food.name, serving: food.serving,
                kcal: food.kcal, kj: food.kj,
                fat: food.fat, carbs: food.carbs,
                protein: food.protein, fiber: food.fiber,
                category: category
            })
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (btn) {
                btn.textContent = d.already_existed ? '✓ Already saved' : '✓ Saved!';
                btn.classList.add('saved');
                btn.disabled = true;
            }
        })
        .catch(function() {
            if (btn) { btn.disabled = false; btn.textContent = '+ Save'; }
            alert('Failed to save. Please try again.');
        });
    });

})();
</script>

</x-app-layout>
