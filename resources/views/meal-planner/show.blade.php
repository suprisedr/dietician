<x-app-layout>
@php
    $days       = \App\Models\MealPlannerWeek::DAYS;
    $slots      = \App\Models\MealPlannerWeek::MEAL_SLOTS;
    $slotLabels = \App\Models\MealPlannerWeek::SLOT_LABELS;

    $slotTheme = [
        'breakfast' => ['bg'=>'#fff7ed','dot'=>'#f97316','tag_bg'=>'#fff7ed','tag_border'=>'#fed7aa','tag_text'=>'#c2410c','header_bg'=>'#fef3c7'],
        'snack1'    => ['bg'=>'#f0fdf4','dot'=>'#16a34a','tag_bg'=>'#dcfce7','tag_border'=>'#86efac','tag_text'=>'#15803d','header_bg'=>'#dcfce7'],
        'lunch'     => ['bg'=>'#eff6ff','dot'=>'#2563eb','tag_bg'=>'#dbeafe','tag_border'=>'#93c5fd','tag_text'=>'#1d4ed8','header_bg'=>'#dbeafe'],
        'snack2'    => ['bg'=>'#f0fdf4','dot'=>'#16a34a','tag_bg'=>'#dcfce7','tag_border'=>'#86efac','tag_text'=>'#15803d','header_bg'=>'#dcfce7'],
        'dinner'    => ['bg'=>'#faf5ff','dot'=>'#7c3aed','tag_bg'=>'#ede9fe','tag_border'=>'#c4b5fd','tag_text'=>'#6d28d9','header_bg'=>'#ede9fe'],
        'snack3'    => ['bg'=>'#f0fdf4','dot'=>'#16a34a','tag_bg'=>'#dcfce7','tag_border'=>'#86efac','tag_text'=>'#15803d','header_bg'=>'#dcfce7'],
    ];

    $jsItems = [];
    foreach ($mealItemsByCategory as $cat => $items) {
        foreach ($items as $item) {
            $jsItems[] = ['value' => (string)$item->id, 'text' => $item->name, 'group' => $cat];
        }
    }
@endphp

<style>
/* ─── Page wrapper ───────────────────────────────────────────── */
.mp-page { max-width:1280px; margin:0 auto; padding:1.75rem 1.25rem 3rem; }

/* ─── Top bar ────────────────────────────────────────────────── */
.mp-topbar {
    display:flex; align-items:flex-start; justify-content:space-between;
    flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;
}
.mp-title { font-size:1.35rem; font-weight:800; color:var(--text-primary); margin:.3rem 0 .15rem; line-height:1.2; }
.mp-subtitle { font-size:.8rem; color:var(--text-muted); display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
.mp-badge { background:#ffedd5; color:#c2410c; font-size:.71rem; font-weight:700; padding:.18rem .6rem; border-radius:20px; }
.mp-actions { display:flex; gap:.5rem; flex-wrap:wrap; align-items:center; }
.mp-btn {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.42rem .9rem; border-radius:8px; font-size:.78rem; font-weight:700;
    text-decoration:none; border:none; cursor:pointer; transition:filter .15s;
}
.mp-btn:hover { filter:brightness(.93); }
.mp-btn-indigo  { background:#e0e7ff; color:#3730a3; }
.mp-btn-green   { background:#dcfce7; color:#15803d; }
.mp-btn-purple  { background:#f3e8ff; color:#7e22ce; }
.mp-btn-orange  { background:var(--primary); color:#fff; font-size:.82rem; padding:.5rem 1.3rem; }
.mp-btn-back    { background:transparent; color:var(--primary); font-size:.8rem; padding:.3rem 0; }

/* ─── Legend strip ───────────────────────────────────────────── */
.mp-legend {
    display:flex; align-items:center; gap:.75rem 1.25rem; flex-wrap:wrap;
    padding:.6rem .9rem; background:#fff; border:1px solid var(--border);
    border-radius:8px; margin-bottom:1.1rem; font-size:.73rem; color:var(--text-muted);
}
.mp-legend-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.mp-legend-item { display:inline-flex; align-items:center; gap:.35rem; font-weight:600; }
.mp-legend-hint {
    margin-left:auto; background:#fff7ed; border:1px solid #fed7aa;
    border-radius:6px; padding:.2rem .65rem; font-size:.69rem; color:#c2410c; font-weight:600;
}

/* ─── Grid card ──────────────────────────────────────────────── */
.mp-card {
    background:#fff; border:1px solid var(--border); border-radius:12px;
    overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.05);
}
.mp-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }

/* ─── Table ──────────────────────────────────────────────────── */
#mp-grid { width:100%; border-collapse:collapse; min-width:800px; table-layout:fixed; }

/* Header row */
#mp-grid thead th {
    padding:.65rem .5rem; font-size:.72rem; font-weight:700; text-align:center;
    background:var(--indigo); color:#fff; position:sticky; top:0; z-index:10;
}
#mp-grid thead th:first-child {
    text-align:left; padding-left:.9rem; width:100px; border-radius:0;
}
#mp-grid thead th .day-num { font-size:.65rem; font-weight:400; opacity:.75; display:block; margin-top:.1rem; }

/* Slot label cell */
#mp-grid td.slot-label {
    padding:.55rem .65rem .55rem .9rem;
    font-size:.73rem; font-weight:700;
    color:var(--text-primary);
    border-bottom:1px solid var(--border);
    border-right:2px solid var(--border);
    white-space:nowrap; vertical-align:middle;
    position:sticky; left:0; z-index:5; background:inherit;
}
#mp-grid td.slot-label .slot-dot {
    display:inline-block; width:8px; height:8px; border-radius:50%;
    margin-right:.4rem; vertical-align:middle; flex-shrink:0;
}

