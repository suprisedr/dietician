<x-app-layout>
@php
    $days       = \App\Models\MealPlannerWeek::DAYS;
    $slots      = \App\Models\MealPlannerWeek::MEAL_SLOTS;
    $slotLabels = \App\Models\MealPlannerWeek::SLOT_LABELS;

    $_bw = ['bg'=>'#ffffff','dot'=>'#374151','tag_bg'=>'#f9fafb','tag_border'=>'#d1d5db','tag_text'=>'#111827','header_bg'=>'#f3f4f6'];
    $slotTheme = [
        'breakfast' => $_bw,
        'snack1'    => $_bw,
        'lunch'     => $_bw,
        'snack2'    => $_bw,
        'dinner'    => $_bw,
        'snack3'    => $_bw,
    ];

    /* Build JS items array — include kcal/kj/serving for kcal display */
    $jsItems = [];
    foreach ($mealItemsByCategory as $cat => $items) {
        foreach ($items as $item) {
            $jsItems[] = [
                'value'   => (string)$item->id,
                'text'    => $item->name,
                'group'   => $cat,
                'kcal'    => $item->energy_kcal,
                'kj'      => $item->energy_kj,
                'serving' => $item->serving_size ?? null,
            ];
        }
    }

    /* Collapse slot distribution by exchange name -> qty */
    $slotDistributionJs = [];
    foreach ($slotDistribution as $slot => $entries) {
        $byName = [];
        foreach ($entries as $e) {
            $byName[$e['name']] = ($byName[$e['name']] ?? 0) + 1;
        }
        $arr = [];
        foreach ($byName as $name => $qty) {
            $arr[] = ['name' => $name, 'qty' => (int)$qty];
        }
        $slotDistributionJs[$slot] = $arr;
    }
@endphp

<style>
/* ── Page ─────────────────────────────────────────────────── */
.mp-page { max-width:1300px; margin:0 auto; padding:1.75rem 1.25rem 3rem; }

