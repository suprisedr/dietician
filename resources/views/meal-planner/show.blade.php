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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">

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
#mp-grid { width:100%; border-collapse:collapse; min-width:900px; table-layout:fixed; }

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

/* Data cells */
#mp-grid td.cell {
    padding:.25rem .2rem;
    border-bottom:1px solid var(--border);
    border-left:1px solid #f0f0f0;
    vertical-align:top;
    min-width:120px;
}
#mp-grid tbody tr:last-child td { border-bottom:none; }
#mp-grid tbody tr:hover td.cell { background-color:rgba(0,0,0,.018) !important; }

/* ─── Tom Select – multi tag mode ───────────────────────────── */
.ts-wrapper.multi { min-height:0 !important; }

.ts-wrapper.multi .ts-control {
    border:1px solid transparent !important;
    border-radius:6px !important;
    padding:.22rem .3rem .22rem .3rem !important;
    font-size:.72rem !important;
    min-height:30px !important;
    background:transparent !important;
    box-shadow:none !important;
    gap:3px !important;
    cursor:text;
    flex-wrap:wrap;
    transition:border-color .15s, background .15s;
}
.ts-wrapper.multi.focus .ts-control,
.ts-wrapper.multi .ts-control:hover {
    border-color:rgba(0,0,0,.15) !important;
    background:#fff !important;
    box-shadow:0 0 0 3px rgba(249,115,22,.12) !important;
}

/* Tags */
.ts-control .item {
    border-radius:5px !important;
    padding:.12rem .4rem !important;
    font-size:.68rem !important;
    font-weight:600 !important;
    line-height:1.45 !important;
    margin:2px 1px !important;
    display:inline-flex !important;
    align-items:center !important;
    gap:3px !important;
    max-width:140px !important;
    overflow:hidden !important;
    text-overflow:ellipsis !important;
    white-space:nowrap !important;
}
/* Remove (×) button inside tag */
.ts-control .item .remove {
    opacity:.55; font-size:.75rem !important;
    padding:0 !important; border-left:none !important;
    margin-left:2px !important;
}
.ts-control .item .remove:hover { opacity:1; }

/* Search input */
.ts-control > input {
    font-size:.72rem !important;
    min-width:55px !important;
    color:var(--text-primary);
}
.ts-control::after { display:none !important; } /* hide caret arrow */