/* ─── Data cells ─────────────────────────────────────────────── */
#mp-grid td.cell {
    padding:.25rem;
    border-bottom:1px solid var(--border);
    border-left:1px solid #f0f0f0;
    vertical-align:top;
    cursor:pointer;
}
#mp-grid tbody tr:last-child td { border-bottom:none; }
#mp-grid tbody tr:hover td.cell { background-color:rgba(0,0,0,.018); }

/* ─── Cell tag display ───────────────────────────────────────── */
.cell-tags { display:flex; flex-wrap:wrap; gap:3px; min-height:30px; align-items:flex-start; align-content:flex-start; }
.cell-tag {
    display:inline-flex; align-items:center; gap:4px;
    border-radius:5px; padding:.1rem .4rem;
    font-size:.67rem; font-weight:600; line-height:1.5;
    border:1px solid transparent;
    max-width:100%; box-sizing:border-box;
}
.cell-tag-remove {
    cursor:pointer; opacity:.55; font-size:.8rem; font-weight:700;
    line-height:1; border:none; background:none; padding:0; color:inherit;
}
.cell-tag-remove:hover { opacity:1; }
.cell-add-btn {
    display:inline-flex; align-items:center; gap:3px;
    font-size:.67rem; color:var(--text-muted); cursor:pointer;
    background:none; border:none; padding:.1rem .2rem;
    border-radius:4px; opacity:.65;
    transition:opacity .15s;
}
.cell-add-btn:hover { opacity:1; color:var(--primary); }

/* ─── Item picker modal ──────────────────────────────────────── */
#item-modal-overlay {
    display:none; position:fixed; inset:0; z-index:10000;
    background:rgba(0,0,0,.45); backdrop-filter:blur(2px);
    align-items:center; justify-content:center;
}
#item-modal-overlay.open { display:flex; }
#item-modal {
    background:#fff; border-radius:16px;
    width:min(520px, 95vw); max-height:82vh;
    display:flex; flex-direction:column;
    box-shadow:0 24px 60px rgba(0,0,0,.22);
    overflow:hidden;
    animation: modalIn .18s ease;
}
@keyframes modalIn {
    from { transform:translateY(18px) scale(.97); opacity:0; }
    to   { transform:translateY(0)    scale(1);   opacity:1; }
}
#item-modal-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 1.1rem .6rem;
    border-bottom:1px solid var(--border);
    flex-shrink:0;
}
#item-modal-title { font-size:.92rem; font-weight:800; color:var(--text-primary); }
#item-modal-close {
    background:none; border:none; cursor:pointer; font-size:1.25rem;
    color:var(--text-muted); line-height:1; padding:.1rem .3rem;
    border-radius:6px; transition:background .15s;
}
#item-modal-close:hover { background:#f1f5f9; }
/* ─── Tabs ─────────────────────────────────────────── */
#item-modal-tabs {
    display:flex; border-bottom:1px solid var(--border); flex-shrink:0;
}
.im-tab {
    flex:1; padding:.52rem .5rem; text-align:center; font-size:.78rem;
    font-weight:700; color:var(--text-muted); cursor:pointer;
    border-bottom:2.5px solid transparent; background:none; border-top:none;
    border-left:none; border-right:none; transition:color .15s, border-color .15s;
    letter-spacing:.01em;
}
.im-tab.active { color:var(--primary); border-bottom-color:var(--primary); }
.im-tab:hover:not(.active) { background:#fef9f5; }
/* ─── Search wrap ──────────────────────────────────── */
#item-modal-search-wrap {
    padding:.65rem 1.1rem .5rem;
    border-bottom:1px solid var(--border);
    flex-shrink:0;
    position:relative;
}
#item-modal-search {
    width:100%; padding:.5rem .75rem;
    border:1.5px solid var(--border); border-radius:9px;
    font-size:.85rem; outline:none;
    transition:border-color .15s, box-shadow .15s;
    box-sizing:border-box;
}
#item-modal-search:focus {
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(249,115,22,.12);
}
#item-modal-body {
    flex:1; overflow-y:auto; padding:.5rem 0;
}
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
    color:var(--text-primary); transition:background .1s;
    user-select:none;
}
.im-option:hover { background:#fef9f5; }
.im-option.selected { background:#fff7ed; }
.im-option input[type=checkbox] {
    width:15px; height:15px; flex-shrink:0; margin-top:2px;
    accent-color:var(--primary); cursor:pointer;
}
.im-option-label { display:flex; flex-direction:column; gap:1px; }
.im-option-name { font-weight:600; line-height:1.3; }
.im-option-desc { font-size:.71rem; color:var(--text-muted); line-height:1.3; }
.im-no-results {
    padding:1.5rem 1.1rem; text-align:center;
    font-size:.82rem; color:var(--text-muted);
}
.im-spinner {
    padding:1.5rem 1.1rem; text-align:center;
    font-size:.82rem; color:var(--text-muted);
}
#item-modal-footer {
    display:flex; align-items:center; justify-content:space-between;
    padding:.7rem 1.1rem; border-top:1px solid var(--border);
    flex-shrink:0; gap:.6rem;
    background:#fafafa;
}
#item-modal-selected-count { font-size:.78rem; color:var(--text-muted); }
.im-footer-btns { display:flex; gap:.5rem; }
#item-modal-cancel {
    padding:.42rem .9rem; border-radius:8px; font-size:.78rem; font-weight:700;
    border:1.5px solid var(--border); background:#fff; cursor:pointer;
    color:var(--text-primary); transition:background .15s;
}
#item-modal-cancel:hover { background:#f1f5f9; }
#item-modal-confirm {
    padding:.42rem 1rem; border-radius:8px; font-size:.78rem; font-weight:700;
    border:none; background:var(--primary); color:#fff; cursor:pointer;
    transition:filter .15s;
}
#item-modal-confirm:hover { filter:brightness(.92); }