/* ── Buttons / top bar ────────────────────────────────────── */
.mp-topbar { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.25rem; }
.mp-title   { font-size:1.35rem; font-weight:800; color:var(--text-primary); margin:.3rem 0 .15rem; }
.mp-subtitle{ font-size:.8rem; color:var(--text-muted); display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
.mp-badge   { background:#ffedd5; color:#c2410c; font-size:.71rem; font-weight:700; padding:.18rem .6rem; border-radius:20px; }
.mp-actions { display:flex; gap:.5rem; flex-wrap:wrap; align-items:center; }
.mp-btn {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.42rem .9rem; border-radius:8px; font-size:.78rem; font-weight:700;
    text-decoration:none; border:none; cursor:pointer; transition:filter .15s;
}
.mp-btn:hover { filter:brightness(.93); }
.mp-btn-indigo { background:#e0e7ff; color:#3730a3; }
.mp-btn-orange { background:var(--primary); color:#fff; font-size:.82rem; padding:.5rem 1.3rem; }
.mp-btn-back   { background:transparent; color:var(--primary); font-size:.8rem; padding:.3rem 0; }

/* ── Grand daily kcal bar ─────────────────────────────────── */
.mp-grand-bar {
    display:grid; grid-template-columns:120px repeat(7,1fr);
    background:#fff; border:1px solid var(--border); border-radius:10px;
    overflow:hidden; margin-bottom:1.25rem;
}
.mp-grand-label {
    padding:.55rem .75rem; font-size:.73rem; font-weight:800;
    color:var(--text-primary); background:#f8fafc;
    border-right:2px solid var(--border); display:flex; align-items:center;
}
.mp-grand-day {
    padding:.45rem .35rem; text-align:center;
    font-size:.62rem; font-weight:700; color:var(--text-muted);
    border-right:1px solid #f0f0f0;
}
.mp-grand-day:last-child { border-right:none; }
.grand-kcal       { display:block; font-size:.82rem; font-weight:800; color:var(--text-primary); margin-top:.15rem; }
.grand-kcal-label { font-size:.6rem; font-weight:600; color:var(--text-muted); display:block; }

/* ── Combined table ───────────────────────────────────────── */
.mp-combined-card {
    background:#fff; border:1px solid var(--border); border-radius:12px;
    overflow:hidden; margin-bottom:1rem; box-shadow:0 1px 4px rgba(0,0,0,.05);
}
.combined-grid-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
.combined-day-grid {
    display:grid;
    grid-template-columns:120px repeat(7,1fr);
    min-width:780px;
}
.slot-kcal-badge { font-size:.63rem; font-weight:700; padding:.1rem .42rem; border-radius:20px; background:#f1f5f9; color:var(--text-muted); transition:background .2s,color .2s; display:inline-block; margin-top:.2rem; align-self:flex-start; }
.slot-kcal-badge.has-kcal { background:#fef9c3; color:#854d0e; }

/* Grid header row cells */
.sgh {
    padding:.48rem .35rem; font-size:.68rem; font-weight:700; text-align:center;
    background:var(--indigo); color:#fff;
    border-right:1px solid rgba(255,255,255,.12);
}
.sgh:first-child {
    text-align:left; padding-left:.75rem;
    border-right:2px solid rgba(255,255,255,.2);
}
.sgh .day-num { font-size:.58rem; font-weight:400; opacity:.72; display:block; margin-top:.06rem; }

/* Slot row label + day column */
.slot-row-label {
    display:flex; flex-direction:column; justify-content:flex-start; gap:.15rem;
    padding:.5rem .6rem .42rem;
    border-right:2px solid var(--border);
    border-bottom:1px solid rgba(0,0,0,.07);
    border-top:2px solid rgba(0,0,0,.09);
}
.slot-row-dot  { width:9px; height:9px; border-radius:50%; flex-shrink:0; }
.slot-row-name { font-size:.79rem; font-weight:800; color:var(--text-primary); }
.sday-col {
    border-right:1px solid #f0f0f0;
    border-bottom:1px solid rgba(0,0,0,.07);
    border-top:2px solid rgba(0,0,0,.09);
    padding:.2rem .25rem; display:flex; flex-direction:column; gap:.3rem;
    min-height:60px;
}
.sday-col:last-child { border-right:none; }

/* Category card inside day column */
.cat-card {
    border-radius:6px; border:1px solid transparent;
    overflow:hidden; cursor:pointer;
    transition:box-shadow .15s, border-color .15s;
}
.cat-card:hover { box-shadow:0 1px 6px rgba(0,0,0,.1); border-color:rgba(0,0,0,.12); }
.cat-card-hdr {
    display:flex; align-items:center; justify-content:space-between; gap:.3rem;
    padding:.2rem .4rem;
    font-size:.65rem; font-weight:800; line-height:1.3;
    text-decoration:underline; text-underline-offset:2px;
    border-bottom:1px solid rgba(0,0,0,.06);
}
.cat-card-name { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; }
.cat-card-badge {
    font-size:.59rem; font-weight:700; padding:.05rem .32rem;
    border-radius:20px; flex-shrink:0; white-space:nowrap;
    background:rgba(0,0,0,.08);
}
.cat-card-badge.done    { background:#dcfce7; color:#166534; }
.cat-card-badge.partial { background:#fef9c3; color:#854d0e; }
.cat-card-badge.full    { background:#fee2e2; color:#991b1b; }
.cat-card-body { padding:.2rem .3rem; }

/* Kcal row */
.skcal-label {
    padding:.28rem .65rem; font-size:.62rem; font-weight:700;
    color:var(--text-muted); text-align:right; letter-spacing:.02em;
    border-right:2px solid var(--border); background:#fafafa;
}
.skcal-day {
    padding:.28rem .35rem; text-align:center;
    font-size:.65rem; font-weight:700; color:var(--text-muted);
    border-right:1px solid #f0f0f0; background:#fafafa;
}
.skcal-day:last-child { border-right:none; }
.skcal-day.has-val { color:#854d0e; background:#fef9c3; }

/* ── Cell tags ────────────────────────────────────────────── */
.cell-tags {
    display:flex; flex-wrap:wrap; gap:3px;
    min-height:28px; align-items:flex-start; align-content:flex-start;
}
.cell-tag {
    display:inline-flex; flex-direction:column; align-items:flex-start;
    border-radius:5px; padding:.1rem .35rem;
    font-size:.63rem; font-weight:600; line-height:1.45;
    border:1px solid transparent; max-width:100%; box-sizing:border-box;
}
.cell-tag-row  { display:flex; align-items:center; gap:3px; width:100%; justify-content:space-between; }
.cell-tag-name { line-height:1.3; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:76px; }
.cell-tag-kcal { font-size:.58rem; opacity:.75; font-weight:700; }
.cell-tag-rm   {
    cursor:pointer; opacity:.55; font-size:.82rem; font-weight:700;
    line-height:1; border:none; background:none; padding:0; color:inherit;
    flex-shrink:0;
}
.cell-tag-rm:hover { opacity:1; }
.cell-add-btn {
    display:inline-flex; align-items:center; gap:3px;
    font-size:.63rem; color:var(--text-muted); cursor:pointer;
    background:none; border:none; padding:.1rem .2rem;
    border-radius:4px; opacity:.6; transition:opacity .15s;
}
.cell-add-btn:hover { opacity:1; color:var(--primary); }

/* ── Modal ────────────────────────────────────────────────── */
#mp-modal-overlay {
    display:none; position:fixed; inset:0; z-index:10000;
    background:rgba(0,0,0,.45); backdrop-filter:blur(2px);
    align-items:center; justify-content:center;
}
#mp-modal-overlay.open { display:flex; }
#mp-modal {
    background:#fff; border-radius:16px;
    width:min(540px,95vw); max-height:84vh;
    display:flex; flex-direction:column;
    box-shadow:0 24px 60px rgba(0,0,0,.22);
    overflow:hidden; animation:modalIn .18s ease;
}
@keyframes modalIn {
    from { transform:translateY(18px) scale(.97); opacity:0; }
    to   { transform:translateY(0) scale(1); opacity:1; }
}
#mp-modal-hdr {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 1.1rem .6rem; border-bottom:1px solid var(--border); flex-shrink:0;
}
#mp-modal-title  { font-size:.88rem; font-weight:800; color:var(--text-primary); }
#mp-modal-close  {
    background:none; border:none; cursor:pointer; font-size:1.25rem;
    color:var(--text-muted); padding:.1rem .3rem; border-radius:6px; transition:background .15s;
}
#mp-modal-close:hover { background:#f1f5f9; }
/* category quota badge row */
#mp-modal-quota {
    display:flex; align-items:center; gap:.5rem;
    padding:.45rem 1.1rem; border-bottom:1px solid var(--border);
    flex-shrink:0; background:#fafafa; font-size:.75rem;
}
#mp-modal-quota-badge {
    font-weight:700; padding:.18rem .7rem; border-radius:20px;
    border:1px solid #86efac; background:#dcfce7; color:#166534;
}
#mp-modal-quota-badge.partial { background:#fef9c3; color:#854d0e; border-color:#fde68a; }
#mp-modal-quota-badge.full    { background:#fee2e2; color:#991b1b; border-color:#fca5a5; }
#mp-modal-quota-label         { color:var(--text-muted); }
/* search */
#mp-modal-search-wrap {
    padding:.65rem 1.1rem .5rem; border-bottom:1px solid var(--border);
    flex-shrink:0; position:relative;
}
#mp-modal-search {
    width:100%; padding:.5rem .75rem;
    border:1.5px solid var(--border); border-radius:9px;
    font-size:.85rem; outline:none;
    transition:border-color .15s,box-shadow .15s; box-sizing:border-box;
}
#mp-modal-search:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(249,115,22,.12); }
#mp-modal-spinner { display:none; position:absolute; right:.75rem; top:50%; transform:translateY(-50%); font-size:.75rem; color:var(--text-muted); }
#mp-modal-body { flex:1; overflow-y:auto; padding:.5rem 0; }
.im-group-header {
    font-size:.67rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em;
    color:var(--primary); background:#fff7ed;
    padding:.3rem 1.1rem; border-top:1px solid #fde8d0;
    position:sticky; top:0; z-index:1;
}
.im-group:first-child .im-group-header { border-top:none; }
.im-option {
    display:flex; align-items:flex-start; gap:.65rem;
    padding:.42rem 1.1rem; cursor:pointer; font-size:.82rem;
    color:var(--text-primary); transition:background .1s; user-select:none;
}
.im-option:hover { background:#fef9f5; }
.im-option.selected { background:#fff7ed; }
.im-option input[type=checkbox] { width:15px; height:15px; flex-shrink:0; margin-top:2px; accent-color:var(--primary); cursor:pointer; }
.im-option-label { display:flex; flex-direction:column; gap:1px; }
.im-option-name  { font-weight:600; line-height:1.3; }
.im-option-desc  { font-size:.71rem; color:var(--text-muted); }
.im-qty-wrap { display:flex; align-items:center; gap:3px; margin-left:auto; flex-shrink:0; align-self:center; }
.im-qty-btn { width:22px; height:22px; border:1.5px solid var(--border); background:#fff; border-radius:4px; font-size:.85rem; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center; padding:0; }
.im-qty-btn:hover { background:#f1f5f9; }
.im-qty-inp { width:32px; height:22px; text-align:center; border:1.5px solid var(--border); border-radius:4px; font-size:.78rem; font-weight:600; padding:0; -moz-appearance:textfield; }
.im-qty-inp::-webkit-inner-spin-button,.im-qty-inp::-webkit-outer-spin-button { -webkit-appearance:none; margin:0; }
.im-no-results   { padding:1.5rem 1.1rem; text-align:center; font-size:.82rem; color:var(--text-muted); }
#mp-modal-ftr {
    display:flex; align-items:center; justify-content:space-between;
    padding:.7rem 1.1rem; border-top:1px solid var(--border);
    flex-shrink:0; gap:.6rem; background:#fafafa;
}
#mp-sel-count { font-size:.78rem; color:var(--text-muted); }
.im-ftr-btns { display:flex; gap:.5rem; }
#mp-btn-cancel  {
    padding:.42rem .9rem; border-radius:8px; font-size:.78rem; font-weight:700;
    border:1.5px solid var(--border); background:#fff; cursor:pointer; color:var(--text-primary);
}
#mp-btn-cancel:hover { background:#f1f5f9; }
#mp-btn-confirm {
    padding:.42rem 1rem; border-radius:8px; font-size:.78rem; font-weight:700;
    border:none; background:var(--primary); color:#fff; cursor:pointer;
}
#mp-btn-confirm:hover { filter:brightness(.92); }

/* ── Save bar ─────────────────────────────────────────────── */
.mp-save-bar {
    display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;
    margin-top:1rem; padding:.75rem 1rem;
    background:#fff; border:1px solid var(--border); border-radius:10px;
}
.mp-save-hint { font-size:.78rem; color:var(--text-muted); }

/* ── Monthly overview ─────────────────────────────────────── */
.mp-details-card  { margin-top:1.75rem; background:#fff; border:1px solid var(--border); border-radius:12px; overflow:hidden; }
.mp-details-summary {
    display:flex; align-items:center; gap:.6rem;
    padding:.85rem 1.1rem; cursor:pointer; font-size:.88rem; font-weight:700;
    color:var(--text-primary); user-select:none; list-style:none;
    background:#fafafa; border-bottom:1px solid transparent; transition:background .15s;
}
.mp-details-summary:hover { background:#f1f5f9; }
details[open] .mp-details-summary { border-bottom-color:var(--border); }
.mp-details-summary .chevron { margin-left:auto; font-size:.75rem; color:var(--text-muted); transition:transform .2s; }
details[open] .mp-details-summary .chevron { transform:rotate(180deg); }
.mp-monthly-table { width:100%; border-collapse:collapse; min-width:760px; font-size:.74rem; }
.mp-monthly-table th { padding:.45rem .65rem; background:var(--indigo); color:#fff; text-align:center; font-size:.71rem; font-weight:700; }
.mp-monthly-table th:first-child,.mp-monthly-table th:nth-child(2){text-align:left;}
.mp-monthly-table td { padding:.32rem .55rem; border:1px solid #f0f0f0; max-width:110px; vertical-align:top; }
.mp-flash { padding:.65rem 1rem; background:#dcfce7; color:#15803d; border-radius:8px; font-size:.82rem; font-weight:600; margin-bottom:1.1rem; border:1px solid #86efac; }
</style>

<div class="mp-page">
    <a href="{{ route('meal-planner.index') }}" class="mp-btn mp-btn-back">&#8592; All Plans</a>

    {{-- Top bar --}}
    <div class="mp-topbar" style="margin-top:.5rem">
        <div>
            <h1 class="mp-title">{{ $mealPlanner->label ?: 'Week of '.$mealPlanner->week_start->format('d M Y') }}</h1>
            <div class="mp-subtitle">
                <span>{{ $mealPlanner->week_start->format('d M Y') }} &ndash; {{ $mealPlanner->week_start->addDays(6)->format('d M Y') }}</span>
                @if($mealPlanner->patient)
                    <span class="mp-badge">{{ $mealPlanner->patient->name }}</span>
                @endif
            </div>
        </div>
        <div class="mp-actions">
            @if($mealPlanner->groceryList)
                <a href="{{ route('grocery-lists.show', $mealPlanner->groceryList) }}" class="mp-btn mp-btn-indigo">&#x1F6D2; View Grocery List</a>
            @else
                <form method="POST" action="{{ route('grocery-lists.generate-from-plan', $mealPlanner) }}" style="display:contents">
                    @csrf
                    <button type="submit" class="mp-btn mp-btn-indigo">&#x1F6D2; Generate Grocery List</button>
                </form>
            @endif
            <a href="{{ route('meal-planner.pdf', [$mealPlanner->patient_id ?? 0, $mealPlanner]) }}"
               class="mp-btn mp-btn-indigo" target="_blank">&#x1F4C4; Download PDF</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mp-flash">&#x2713; {{ session('success') }}</div>
    @endif

    {{-- Grand daily kcal totals bar --}}
    <div class="mp-grand-bar">
        <div class="mp-grand-label">&#x1F4CA; Daily kJ</div>
        @foreach($days as $di => $dayName)
            <div class="mp-grand-day">
                <span>{{ $dayName }}</span>
                <span class="grand-kcal" id="grand-kcal-{{ $di }}">0</span>
                <span class="grand-kcal-label">kJ</span>
            </div>
        @endforeach
    </div>

    <form id="mp-form" method="POST" action="{{ route('meal-planner.save-entries', [$mealPlanner->patient_id ?? 0, $mealPlanner]) }}">
        @csrf @method('PATCH')

        {{-- Hidden cell inputs (one per day × slot) --}}
        @foreach($slots as $slot)
            @foreach($days as $di => $dayName)
                @php
                    $cellEntries = $grid[$di][$slot];
                    $initialJson = json_encode(
                        collect($cellEntries)->map(fn($e) => [
                            'id'      => $e->meal_item_id ? (string)$e->meal_item_id : null,
                            'text'    => $e->meal_text ?? '',
                            'exchCat' => $e->exchange_category ?? null,
                            'qty'     => (int) ($e->qty ?? 1),
                        ])->values()->all()
                    );
                @endphp
                <input type="hidden"
                    id="cell_{{ $di }}_{{ $slot }}"
                    name="cells[{{ $di }}][{{ $slot }}]"
                    value="{{ $initialJson }}">
            @endforeach
        @endforeach

        {{-- Combined grid (all slots in one table) --}}
        <div class="mp-combined-card">
            <div class="combined-grid-wrap">
                <div class="combined-day-grid">

                    {{-- Header row: Meal label + day columns --}}
                    <div class="sgh" style="text-align:left;padding-left:.75rem">Meal</div>
                    @foreach($days as $di => $dayName)
                        <div class="sgh">
                            {{ $dayName }}
                            <span class="day-num">{{ $mealPlanner->week_start->addDays($di)->format('d M') }}</span>
                        </div>
                    @endforeach

                    {{-- One row per slot --}}
                    @foreach($slots as $slot)
                        @php
                            $theme       = $slotTheme[$slot];
                            $distForSlot = $slotDistributionJs[$slot] ?? [];
                        @endphp

                        {{-- Slot label (left column) --}}
                        <div class="slot-row-label" style="background:{{ $theme['header_bg'] }}">
                            <span class="slot-row-dot" style="background:{{ $theme['dot'] }}"></span>
                            <span class="slot-row-name">{{ $slotLabels[$slot] }}</span>
                            <span class="slot-kcal-badge" id="slot-badge-{{ $slot }}">0 kcal</span>
                        </div>

                        {{-- Day cells for this slot --}}
                        @foreach($days as $di => $dayName)
                            <div class="sday-col" style="background:{{ $theme['bg'] }}">
                                @if(count($distForSlot) > 0)
                                    @foreach($distForSlot as $catEntry)
                                        @php $catSlug = Str::slug($catEntry['name']); @endphp
                                        <div class="cat-card"
                                            style="background:{{ $theme['tag_bg'] }};color:{{ $theme['tag_text'] }};border-color:{{ $theme['tag_border'] }}"
                                            data-day="{{ $di }}"
                                            data-slot="{{ $slot }}"
                                            data-category="{{ $catEntry['name'] }}"
                                            data-cat-slug="{{ $catSlug }}"
                                            data-qty="{{ $catEntry['qty'] }}"
                                            data-tag-bg="{{ $theme['tag_bg'] }}"
                                            data-tag-border="{{ $theme['tag_border'] }}"
                                            data-tag-text="{{ $theme['tag_text'] }}"
                                            onclick="openModal(this)">
                                            <div class="cat-card-hdr" style="border-bottom-color:{{ $theme['tag_border'] }}">
                                                <span class="cat-card-name">{{ $catEntry['name'] }}</span>
                                                <span class="cat-card-badge" id="catqty_{{ $di }}_{{ $slot }}_{{ $catSlug }}">0/{{ $catEntry['qty'] }}</span>
                                            </div>
                                            <div class="cat-card-body">
                                                <div class="cell-tags" id="tags_{{ $di }}_{{ $slot }}_{{ $catSlug }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="cat-card"
                                        style="background:{{ $theme['tag_bg'] }};color:{{ $theme['tag_text'] }};border-color:{{ $theme['tag_border'] }}"
                                        data-day="{{ $di }}"
                                        data-slot="{{ $slot }}"
                                        data-category=""
                                        data-cat-slug="all"
                                        data-qty="0"
                                        data-tag-bg="{{ $theme['tag_bg'] }}"
                                        data-tag-border="{{ $theme['tag_border'] }}"
                                        data-tag-text="{{ $theme['tag_text'] }}"
                                        onclick="openModal(this)">
                                        <div class="cat-card-hdr" style="border-bottom-color:{{ $theme['tag_border'] }}">
                                            <span class="cat-card-name">All items</span>
                                        </div>
                                        <div class="cat-card-body">
                                            <div class="cell-tags" id="tags_{{ $di }}_{{ $slot }}_all"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        {{-- Kcal row for this slot --}}
                        <div class="skcal-label" style="background:{{ $theme['bg'] }}">kJ</div>
                        @foreach($days as $di => $dayName)
                            <div class="skcal-day" id="slot-day-kcal-{{ $slot }}-{{ $di }}">0</div>
                        @endforeach

                    @endforeach

                </div>{{-- .combined-day-grid --}}
            </div>{{-- .combined-grid-wrap --}}
        </div>{{-- .mp-combined-card --}}

        {{-- Save bar --}}
        <div class="mp-save-bar">
            <button type="submit" class="mp-btn mp-btn-orange">&#x1F4BE; Save Plan</button>
            <span class="mp-save-hint">All cells are saved together when you click Save.</span>
        </div>
    </form>

    {{-- Monthly Overview --}}
    <details class="mp-details-card" open>
        <summary class="mp-details-summary">
            &#x1F4C5; Monthly Overview
            <span class="chevron">&#9660;</span>
        </summary>
        <div style="padding:1.25rem;overflow-x:auto">
            @php
                $monthlyWeeks = \App\Models\MealPlannerWeek::where('user_id', auth()->id())
                    ->when($mealPlanner->patient_id, fn($q) => $q->where('patient_id', $mealPlanner->patient_id))
                    ->orderBy('week_start')->limit(4)->get()->load('entries');
            @endphp
            <table class="mp-monthly-table">
                <thead>
                    <tr>
                        <th style="text-align:left;width:72px">Week</th>
                        <th style="text-align:left;width:80px">Meal</th>
                        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)<th>{{ $d }}</th>@endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyWeeks as $wk)
                        @php
                            $wkGrid = $wk->grid;
                            $allSlots = ['breakfast'=>'Breakfast','snack1'=>'Snack 1','lunch'=>'Lunch','snack2'=>'Snack 2','dinner'=>'Dinner','snack3'=>'Snack 3'];
                            $sc = ['breakfast'=>['bg'=>'#fff7ed','text'=>'#c2410c','dot'=>'#f97316'],'snack1'=>['bg'=>'#f0fdf4','text'=>'#15803d','dot'=>'#16a34a'],'lunch'=>['bg'=>'#eff6ff','text'=>'#1d4ed8','dot'=>'#2563eb'],'snack2'=>['bg'=>'#f0fdf4','text'=>'#15803d','dot'=>'#16a34a'],'dinner'=>['bg'=>'#faf5ff','text'=>'#6d28d9','dot'=>'#7c3aed'],'snack3'=>['bg'=>'#f0fdf4','text'=>'#15803d','dot'=>'#16a34a']];
                            $weekRowspan = count($allSlots);
                        @endphp
                        @foreach($allSlots as $sl => $slLabel)
                            @php
                                $c = $sc[$sl]; $isFirst = $loop->first;
                                $anyEntry = collect(range(0,6))->contains(fn($d) => !empty($wkGrid[$d][$sl]));
                            @endphp
                            <tr style="background:{{ $c['bg'] }};border-top:{{ $isFirst ? '2px solid var(--primary)' : '1px solid #e5e7eb' }}">
                                @if($isFirst)
                                    <td rowspan="{{ $weekRowspan }}" style="padding:.4rem .6rem;font-weight:700;vertical-align:top;background:#f8fafc;border-right:1px solid var(--border);border-top:2px solid var(--primary)">
                                        <span style="display:block;font-size:.65rem;color:var(--text-muted)">Week {{ $loop->parent->iteration }}</span>
                                        {{ $wk->week_start->format('d M') }}
                                    </td>
                                @endif
                                <td style="padding:.3rem .5rem;white-space:nowrap;border-right:1px solid var(--border);background:{{ $c['bg'] }}">
                                    <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.68rem;font-weight:700;color:{{ $c['text'] }}">
                                        <span style="width:6px;height:6px;border-radius:50%;background:{{ $c['dot'] }};flex-shrink:0;display:inline-block"></span>
                                        {{ $slLabel }}
                                    </span>
                                </td>
                                @foreach(range(0,6) as $d)
                                    @php $cellArr = $wkGrid[$d][$sl] ?? []; @endphp
                                    <td style="color:{{ $c['text'] }};padding:.3rem .5rem;{{ !$anyEntry ? 'opacity:.35' : '' }}">
                                        @forelse($cellArr as $ce)
                                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:.72rem">{{ $ce->meal_text }}</div>
                                        @empty
                                            <span style="color:#d1d5db;font-size:.68rem">&mdash;</span>
                                        @endforelse
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </details>
</div>

{{-- ── Item Picker Modal ─────────────────────────────────────── --}}
<div id="mp-modal-overlay" role="dialog" aria-modal="true">
    <div id="mp-modal">
        <div id="mp-modal-hdr">
            <span id="mp-modal-title">Select items</span>
            <button id="mp-modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div id="mp-modal-quota">
            <span id="mp-modal-quota-label"></span>
            <span id="mp-modal-quota-badge"></span>
        </div>
        <div id="mp-modal-search-wrap">
            <input type="text" id="mp-modal-search" placeholder="Search&#x2026;" autocomplete="off" spellcheck="false">
            <span id="mp-modal-spinner">&#x23F3;</span>
        </div>
        <div id="mp-modal-body"></div>
        <div id="mp-modal-ftr">
            <span id="mp-sel-count"></span>
            <div class="im-ftr-btns">
                <button type="button" id="mp-btn-cancel"  onclick="closeModal()">Cancel</button>
                <button type="button" id="mp-btn-confirm" onclick="confirmModal()">Done</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
'use strict';

/* ── Server data ──────────────────────────────────────── */
const ITEMS   = @json($jsItems);          // [{value,text,group,kcal,kj,serving}]
const DISTRIB = @json($slotDistributionJs); // {slot:[{name,qty},...]}
const SLOTS   = @json($slots);

/* ── Derived lookups ──────────────────────────────────── */
const groups   = {};   // group name -> [item,...]
const idToItem = {};   // id string  -> item
ITEMS.forEach(function(it){
    if(!groups[it.group]) groups[it.group]=[];
    groups[it.group].push(it);
    idToItem[it.value]=it;
});

/* Official library categories — non-matching groups (e.g. FatSecret sub-types) are hidden */
const VALID_CATS = new Set([
    'fruit & vegetables',
    'starchy foods',
    'protein',
    'milk & dairy',
    'spreading fat, oil & sauce',
]);

function toKcal(it){
    if(!it) return 0;
    if(it.kcal && it.kcal>0) return Math.round(it.kcal);
    if(it.kj  && it.kj >0)  return Math.round(it.kj/4.184);
    return 0;
}
function toKj(it){
    if(!it) return 0;
    if(it.kj  && it.kj >0)  return Math.round(it.kj);
    if(it.kcal && it.kcal>0) return Math.round(it.kcal*4.184);
    return 0;
}
function slugify(s){
    return String(s).toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
}
function esc(s){
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ── STATE: "di_slot" -> [{id, text, kcal, group}] ───── */
const STATE = {};

/* ── Render all category cells for one day×slot ───────── */
function renderAll(di, slot){
    const distrib = DISTRIB[slot]||[];
    if(distrib.length>0){
        distrib.forEach(function(d){ renderCatCell(di,slot,d.name,slugify(d.name)); });
    } else {
        renderCatCell(di,slot,'','all');
    }
    // sync hidden input
    var hidden = document.getElementById('cell_'+di+'_'+slot);
    if(hidden) hidden.value = JSON.stringify(STATE[di+'_'+slot]||[]);
    recalc();
}

/* ── Render one category cell ─────────────────────────── */
function renderCatCell(di, slot, catName, catSlug){
    var key      = di+'_'+slot;
    var allItems = STATE[key]||[];
    // filter to this category (or all when catName is empty)
    // Filter by exchCat (exchange card slug), not library group name
    var catItems = catSlug==='all'
        ? allItems
        : allItems.filter(function(it){ return it.exchCat===catSlug; });

    var tagsDiv = document.getElementById('tags_'+di+'_'+slot+'_'+catSlug);
    var cellEl  = document.querySelector('.cat-card[data-day="'+di+'"][data-slot="'+slot+'"][data-cat-slug="'+catSlug+'"]');
    if(!tagsDiv||!cellEl) return;

    var bg  = cellEl.dataset.tagBg     || '#fff7ed';
    var brd = cellEl.dataset.tagBorder  || '#fed7aa';
    var txt = cellEl.dataset.tagText    || '#c2410c';

    // Update the card's quota badge
    var badge = document.getElementById('catqty_'+di+'_'+slot+'_'+catSlug);
    if(badge){
        var used=catItems.length, allowed=parseInt(cellEl.dataset.qty||'0',10);
        if(allowed>0){
            badge.textContent=used+'/'+allowed;
            badge.className='cat-card-badge'+(used>=allowed?' full':used>0?' partial':'');
        } else {
            badge.textContent=used>0?used+' added':'';
            badge.className='cat-card-badge'+(used>0?' done':'');
        }
    }

    tagsDiv.innerHTML='';
    catItems.forEach(function(item){
        if(!item.text) return;
        var allIdx = allItems.indexOf(item);
        var qty    = item.qty||1;
        var kj     = toKj(item)*qty;
        var qtyLbl = qty>1 ? qty+'\u00D7 ' : '';
        var tag    = document.createElement('span');
        tag.className='cell-tag';
        tag.style.cssText='background:'+bg+';color:'+txt+';border-color:'+brd;
        tag.innerHTML=
            '<div class="cell-tag-row">'+
              '<span class="cell-tag-name">'+esc(qtyLbl+item.text)+'</span>'+
              '<button class="cell-tag-rm" type="button" data-day="'+di+'" data-slot="'+slot+'" data-idx="'+allIdx+'" onclick="removeItem(this,event)">&#xD7;</button>'+
            '</div>'+
            (kj>0?'<span class="cell-tag-kcal">'+kj+' kJ</span>':'');
        tagsDiv.appendChild(tag);
    });
    var addBtn=document.createElement('button');
    addBtn.type='button'; addBtn.className='cell-add-btn'; addBtn.textContent='+ Add';
    addBtn.addEventListener('click',function(e){ e.stopPropagation(); openModal(cellEl); });
    tagsDiv.appendChild(addBtn);
}

window.removeItem=function(btn,e){
    e.stopPropagation();
    var di=btn.dataset.day, slot=btn.dataset.slot, idx=parseInt(btn.dataset.idx,10), key=di+'_'+slot;
    STATE[key].splice(idx,1);
    renderAll(di,slot);
};

/* ── Recalc kJ badges & grand totals ────────────────── */
/* recalcWithState(map) — shared engine used by both recalc() and recalcLive() */
function recalcWithState(stateMap){
    var slotTotals={}, dayTotals={};
    Object.keys(stateMap).forEach(function(key){
        var parts=key.split('_'), di=parts[0], slot=parts.slice(1).join('_');
        var kj=stateMap[key].reduce(function(s,it){ return s+toKj(it)*(it.qty||1); },0);
        var kdEl=document.getElementById('slot-day-kcal-'+slot+'-'+di);
        if(kdEl){ kdEl.textContent=kj>0?kj:'0'; kdEl.classList.toggle('has-val',kj>0); }
        slotTotals[slot]=(slotTotals[slot]||0)+kj;
        dayTotals[di]  =(dayTotals[di]  ||0)+kj;
    });
    Object.keys(slotTotals).forEach(function(slot){
        var badge=document.getElementById('slot-badge-'+slot);
        if(!badge) return;
        var k=slotTotals[slot];
        badge.textContent=k>0?k+' kJ':'0 kJ';
        badge.classList.toggle('has-kcal',k>0);
    });
    for(var di=0;di<7;di++){
        var el=document.getElementById('grand-kcal-'+di);
        if(el) el.textContent=(dayTotals[di]||0);
    }
}
function recalc(){ recalcWithState(STATE); }
/* recalcLive — called while modal is open; previews pending _sel changes */
function recalcLive(){
    if(_day===null){ recalc(); return; }
    var key=_day+'_'+_slot;
    var others=(_catSlug==='all')?[]:(STATE[key]||[]).filter(function(it){return it.exchCat!==_catSlug;});
    var preview=Object.assign({},STATE);
    preview[key]=others.concat(Object.values(_sel));
    recalcWithState(preview);
}



/* ── Modal state ──────────────────────────────────────── */
var _day=null, _slot=null, _cat=null, _catSlug=null, _qty=0;
var _sel={};  // value -> {id,text,kcal,group}

window.openModal=function(cellEl){
    _day     = cellEl.dataset.day;
    _slot    = cellEl.dataset.slot;
    _cat     = cellEl.dataset.category||'';
    _catSlug = cellEl.dataset.catSlug||'all';
    _qty     = parseInt(cellEl.dataset.qty||'0',10);

    var key=_day+'_'+_slot;
    // pre-select items already tagged to this exchange card
    _sel={};
    (STATE[key]||[]).forEach(function(it){
        if(_catSlug==='all'||(it.exchCat===_catSlug)){
            _sel[it.id||('_f_'+it.text)]=it;
        }
    });

    // Modal title
    var slotName={'breakfast':'Breakfast','snack1':'Snack 1','lunch':'Lunch','snack2':'Snack 2','dinner':'Dinner','snack3':'Snack 3'}[_slot]||_slot;
    var dayNames=['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    document.getElementById('mp-modal-title').textContent=
        (_cat? _cat+' \u2014 ':'')+slotName+' \u00B7 '+(dayNames[parseInt(_day,10)]||'Day '+(parseInt(_day,10)+1));

    // Quota badge
    updateQuotaBadge();

    // Reset search
    var se=document.getElementById('mp-modal-search');
    se.value=''; _fsResults=[]; clearTimeout(_fsDeb); setSpinner(false);
    renderBody(''); updateCount();
    document.getElementById('mp-modal-overlay').classList.add('open');
    setTimeout(function(){ se.focus(); },60);
};

function updateQuotaBadge(){
    var quotaWrap  = document.getElementById('mp-modal-quota');
    var badge      = document.getElementById('mp-modal-quota-badge');
    var lbl        = document.getElementById('mp-modal-quota-label');
    if(!_cat||_qty===0){ quotaWrap.style.display='none'; return; }
    quotaWrap.style.display='flex';
    var n=Object.keys(_sel).length;
    lbl.textContent=_cat+':';
    badge.textContent=n+'/'+_qty+' selected';
    badge.className=''; // reset
    if(n>=_qty) badge.classList.add('full');
    else if(n>0) badge.classList.add('partial');
}

window.closeModal=function(){
    document.getElementById('mp-modal-overlay').classList.remove('open');
    _day=null;_slot=null;_cat=null;_catSlug=null;_qty=0;_sel={};
};
window.confirmModal=function(){
    if(_day===null) return;
    var key=_day+'_'+_slot;
    // Keep items tagged to other exchange cards; replace this card's items
    var others=(STATE[key]||[]).filter(function(it){
        if(_catSlug==='all') return false;
        return it.exchCat!==_catSlug;
    });
    STATE[key]=others.concat(Object.values(_sel));
    renderAll(_day,_slot);
    closeModal();
};

// Close on overlay click or Escape
document.getElementById('mp-modal-overlay').addEventListener('click',function(e){ if(e.target===this) closeModal(); });
document.addEventListener('keydown',function(e){ if(e.key==='Escape') closeModal(); });

/* ── Render modal body ────────────────────────────────── */
function renderBody(q){
    var body=document.getElementById('mp-modal-body');
    body.innerHTML='';
    var lq=q.toLowerCase().trim();
    var visible=0;

    // When searching: include ALL groups (covers previously-imported FatSecret items in 'Other').
    // When no query: restrict to canonical exchange categories for the default browse view.
    var targetGroups = Object.keys(groups).filter(function(g){
        if(lq) return true;
        return VALID_CATS.size===0 || VALID_CATS.has(g.toLowerCase());
    });

    targetGroups.forEach(function(gName){
        var grpItems=(groups[gName]||[]).filter(function(it){ return !lq||it.text.toLowerCase().includes(lq); });
        if(grpItems.length===0) return;
        visible+=grpItems.length;
        var grpDiv=document.createElement('div'); grpDiv.className='im-group';
        var hdr=document.createElement('div'); hdr.className='im-group-header'; hdr.textContent=gName;
        grpDiv.appendChild(hdr);
        grpItems.forEach(function(it){ grpDiv.appendChild(makeRow(it)); });
        body.appendChild(grpDiv);
    });

    if(visible===0 && lq){
        // Nothing in local library — show FatSecret fallback
        if(_fsResults.length===0){
            // Still searching or query too short — show a friendly "searching…" hint
            var hint=document.createElement('div'); hint.className='im-no-results';
            hint.innerHTML='No results in your meal library for &ldquo;<strong>'+esc(lq)+'</strong>&rdquo;.'
                +(q.length>=2?'<br><span style="font-size:.75rem;color:var(--text-muted)">Searching food database\u2026</span>':'')
                +'<br><button type="button" class="mp-btn mp-btn-orange" style="margin-top:.65rem;font-size:.75rem;padding:.4rem .9rem" onclick="addCustom()">+ Add &ldquo;'+esc(lq)+'&rdquo; as custom</button>';
            body.appendChild(hint);
        }
        // FatSecret results (fallback) — only shown when library has 0 matches
        appendFS(body,lq);
    }
    // If library had results: FatSecret section is intentionally NOT shown
}

function makeRow(it){
    var val=it.value, isChecked=!!_sel[val];
    var kcal=toKcal(it);
    var kj=toKj(it);
    var curQty=isChecked?((_sel[val].qty)||1):1;
    var desc=[kj>0?kj+' kJ':'',it.serving?it.serving:''].filter(Boolean).join(' \u00B7 ');
    var row=document.createElement('label');
    row.className='im-option'+(isChecked?' selected':'');
    row.innerHTML='<input type="checkbox" value="'+esc(val)+'"'+(isChecked?' checked':'')+'>'
        +'<div class="im-option-label">'
        +'<span class="im-option-name">'+esc(it.text)+'</span>'
        +(desc?'<span class="im-option-desc">'+esc(desc)+'</span>':'')
        +'</div>'
        +'<div class="im-qty-wrap"'+(isChecked?'':' style="display:none"')+'>'
        +'<button type="button" class="im-qty-btn im-qty-minus">&#x2212;</button>'
        +'<input type="number" class="im-qty-inp" value="'+curQty+'" min="1" max="99">'
        +'<button type="button" class="im-qty-btn im-qty-plus">&#x2B;</button>'
        +'</div>';
    var chk=row.querySelector('input[type=checkbox]');
    var qtyWrap=row.querySelector('.im-qty-wrap');
    var qtyInp=row.querySelector('.im-qty-inp');
    // Prevent qty stepper clicks from toggling the checkbox via label default behavior
    qtyWrap.addEventListener('click',function(e){ e.preventDefault(); e.stopPropagation(); });
    function updateQty(q){
        q=Math.max(1,Math.min(99,Math.round(q)||1));
        qtyInp.value=q;
        if(_sel[val]){ _sel[val].qty=q; recalcLive(); }
    }
    row.querySelector('.im-qty-minus').addEventListener('click',function(e){ e.preventDefault(); e.stopPropagation(); updateQty((parseInt(qtyInp.value,10)||1)-1); });
    row.querySelector('.im-qty-plus').addEventListener('click',function(e){ e.preventDefault(); e.stopPropagation(); updateQty((parseInt(qtyInp.value,10)||1)+1); });
    qtyInp.addEventListener('click',function(e){ e.stopPropagation(); });
    qtyInp.addEventListener('change',function(e){ e.stopPropagation(); updateQty(parseInt(this.value,10)||1); });
    chk.addEventListener('change',function(e){
        if(e.target.checked){
            if(_qty>0&&Object.keys(_sel).length>=_qty){ e.target.checked=false; return; }
            _sel[val]={id:val,text:it.text,kcal:kcal,kj:kj,group:it.group,exchCat:_catSlug,qty:1};
            row.classList.add('selected'); qtyWrap.style.display='';
        } else {
            delete _sel[val]; row.classList.remove('selected'); qtyWrap.style.display='none';
        }
        updateCount(); updateQuotaBadge(); recalcLive();
    });
    return row;
}

window.addCustom=function(){
    var q=document.getElementById('mp-modal-search').value.trim();
    if(!q) return;
    var val='_f_'+Date.now()+'_'+q;
    _sel[val]={id:null,text:q,kcal:0,kj:0,group:null,exchCat:_catSlug,qty:1};
    updateCount(); updateQuotaBadge();
    document.getElementById('mp-modal-search').value='';
    renderBody('');
};

function updateCount(){
    var n=Object.keys(_sel).length, cntEl=document.getElementById('mp-sel-count'), cfm=document.getElementById('mp-btn-confirm');
    if(_qty>0){
        var rem=_qty-n;
        if(rem<0){ cntEl.innerHTML='<span style="color:#b91c1c;font-weight:700">'+n+' selected \u2014 over limit of '+_qty+'</span>'; cfm.disabled=true; cfm.style.opacity='.45'; }
        else { cntEl.innerHTML=n+' selected <span style="color:'+(rem===0?'#15803d':'var(--text-muted)')+';font-weight:700">'+rem+' remaining of '+_qty+'</span>'; cfm.disabled=false; cfm.style.opacity='1'; }
    } else {
        cntEl.textContent=n===0?'':n+' item'+(n>1?'s':'')+' selected';
        cfm.disabled=false; cfm.style.opacity='1';
    }
}

/* ── FatSecret search ─────────────────────────────────── */
var _fsResults=[], _fsDeb=null;
var SEARCH_URL='{{ route("meal-items.search") }}';
var IMPORT_URL='{{ route("meal-items.import-fatsecret") }}';
var CSRF=document.querySelector('meta[name="csrf-token"]')?.content||'';

document.getElementById('mp-modal-search').addEventListener('input',function(){
    var q=this.value.trim(); clearTimeout(_fsDeb); _fsResults=[];
    renderBody(q);
    if(q.length<2){ setSpinner(false); return; }
    _fsDeb=setTimeout(function(){
        setSpinner(true);
        fetch(SEARCH_URL+'?q='+encodeURIComponent(q),{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
        .then(function(r){ return r.json(); })
        .then(function(data){
            setSpinner(false);
            // Merge DB results into local ITEMS so renderBody() can display them
            (data.db||[]).forEach(function(dbIt){
                var val=String(dbIt.id);
                if(!idToItem[val]){
                    var newIt={value:val,text:dbIt.name,group:dbIt.category||'Other',kcal:dbIt.kcal||0,kj:dbIt.kj||0,serving:dbIt.serving||null};
                    ITEMS.push(newIt); idToItem[val]=newIt;
                    if(!groups[newIt.group]) groups[newIt.group]=[];
                    groups[newIt.group].push(newIt);
                }
            });
            _fsResults=data.fs||[];
            renderBody(document.getElementById('mp-modal-search').value.trim());
        })
        .catch(function(){ setSpinner(false); });
    },400);
});

function setSpinner(on){
    var el=document.getElementById('mp-modal-spinner');
    if(el) el.style.display=on?'inline':'none';
}

function appendFS(body,q){
    if(_fsResults.length===0) return;
    var alreadyNames=ITEMS.map(function(i){ return i.text.toLowerCase(); });
    var grp=document.createElement('div'); grp.className='im-group';
    var hdr=document.createElement('div'); hdr.className='im-group-header';
    hdr.style.cssText='background:#eff6ff;color:#1d4ed8;border-bottom:1px solid #bfdbfe';
    hdr.innerHTML='\uD83C\uDF10 Food Database <span style="font-weight:400;font-size:.68rem">(adds to library)</span>';
    grp.appendChild(hdr);
    _fsResults.forEach(function(food){
        if(alreadyNames.includes(food.name.toLowerCase())) return;
        var row=document.createElement('label'); row.className='im-option';
        var kjStr=(food.kj?Math.round(food.kj):(food.kcal?Math.round(food.kcal*4.184):0)); var kjDisp=kjStr?kjStr+' kJ':''; var servStr=food.serving?' \u00B7 '+food.serving:'';
        row.innerHTML='<input type="checkbox" value="'+esc(food.id)+'">'
            +'<div class="im-option-label"><span class="im-option-name">'+esc(food.name)+'</span>'
            +(kjDisp||servStr?'<span class="im-option-desc">'+esc(kjDisp+servStr)+'</span>':'')
            +'<span style="font-size:.65rem;color:#2563eb;font-weight:600">+ library</span></div>';
        row.querySelector('input').addEventListener('change',function(e){
            if(!e.target.checked){ delete _sel[food.id]; row.classList.remove('selected'); updateCount(); recalcLive(); return; }
            if(_qty>0&&Object.keys(_sel).length>=_qty){ e.target.checked=false; return; }
            row.classList.add('selected'); e.target.disabled=true;
            var payload=new URLSearchParams({_token:CSRF,name:food.name,serving:food.serving||'',kcal:food.kcal||'',kj:food.kj||'',fat:food.fat||'',carbs:food.carbs||'',protein:food.protein||'',fiber:food.fiber||''});
            fetch(IMPORT_URL,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest','Content-Type':'application/x-www-form-urlencoded','Accept':'application/json'},body:payload.toString()})
            .then(function(r){ return r.json(); })
            .then(function(saved){
                var newIt={value:String(saved.id),text:saved.name,group:saved.category||'Other',kcal:saved.energy_kcal||0,kj:saved.energy_kj||0,serving:saved.serving_size||null};
                ITEMS.push(newIt); idToItem[newIt.value]=newIt;
                if(!groups[newIt.group]){ groups[newIt.group]=[]; }
                groups[newIt.group].push(newIt);
                _sel[newIt.value]={id:newIt.value,text:newIt.text,kcal:toKcal(newIt),kj:toKj(newIt),group:newIt.group,exchCat:_catSlug,qty:1};
                _fsResults=_fsResults.filter(function(f){ return f.id!==food.id; });
                updateCount(); updateQuotaBadge(); recalcLive();
                renderBody(document.getElementById('mp-modal-search').value.trim());
            })
            .catch(function(){
                var val='_f_'+Date.now(); _sel[val]={id:null,text:food.name,kcal:food.kcal?Math.round(food.kcal):0,kj:food.kj?Math.round(food.kj):0,group:null,exchCat:_catSlug,qty:1};
                updateCount(); recalcLive();
            });
        });
        grp.appendChild(row);
    });
    if(grp.children.length>1) body.appendChild(grp);
}

/* ── Initialise from saved DB data ───────────────────── */
for(var di=0;di<7;di++){
    SLOTS.forEach(function(slot){
        var key=di+'_'+slot;
        var hidden=document.getElementById('cell_'+di+'_'+slot);
        try{
            var parsed=JSON.parse(hidden?hidden.value:'[]');
            STATE[key]=Array.isArray(parsed)?parsed.map(function(it){
                var lib=it.id?idToItem[it.id]:null;
                return {id:it.id||null,text:it.text||(lib?lib.text:''),kcal:lib?toKcal(lib):0,kj:lib?toKj(lib):0,group:lib?lib.group:null,exchCat:it.exchCat||null,qty:it.qty||1};
            }).filter(function(it){ return it.text||it.id; }):[];
        }catch(ex){ STATE[key]=[]; }
        renderAll(di,slot);
    });
}

/* ── Sync on submit ───────────────────────────────────── */
document.getElementById('mp-form').addEventListener('submit',function(){
    for(var di=0;di<7;di++){
        SLOTS.forEach(function(slot){
            var hidden=document.getElementById('cell_'+di+'_'+slot);
            if(hidden) hidden.value=JSON.stringify(STATE[di+'_'+slot]||[]);
        });
    }
});

}());
</script>
</x-app-layout>
