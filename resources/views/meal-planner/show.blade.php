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
                'cho'     => $item->cho_g,
                'pro'     => $item->protein_g,
                'fat'     => $item->fat_g,
                'fib'     => $item->fiber_g,
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

/* ── Daily macro summary bar ─────────────────────────────── */
.mp-macro-card {
    background:#fff; border:1px solid var(--border); border-radius:10px;
    overflow:hidden; margin-bottom:1.25rem; overflow-x:auto; -webkit-overflow-scrolling:touch;
}
.mp-macro-table {
    width:100%; border-collapse:collapse; min-width:640px;
}
.mp-macro-th-label {
    padding:.42rem .75rem; font-size:.7rem; font-weight:800;
    color:#fff; background:var(--indigo); text-align:left;
    border-right:2px solid rgba(255,255,255,.2); white-space:nowrap;
}
.mp-macro-th-day {
    padding:.42rem .35rem; font-size:.68rem; font-weight:700;
    color:#fff; background:var(--indigo); text-align:center;
    border-right:1px solid rgba(255,255,255,.12);
}
.mp-macro-th-day:last-child { border-right:none; }
.mp-macro-td-label {
    padding:.28rem .7rem; font-size:.67rem; font-weight:700;
    color:var(--text-muted); white-space:nowrap; background:#fafafa;
    border-right:2px solid var(--border); border-top:1px solid #f0f0f0;
    vertical-align:middle;
}
.mp-macro-dot {
    display:inline-block; width:6px; height:6px; border-radius:50%;
    margin-right:3px; vertical-align:middle;
}
.mp-macro-td {
    padding:.3rem .35rem; font-size:.72rem; font-weight:700;
    color:#d1d5db; text-align:center;
    border-right:1px solid #f0f0f0; border-top:1px solid #f0f0f0;
    vertical-align:middle;
}
.mp-macro-td:last-child { border-right:none; }
.mp-macro-td.has-val { color:var(--text-primary); }
.macro-row-kj  .mp-macro-td.has-val { color:#9a3412; }
.macro-row-cho .mp-macro-td.has-val { color:#92400e; }
.macro-row-pro .mp-macro-td.has-val { color:#3730a3; }
.macro-row-fat .mp-macro-td.has-val { color:#0f766e; }

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
.cell-tag-qty-row { display:flex; align-items:center; gap:2px; margin-top:2px; }
.cell-tag-qty-btn {
    background:rgba(0,0,0,.08); border:none; border-radius:4px; cursor:pointer;
    width:18px; height:18px; font-size:.65rem; line-height:1; display:flex;
    align-items:center; justify-content:center; color:inherit; padding:0;
}
.cell-tag-qty-btn:hover { background:rgba(0,0,0,.16); }
.cell-tag-qty-inp {
    font-size:.68rem; font-weight:700; width:36px; text-align:center;
    border:1px solid rgba(0,0,0,.12); border-radius:4px; padding:0 2px;
    height:18px; background:transparent; color:inherit;
    -moz-appearance:textfield;
}
.cell-tag-qty-inp::-webkit-inner-spin-button,
.cell-tag-qty-inp::-webkit-outer-spin-button { -webkit-appearance:none; margin:0; }
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

/* ── PDF Preview Modal ───────────────────────────────────── */
#pdf-preview-overlay {
    display:none; position:fixed; inset:0; z-index:10100;
    background:rgba(0,0,0,.6); backdrop-filter:blur(3px);
    align-items:center; justify-content:center;
}
#pdf-preview-overlay.open { display:flex; }
#pdf-preview-modal {
    background:#1e1e2e; border-radius:14px;
    width:min(960px,96vw); height:min(86vh,820px);
    display:flex; flex-direction:column;
    box-shadow:0 32px 80px rgba(0,0,0,.5);
    overflow:hidden; animation:modalIn .18s ease;
}
#pdf-preview-hdr {
    display:flex; align-items:center; justify-content:space-between;
    padding:.65rem 1rem; background:#2d2d3f; flex-shrink:0;
    border-bottom:1px solid rgba(255,255,255,.08);
}
#pdf-preview-hdr-title {
    font-size:.82rem; font-weight:700; color:#e2e8f0;
    display:flex; align-items:center; gap:.5rem;
}
#pdf-preview-hdr-actions { display:flex; align-items:center; gap:.5rem; }
.pdf-preview-dl-btn {
    background:#4f46e5; color:#fff; border:none; border-radius:7px;
    font-size:.78rem; font-weight:700; padding:.35rem .85rem;
    cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:.35rem;
    transition:background .15s;
}
.pdf-preview-dl-btn:hover { background:#4338ca; color:#fff; }
#pdf-preview-close {
    background:none; border:none; cursor:pointer; font-size:1.3rem;
    color:#94a3b8; padding:.1rem .3rem; border-radius:6px; transition:background .15s; line-height:1;
}
#pdf-preview-close:hover { background:rgba(255,255,255,.1); color:#e2e8f0; }
#pdf-preview-frame {
    flex:1; width:100%; border:none; background:#525659;
}
#pdf-preview-loading {
    position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
    background:#1e1e2e; color:#94a3b8; font-size:.85rem; gap:.6rem;
    pointer-events:none;
}
#pdf-preview-frame-wrap { position:relative; flex:1; display:flex; flex-direction:column; }
#mp-modal {
    background:#fff; border-radius:16px;
    width:min(600px,97vw); max-height:84vh;
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
.im-option-label { display:flex; flex-direction:column; gap:2px; flex:1; min-width:0; }
.im-option-name  { font-weight:600; line-height:1.3; }
.im-option-desc  { font-size:.71rem; color:var(--text-muted); }
.im-option-macros { display:flex; flex-wrap:wrap; gap:3px; margin-top:2px; }
.im-macro-chip {
    display:inline-flex; gap:2px; align-items:baseline;
    padding:.1rem .35rem; border-radius:4px; font-size:.64rem; font-weight:700;
}
.im-macro-chip.kj  { background:#fff1e6; color:#c2410c; }
.im-macro-chip.cho { background:#fef9c3; color:#a16207; }
.im-macro-chip.pro { background:#ede9fe; color:#5b21b6; }
.im-macro-chip.fat { background:#ccfbf1; color:#0f766e; }
.im-macro-chip-lbl { font-weight:400; opacity:.75; }
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

/* ── FatSecret Portion Modal ─────────────────────────────────── */
#fs-portion-overlay {
    display:none; position:fixed; inset:0; z-index:10100;
    background:rgba(0,0,0,.5); backdrop-filter:blur(2px);
    align-items:center; justify-content:center;
}
#fs-portion-overlay.open { display:flex; }
#fs-portion-modal {
    background:#fff; border-radius:14px;
    width:min(420px,95vw); max-height:90vh; overflow-y:auto;
    box-shadow:0 24px 60px rgba(0,0,0,.25);
    animation:modalIn .18s ease;
    padding:0;
}
#fs-portion-hdr {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 1.1rem .6rem; border-bottom:1px solid var(--border);
}
#fs-portion-title { font-size:.88rem; font-weight:800; color:var(--text-primary); }
#fs-portion-close {
    background:none; border:none; cursor:pointer; font-size:1.25rem;
    color:var(--text-muted); padding:.1rem .3rem; border-radius:6px;
}
#fs-portion-close:hover { background:#f1f5f9; }
#fs-portion-body { padding:1rem 1.1rem; }
.fp-food-name { font-size:.95rem; font-weight:800; color:var(--text-primary); margin-bottom:.15rem; }
.fp-serving-orig { font-size:.73rem; color:var(--text-muted); margin-bottom:1rem; }
.fp-multiplier-row {
    display:flex; align-items:center; gap:.6rem; margin-bottom:1rem;
}
.fp-multiplier-label { font-size:.78rem; font-weight:600; color:var(--text-primary); white-space:nowrap; }
.fp-multiplier-inp {
    width:80px; padding:.4rem .6rem; border:1.5px solid var(--border); border-radius:8px;
    font-size:.9rem; font-weight:700; text-align:center; outline:none;
    -moz-appearance:textfield;
}
.fp-multiplier-inp::-webkit-inner-spin-button,.fp-multiplier-inp::-webkit-outer-spin-button{-webkit-appearance:none;margin:0;}
.fp-multiplier-inp:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(249,115,22,.12); }
.fp-serving-desc { font-size:.78rem; color:var(--text-muted); }
.fp-nutrients {
    background:#f8fafc; border:1px solid var(--border); border-radius:10px;
    padding:.65rem .85rem; display:grid; grid-template-columns:1fr 1fr 1fr;
    gap:.4rem .2rem; margin-bottom:1rem;
}
.fp-nut-cell { display:flex; flex-direction:column; align-items:center; padding:.3rem .2rem; }
.fp-nut-val  { font-size:.92rem; font-weight:800; color:var(--text-primary); }
.fp-nut-lbl  { font-size:.62rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); }
.fp-nut-cell.nut-kj .fp-nut-val   { color:#c2410c; }
.fp-nut-cell.nut-cho .fp-nut-val  { color:#d97706; }
.fp-nut-cell.nut-pro .fp-nut-val  { color:#6366f1; }
.fp-nut-cell.nut-fat .fp-nut-val  { color:#0d9488; }
.fp-nut-cell.nut-kcal .fp-nut-val { color:#64748b; }
#fs-portion-ftr {
    display:flex; gap:.5rem; justify-content:flex-end;
    padding:.75rem 1.1rem; border-top:1px solid var(--border); background:#fafafa; border-radius:0 0 14px 14px;
}
#fs-portion-cancel {
    padding:.42rem .9rem; border-radius:8px; font-size:.78rem; font-weight:700;
    border:1.5px solid var(--border); background:#fff; cursor:pointer;
}
#fs-portion-cancel:hover { background:#f1f5f9; }
#fs-portion-confirm {
    padding:.42rem 1rem; border-radius:8px; font-size:.78rem; font-weight:700;
    border:none; background:#1d4ed8; color:#fff; cursor:pointer;
}
#fs-portion-confirm:hover { filter:brightness(.92); }
#fs-portion-confirm:disabled { opacity:.5; cursor:not-allowed; }

/* ── Item Edit Modal ─────────────────────────────────────────── */
#item-edit-overlay {
    display:none; position:fixed; inset:0; z-index:10200;
    background:rgba(0,0,0,.5); backdrop-filter:blur(2px);
    align-items:center; justify-content:center;
}
#item-edit-overlay.open { display:flex; }
#item-edit-modal {
    background:#fff; border-radius:14px;
    width:min(380px,95vw); box-shadow:0 24px 60px rgba(0,0,0,.25);
    animation:modalIn .18s ease;
}
#item-edit-hdr {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 1.1rem .6rem; border-bottom:1px solid var(--border);
}
#item-edit-hdr-title { font-size:.88rem; font-weight:800; color:var(--text-primary); }
#item-edit-close-btn {
    background:none; border:none; cursor:pointer; font-size:1.25rem;
    color:var(--text-muted); padding:.1rem .3rem; border-radius:6px;
}
#item-edit-close-btn:hover { background:#f1f5f9; }
#item-edit-body { padding:1rem 1.1rem; }
.ie-food-name { font-size:.93rem; font-weight:800; color:var(--text-primary); margin-bottom:.9rem; line-height:1.4; }
.ie-field-row { display:flex; flex-direction:column; gap:.3rem; margin-bottom:.85rem; }
.ie-label { font-size:.69rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); }
.ie-qty-row { display:flex; align-items:center; gap:.5rem; }
.ie-qty-btn {
    width:30px; height:30px; border-radius:6px; border:1.5px solid var(--border);
    background:#f8fafc; font-size:1rem; font-weight:700; cursor:pointer; color:var(--text-primary);
    display:flex; align-items:center; justify-content:center; flex-shrink:0; line-height:1;
}
.ie-qty-btn:hover { background:#e2e8f0; }
.ie-qty-inp {
    width:60px; padding:.35rem .5rem; border:1.5px solid var(--border); border-radius:8px;
    font-size:.9rem; font-weight:700; text-align:center; outline:none;
    -moz-appearance:textfield;
}
.ie-qty-inp::-webkit-inner-spin-button,.ie-qty-inp::-webkit-outer-spin-button{-webkit-appearance:none;margin:0;}
.ie-qty-inp:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(249,115,22,.12); }
.ie-note-inp {
    width:100%; padding:.4rem .65rem; border:1.5px solid var(--border); border-radius:8px;
    font-size:.82rem; outline:none; box-sizing:border-box;
}
.ie-note-inp:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(249,115,22,.12); }
#item-edit-ftr {
    display:flex; gap:.5rem; justify-content:flex-end;
    padding:.75rem 1.1rem; border-top:1px solid var(--border); background:#fafafa; border-radius:0 0 14px 14px;
}
#item-edit-cancel-btn {
    padding:.42rem .9rem; border-radius:8px; font-size:.78rem; font-weight:700;
    border:1.5px solid var(--border); background:#fff; cursor:pointer;
}
#item-edit-cancel-btn:hover { background:#f1f5f9; }
#item-edit-save-btn {
    padding:.42rem 1rem; border-radius:8px; font-size:.78rem; font-weight:700;
    border:none; background:var(--primary); color:#fff; cursor:pointer;
}
#item-edit-save-btn:hover { filter:brightness(.92); }
.cell-tag-name { cursor:pointer; }
.cell-tag-name:hover { text-decoration:underline dotted; }
.cell-tag-note { display:block; font-size:.66rem; opacity:.8; margin-top:.1rem; font-style:italic; }