/* ─── Save bar ───────────────────────────────────────────────── */
.mp-save-bar {
    display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;
    margin-top:1.1rem; padding:.75rem 1rem;
    background:#fff; border:1px solid var(--border); border-radius:10px;
}
.mp-save-hint { font-size:.78rem; color:var(--text-muted); }

/* ─── Monthly overview ───────────────────────────────────────── */
.mp-details-card {
    margin-top:1.75rem; background:#fff;
    border:1px solid var(--border); border-radius:12px;
    overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.mp-details-summary {
    display:flex; align-items:center; gap:.6rem;
    padding:.85rem 1.1rem; cursor:pointer;
    font-size:.88rem; font-weight:700; color:var(--text-primary);
    user-select:none; list-style:none;
    background:#fafafa; border-bottom:1px solid transparent;
    transition:background .15s;
}
.mp-details-summary:hover { background:#f1f5f9; }
details[open] .mp-details-summary { border-bottom-color:var(--border); }
.mp-details-summary .chevron { margin-left:auto; font-size:.75rem; color:var(--text-muted); transition:transform .2s; }
details[open] .mp-details-summary .chevron { transform:rotate(180deg); }
.mp-monthly-table { width:100%; border-collapse:collapse; min-width:760px; font-size:.74rem; }
.mp-monthly-table th { padding:.45rem .65rem; background:var(--indigo); color:#fff; text-align:center; font-size:.71rem; font-weight:700; }
.mp-monthly-table th:first-child, .mp-monthly-table th:nth-child(2) { text-align:left; }
.mp-monthly-table td { padding:.32rem .55rem; border:1px solid #f0f0f0; max-width:110px; vertical-align:top; }

/* Flash */
.mp-flash { padding:.65rem 1rem; background:#dcfce7; color:#15803d; border-radius:8px; font-size:.82rem; font-weight:600; margin-bottom:1.1rem; border:1px solid #86efac; }
</style>

<div class="mp-page">

    {{-- Back link --}}
    <a href="{{ route('meal-planner.index') }}" class="mp-btn mp-btn-back">← All Plans</a>

    {{-- Top bar --}}
    <div class="mp-topbar" style="margin-top:.5rem">
        <div>
            <h1 class="mp-title">
                {{ $mealPlanner->label ?: 'Week of ' . $mealPlanner->week_start->format('d M Y') }}
            </h1>
            <div class="mp-subtitle">
                <span>{{ $mealPlanner->week_start->format('d M Y') }} – {{ $mealPlanner->week_start->addDays(6)->format('d M Y') }}</span>
                @if($mealPlanner->patient)
                    <span class="mp-badge">{{ $mealPlanner->patient->name }}</span>
                @endif
            </div>
        </div>
        <div class="mp-actions">
            {{-- Generate grocery list from this plan --}}
            @if($mealPlanner->groceryList)
                <a href="{{ route('grocery-lists.show', $mealPlanner->groceryList) }}" class="mp-btn mp-btn-indigo">🛒 View Grocery List</a>
            @else
                <form method="POST" action="{{ route('grocery-lists.generate-from-plan', $mealPlanner) }}" style="display:contents">
                    @csrf
                    <button type="submit" class="mp-btn mp-btn-indigo">🛒 Generate Grocery List</button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mp-flash">✓ {{ session('success') }}</div>
    @endif

    {{-- Legend --}}
    <div class="mp-legend">
        @foreach($slotTheme as $s => $theme)
            <span class="mp-legend-item">
                <span class="mp-legend-dot" style="background:{{ $theme['dot'] }}"></span>
                {{ \App\Models\MealPlannerWeek::SLOT_LABELS[$s] }}
            </span>
        @endforeach
        <span class="mp-legend-hint">Click any cell to add food items</span>
    </div>

    {{-- Grid Form --}}
    <form id="mp-form" method="POST" action="{{ route('meal-planner.save-entries', [$mealPlanner->patient_id ?? 0, $mealPlanner]) }}">
        @csrf @method('PATCH')

        <div class="mp-card">
            <div class="mp-scroll">
                <table id="mp-grid">
                    <thead>
                        <tr>
                            <th style="text-align:left">Meal</th>
                            @foreach($days as $dayIndex => $dayName)
                                <th>
                                    {{ $dayName }}
                                    <span class="day-num">{{ $mealPlanner->week_start->addDays($dayIndex)->format('d M') }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slots as $slot)
                            @php $theme = $slotTheme[$slot]; @endphp
                            <tr style="background:{{ $theme['bg'] }}">
                                <td class="slot-label" style="background:{{ $theme['bg'] }}">
                                    <span class="slot-dot" style="background:{{ $theme['dot'] }}"></span>
                                    {{ \App\Models\MealPlannerWeek::SLOT_LABELS[$slot] }}
                                </td>
                                @foreach($days as $dayIndex => $dayName)
                                    @php
                                        $cellEntries = $grid[$dayIndex][$slot];
                                        $initialJson = json_encode(
                                            collect($cellEntries)->map(fn($e) => [
                                                'id'   => $e->meal_item_id ? (string)$e->meal_item_id : null,
                                                'text' => $e->meal_text ?? '',
                                            ])->values()->all()
                                        );
                                    @endphp
                                    <td class="cell"
                                        data-day="{{ $dayIndex }}"
                                        data-slot="{{ $slot }}"
                                        data-tag-bg="{{ $theme['tag_bg'] }}"
                                        data-tag-border="{{ $theme['tag_border'] }}"
                                        data-tag-text="{{ $theme['tag_text'] }}"
                                        onclick="openItemModal(this)">
                                        <input type="hidden"
                                               name="cells[{{ $dayIndex }}][{{ $slot }}]"
                                               id="cell_{{ $dayIndex }}_{{ $slot }}"
                                               value="{{ $initialJson }}">
                                        <div class="cell-tags" id="tags_{{ $dayIndex }}_{{ $slot }}"></div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Save bar --}}
        <div class="mp-save-bar">
            <button type="submit" class="mp-btn mp-btn-orange">💾 Save Plan</button>
            <span class="mp-save-hint">All cells are saved together when you click Save.</span>
        </div>
    </form>

    {{-- Monthly Overview --}}
    <details class="mp-details-card" open>
        <summary class="mp-details-summary">
            📅 Monthly Overview
            <span class="chevron">▼</span>
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
                        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
                            <th>{{ $d }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyWeeks as $wk)
                        @php
                            $wkGrid   = $wk->grid;
                            $allSlots = ['breakfast','snack1','lunch','snack2','dinner','snack3'];
                            $slotColors = [
                                'breakfast' => ['bg'=>'#fff7ed','text'=>'#c2410c','dot'=>'#f97316','label'=>'Breakfast'],
                                'snack1'    => ['bg'=>'#f0fdf4','text'=>'#15803d','dot'=>'#16a34a','label'=>'Snack 1'],
                                'lunch'     => ['bg'=>'#eff6ff','text'=>'#1d4ed8','dot'=>'#2563eb','label'=>'Lunch'],
                                'snack2'    => ['bg'=>'#f0fdf4','text'=>'#15803d','dot'=>'#16a34a','label'=>'Snack 2'],
                                'dinner'    => ['bg'=>'#faf5ff','text'=>'#6d28d9','dot'=>'#7c3aed','label'=>'Dinner'],
                                'snack3'    => ['bg'=>'#f0fdf4','text'=>'#15803d','dot'=>'#16a34a','label'=>'Snack 3'],
                            ];
                            $weekRowspan = count($allSlots);
                        @endphp
                        @foreach($allSlots as $slIdx => $sl)
                            @php
                                $sc = $slotColors[$sl];
                                $hasAnyEntry = false;
                                foreach(range(0,6) as $d) {
                                    if (!empty($wkGrid[$d][$sl])) { $hasAnyEntry = true; break; }
                                }
                                $isFirstSlot = $slIdx === 0;
                                $isLastSlot  = $slIdx === count($allSlots) - 1;
                                $topBorder   = $isFirstSlot ? 'border-top:2px solid var(--primary)' : ($sl === 'breakfast' || $sl === 'snack1' || $sl === 'lunch' || $sl === 'snack2' || $sl === 'dinner' || $sl === 'snack3' ? 'border-top:1px solid #e5e7eb' : '');
                            @endphp
                            <tr style="background:{{ $sc['bg'] }};{{ $topBorder }}">
                                @if($isFirstSlot)
                                    <td rowspan="{{ $weekRowspan }}" style="padding:.4rem .6rem;font-weight:700;vertical-align:top;background:#f8fafc;border-right:1px solid var(--border);border-top:2px solid var(--primary)">
                                        <span style="display:block;font-size:.65rem;color:var(--text-muted)">Week {{ $loop->parent->iteration }}</span>
                                        {{ $wk->week_start->format('d M') }}
                                    </td>
                                @endif
                                {{-- Slot label cell --}}
                                <td style="padding:.3rem .5rem;white-space:nowrap;border-right:1px solid var(--border);background:{{ $sc['bg'] }}">
                                    <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.68rem;font-weight:700;color:{{ $sc['text'] }}">
                                        <span style="width:6px;height:6px;border-radius:50%;background:{{ $sc['dot'] }};flex-shrink:0;display:inline-block"></span>
                                        {{ $sc['label'] }}
                                    </span>
                                </td>
                                @foreach(range(0,6) as $d)
                                    @php $cellArr = $wkGrid[$d][$sl] ?? []; @endphp
                                    <td style="color:{{ $sc['text'] }};padding:.3rem .5rem;{{ !$hasAnyEntry ? 'opacity:.35' : '' }}">
                                        @forelse($cellArr as $ce)
                                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:.72rem">{{ $ce->meal_text }}</div>
                                        @empty
                                            <span style="color:#d1d5db;font-size:.68rem">—</span>
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

{{-- ─── Item Picker Modal ──────────────────────────────────────────────── --}}
<div id="item-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="item-modal-title">
    <div id="item-modal">
        <div id="item-modal-header">
            <span id="item-modal-title">Add food items</span>
            <button id="item-modal-close" aria-label="Close" onclick="closeItemModal()">&times;</button>
        </div>
        <div id="item-modal-tabs" style="display:none">
            <button class="im-tab active" id="tab-library">📋 My Library</button>
        </div>
        <div id="item-modal-search-wrap">
            <input type="text" id="item-modal-search" placeholder="Search library or food database…" autocomplete="off" spellcheck="false">
            <span id="item-modal-search-spinner" style="display:none;position:absolute;right:.75rem;top:50%;transform:translateY(-50%);font-size:.75rem;color:var(--text-muted)">⏳</span>
        </div>
        <div id="item-modal-body"></div>
        <div id="item-modal-footer">
            <span id="item-modal-selected-count"></span>
            <div class="im-footer-btns">
                <button type="button" id="item-modal-cancel"  onclick="closeItemModal()">Cancel</button>
                <button type="button" id="item-modal-confirm" onclick="confirmItemModal()">Done</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {

    /* ── Data ─────────────────────────────────────────────────────────── */
    const libraryItems = @json($jsItems);   // [{value,text,group}, ...]
    const slotLimits   = @json($slotLimits); // {breakfast: 3, lunch: 4, ...} or null = no limit

    /* Build grouped structure */
    const groups = {};
    libraryItems.forEach(function (item) {
        if (!groups[item.group]) groups[item.group] = [];
        groups[item.group].push(item);
    });
    const groupNames = Object.keys(groups);

    /* id → name map */
    const idToName = {};
    libraryItems.forEach(function (i) { idToName[i.value] = i.text; });

    /* ── Per-cell state: Map of "day_slot" → [{id, text}, ...] ───────── */
    const cellState = {};

    /* ── Render tags inside a cell from its state ─────────────────────── */
    function renderCellTags(day, slot) {
        const key      = day + '_' + slot;
        const items    = cellState[key] || [];
        const td       = document.querySelector('td.cell[data-day="' + day + '"][data-slot="' + slot + '"]');
        const tagsDiv  = document.getElementById('tags_' + day + '_' + slot);
        const hiddenEl = document.getElementById('cell_' + day + '_' + slot);
        if (!tagsDiv || !hiddenEl || !td) return;

        const tagBg     = td.dataset.tagBg     || '#fff7ed';
        const tagBorder = td.dataset.tagBorder  || '#fed7aa';
        const tagText   = td.dataset.tagText    || '#c2410c';

        tagsDiv.innerHTML = '';

        items.forEach(function (item, idx) {
            const displayText = item.text || (item.id ? (idToName[item.id] || item.id) : '');
            if (!displayText) return;
            const tag = document.createElement('span');
            tag.className = 'cell-tag';
            tag.style.cssText = 'background:' + tagBg + ';color:' + tagText + ';border-color:' + tagBorder;
            tag.innerHTML = '<span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:90px">' + escHtml(displayText) + '</span>'
                          + '<button class="cell-tag-remove" data-day="' + day + '" data-slot="' + slot + '" data-idx="' + idx + '" title="Remove" onclick="removeItem(this,event)" type="button">×</button>';
            tagsDiv.appendChild(tag);
        });

        /* + Add button */
        const addBtn = document.createElement('button');
        addBtn.type = 'button';
        addBtn.className = 'cell-add-btn';
        addBtn.textContent = '+ Add';
        addBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            openItemModal(td);
        });
        tagsDiv.appendChild(addBtn);

        /* Sync hidden input */
        hiddenEl.value = JSON.stringify(items);
    }

    /* ── Remove a single tag ──────────────────────────────────────────── */
    window.removeItem = function (btn, e) {
        e.stopPropagation();
        const day  = btn.dataset.day;
        const slot = btn.dataset.slot;
        const idx  = parseInt(btn.dataset.idx, 10);
        const key  = day + '_' + slot;
        cellState[key].splice(idx, 1);
        renderCellTags(day, slot);
    };

    /* ── Modal state ──────────────────────────────────────────────────── */
    let _activeDay  = null;
    let _activeSlot = null;
    let _selected   = {};   // value → {id, text}

    /* ── Open modal ───────────────────────────────────────────────────── */
    window.openItemModal = function (td) {
        _activeDay  = td.dataset.day;
        _activeSlot = td.dataset.slot;
        const key   = _activeDay + '_' + _activeSlot;

        /* Seed selected from current cell state */
        _selected = {};
        (cellState[key] || []).forEach(function (item) {
            const val = item.id || ('_free_' + item.text);
            _selected[val] = item;
        });

        /* Update title */
        const slotLabel = _activeSlot.charAt(0).toUpperCase() + _activeSlot.slice(1);
        const limit     = slotLimits[_activeSlot] ?? null;
        const titleEl   = document.getElementById('item-modal-title');
        titleEl.textContent = 'Add items – ' + slotLabel + ' · Day ' + (parseInt(_activeDay, 10) + 1)
            + (limit !== null ? ' (max ' + limit + ')' : '');

        /* Reset to library tab */
        document.getElementById('item-modal-search').placeholder = 'Search library or food database…';
        document.getElementById('item-modal-search').value = '';
        _fsResults = [];
        clearTimeout(_fsDebounce);
        setSpinner(false);
        renderModalBody('');
        updateSelectedCount();

        const overlay = document.getElementById('item-modal-overlay');
        overlay.classList.add('open');
        setTimeout(function () { document.getElementById('item-modal-search').focus(); }, 60);
    };

    /* ── Render modal body ────────────────────────────────────────────── */
    function renderModalBody(query) {
        renderLibraryBody(query);
    }

    /* ── Library tab body ─────────────────────────────────────────────── */
    function renderLibraryBody(query) {
        const body = document.getElementById('item-modal-body');
        body.innerHTML = '';
        const q = query.toLowerCase().trim();
        let totalVisible = 0;

        groupNames.forEach(function (groupName) {
            const filtered = groups[groupName].filter(function (item) {
                return !q || item.text.toLowerCase().includes(q);
            });
            if (filtered.length === 0) return;
            totalVisible += filtered.length;

            const grpDiv = document.createElement('div');
            grpDiv.className = 'im-group';

            const hdr = document.createElement('div');
            hdr.className = 'im-group-header';
            hdr.textContent = groupName;
            grpDiv.appendChild(hdr);

            filtered.forEach(function (item) {
                grpDiv.appendChild(makeLibraryRow(item));
            });

            body.appendChild(grpDiv);
        });

        /* Custom / free-text items already selected (not in library) */
        const freeItems = Object.values(_selected).filter(function (it) { return !it.id || it.id.startsWith('_free_'); });
        if (freeItems.length > 0 && q === '') {
            const grpDiv = document.createElement('div');
            grpDiv.className = 'im-group';
            const hdr = document.createElement('div');
            hdr.className = 'im-group-header';
            hdr.textContent = '✏ Custom Entry';
            grpDiv.appendChild(hdr);
            freeItems.forEach(function (item) {
                const val = item.id || ('_free_' + item.text);
                const row = document.createElement('label');
                row.className = 'im-option selected';
                row.innerHTML = '<input type="checkbox" checked><div class="im-option-label"><span class="im-option-name">' + escHtml(item.text) + '</span></div>';
                row.querySelector('input').addEventListener('change', function (e) {
                    if (!e.target.checked) {
                        delete _selected[val];
                        row.remove();
                        updateSelectedCount();
                    }
                });
                grpDiv.appendChild(row);
            });
            body.appendChild(grpDiv);
            totalVisible += freeItems.length;
        }

        if (totalVisible === 0) {
            const noRes = document.createElement('div');
            noRes.className = 'im-no-results';
            if (q) {
                noRes.innerHTML = 'No results for "<strong>' + escHtml(q) + '</strong>"'
                    + '<br><button type="button" class="mp-btn mp-btn-green" style="margin-top:.65rem;font-size:.75rem" onclick="addCustomFromModal()">+ Add "' + escHtml(q) + '" as custom item</button>';
            } else {
                noRes.textContent = 'No food items in library.';
            }
            body.appendChild(noRes);
        }

        // Append FatSecret results below library results
        appendFatSecretResults(body, q);
    }

    function makeLibraryRow(item) {
        const isChecked = !!_selected[item.value];
        const row = document.createElement('label');
        row.className = 'im-option' + (isChecked ? ' selected' : '');
        row.innerHTML = '<input type="checkbox" value="' + escHtml(item.value) + '"'
            + (isChecked ? ' checked' : '') + '>'
            + '<div class="im-option-label"><span class="im-option-name">' + escHtml(item.text) + '</span></div>';
        row.querySelector('input').addEventListener('change', function (e) {
            if (e.target.checked) {
                const limit = _activeSlot ? (slotLimits[_activeSlot] ?? null) : null;
                if (limit !== null && Object.keys(_selected).length >= limit) {
                    e.target.checked = false;
                    row.classList.remove('selected');
                    return;
                }
                _selected[item.value] = { id: item.value, text: item.text || idToName[item.value] || item.value };
                row.classList.add('selected');
            } else {
                delete _selected[item.value];
                row.classList.remove('selected');
            }
            updateSelectedCount();
        });
        return row;
    }

    /* ── Add custom item from search ──────────────────────────────────── */
    window.addCustomFromModal = function () {
        const q = document.getElementById('item-modal-search').value.trim();
        if (!q) return;
        const val = '_free_' + Date.now() + '_' + q;
        _selected[val] = { id: null, text: q };
        updateSelectedCount();
        document.getElementById('item-modal-search').value = '';
        renderModalBody('');
    };

    /* ── Update selected count label ──────────────────────────────────── */
    function updateSelectedCount() {
        const n       = Object.keys(_selected).length;
        const limit   = _activeSlot ? (slotLimits[_activeSlot] ?? null) : null;
        const countEl = document.getElementById('item-modal-selected-count');
        const confirmBtn = document.getElementById('item-modal-confirm');

        if (limit !== null) {
            const remaining = limit - n;
            if (remaining < 0) {
                countEl.innerHTML = '<span style="color:#b91c1c;font-weight:700">' + n + ' selected — ' + Math.abs(remaining) + ' over limit of ' + limit + '</span>';
                confirmBtn.disabled = true;
                confirmBtn.style.opacity = '.45';
                confirmBtn.style.cursor  = 'not-allowed';
            } else {
                countEl.innerHTML = n + ' selected&nbsp;<span style="color:' + (remaining === 0 ? '#15803d' : 'var(--text-muted)') + ';font-weight:700">' + remaining + ' remaining of ' + limit + '</span>';
                confirmBtn.disabled = false;
                confirmBtn.style.opacity = '1';
                confirmBtn.style.cursor  = 'pointer';
            }
        } else {
            countEl.textContent = n === 0 ? '' : n + ' item' + (n > 1 ? 's' : '') + ' selected';
            confirmBtn.disabled = false;
            confirmBtn.style.opacity = '1';
            confirmBtn.style.cursor  = 'pointer';
        }
    }

    /* ── Confirm selection ────────────────────────────────────────────── */
    window.confirmItemModal = function () {
        if (_activeDay === null) return;
        const key = _activeDay + '_' + _activeSlot;
        cellState[key] = Object.values(_selected);
        renderCellTags(_activeDay, _activeSlot);
        closeItemModal();
    };

    /* ── Close modal ──────────────────────────────────────────────────── */
    window.closeItemModal = function () {
        document.getElementById('item-modal-overlay').classList.remove('open');
        _activeDay = null; _activeSlot = null; _selected = {};
    };

    /* Close on overlay click */
    document.getElementById('item-modal-overlay').addEventListener('click', function (e) {
        if (e.target === this) closeItemModal();
    });

    /* Close on Escape */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeItemModal();
    });

    /* ── FatSecret search state ──────────────────────────────────────── */
    let _fsDebounce  = null;
    let _fsResults   = [];   // latest FatSecret results
    let _fsSearching = false;

    const SEARCH_URL = '{{ route("meal-items.search") }}';
    const IMPORT_URL = '{{ route("meal-items.import-fatsecret") }}';
    const CSRF       = document.querySelector('meta[name="csrf-token"]')?.content || '';

    /* ── Live search: DB instantly + FatSecret debounced ─────────────── */
    document.getElementById('item-modal-search').addEventListener('input', function () {
        const q = this.value.trim();
        clearTimeout(_fsDebounce);
        _fsResults = [];

        // Always re-render library portion immediately
        renderLibraryBody(q);

        if (q.length < 2) {
            setSpinner(false);
            return;
        }

        // Debounce the AJAX call by 400ms
        _fsDebounce = setTimeout(function () {
            setSpinner(true);
            fetch(SEARCH_URL + '?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                setSpinner(false);
                _fsResults = data.fs || [];
                // Re-render with FatSecret section appended
                renderLibraryBody(document.getElementById('item-modal-search').value.trim());
            })
            .catch(function () { setSpinner(false); });
        }, 400);
    });

    function setSpinner(on) {
        const el = document.getElementById('item-modal-search-spinner');
        if (el) el.style.display = on ? 'inline' : 'none';
        _fsSearching = on;
    }

    /* ── Append FatSecret results to modal body ───────────────────────── */
    function appendFatSecretResults(body, q) {
        if (_fsResults.length === 0) return;

        const grpDiv = document.createElement('div');
        grpDiv.className = 'im-group';

        const hdr = document.createElement('div');
        hdr.className = 'im-group-header';
        hdr.style.cssText = 'background:#eff6ff;color:#1d4ed8;border-bottom:1px solid #bfdbfe';
        hdr.innerHTML = '🌐 Food Database results <span style="font-weight:400;font-size:.68rem">(tap to add to library &amp; plan)</span>';
        grpDiv.appendChild(hdr);

        const alreadyImportedNames = libraryItems.map(function (i) { return i.text.toLowerCase(); });

        _fsResults.forEach(function (food) {
            if (alreadyImportedNames.includes(food.name.toLowerCase())) return;

            const row = document.createElement('label');
            row.className = 'im-option';
            row.style.cursor = 'pointer';

            const kcalStr = food.kcal ? food.kcal + ' kcal' : '';
            const servStr = food.serving ? ' · ' + food.serving : '';
            row.innerHTML =
                '<input type="checkbox" value="' + escHtml(food.id) + '">' +
                '<div class="im-option-label">' +
                  '<span class="im-option-name">' + escHtml(food.name) + '</span>' +
                  (kcalStr || servStr ? '<span style="font-size:.7rem;color:var(--text-muted);margin-left:.4rem">' + escHtml(kcalStr + servStr) + '</span>' : '') +
                  '<span style="font-size:.65rem;color:#2563eb;margin-left:.4rem;font-weight:600">+ library</span>' +
                '</div>';

            row.querySelector('input').addEventListener('change', function (e) {
                if (!e.target.checked) { delete _selected[food.id]; row.classList.remove('selected'); updateSelectedCount(); return; }

                const limit = _activeSlot ? (slotLimits[_activeSlot] ?? null) : null;
                if (limit !== null && Object.keys(_selected).length >= limit) {
                    e.target.checked = false; return;
                }

                // Optimistically select it
                row.classList.add('selected');
                e.target.disabled = true;

                // Import into meal library
                const payload = new URLSearchParams({
                    _token:  CSRF,
                    name:    food.name,
                    serving: food.serving  || '',
                    kcal:    food.kcal     || '',
                    kj:      food.kj       || '',
                    fat:     food.fat      || '',
                    carbs:   food.carbs    || '',
                    protein: food.protein  || '',
                    fiber:   food.fiber    || '',
                });

                fetch(IMPORT_URL, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json' },
                    body: payload.toString()
                })
                .then(function (r) { return r.json(); })
                .then(function (saved) {
                    // Add to local library so subsequent searches find it
                    const newItem = { value: String(saved.id), text: saved.name, group: saved.category || 'Online' };
                    libraryItems.push(newItem);
                    idToName[newItem.value] = newItem.text;
                    if (!groups[newItem.group]) { groups[newItem.group] = []; groupNames.push(newItem.group); }
                    groups[newItem.group].push(newItem);

                    _selected[newItem.value] = { id: newItem.value, text: newItem.text };
                    // Remove from FatSecret results to avoid duplicate
                    _fsResults = _fsResults.filter(function (f) { return f.id !== food.id; });
                    updateSelectedCount();
                    renderLibraryBody(document.getElementById('item-modal-search').value.trim());
                })
                .catch(function () {
                    // If import fails still allow as free-text
                    const val = '_free_' + Date.now();
                    _selected[val] = { id: null, text: food.name };
                    updateSelectedCount();
                });
            });

            grpDiv.appendChild(row);
        });

        if (grpDiv.children.length > 1) body.appendChild(grpDiv);
    }

    /* ── Initialise cells from hidden inputs ──────────────────────────── */
    document.querySelectorAll('td.cell').forEach(function (td) {
        const day  = td.dataset.day;
        const slot = td.dataset.slot;
        const key  = day + '_' + slot;
        const hiddenEl = document.getElementById('cell_' + day + '_' + slot);
        try {
            const parsed = JSON.parse(hiddenEl ? hiddenEl.value : '[]');
            cellState[key] = Array.isArray(parsed) ? parsed.map(function (item) {
                /* Ensure text is always populated — fall back to idToName lookup */
                return {
                    id:   item.id   || null,
                    text: item.text || (item.id ? (idToName[item.id] || '') : '')
                };
            }).filter(function (item) { return item.text || item.id; }) : [];
        } catch (e) {
            cellState[key] = [];
        }
        renderCellTags(day, slot);
    });

    /* ── Safety-net sync on submit ────────────────────────────────────── */
    document.getElementById('mp-form').addEventListener('submit', function () {
        document.querySelectorAll('td.cell').forEach(function (td) {
            const day  = td.dataset.day;
            const slot = td.dataset.slot;
            const key  = day + '_' + slot;
            const hiddenEl = document.getElementById('cell_' + day + '_' + slot);
            if (hiddenEl) hiddenEl.value = JSON.stringify(cellState[key] || []);
        });
    });

    /* ── Utility: HTML escape ─────────────────────────────────────────── */
    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

}());
</script>
</x-app-layout>