/* Dropdown */
.ts-dropdown {
    font-size:.77rem !important;
    border-radius:10px !important;
    border:1px solid var(--border) !important;
    box-shadow:0 8px 28px rgba(0,0,0,.13) !important;
    z-index:9999 !important;
    min-width:230px !important;
    overflow:hidden;
}
.ts-dropdown .ts-dropdown-content { max-height:230px !important; overflow-y:auto; }
.ts-dropdown .optgroup-header {
    font-size:.66rem !important; font-weight:800 !important;
    color:var(--primary) !important; background:#fff7ed !important;
    padding:.32rem .8rem !important; text-transform:uppercase; letter-spacing:.06em;
    border-top:1px solid #fde8d0;
}
.ts-dropdown .optgroup:first-child .optgroup-header { border-top:none; }
.ts-dropdown .option {
    padding:.34rem .85rem !important; font-size:.74rem !important;
    color:var(--text-primary); cursor:pointer;
}
.ts-dropdown .option.active { background:#fff7ed !important; color:var(--text-primary) !important; }
.ts-dropdown .option:hover  { background:#fef9f5 !important; }
.ts-dropdown .create        { background:#f0fdf4 !important; color:#15803d !important; font-weight:600; }
.ts-dropdown .no-results    { font-size:.73rem !important; color:var(--text-muted) !important; }

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
.mp-monthly-table { width:100%; border-collapse:collapse; min-width:700px; font-size:.74rem; }
.mp-monthly-table th { padding:.45rem .65rem; background:var(--indigo); color:#fff; text-align:center; font-size:.71rem; font-weight:700; }
.mp-monthly-table th:first-child { text-align:left; }
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
            <a href="{{ route('grocery-lists.create') }}" class="mp-btn mp-btn-indigo">🛒 Grocery List</a>
            <a href="{{ route('pantry.index') }}"         class="mp-btn mp-btn-green">📦 Pantry</a>
            <a href="{{ route('recipes.index') }}"        class="mp-btn mp-btn-purple">📋 Recipes</a>
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
        <span class="mp-legend-hint">🔍 Search &amp; add multiple items per cell</span>
    </div>

    {{-- Grid Form --}}
    <form id="mp-form" method="POST" action="{{ route('meal-planner.save-entries', $mealPlanner) }}">
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
                                    <td class="cell">
                                        <input type="hidden"
                                               name="cells[{{ $dayIndex }}][{{ $slot }}]"
                                               id="cell_{{ $dayIndex }}_{{ $slot }}"
                                               value="{{ htmlspecialchars($initialJson, ENT_QUOTES) }}">

                                        <select id="ts_{{ $dayIndex }}_{{ $slot }}"
                                                class="mp-ts-select"
                                                data-day="{{ $dayIndex }}"
                                                data-slot="{{ $slot }}"
                                                data-initial="{{ htmlspecialchars($initialJson, ENT_QUOTES) }}"
                                                data-tag-bg="{{ $theme['tag_bg'] }}"
                                                data-tag-border="{{ $theme['tag_border'] }}"
                                                data-tag-text="{{ $theme['tag_text'] }}"
                                                multiple autocomplete="off">
                                        </select>
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
    <details class="mp-details-card">
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
                        @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
                            <th>{{ $d }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyWeeks as $wk)
                        @php $wkGrid = $wk->grid; @endphp
                        @foreach(['breakfast','lunch','dinner','snack1'] as $sl)
                            @php $colors = ['breakfast'=>'#c2410c','lunch'=>'#1d4ed8','dinner'=>'#6d28d9','snack1'=>'#15803d']; @endphp
                            <tr style="{{ $loop->first ? 'border-top:2px solid var(--primary)' : '' }}">
                                @if($loop->first)
                                    <td rowspan="4" style="padding:.4rem .6rem;font-weight:700;vertical-align:top;background:#f8fafc;border-right:1px solid var(--border)">
                                        <span style="display:block;font-size:.65rem;color:var(--text-muted)">Week {{ $loop->parent->iteration }}</span>
                                        {{ $wk->week_start->format('d M') }}
                                    </td>
                                @endif
                                @foreach(range(0,6) as $d)
                                    @php $cellArr = $wkGrid[$d][$sl]; @endphp
                                    <td style="color:{{ $colors[$sl] ?? '#333' }}">
                                        @foreach($cellArr as $ce)
                                            <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $ce->meal_text }}</div>
                                        @endforeach
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

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
(function () {
    const libraryItems = @json($jsItems);

    /* Build optgroups */
    const groupOrder = [], seenGroups = {};
    libraryItems.forEach(function (item) {
        if (!seenGroups[item.group]) { seenGroups[item.group] = 1; groupOrder.push(item.group); }
    });
    const optgroups = groupOrder.map(function (g) { return { value: g, label: g }; });
    optgroups.push({ value: '_custom', label: '✏  Custom Entry' });

    /* id → name map */
    const idToName = {};
    libraryItems.forEach(function (i) { idToName[i.value] = i.text; });

    /* Shared syncHidden */
    function syncHidden(ts, cellEl) {
        if (!cellEl) return;
        const payload = ts.items.map(function (val) {
            if (val.startsWith('_free_')) {
                const opt = ts.options[val];
                return { id: null, text: opt ? opt.text : '' };
            }
            return { id: val, text: idToName[val] || '' };
        });
        cellEl.value = JSON.stringify(payload);
    }

    document.querySelectorAll('.mp-ts-select').forEach(function (el) {
        const day     = el.dataset.day;
        const slot    = el.dataset.slot;
        const cellEl  = document.getElementById('cell_' + day + '_' + slot);
        const initial = JSON.parse(el.dataset.initial || '[]');

        /* Tag colours from data attributes */
        const tagBg     = el.dataset.tagBg     || '#fff7ed';
        const tagBorder = el.dataset.tagBorder  || '#fed7aa';
        const tagText   = el.dataset.tagText    || '#c2410c';

        /* Pre-selected values + extra options for free-text entries */
        const extraOptions = [], initValues = [];
        initial.forEach(function (entry, idx) {
            if (entry.id) {
                initValues.push(entry.id);
            } else if (entry.text) {
                const key = '_free_' + idx + '_' + entry.text;
                extraOptions.push({ value: key, text: entry.text, group: '_custom' });
                initValues.push(key);
            }
        });

        const ts = new TomSelect(el, {
            options:       libraryItems.concat(extraOptions),
            optgroups:     optgroups,
            optgroupField: 'group',
            valueField:    'value',
            labelField:    'text',
            searchField:   ['text'],
            plugins:       ['remove_button'],
            create: function (input) {
                return { value: '_free_new_' + Date.now() + '_' + input, text: input, group: '_custom' };
            },
            createOnBlur:  true,
            delimiter:     ',',
            placeholder:   'Add items…',
            maxOptions:    null,
            items:         initValues,
            onItemAdd:     function () { syncHidden(ts, cellEl); },
            onItemRemove:  function () { syncHidden(ts, cellEl); },
            render: {
                item: function (data, escape) {
                    return '<div style="background:' + tagBg + ';color:' + tagText + ';border:1px solid ' + tagBorder + '">' + escape(data.text) + '</div>';
                }
            }
        });

        syncHidden(ts, cellEl);
    });

    /* Safety-net sync on submit */
    document.getElementById('mp-form').addEventListener('submit', function () {
        document.querySelectorAll('.mp-ts-select').forEach(function (el) {
            const ts     = el.tomselect;
            const cellEl = document.getElementById('cell_' + el.dataset.day + '_' + el.dataset.slot);
            if (ts && cellEl) syncHidden(ts, cellEl);
        });
    });
}());
</script>
</x-app-layout>