/* ── Template button ──────────────────────────────────────── */
.mp-btn-template { background:linear-gradient(135deg,#0d9488,#059669); color:#fff; }
.mp-btn-template:hover { opacity:.88; }

/* ── Template modal ──────────────────────────────────────── */
#template-overlay {
    display:none; position:fixed; inset:0; z-index:9990;
    background:rgba(0,0,0,.5); backdrop-filter:blur(3px);
    align-items:center; justify-content:center;
}
#template-overlay.is-open { display:flex; }
#template-modal {
    background:#fff; border-radius:14px; width:min(520px,94vw); max-height:82vh;
    display:flex; flex-direction:column;
    box-shadow:0 24px 60px rgba(0,0,0,.25);
    overflow:hidden; animation:modalIn .18s ease;
}
#template-modal-hdr {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 1.2rem; border-bottom:1px solid var(--border,#e5e7eb); flex-shrink:0;
    background:linear-gradient(135deg,#f0fdf4,#ecfdf5);
}
#template-modal-hdr h3 {
    font-size:.92rem; font-weight:800; color:var(--text-primary,#1e293b); margin:0;
    display:flex; align-items:center; gap:.45rem;
}
#template-modal-hdr button {
    background:none; border:none; font-size:1.3rem; cursor:pointer;
    color:#6b7280; padding:.15rem .3rem; border-radius:6px; line-height:1;
    transition:background .12s;
}
#template-modal-hdr button:hover { background:rgba(0,0,0,.06); }
.tpl-list {
    padding:.4rem .5rem; overflow-y:auto; flex:1;
}
.tpl-item {
    display:flex; align-items:center; justify-content:space-between;
    padding:.65rem .9rem; border-radius:10px; cursor:pointer;
    transition:all .12s; border:1.5px solid transparent; margin-bottom:2px;
}
.tpl-item:hover { background:#f0fdf4; border-color:#bbf7d0; }
.tpl-item.is-selected { background:#ecfdf5; border-color:#86efac; }
.tpl-item-name { font-size:.82rem; font-weight:600; color:var(--text-primary,#1e293b); }
.tpl-item-meta {
    display:flex; gap:.4rem; align-items:center;
}
.tpl-item-kcal {
    font-size:.68rem; color:#059669; background:#dcfce7; padding:.15rem .5rem;
    border-radius:20px; font-weight:600;
}
.tpl-item-arrow { color:#9ca3af; font-size:.65rem; transition:transform .12s; }
.tpl-item:hover .tpl-item-arrow { transform:translateX(2px); color:#059669; }
.tpl-confirm {
    padding:.85rem 1.2rem; border-top:1px solid var(--border,#e5e7eb);
    display:none; background:#fefce8; flex-shrink:0;
}
.tpl-confirm p {
    font-size:.78rem; color:#92400e; margin:0 0 .6rem;
    display:flex; align-items:center; gap:.35rem;
}
.tpl-confirm-btns { display:flex; gap:.5rem; }
.tpl-confirm-btns button {
    flex:1; padding:.55rem; border-radius:8px; font-size:.8rem;
    font-weight:700; cursor:pointer; border:none; transition:opacity .12s;
}
.tpl-confirm-btns button:hover { opacity:.85; }
.tpl-confirm-cancel { background:#f3f4f6; color:var(--text-primary,#1e293b); }
.tpl-confirm-apply { background:linear-gradient(135deg,#0d9488,#059669); color:#fff; }

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
.mp-monthly-table td { padding:.32rem .4rem; border:1px solid #f0f0f0; max-width:130px; vertical-align:top; }
.mo-item { margin-bottom:.2rem; line-height:1.35; }
.mo-item:last-child { margin-bottom:0; }
.mo-item-name { font-size:.68rem; font-weight:700; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.mo-item-serving { font-size:.6rem; font-weight:400; color:var(--text-muted); margin-left:.2rem; }
.mo-macros { font-size:.59rem; color:#9ca3af; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:.05rem; }
.mp-flash { padding:.65rem 1rem; background:#dcfce7; color:#15803d; border-radius:8px; font-size:.82rem; font-weight:600; margin-bottom:1.1rem; border:1px solid #86efac; }

/* ── Copy-slot button ────────────────────────────────── */
.copy-slot-btn {
    position:absolute; top:3px; right:3px; z-index:2;
    background:rgba(255,255,255,.6); border:1px solid rgba(0,0,0,.08); border-radius:4px;
    padding:2px 4px; cursor:pointer; opacity:0; transition:opacity .15s;
    color:var(--text-muted); line-height:1; display:flex; align-items:center;
}
.sday-col:hover .copy-slot-btn { opacity:1; }
.copy-slot-btn:hover { background:rgba(255,255,255,.9); color:var(--primary); }

/* ── Copy-day button ─────────────────────────────────── */
.copy-day-btn {
    display:inline-flex; align-items:center; gap:.2rem;
    margin-top:.3rem; padding:.2rem .5rem;
    background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.35);
    border-radius:5px; color:#fff; font-size:.63rem; font-weight:700;
    cursor:pointer; transition:background .15s;
}
.copy-day-btn:hover { background:rgba(255,255,255,.32); }

/* ── Copy popover ────────────────────────────────────── */
#copy-day-popover {
    display:none; position:fixed; z-index:9999;
    background:#fff; border:1px solid var(--border);
    border-radius:10px; box-shadow:0 8px 28px rgba(0,0,0,.18);
    padding:1rem 1.1rem; min-width:200px;
}
#copy-day-popover h4 {
    font-size:.78rem; font-weight:800; color:var(--text-primary);
    margin-bottom:.7rem; display:flex; align-items:center; justify-content:space-between;
}
#copy-day-popover .cp-close {
    background:none; border:none; cursor:pointer; font-size:1rem;
    color:var(--text-muted); line-height:1; padding:0;
}
.cp-day-row {
    display:flex; align-items:center; gap:.5rem;
    padding:.3rem .25rem; border-radius:5px;
    font-size:.78rem; color:var(--text-primary); cursor:pointer;
    transition:background .1s;
}
.cp-day-row:hover { background:#f3f4f6; }
.cp-day-row input { accent-color:var(--primary); }
.cp-day-row.cp-source { opacity:.4; pointer-events:none; }
#copy-day-popover .cp-apply {
    margin-top:.8rem; width:100%;
    padding:.42rem; background:var(--primary); color:#fff;
    border:none; border-radius:7px; font-size:.8rem; font-weight:700;
    cursor:pointer; transition:opacity .15s;
}
#copy-day-popover .cp-apply:hover { opacity:.88; }
#copy-day-popover .cp-note {
    font-size:.67rem; color:var(--text-muted); margin-top:.5rem; text-align:center;
}
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
        <button type="button" class="mp-btn mp-btn-template" onclick="openTemplateModal()" title="Apply a meal plan template to all days">&#x1F4CB; Apply Template</button>
    </div>

    @if(session('success'))
        <div class="mp-flash">&#x2713; {{ session('success') }}</div>
    @endif

    {{-- Daily macro summary bar --}}
    <div class="mp-macro-card">
        <table class="mp-macro-table">
            <thead>
                <tr>
                    <th class="mp-macro-th-label">&#x1F4CA; Daily totals</th>
                    @foreach($days as $di => $dayName)
                        <th class="mp-macro-th-day">{{ $dayName }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr class="mp-macro-row macro-row-kj">
                    <td class="mp-macro-td-label"><span class="mp-macro-dot" style="background:#f97316"></span>kJ</td>
                    @foreach($days as $di => $dayName)
                        <td class="mp-macro-td" id="grand-kj-{{ $di }}">0</td>
                    @endforeach
                </tr>
                <tr class="mp-macro-row macro-row-cho">
                    <td class="mp-macro-td-label"><span class="mp-macro-dot" style="background:#d97706"></span>CHO (g)</td>
                    @foreach($days as $di => $dayName)
                        <td class="mp-macro-td" id="grand-cho-{{ $di }}">0</td>
                    @endforeach
                </tr>
                <tr class="mp-macro-row macro-row-pro">
                    <td class="mp-macro-td-label"><span class="mp-macro-dot" style="background:#6366f1"></span>Protein (g)</td>
                    @foreach($days as $di => $dayName)
                        <td class="mp-macro-td" id="grand-pro-{{ $di }}">0</td>
                    @endforeach
                </tr>
                <tr class="mp-macro-row macro-row-fat">
                    <td class="mp-macro-td-label"><span class="mp-macro-dot" style="background:#0d9488"></span>Fat (g)</td>
                    @foreach($days as $di => $dayName)
                        <td class="mp-macro-td" id="grand-fat-{{ $di }}">0</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
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
                            'qty'     => (float) ($e->qty ?? 1),
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
                        <div class="sgh" style="position:relative">
                            {{ $dayName }}
                            <span class="day-num">{{ $mealPlanner->week_start->addDays($di)->format('d M') }}</span>
                            <button type="button" class="copy-day-btn" title="Copy this day to another day"
                                onclick="openCopyPopover(event,{{ $di }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                Copy
                            </button>
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
                            <div class="sday-col" style="background:{{ $theme['bg'] }};position:relative">
                                <button type="button" class="copy-slot-btn" title="Copy {{ $slotLabels[$slot] }} to other days"
                                    onclick="openCopySlotPopover(event,{{ $di }},'{{ $slot }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                </button>
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
                                    {{-- Catch-all card: items with no matching exchCat --}}
                                    <div class="cat-card"
                                        style="background:#f9fafb;color:#6b7280;border-color:#e5e7eb;border-style:dashed;display:none"
                                        data-day="{{ $di }}"
                                        data-slot="{{ $slot }}"
                                        data-category="Other"
                                        data-cat-slug="__orphan__"
                                        data-qty="0"
                                        data-tag-bg="#f3f4f6"
                                        data-tag-border="#d1d5db"
                                        data-tag-text="#374151"
                                        onclick="openModal(this)"
                                        id="other-card-{{ $di }}-{{ $slot }}">
                                        <div class="cat-card-hdr" style="border-bottom-color:#e5e7eb">
                                            <span class="cat-card-name" style="font-style:italic;font-size:.72rem">Other / General</span>
                                            <span class="cat-card-badge" id="catqty_{{ $di }}_{{ $slot }}___orphan__"></span>
                                        </div>
                                        <div class="cat-card-body">
                                            <div class="cell-tags" id="tags_{{ $di }}_{{ $slot }}___orphan__"></div>
                                        </div>
                                    </div>
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
            <span id="mp-autosave-status" class="mp-save-hint"></span>
            <span style="flex:1"></span>
            @if($mealPlanner->groceryList)
                <a href="{{ route('grocery-lists.show', $mealPlanner->groceryList) }}" class="mp-btn mp-btn-indigo">&#x1F6D2; View Grocery List</a>
            @else
                <button type="button" class="mp-btn mp-btn-indigo" onclick="document.getElementById('grocery-gen-form').submit()">&#x1F6D2; Generate Grocery List</button>
            @endif
            <button type="button" class="mp-btn mp-btn-indigo"
                onclick="openPdfPreview('{{ route('meal-planner.pdf-preview', [$mealPlanner->patient_id ?? 0, $mealPlanner]) }}','{{ route('meal-planner.pdf', [$mealPlanner->patient_id ?? 0, $mealPlanner]) }}')">&#x1F441; Preview PDF</button>
            <button type="button" class="mp-btn" style="background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff"
                onclick="openRepeatModal()">&#x1F501; Repeat Plan</button>
        </div>
    </form>

    @if(!$mealPlanner->groceryList)
    <form id="grocery-gen-form" method="POST" action="{{ route('grocery-lists.generate-from-plan', $mealPlanner) }}" style="display:none">
        @csrf
    </form>
    @endif

    {{-- Monthly Overview --}}
    <details class="mp-details-card" open>
        <summary class="mp-details-summary">
            &#x1F4C5; Monthly Overview
            <span class="chevron">&#9660;</span>
        </summary>
        <div style="padding:1.25rem;overflow-x:auto">
            @php
                $selMonth   = request()->input('month', now()->format('Y-m'));
                // Validate format, fall back to current month
                if (!preg_match('/^\d{4}-\d{2}$/', $selMonth)) { $selMonth = now()->format('Y-m'); }
                $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $selMonth)->startOfMonth();
                $monthEnd   = $monthStart->copy()->endOfMonth();
                $prevMonth  = $monthStart->copy()->subMonth()->format('Y-m');
                $nextMonth  = $monthStart->copy()->addMonth()->format('Y-m');

                // Include weeks whose 7-day span overlaps the selected month
                $monthlyWeeks = \App\Models\MealPlannerWeek::where('user_id', auth()->id())
                    ->when($mealPlanner->patient_id, fn($q) => $q->where('patient_id', $mealPlanner->patient_id))
                    ->where('week_start', '<=', $monthEnd->toDateString())
                    ->where('week_start', '>=', $monthStart->copy()->subDays(6)->toDateString())
                    ->orderBy('week_start')
                    ->get()->load('entries.mealItem');

                $prevUrl = request()->fullUrlWithQuery(['month' => $prevMonth]);
                $nextUrl = request()->fullUrlWithQuery(['month' => $nextMonth]);
            @endphp

            {{-- Month navigator --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
                <a href="{{ $prevUrl }}" style="display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .75rem;border:1.5px solid var(--border);border-radius:8px;font-size:.78rem;font-weight:700;color:var(--text-primary);text-decoration:none;background:#fff" title="Previous month">
                    &#8592; {{ $monthStart->copy()->subMonth()->format('M Y') }}
                </a>
                <span style="font-size:.92rem;font-weight:800;color:var(--text-primary)">
                    {{ $monthStart->format('F Y') }}
                </span>
                <a href="{{ $nextUrl }}" style="display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .75rem;border:1.5px solid var(--border);border-radius:8px;font-size:.78rem;font-weight:700;color:var(--text-primary);text-decoration:none;background:#fff" title="Next month">
                    {{ $monthStart->copy()->addMonth()->format('M Y') }} &#8594;
                </a>
            </div>
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
                                    <td style="color:{{ $c['text'] }};padding:.25rem .35rem;{{ !$anyEntry ? 'opacity:.35' : '' }}">
                                        @forelse($cellArr as $ce)
                                            @php
                                                $mi      = $ce->mealItem;
                                                $qty     = max(0.25, (float)($ce->qty ?? 1));
                                                $qtyLbl  = rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.');
                                                $serving = $mi?->serving_size;
                                                $kj  = $mi?->energy_kj  ? round($mi->energy_kj  * $qty) : null;
                                                $cho = $mi?->cho_g      ? round($mi->cho_g      * $qty, 1) : null;
                                                $pro = $mi?->protein_g  ? round($mi->protein_g  * $qty, 1) : null;
                                                $fat = $mi?->fat_g      ? round($mi->fat_g      * $qty, 1) : null;
                                                $fib = $mi?->fiber_g && $mi->fiber_g > 0 ? round($mi->fiber_g * $qty, 1) : null;
                                                $macroParts = array_filter([
                                                    $kj  ? $kj.'kJ'       : null,
                                                    $cho ? $cho.'g C'     : null,
                                                    $pro ? $pro.'g P'     : null,
                                                    $fat ? $fat.'g F'     : null,
                                                ]);
                                            @endphp
                                            <div class="mo-item">
                                                <div class="mo-item-name">{{ $qty != 1 ? $qtyLbl.'× ' : '' }}{{ $ce->meal_text }}@if($serving)<span class="mo-item-serving">({{ $serving }})</span>@endif</div>
                                                @if($macroParts)
                                                    <div class="mo-macros">{{ implode(' · ', $macroParts) }}</div>
                                                @endif
                                            </div>
                                        @empty
                                            <span style="color:#d1d5db;font-size:.68rem">&mdash;</span>
                                        @endforelse
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                    @if($monthlyWeeks->isEmpty())
                        <tr>
                            <td colspan="9" style="text-align:center;padding:2rem 1rem;color:var(--text-muted);font-size:.82rem">
                                No meal plans found for <strong>{{ $monthStart->format('F Y') }}</strong>.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </details>
</div>

{{-- ── Item Edit Modal ─────────────────────────────────────────── --}}
<div id="item-edit-overlay" role="dialog" aria-modal="true">
    <div id="item-edit-modal">
        <div id="item-edit-hdr">
            <span id="item-edit-hdr-title">&#x270F;&#xFE0F; Edit Item</span>
            <button id="item-edit-close-btn" onclick="closeItemEdit()">&times;</button>
        </div>
        <div id="item-edit-body">
            <div class="ie-food-name" id="item-edit-name"></div>
            <div class="fp-serving-orig" id="item-edit-serving"></div>
            <div class="ie-field-row">
                <span class="ie-label">Servings</span>
                <div class="ie-qty-row">
                    <button type="button" class="ie-qty-btn" id="item-edit-qty-minus">&#x2212;</button>
                    <input type="number" id="item-edit-qty" class="ie-qty-inp" value="1" min="0.25" max="99" step="0.25">
                    <button type="button" class="ie-qty-btn" id="item-edit-qty-plus">&#x2B;</button>
                    <span class="fp-serving-desc" id="item-edit-serving-unit" style="font-size:.72rem;color:var(--text-muted)"></span>
                </div>
            </div>
            <div class="fp-nutrients">
                <div class="fp-nut-cell nut-kj">  <span class="fp-nut-val" id="ie-kj">&#x2014;</span>  <span class="fp-nut-lbl">kJ</span></div>
                <div class="fp-nut-cell nut-kcal"><span class="fp-nut-val" id="ie-kcal">&#x2014;</span><span class="fp-nut-lbl">kcal</span></div>
                <div class="fp-nut-cell nut-cho"> <span class="fp-nut-val" id="ie-cho">&#x2014;</span> <span class="fp-nut-lbl">CHO (g)</span></div>
                <div class="fp-nut-cell nut-pro"> <span class="fp-nut-val" id="ie-pro">&#x2014;</span> <span class="fp-nut-lbl">Protein (g)</span></div>
                <div class="fp-nut-cell nut-fat"> <span class="fp-nut-val" id="ie-fat">&#x2014;</span> <span class="fp-nut-lbl">Fat (g)</span></div>
            </div>
            <div class="ie-field-row">
                <span class="ie-label">Note</span>
                <input type="text" id="item-edit-note" class="ie-note-inp" placeholder="Optional note&#x2026;" autocomplete="off">
            </div>
        </div>
        <div id="item-edit-ftr">
            <button type="button" id="item-edit-cancel-btn" onclick="closeItemEdit()">Cancel</button>
            <button type="button" id="item-edit-save-btn" onclick="confirmItemEdit()">&#x2713; Save</button>
        </div>
    </div>
</div>

{{-- ── FatSecret Portion Adjustment Modal ────────────────────── --}}
<div id="fs-portion-overlay" role="dialog" aria-modal="true">
    <div id="fs-portion-modal">
        <div id="fs-portion-hdr">
            <span id="fs-portion-title">🌐 Adjust Portion</span>
            <button id="fs-portion-close" onclick="fsPortion.cancel()">&times;</button>
        </div>
        <div id="fs-portion-body">
            <div class="fp-food-name" id="fp-name"></div>
            <div class="fp-serving-orig" id="fp-serving"></div>
            <div class="fp-multiplier-row">
                <span class="fp-multiplier-label">Servings:</span>
                <input type="number" id="fp-mult" class="fp-multiplier-inp" value="1" min="0.1" step="0.1">
                <span class="fp-serving-desc" id="fp-serving-unit"></span>
            </div>
            <div class="fp-multiplier-row" id="fp-grams-row" style="display:none">
                <span class="fp-multiplier-label">Custom (g):</span>
                <input type="number" id="fp-grams" class="fp-multiplier-inp" min="0.1" step="1">
                <span class="fp-serving-desc" style="font-size:.72rem;color:#6366f1">scales nutrients</span>
            </div>
            <div class="fp-nutrients">
                <div class="fp-nut-cell nut-kj">   <span class="fp-nut-val" id="fp-kj">—</span>   <span class="fp-nut-lbl">kJ</span></div>
                <div class="fp-nut-cell nut-kcal"> <span class="fp-nut-val" id="fp-kcal">—</span> <span class="fp-nut-lbl">kcal</span></div>
                <div class="fp-nut-cell nut-cho">  <span class="fp-nut-val" id="fp-carbs">—</span> <span class="fp-nut-lbl">CHO (g)</span></div>
                <div class="fp-nut-cell nut-pro">  <span class="fp-nut-val" id="fp-prot">—</span>  <span class="fp-nut-lbl">Protein (g)</span></div>
                <div class="fp-nut-cell nut-fat">  <span class="fp-nut-val" id="fp-fat">—</span>   <span class="fp-nut-lbl">Fat (g)</span></div>
            </div>
        </div>
        <div id="fs-portion-ftr">
            <button type="button" id="fs-portion-cancel" onclick="fsPortion.cancel()">Cancel</button>
            <button type="button" id="fs-portion-confirm" onclick="fsPortion.confirm()">&#x2713; Add to Library &amp; Select</button>
        </div>
    </div>
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
        renderCatCell(di, slot, 'Other', '__orphan__');
    } else {
        renderCatCell(di,slot,'','all');
    }
    // sync hidden input
    var hidden = document.getElementById('cell_'+di+'_'+slot);
    if(hidden) hidden.value = JSON.stringify(STATE[di+'_'+slot]||[]);
    recalc();
    scheduleAutosave();
}

/* ── Render one category cell ─────────────────────────── */
function renderCatCell(di, slot, catName, catSlug){
    var key      = di+'_'+slot;
    var allItems = STATE[key]||[];
    // filter to this category (or all when catName is empty)
    // Filter by exchCat (exchange card slug), not library group name
    var catItems;
    if(catSlug==='all'){
        catItems = allItems;
    } else if(catSlug==='__orphan__'){
        // Items whose exchCat is null or doesn't match any distribution category
        var distribSlugs = (DISTRIB[slot]||[]).map(function(d){ return slugify(d.name); });
        catItems = allItems.filter(function(it){
            return !it.exchCat || distribSlugs.indexOf(it.exchCat) === -1;
        });
        // Show the orphan card only when there are such items
        var orphanCard = document.getElementById('other-card-'+di+'-'+slot);
        if(orphanCard) orphanCard.style.display = catItems.length > 0 ? '' : 'none';
    } else {
        catItems = allItems.filter(function(it){ return it.exchCat===catSlug; });
    }

    var tagsDiv = document.getElementById('tags_'+di+'_'+slot+'_'+catSlug);
    var cellEl  = document.querySelector('.cat-card[data-day="'+di+'"][data-slot="'+slot+'"][data-cat-slug="'+catSlug+'"]');
    if(!tagsDiv||!cellEl) return;

    var bg  = cellEl.dataset.tagBg     || '#fff7ed';
    var brd = cellEl.dataset.tagBorder  || '#fed7aa';
    var txt = cellEl.dataset.tagText    || '#c2410c';

    // Update the card's quota badge
    var badge = document.getElementById('catqty_'+di+'_'+slot+'_'+catSlug);
    if(badge){
        var used=catItems.reduce(function(s,it){ return s+(it.qty||1); },0);
        var allowed=parseInt(cellEl.dataset.qty||'0',10);
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
        var qtyDisp = (Math.round(qty * 100) / 100).toString();
        var qtyLbl = qty!=1 ? qtyDisp+'\u00D7 ' : '';
        var tag    = document.createElement('span');
        tag.className='cell-tag';
        tag.style.cssText='background:'+bg+';color:'+txt+';border-color:'+brd;
        tag.innerHTML=
            '<div class="cell-tag-row">'+
              '<span class="cell-tag-name" onclick="editItem(\''+di+'\',\''+slot+'\','+allIdx+',event)">'+esc(item.text)+'</span>'+
              '<button class="cell-tag-rm" type="button" data-day="'+di+'" data-slot="'+slot+'" data-idx="'+allIdx+'" onclick="removeItem(this,event)">&#xD7;</button>'+
            '</div>'+
            '<div class="cell-tag-qty-row">'+
              '<button type="button" class="cell-tag-qty-btn" onclick="inlineQty(\''+di+'\',\''+slot+'\','+allIdx+',-0.1,event)">&#x2212;</button>'+
              '<input type="number" class="cell-tag-qty-inp" value="'+qtyDisp+'" min="0.1" step="0.1" onclick="event.stopPropagation()" onchange="inlineQtySet(\''+di+'\',\''+slot+'\','+allIdx+',this)" />'+
              '<button type="button" class="cell-tag-qty-btn" onclick="inlineQty(\''+di+'\',\''+slot+'\','+allIdx+',0.1,event)">&#x2B;</button>'+
            '</div>'+
            (item.note?'<span class="cell-tag-note">'+esc(item.note)+'</span>':'')+
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

/* ── Item Edit Modal ─────────────────────────────────── */
var _editDi=null, _editSlot=null, _editIdx=null, _ieLib=null;

function fmt(v){ return (v!==null&&v!==undefined&&!isNaN(v)&&v!=='')?Math.round(v*10)/10:null; }
function ieSet(id,v){ document.getElementById(id).textContent = v!==null?v:'\u2014'; }

function refreshItemEdit(){
    var qty=Math.max(0.25,parseFloat(document.getElementById('item-edit-qty').value)||1);
    var lib=_ieLib;
    function sc(v){ return v?Math.round(v*qty*10)/10:null; }
    var kj  = lib&&lib.kj   ? Math.round(lib.kj*qty)   : null;
    var kcal= lib&&lib.kcal ? Math.round(lib.kcal*qty)  : (kj?Math.round(kj/4.184):null);
    ieSet('ie-kj',  kj);
    ieSet('ie-kcal',kcal);
    ieSet('ie-cho', sc(lib&&lib.cho));
    ieSet('ie-pro', sc(lib&&lib.pro));
    ieSet('ie-fat', sc(lib&&lib.fat));
    ieSet('ie-fib', sc(lib&&lib.fib));
}

window.editItem=function(di,slot,idx,e){
    e.stopPropagation();
    var item=(STATE[di+'_'+slot]||[])[idx];
    if(!item) return;
    _editDi=di; _editSlot=slot; _editIdx=idx;
    _ieLib = item.id ? (idToItem[item.id]||null) : null;
    // fall back to kj/kcal stored on the state item (e.g. custom/FatSecret items)
    if(!_ieLib) _ieLib={kj:item.kj||0,kcal:item.kcal||0,cho:null,pro:null,fat:null,fib:null,serving:null};
    var serving=_ieLib.serving||'';
    document.getElementById('item-edit-name').textContent=item.text;
    document.getElementById('item-edit-serving').textContent=serving?'Per serving: '+serving:'';
    document.getElementById('item-edit-serving-unit').textContent=serving?'\u00D7 '+serving:'';
    document.getElementById('item-edit-qty').value=item.qty||1;
    document.getElementById('item-edit-note').value=item.note||'';
    refreshItemEdit();
    document.getElementById('item-edit-overlay').classList.add('open');
    setTimeout(function(){ var q=document.getElementById('item-edit-qty'); q.focus(); q.select(); },60);
};

window.closeItemEdit=function(){
    document.getElementById('item-edit-overlay').classList.remove('open');
    _editDi=null; _editSlot=null; _editIdx=null;
};

window.confirmItemEdit=function(){
    if(_editDi===null) return;
    var item=(STATE[_editDi+'_'+_editSlot]||[])[_editIdx];
    if(!item){ closeItemEdit(); return; }
    item.qty=Math.max(0.25,parseFloat(document.getElementById('item-edit-qty').value)||1);
    item.note=document.getElementById('item-edit-note').value.trim();
    renderAll(_editDi,_editSlot);
    closeItemEdit();
};

document.getElementById('item-edit-qty-minus').addEventListener('click',function(){
    var inp=document.getElementById('item-edit-qty');
    inp.value=Math.max(0.25,(parseFloat(inp.value)||1)-0.5);
    refreshItemEdit();
});
document.getElementById('item-edit-qty-plus').addEventListener('click',function(){
    var inp=document.getElementById('item-edit-qty');
    inp.value=Math.min(99,(parseFloat(inp.value)||1)+0.5);
    refreshItemEdit();
});
document.getElementById('item-edit-qty').addEventListener('input',refreshItemEdit);
document.getElementById('item-edit-overlay').addEventListener('click',function(e){ if(e.target===this) closeItemEdit(); });

/* ── Autosave ─────────────────────────────────────────── */
var _autosaveTimer = null;
var _autosaveStatus = document.getElementById('mp-autosave-status');
var _autosaveForm   = document.getElementById('mp-form');
var AUTOSAVE_URL    = _autosaveForm ? _autosaveForm.getAttribute('action') : '';
var AUTOSAVE_TOKEN  = document.querySelector('meta[name="csrf-token"]')
                        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        : (document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '');

function setAutosaveStatus(msg, color){
    if(!_autosaveStatus) return;
    _autosaveStatus.textContent = msg;
    _autosaveStatus.style.color = color || 'var(--text-muted)';
}

function scheduleAutosave(){
    clearTimeout(_autosaveTimer);
    setAutosaveStatus('Unsaved changes…', '#c2410c');
    _autosaveTimer = setTimeout(doAutosave, 800);
}

function doAutosave(){
    if(!_autosaveForm) return;
    var data = new FormData(_autosaveForm);
    data.set('_method', 'PATCH');
    setAutosaveStatus('Saving…', '#6b7280');
    fetch(AUTOSAVE_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': AUTOSAVE_TOKEN, 'Accept': 'application/json' },
        body: data,
    })
    .then(function(r){ return r.ok ? r.json() : Promise.reject(r.status); })
    .then(function(){ setAutosaveStatus('Saved ✓', '#15803d'); })
    .catch(function(){ setAutosaveStatus('Save failed – click Save Plan to retry', '#b91c1c'); });
}

/* ── Recalc kJ badges & grand totals ────────────────── */
/* recalcWithState(map) — shared engine used by both recalc() and recalcLive() */
function recalcWithState(stateMap){
    var slotTotals={};
    var dayMacros={};
    for(var i=0;i<7;i++) dayMacros[i]={kj:0,cho:0,pro:0,fat:0,fib:0};
    Object.keys(stateMap).forEach(function(key){
        var parts=key.split('_'), di=parseInt(parts[0],10), slot=parts.slice(1).join('_');
        var kj=0;
        stateMap[key].forEach(function(it){
            var qty=it.qty||1;
            var lib=it.id?idToItem[it.id]:null;
            var itKj=toKj(it);
            kj+=itKj*qty;
            dayMacros[di].kj +=itKj*qty;
            dayMacros[di].cho+=((it.cho!=null?it.cho:(lib?lib.cho:0))||0)*qty;
            dayMacros[di].pro+=((it.pro!=null?it.pro:(lib?lib.pro:0))||0)*qty;
            dayMacros[di].fat+=((it.fat!=null?it.fat:(lib?lib.fat:0))||0)*qty;
            dayMacros[di].fib+=((it.fib!=null?it.fib:(lib?lib.fib:0))||0)*qty;
        });
        var kdEl=document.getElementById('slot-day-kcal-'+slot+'-'+di);
        if(kdEl){ kdEl.textContent=kj>0?kj:'0'; kdEl.classList.toggle('has-val',kj>0); }
        slotTotals[slot]=(slotTotals[slot]||0)+kj;
    });
    Object.keys(slotTotals).forEach(function(slot){
        var badge=document.getElementById('slot-badge-'+slot);
        if(!badge) return;
        var k=slotTotals[slot];
        badge.textContent=k>0?k+' kJ':'0 kJ';
        badge.classList.toggle('has-kcal',k>0);
    });
    for(var di=0;di<7;di++){
        var m=dayMacros[di];
        var kjEl=document.getElementById('grand-kj-'+di);
        if(kjEl){ kjEl.textContent=m.kj>0?Math.round(m.kj):0; kjEl.classList.toggle('has-val',m.kj>0); }
        ['cho','pro','fat'].forEach(function(mac){
            var el=document.getElementById('grand-'+mac+'-'+di);
            if(el){
                var v=Math.round(m[mac]*10)/10;
                el.textContent=m[mac]>0?v:0;
                el.classList.toggle('has-val',m[mac]>0);
            }
        });
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

function selQtyTotal(){ return Object.values(_sel).reduce(function(s,it){ return s+(it.qty||1); },0); }

function updateQuotaBadge(){
    var quotaWrap  = document.getElementById('mp-modal-quota');
    var badge      = document.getElementById('mp-modal-quota-badge');
    var lbl        = document.getElementById('mp-modal-quota-label');
    if(!_cat||_qty===0){ quotaWrap.style.display='none'; return; }
    quotaWrap.style.display='flex';
    var n=selQtyTotal();
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
document.addEventListener('keydown',function(e){ if(e.key==='Escape'){ closeModal(); if(window.closeItemEdit) closeItemEdit(); } });

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

    function chip(cls,lbl,v){
        if(v===null||v===undefined||v===''||isNaN(v)) return '';
        return '<span class="im-macro-chip '+cls+'">'+Math.round(Number(v)*10)/10+'<span class="im-macro-chip-lbl">&thinsp;'+lbl+'</span></span>';
    }
    var macroChips=chip('kj','kJ',kj)+chip('cho','CHO',it.cho)+chip('pro','Pro',it.pro)+chip('fat','Fat',it.fat);

    var row=document.createElement('label');
    row.className='im-option'+(isChecked?' selected':'');
    row.innerHTML='<input type="checkbox" value="'+esc(val)+'"'+(isChecked?' checked':'')+'>'
        +'<div class="im-option-label">'
        +'<span class="im-option-name">'+esc(it.text)+'</span>'
        +(it.serving?'<span class="im-option-desc">'+esc(it.serving)+'</span>':'')
        +(macroChips?'<div class="im-option-macros">'+macroChips+'</div>':'')
        +'</div>'
        +'<div class="im-qty-wrap"'+(isChecked?'':' style="display:none"')+'>'
        +'<button type="button" class="im-qty-btn im-qty-minus">&#x2212;</button>'
        +'<input type="number" class="im-qty-inp" value="'+curQty+'" min="1" max="99">'
        +'<button type="button" class="im-qty-btn im-qty-plus">&#x2B;</button>'
        +'</div>';
    var chk=row.querySelector('input[type=checkbox]');
    var qtyWrap=row.querySelector('.im-qty-wrap');
    var qtyInp=row.querySelector('.im-qty-inp');
    qtyWrap.addEventListener('click',function(e){ e.preventDefault(); e.stopPropagation(); });
    function updateQty(q){
        q=Math.max(1,Math.min(99,Math.round(q)||1));
        // If quota is set, cap so total qty across _sel doesn't exceed it
        if(_qty>0&&_sel[val]){
            var others=selQtyTotal()-(_sel[val].qty||1);
            q=Math.min(q,Math.max(1,_qty-others));
        }
        qtyInp.value=q;
        if(_sel[val]){ _sel[val].qty=q; updateCount(); updateQuotaBadge(); recalcLive(); }
    }
    row.querySelector('.im-qty-minus').addEventListener('click',function(e){ e.preventDefault(); e.stopPropagation(); updateQty((parseInt(qtyInp.value,10)||1)-1); });
    row.querySelector('.im-qty-plus').addEventListener('click',function(e){ e.preventDefault(); e.stopPropagation(); updateQty((parseInt(qtyInp.value,10)||1)+1); });
    qtyInp.addEventListener('click',function(e){ e.stopPropagation(); });
    qtyInp.addEventListener('change',function(e){ e.stopPropagation(); updateQty(parseInt(this.value,10)||1); });
    chk.addEventListener('change',function(e){
        if(e.target.checked){
            if(_qty>0&&selQtyTotal()>=_qty){ e.target.checked=false; return; }
            _sel[val]={id:val,text:it.text,kcal:kcal,kj:kj,cho:it.cho||null,pro:it.pro||null,fat:it.fat||null,fib:it.fib||null,group:it.group,exchCat:_catSlug,qty:1};
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
    var n=selQtyTotal(), cntEl=document.getElementById('mp-sel-count'), cfm=document.getElementById('mp-btn-confirm');
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
            // Uncheck immediately — the portion modal will decide whether to proceed
            e.target.checked=false;
            if(_qty>0&&selQtyTotal()>=_qty){ return; }
            fsPortion.open(food, function(scaled){
                var payload=new URLSearchParams({_token:CSRF,name:scaled.name,serving:scaled.serving||'',kcal:scaled.kcal||'',kj:scaled.kj||'',fat:scaled.fat||'',carbs:scaled.carbs||'',protein:scaled.protein||'',fiber:scaled.fiber||''});
                var confirmBtn=document.getElementById('fs-portion-confirm');
                if(confirmBtn){ confirmBtn.disabled=true; confirmBtn.textContent='Saving…'; }
                fetch(IMPORT_URL,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest','Content-Type':'application/x-www-form-urlencoded','Accept':'application/json'},body:payload.toString()})
                .then(function(r){ return r.json(); })
                .then(function(saved){
                    var newIt={value:String(saved.id),text:saved.name,group:saved.category||'Other',kcal:saved.energy_kcal||0,kj:saved.energy_kj||0,serving:saved.serving_size||null};
                    if(!idToItem[newIt.value]){ ITEMS.push(newIt); idToItem[newIt.value]=newIt; if(!groups[newIt.group]){ groups[newIt.group]=[]; } groups[newIt.group].push(newIt); }
                    _sel[newIt.value]={id:newIt.value,text:newIt.text,kcal:toKcal(newIt),kj:toKj(newIt),group:newIt.group,exchCat:_catSlug,qty:1};
                    _fsResults=_fsResults.filter(function(f){ return f.id!==food.id; });
                    fsPortion.close();
                    updateCount(); updateQuotaBadge(); recalcLive();
                    renderBody(document.getElementById('mp-modal-search').value.trim());
                })
                .catch(function(){
                    var val='_f_'+Date.now(); _sel[val]={id:null,text:scaled.name,kcal:scaled.kcal?Math.round(scaled.kcal):0,kj:scaled.kj?Math.round(scaled.kj):0,cho:scaled.carbs||null,pro:scaled.protein||null,fat:scaled.fat||null,fib:scaled.fiber||null,group:null,exchCat:_catSlug,qty:1};
                    fsPortion.close();
                    updateCount(); recalcLive();
                });
            });
        });
        grp.appendChild(row);
    });
    if(grp.children.length>1) body.appendChild(grp);
}

/* ── FatSecret Portion Modal controller ──────────────── */
window.fsPortion=(function(){
    var _food=null, _onConfirm=null, _baseGrams=null, _syncing=false;
    var overlay=document.getElementById('fs-portion-overlay');
    var multInp=document.getElementById('fp-mult');
    var gramsInp=document.getElementById('fp-grams');
    var gramsRow=document.getElementById('fp-grams-row');

    function fmt(v){ return v!==null&&v!==undefined&&!isNaN(v)?Math.round(v*10)/10:'—'; }

    function refresh(){
        var m=Math.max(0.1,parseFloat(multInp.value)||1);
        document.getElementById('fp-kj').textContent   = _food.kj      ? Math.round(_food.kj*m)       : '—';
        document.getElementById('fp-kcal').textContent = _food.kcal    ? Math.round(_food.kcal*m)     : '—';
        document.getElementById('fp-carbs').textContent= _food.carbs   ? fmt(_food.carbs*m)           : '—';
        document.getElementById('fp-prot').textContent = _food.protein ? fmt(_food.protein*m)         : '—';
        document.getElementById('fp-fat').textContent  = _food.fat     ? fmt(_food.fat*m)             : '—';
    }

    multInp.addEventListener('input', function(){
        if(_syncing) return;
        if(_baseGrams){
            _syncing=true;
            gramsInp.value=Math.round(Math.max(0.1,parseFloat(multInp.value)||1)*_baseGrams*10)/10;
            _syncing=false;
        }
        refresh();
    });
    multInp.addEventListener('change', function(){
        if(_syncing) return;
        if(_baseGrams){
            _syncing=true;
            gramsInp.value=Math.round(Math.max(0.1,parseFloat(multInp.value)||1)*_baseGrams*10)/10;
            _syncing=false;
        }
        refresh();
    });

    gramsInp.addEventListener('input', function(){
        if(_syncing||!_baseGrams) return;
        var g=Math.max(0.1,parseFloat(gramsInp.value)||0.1);
        _syncing=true;
        multInp.value=Math.round(g/_baseGrams*1000)/1000;
        _syncing=false;
        refresh();
    });
    gramsInp.addEventListener('change', function(){
        if(_syncing||!_baseGrams) return;
        var g=Math.max(0.1,parseFloat(gramsInp.value)||0.1);
        _syncing=true;
        multInp.value=Math.round(g/_baseGrams*1000)/1000;
        _syncing=false;
        refresh();
    });

    return {
        open: function(food, onConfirm){
            _food=food; _onConfirm=onConfirm;
            // Parse base grams from serving string (e.g. "100g", "1 serving (85g)", "85 g")
            var gMatch=food.serving&&food.serving.match(/(\d+\.?\d*)\s*g\b/i);
            _baseGrams=gMatch?parseFloat(gMatch[1]):null;
            document.getElementById('fp-name').textContent=food.name;
            var servingText=food.serving?food.serving:'(unknown serving)';
            document.getElementById('fp-serving').textContent='Per serving: '+servingText;
            document.getElementById('fp-serving-unit').textContent='× '+servingText;
            multInp.value='1';
            if(_baseGrams){
                gramsRow.style.display='';
                gramsInp.value=_baseGrams;
            } else {
                gramsRow.style.display='none';
                gramsInp.value='';
            }
            var btn=document.getElementById('fs-portion-confirm');
            if(btn){ btn.disabled=false; btn.innerHTML='&#x2713; Add to Library &amp; Select'; }
            refresh();
            overlay.classList.add('open');
            (_baseGrams?gramsInp:multInp).focus();
            (_baseGrams?gramsInp:multInp).select();
        },
        cancel: function(){
            overlay.classList.remove('open');
            _food=null; _onConfirm=null;
        },
        close: function(){
            overlay.classList.remove('open');
            _food=null; _onConfirm=null;
        },
        confirm: function(){
            if(!_food||!_onConfirm) return;
            var m=Math.max(0.1,parseFloat(multInp.value)||1);
            var servingLabel;
            if(_baseGrams){
                var customG=Math.round(parseFloat(gramsInp.value)||_baseGrams);
                servingLabel=customG+'g';
            } else {
                servingLabel=_food.serving?(Math.round(m*10)/10+' × '+_food.serving):null;
            }
            var scaled={
                name:    _food.name,
                serving: servingLabel,
                kcal:    _food.kcal    ? _food.kcal*m    : null,
                kj:      _food.kj     ? _food.kj*m      : null,
                fat:     _food.fat    ? _food.fat*m     : null,
                carbs:   _food.carbs  ? _food.carbs*m   : null,
                protein: _food.protein? _food.protein*m : null,
                fiber:   _food.fiber  ? _food.fiber*m   : null,
            };
            _onConfirm(scaled);
        }
    };
})();

/* ── Initialise from saved DB data ───────────────────── */
for(var di=0;di<7;di++){
    SLOTS.forEach(function(slot){
        var key=di+'_'+slot;
        var hidden=document.getElementById('cell_'+di+'_'+slot);
        try{
            var parsed=JSON.parse(hidden?hidden.value:'[]');
            STATE[key]=Array.isArray(parsed)?parsed.map(function(it){
                var lib=it.id?idToItem[it.id]:null;
                return {id:it.id||null,text:it.text||(lib?lib.text:''),kcal:lib?toKcal(lib):0,kj:lib?toKj(lib):0,cho:lib?lib.cho:null,pro:lib?lib.pro:null,fat:lib?lib.fat:null,fib:lib?lib.fib:null,group:lib?lib.group:null,exchCat:it.exchCat||null,qty:it.qty||1};
            }).filter(function(it){ return it.text||it.id; }):[];
        }catch(ex){ STATE[key]=[]; }
        renderAll(di,slot);
    });
}

/* ── Copy-day logic ──────────────────────────────────── */
var _cpSourceDay = null;
window.openCopyPopover = function(e, di) {
    e.stopPropagation();
    _cpSourceDay = di;
    var days = @json($days);
    document.getElementById('cp-source-name').textContent = days[di];
    // Mark source row disabled, uncheck all
    document.querySelectorAll('.cp-day-check').forEach(function(cb) {
        cb.checked = false;
    });
    document.querySelectorAll('.cp-day-row').forEach(function(row) {
        var val = parseInt(row.querySelector('input').value, 10);
        if (val === di) {
            row.classList.add('cp-source');
        } else {
            row.classList.remove('cp-source');
        }
    });
    var pop = document.getElementById('copy-day-popover');
    pop.style.display = 'block';
    // Position near the button
    var btn = e.currentTarget;
    var rect = btn.getBoundingClientRect();
    var popW = 220;
    var left = Math.min(rect.left, window.innerWidth - popW - 12);
    pop.style.top  = (rect.bottom + window.scrollY + 6) + 'px';
    pop.style.left = Math.max(8, left) + 'px';
};
window.closeCopyPopover = function() {
    document.getElementById('copy-day-popover').style.display = 'none';
    _cpSourceDay = null;
};
window.applyCopyDay = function() {
    if (_cpSourceDay === null) return;
    var targets = [];
    document.querySelectorAll('.cp-day-check:checked').forEach(function(cb) {
        targets.push(parseInt(cb.value, 10));
    });
    if (targets.length === 0) { closeCopyPopover(); return; }
    SLOTS.forEach(function(slot) {
        var srcKey = _cpSourceDay + '_' + slot;
        var srcItems = STATE[srcKey] || [];
        targets.forEach(function(tdi) {
            var tKey = tdi + '_' + slot;
            // Deep clone the source items
            STATE[tKey] = JSON.parse(JSON.stringify(srcItems));
            renderAll(tdi, slot);
        });
    });
    closeCopyPopover();
    scheduleAutosave();
};
// Close popover on outside click
document.addEventListener('click', function(e) {
    var pop = document.getElementById('copy-day-popover');
    if (pop && pop.style.display === 'block' && !pop.contains(e.target) && !e.target.closest('.copy-day-btn')) {
        closeCopyPopover();
    }
});

/* ── Copy-slot logic ──────────────────────────────────── */
var _cpsSourceDay = null;
var _cpsSourceSlot = null;
window.openCopySlotPopover = function(e, di, slot) {
    e.stopPropagation();
    closeCopyPopover();
    _cpsSourceDay = di;
    _cpsSourceSlot = slot;
    var days = @json($days);
    var slotLabels = @json($slotLabels);
    document.getElementById('cps-source-name').textContent = days[di] + ' — ' + slotLabels[slot];
    document.querySelectorAll('.cps-day-check').forEach(function(cb) { cb.checked = false; });
    document.querySelectorAll('#cps-day-list .cp-day-row').forEach(function(row) {
        var val = parseInt(row.querySelector('input').value, 10);
        if (val === di) { row.classList.add('cp-source'); } else { row.classList.remove('cp-source'); }
    });
    var pop = document.getElementById('copy-slot-popover');
    pop.style.display = 'block';
    var rect = e.currentTarget.getBoundingClientRect();
    var popW = 220;
    var left = Math.min(rect.left, window.innerWidth - popW - 12);
    pop.style.top  = (rect.bottom + window.scrollY + 6) + 'px';
    pop.style.left = Math.max(8, left) + 'px';
};
window.closeCopySlotPopover = function() {
    document.getElementById('copy-slot-popover').style.display = 'none';
    _cpsSourceDay = null;
    _cpsSourceSlot = null;
};
window.applyCopySlot = function() {
    if (_cpsSourceDay === null || !_cpsSourceSlot) return;
    var targets = [];
    document.querySelectorAll('.cps-day-check:checked').forEach(function(cb) {
        targets.push(parseInt(cb.value, 10));
    });
    if (targets.length === 0) { closeCopySlotPopover(); return; }
    var srcKey = _cpsSourceDay + '_' + _cpsSourceSlot;
    var srcItems = STATE[srcKey] || [];
    targets.forEach(function(tdi) {
        var tKey = tdi + '_' + _cpsSourceSlot;
        STATE[tKey] = JSON.parse(JSON.stringify(srcItems));
        renderAll(tdi, _cpsSourceSlot);
    });
    closeCopySlotPopover();
    recalc();
    scheduleAutosave();
};
document.addEventListener('click', function(e) {
    var pop = document.getElementById('copy-slot-popover');
    if (pop && pop.style.display === 'block' && !pop.contains(e.target) && !e.target.closest('.copy-slot-btn')) {
        closeCopySlotPopover();
    }
});

/* ── Inline qty adjustment on tags ────────────────────── */
window.inlineQty = function(di, slot, idx, delta, e) {
    e.stopPropagation();
    var key = di + '_' + slot;
    var item = (STATE[key]||[])[idx];
    if (!item) return;
    var newQty = Math.max(0.1, (item.qty||1) + delta);
    item.qty = Math.round(newQty * 100) / 100;
    renderAll(di, slot);
};
window.inlineQtySet = function(di, slot, idx, inp) {
    var key = di + '_' + slot;
    var item = (STATE[key]||[])[idx];
    if (!item) return;
    var val = parseFloat(inp.value);
    if (isNaN(val) || val < 0.1) val = 0.1;
    item.qty = Math.round(val * 100) / 100;
    renderAll(di, slot);
};

/* ── Template (preset) logic ──────────────────────────── */
var _tplSlug = null;
var PRESETS_URL = '{{ route("meal-planner.presets") }}';

window.openTemplateModal = function() {
    _tplSlug = null;
    document.getElementById('tpl-confirm').style.display = 'none';
    document.getElementById('template-overlay').classList.add('is-open');
    fetch(PRESETS_URL, {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){ return r.json(); })
    .then(function(list){
        var html = '';
        list.forEach(function(p){
            var kcalBadge = p.kcal ? '<span class="tpl-item-kcal">' + p.kcal + ' kcal</span>' : '';
            var dayBadge  = p.is_7day ? '<span class="tpl-item-kcal" style="background:#ede9fe;color:#6d28d9">7-day</span>' : '';
            var catLine   = p.category ? '<span style="display:block;font-size:.7rem;color:#6b7280;margin-top:1px">' + p.category + '</span>' : '';
            var descLine  = p.description ? '<span style="display:block;font-size:.68rem;color:#9ca3af;margin-top:2px;line-height:1.3">' + p.description + '</span>' : '';
            html += '<div class="tpl-item" data-slug="' + p.slug + '" onclick="selectTemplate(\'' + p.slug + '\')">'
                + '<span class="tpl-item-name">' + p.name + catLine + descLine + '</span>'
                + '<span class="tpl-item-meta">' + dayBadge + kcalBadge + '<span class="tpl-item-arrow">&#x25B6;</span></span>'
                + '</div>';
        });
        document.getElementById('tpl-list').innerHTML = html;
    });
};

window.closeTemplateModal = function() {
    document.getElementById('template-overlay').classList.remove('is-open');
    _tplSlug = null;
};

window.selectTemplate = function(slug) {
    _tplSlug = slug;
    document.querySelectorAll('.tpl-item').forEach(function(el){
        el.classList.toggle('is-selected', el.dataset.slug === slug);
    });
    document.getElementById('tpl-confirm').style.display = 'block';
};

window.cancelTemplateApply = function() {
    _tplSlug = null;
    document.getElementById('tpl-confirm').style.display = 'none';
    document.querySelectorAll('.tpl-item').forEach(function(el){
        el.classList.remove('is-selected');
    });
};

window.confirmTemplateApply = function() {
    if (!_tplSlug) return;
    var btn = document.getElementById('tpl-confirm-btn');
    btn.disabled = true;
    btn.textContent = 'Applying…';

    fetch(PRESETS_URL + '/' + _tplSlug, {headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
    .then(function(r){ return r.json(); })
    .then(function(data){
        var is7Day = data.is_7day && data.days;
        for (var di = 0; di < 7; di++) {
            var daySlots = is7Day ? (data.days[di] || {}) : (data.slots || {});
            SLOTS.forEach(function(slot) {
                var key = di + '_' + slot;
                STATE[key] = daySlots[slot]
                    ? JSON.parse(JSON.stringify(daySlots[slot]))
                    : [];
                renderAll(di, slot);
            });
        }
        recalc();
        closeTemplateModal();
        btn.disabled = false;
        btn.textContent = 'Apply Template';
        for (var si = 0; si < 7; si++) {
            SLOTS.forEach(function(s) {
                var h = document.getElementById('cell_' + si + '_' + s);
                if (h) h.value = JSON.stringify(STATE[si + '_' + s] || []);
            });
        }
        scheduleAutosave();
    })
    .catch(function(){
        btn.disabled = false;
        btn.textContent = 'Apply Template';
        alert('Failed to apply template. Please try again.');
    });
};

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

{{-- ── Repeat Plan Modal ──────────────────────────────────────── --}}
<div id="repeat-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9990;align-items:center;justify-content:center" onclick="if(event.target===this)closeRepeatModal()">
    <div style="background:#fff;border-radius:14px;box-shadow:0 20px 60px rgba(0,0,0,.25);padding:1.75rem 2rem;width:min(420px,94vw)">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.1rem">
            <h3 style="font-size:1rem;font-weight:800;color:var(--text-primary)">&#x1F501; Repeat This Meal Plan</h3>
            <button type="button" onclick="closeRepeatModal()" style="background:none;border:none;font-size:1.3rem;color:var(--text-muted);cursor:pointer;line-height:1">&times;</button>
        </div>
        <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:1.2rem;line-height:1.55">
            Creates identical copies of this plan for upcoming week(s).
            Weeks that already have a plan for this patient will be skipped automatically.
        </p>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem">
            <button type="button" class="repeat-pill" onclick="setRepeatWeeks(1)">1 week</button>
            <button type="button" class="repeat-pill" onclick="setRepeatWeeks(2)">2 weeks</button>
            <button type="button" class="repeat-pill" onclick="setRepeatWeeks(4)">4 weeks</button>
            <button type="button" class="repeat-pill" onclick="setRepeatWeeks(8)">8 weeks</button>
            <button type="button" class="repeat-pill" onclick="setRepeatWeeks(12)">12 weeks</button>
        </div>
        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:1.3rem">
            <label style="font-size:.8rem;font-weight:700;white-space:nowrap;color:var(--text-primary)">Repeat for</label>
            <input type="number" id="repeat-weeks-input" min="1" max="52" value="4"
                style="width:72px;padding:.4rem .6rem;border:1px solid var(--border);border-radius:7px;font-size:.88rem;text-align:center">
            <span style="font-size:.8rem;color:var(--text-muted)">upcoming week(s)</span>
        </div>
        <div id="repeat-result" style="display:none;padding:.6rem .9rem;border-radius:8px;font-size:.8rem;font-weight:600;margin-bottom:1rem"></div>
        <div style="display:flex;gap:.6rem;justify-content:flex-end">
            <button type="button" onclick="closeRepeatModal()"
                style="padding:.48rem 1.1rem;background:#f3f4f6;border:1px solid var(--border);border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer">Cancel</button>
            <button type="button" id="repeat-submit-btn" onclick="submitRepeat()"
                style="padding:.48rem 1.3rem;background:linear-gradient(135deg,#7c3aed,#5b21b6);color:#fff;border:none;border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer;transition:opacity .15s">
                Create Copies
            </button>
        </div>
    </div>
</div>
<style>
.repeat-pill {
    padding:.32rem .75rem;
    background:#f3f0ff;border:1px solid #c4b5fd;
    border-radius:20px;font-size:.75rem;font-weight:700;
    color:#6d28d9;cursor:pointer;transition:background .15s;
}
.repeat-pill:hover,.repeat-pill.active { background:#7c3aed;color:#fff;border-color:#7c3aed; }
</style>
<script>
function openRepeatModal() {
    document.getElementById('repeat-weeks-input').value = 4;
    document.getElementById('repeat-result').style.display = 'none';
    document.getElementById('repeat-submit-btn').disabled = false;
    document.getElementById('repeat-submit-btn').textContent = 'Create Copies';
    document.querySelectorAll('.repeat-pill').forEach(function(p){ p.classList.remove('active'); });
    var ov = document.getElementById('repeat-overlay');
    ov.style.display = 'flex';
}
function closeRepeatModal() {
    document.getElementById('repeat-overlay').style.display = 'none';
}
function setRepeatWeeks(n) {
    document.getElementById('repeat-weeks-input').value = n;
    document.querySelectorAll('.repeat-pill').forEach(function(p){ p.classList.remove('active'); });
    event.currentTarget.classList.add('active');
}
function submitRepeat() {
    var weeks = parseInt(document.getElementById('repeat-weeks-input').value, 10);
    if (!weeks || weeks < 1 || weeks > 52) {
        alert('Please enter a number between 1 and 52.');
        return;
    }
    var btn = document.getElementById('repeat-submit-btn');
    btn.disabled = true;
    btn.textContent = 'Creating…';
    var res = document.getElementById('repeat-result');
    res.style.display = 'none';

    fetch('{{ route('meal-planner.repeat', [$mealPlanner->patient_id ?? 0, $mealPlanner]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ weeks: weeks }),
    })
    .then(function(r){ return r.json(); })
    .then(function(data) {
        res.style.display = 'block';
        if (data.ok) {
            res.style.background = '#dcfce7';
            res.style.color = '#15803d';
            res.style.border = '1px solid #86efac';
            res.textContent = '✓ ' + data.message;
            btn.textContent = 'Done';
        } else {
            res.style.background = '#fef2f2';
            res.style.color = '#dc2626';
            res.style.border = '1px solid #fca5a5';
            res.textContent = data.message || 'Something went wrong.';
            btn.disabled = false;
            btn.textContent = 'Create Copies';
        }
    })
    .catch(function() {
        res.style.display = 'block';
        res.style.background = '#fef2f2';
        res.style.color = '#dc2626';
        res.style.border = '1px solid #fca5a5';
        res.textContent = 'Network error. Please try again.';
        btn.disabled = false;
        btn.textContent = 'Create Copies';
    });
}
</script>

{{-- ── Copy-day popover ──────────────────────────────────────── --}}
<div id="copy-day-popover" role="dialog" aria-modal="true" aria-label="Copy day">
    <h4>
        <span id="cp-title">Copy <strong id="cp-source-name"></strong> to…</span>
        <button class="cp-close" type="button" onclick="closeCopyPopover()" aria-label="Close">&times;</button>
    </h4>
    <div id="cp-day-list">
        @foreach($days as $di => $dayName)
            <label class="cp-day-row" id="cp-row-{{ $di }}">
                <input type="checkbox" class="cp-day-check" value="{{ $di }}">
                <span>{{ $dayName }}</span>
                <span style="font-size:.68rem;color:var(--text-muted);margin-left:auto">{{ $mealPlanner->week_start->addDays($di)->format('d M') }}</span>
            </label>
        @endforeach
    </div>
    <button type="button" class="cp-apply" onclick="applyCopyDay()">Copy items</button>
    <p class="cp-note">Existing items in target day(s) will be replaced.</p>
</div>

{{-- ── Copy-slot popover ──────────────────────────────────────── --}}
<div id="copy-slot-popover" role="dialog" aria-modal="true" aria-label="Copy meal slot" style="display:none;position:absolute;z-index:120;background:#fff;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,.18);width:220px;padding:0;overflow:hidden">
    <h4 style="display:flex;align-items:center;justify-content:space-between;padding:.6rem .75rem;margin:0;font-size:.78rem;font-weight:700;background:#f8fafc;border-bottom:1px solid #e5e7eb">
        <span id="cps-title">Copy <strong id="cps-source-name"></strong> to…</span>
        <button class="cp-close" type="button" onclick="closeCopySlotPopover()" aria-label="Close">&times;</button>
    </h4>
    <div id="cps-day-list" style="padding:.5rem .75rem">
        @foreach($days as $di => $dayName)
            <label class="cp-day-row" id="cps-row-{{ $di }}" style="display:flex;align-items:center;gap:.4rem;padding:.3rem 0;font-size:.8rem;cursor:pointer">
                <input type="checkbox" class="cps-day-check" value="{{ $di }}">
                <span>{{ $dayName }}</span>
                <span style="font-size:.68rem;color:var(--text-muted);margin-left:auto">{{ $mealPlanner->week_start->addDays($di)->format('d M') }}</span>
            </label>
        @endforeach
    </div>
    <div style="padding:0 .75rem .6rem">
        <button type="button" class="cp-apply" onclick="applyCopySlot()" style="width:100%;padding:.5rem;background:var(--primary);color:#fff;border:none;border-radius:8px;font-size:.78rem;font-weight:700;cursor:pointer">Copy items</button>
        <p class="cp-note" style="font-size:.65rem;color:var(--text-muted);text-align:center;margin:.35rem 0 0">Only this meal slot will be copied.</p>
    </div>
</div>

{{-- ── Template Modal ────────────────────────────────────────── --}}
<div id="template-overlay" onclick="if(event.target===this)closeTemplateModal()">
    <div id="template-modal">
        <div id="template-modal-hdr">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Meal Plan Templates
            </h3>
            <button onclick="closeTemplateModal()" title="Close">&times;</button>
        </div>
        <div class="tpl-list" id="tpl-list">
            <div style="padding:2.5rem;text-align:center;color:var(--text-muted,#94a3b8);font-size:.82rem">Loading templates&hellip;</div>
        </div>
        <div class="tpl-confirm" id="tpl-confirm">
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                This will replace all existing items for every day. You can adjust portions after applying.
            </p>
            <div class="tpl-confirm-btns">
                <button class="tpl-confirm-cancel" onclick="cancelTemplateApply()">Cancel</button>
                <button class="tpl-confirm-apply" id="tpl-confirm-btn" onclick="confirmTemplateApply()">Apply &amp; Save</button>
            </div>
        </div>
    </div>
</div>
{{-- ── PDF Preview Modal ─────────────────────────────────────── --}}
<div id="pdf-preview-overlay" role="dialog" aria-modal="true" aria-label="PDF Preview">
    <div id="pdf-preview-modal">
        <div id="pdf-preview-hdr">
            <span id="pdf-preview-hdr-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Meal Plan Preview
            </span>
            <div id="pdf-preview-hdr-actions">
                <a id="pdf-preview-download-btn" href="#" target="_blank" class="pdf-preview-dl-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download PDF
                </a>
                <button type="button" id="pdf-preview-close" onclick="closePdfPreview()" aria-label="Close preview">&times;</button>
            </div>
        </div>
        <div id="pdf-preview-frame-wrap">
            <div id="pdf-preview-loading">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                Loading preview…
            </div>
            <iframe id="pdf-preview-frame" title="PDF Preview"></iframe>
        </div>
    </div>
</div>
<style>
@keyframes spin { to { transform:rotate(360deg); } }
</style>
<script>
(function(){
    var overlay  = document.getElementById('pdf-preview-overlay');
    var frame    = document.getElementById('pdf-preview-frame');
    var loading  = document.getElementById('pdf-preview-loading');
    var dlBtn    = document.getElementById('pdf-preview-download-btn');

    window.openPdfPreview = function(previewUrl, downloadUrl) {
        dlBtn.href = downloadUrl;
        loading.style.display = 'flex';
        frame.src = '';
        overlay.classList.add('open');
        document.body.style.overflow = 'hidden';
        frame.onload = function() { loading.style.display = 'none'; };
        frame.src = previewUrl;
    };

    window.closePdfPreview = function() {
        overlay.classList.remove('open');
        document.body.style.overflow = '';
        frame.src = '';
    };

    overlay.addEventListener('click', function(e) {
        if (e.target === this) closePdfPreview();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('open')) closePdfPreview();
    });
}());
</script>
</x-app-layout>
