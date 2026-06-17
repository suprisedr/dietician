@props([
    'name',
    'label',
    'options'     => [],
    'value'       => '',
    'placeholder' => 'Type to add',
])

@php
    $current = collect(explode(',', (string) $value))
        ->map(fn ($v) => trim($v))
        ->filter()
        ->values();

    $optionMap = collect($options)->mapWithKeys(fn ($o) => [strtolower($o) => $o]);

    $selectedPreset = $current->filter(fn ($v) => $optionMap->has(strtolower($v)))
                              ->map(fn ($v) => $optionMap->get(strtolower($v)))
                              ->unique()->values();

    $otherItems = $current->reject(fn ($v) => $optionMap->has(strtolower($v)))->values();

    $summaryText = $current->isEmpty()
        ? null
        : ($current->count() > 2
            ? $current->count() . ' selected'
            : $current->implode(', '));
@endphp

<div class="cms-wrap">
    <label class="cms-label">{{ $label }}</label>

    <div style="position:relative">
        <button type="button"
                class="cms-trigger {{ $summaryText ? 'cms-has-val' : '' }}"
                onclick="cmsToggle(this.closest('.cms-wrap'))">
            <span class="cms-trigger-val">{{ $summaryText ?? 'Select ' . $label . '…' }}</span>
            <svg class="cms-caret" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>

        <div class="cms-panel" style="display:none">

            {{-- Preset options --}}
            <div class="cms-scroll">
                @foreach($options as $opt)
                <label class="cms-opt {{ $selectedPreset->contains($opt) ? 'is-checked' : '' }}">
                    <input type="checkbox" class="cms-cb" value="{{ $opt }}"
                           onchange="cmsCbChange(this)"
                           {{ $selectedPreset->contains($opt) ? 'checked' : '' }}>
                    <span class="cms-opt-box">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <span class="cms-opt-text">{{ $opt }}</span>
                </label>
                @endforeach
            </div>

            {{-- Other / free-text section --}}
            <div class="cms-other-section">
                <div class="cms-other-lbl">— Other —</div>
                <div class="cms-other-tags">
                    @foreach($otherItems as $other)
                    <span class="cms-other-tag" data-value="{{ $other }}">
                        {{ $other }}
                        <button type="button" onclick="cmsRemoveTag(this)" aria-label="Remove">&times;</button>
                    </span>
                    @endforeach
                </div>
                <div class="cms-other-row">
                    <input type="text" class="cms-other-input"
                           placeholder="{{ $placeholder }}"
                           onkeydown="if(event.key==='Enter'||event.key===','){event.preventDefault();cmsAddTag(this.closest('.cms-wrap'))}">
                    <button type="button" class="cms-other-btn"
                            onclick="cmsAddTag(this.closest('.cms-wrap'))">Add</button>
                </div>
            </div>

        </div>{{-- /.cms-panel --}}
    </div>

    <input type="hidden" class="cms-hidden" name="{{ $name }}" value="{{ $value }}">
</div>

@once
<style>
/* ── Dropdown multi-select ─────────────────────────── */
.cms-wrap { margin-bottom: 1rem; }

.cms-label {
    display: block;
    font-size: .78rem;
    font-weight: 600;
    color: var(--text-muted, #52705e);
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: .4rem;
}

/* Trigger — looks like a <select> */
.cms-trigger {
    width: 100%;
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .5rem .75rem;
    background: #fff;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: .875rem;
    color: #9ca3af;
    cursor: pointer;
    text-align: left;
    transition: border-color .15s, box-shadow .15s;
    font-family: inherit;
}
.cms-trigger.cms-has-val { color: var(--text-primary, #0d1f0c); }
.cms-trigger:hover { border-color: var(--primary-light, #8dc485); }
.cms-trigger.is-open {
    border-color: var(--primary, #679F5F);
    box-shadow: 0 0 0 3px rgba(103,159,95,.12);
    outline: none;
}
.cms-trigger-val {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.cms-caret {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
    color: #9ca3af;
    transition: transform .2s;
}
.cms-trigger.is-open .cms-caret { transform: rotate(180deg); }

/* Panel */
.cms-panel {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid var(--border, #d4e6d1);
    border-radius: .75rem;
    box-shadow: 0 8px 24px rgba(0,0,0,.10);
    z-index: 200;
    overflow: hidden;
}

/* Scrollable options list */
.cms-scroll {
    max-height: 220px;
    overflow-y: auto;
    padding: .4rem 0;
}

/* Option row */
.cms-opt {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .5rem .85rem;
    cursor: pointer;
    transition: background .1s;
    user-select: none;
}
.cms-opt:hover { background: #f0fdf4; }
.cms-opt.is-checked { background: rgba(103,159,95,.07); }

.cms-opt input[type=checkbox] { display: none; }

.cms-opt-box {
    width: 1.1rem;
    height: 1.1rem;
    border: 1.5px solid #d1d5db;
    border-radius: 3px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .15s, border-color .15s;
    background: #fff;
}
.cms-opt-box svg { width: .7rem; height: .7rem; color: #fff; display: none; }

.cms-opt input:checked ~ .cms-opt-box {
    background: var(--primary, #679F5F);
    border-color: var(--primary, #679F5F);
}
.cms-opt input:checked ~ .cms-opt-box svg { display: block; }

.cms-opt-text {
    font-size: .85rem;
    color: var(--text-primary, #0d1f0c);
}
.cms-opt.is-checked .cms-opt-text { color: var(--primary-dark, #4d7d47); font-weight: 600; }

/* Other section */
.cms-other-section {
    border-top: 1px solid var(--border, #d4e6d1);
    padding: .65rem .85rem .75rem;
    background: #f8fafc;
}
.cms-other-lbl {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--text-muted, #52705e);
    margin-bottom: .45rem;
    text-align: center;
}
.cms-other-tags {
    display: flex;
    flex-wrap: wrap;
    gap: .3rem;
    margin-bottom: .5rem;
}
.cms-other-tags:empty { margin-bottom: 0; }
.cms-other-tag {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .2rem .55rem .2rem .65rem;
    background: rgba(103,159,95,.12);
    border: 1px solid var(--primary-light, #8dc485);
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 600;
    color: var(--primary-dark, #4d7d47);
}
.cms-other-tag button {
    background: none;
    border: none;
    font-size: .95rem;
    line-height: 1;
    color: var(--primary, #679F5F);
    cursor: pointer;
    padding: 0 .1rem;
}
.cms-other-tag button:hover { color: #b91c1c; }

.cms-other-row {
    display: flex;
    gap: .4rem;
}
.cms-other-input {
    flex: 1;
    padding: .38rem .65rem;
    font-size: .82rem;
    border: 1px solid #d1d5db;
    border-radius: .45rem;
    outline: none;
    background: #fff;
    font-family: inherit;
}
.cms-other-input:focus {
    border-color: var(--primary, #679F5F);
    box-shadow: 0 0 0 2px rgba(103,159,95,.12);
}
.cms-other-btn {
    padding: .38rem .85rem;
    background: var(--primary, #679F5F);
    color: #fff;
    font-size: .78rem;
    font-weight: 700;
    border: none;
    border-radius: .45rem;
    cursor: pointer;
    font-family: inherit;
    white-space: nowrap;
    transition: background .15s;
}
.cms-other-btn:hover { background: var(--primary-dark, #4d7d47); }
</style>

<script>
(function () {
    function wrap(el)   { return el.closest('.cms-wrap'); }
    function panel(w)   { return w.querySelector('.cms-panel'); }
    function trigger(w) { return w.querySelector('.cms-trigger'); }
    function hidden(w)  { return w.querySelector('.cms-hidden'); }

    function syncWrap(w) {
        var vals = [];
        w.querySelectorAll('.cms-cb:checked').forEach(function (cb) { vals.push(cb.value); });
        w.querySelectorAll('.cms-other-tag').forEach(function (t)  { vals.push(t.dataset.value); });

        hidden(w).value = vals.join(', ');

        var t   = trigger(w);
        var txt = t.querySelector('.cms-trigger-val');
        if (txt) {
            if (vals.length === 0) {
                var lbl = w.querySelector('.cms-label');
                txt.textContent = 'Select ' + (lbl ? lbl.textContent.trim() : '') + '…';
                t.classList.remove('cms-has-val');
            } else {
                txt.textContent = vals.length <= 2 ? vals.join(', ') : vals.length + ' selected';
                t.classList.add('cms-has-val');
            }
        }
    }

    function closeAll(except) {
        document.querySelectorAll('.cms-wrap').forEach(function (w) {
            if (w === except) return;
            panel(w).style.display = 'none';
            trigger(w).classList.remove('is-open');
        });
    }

    window.cmsToggle = function (w) {
        var p  = panel(w);
        var t  = trigger(w);
        var opening = p.style.display === 'none';
        closeAll(w);
        p.style.display  = opening ? '' : 'none';
        t.classList.toggle('is-open', opening);
        if (opening) {
            var inp = p.querySelector('.cms-other-input');
            // don't auto-focus the text input — focus the panel so keyboard works
        }
    };

    window.cmsCbChange = function (cb) {
        var opt = cb.closest('.cms-opt');
        if (opt) opt.classList.toggle('is-checked', cb.checked);
        syncWrap(wrap(cb));
    };

    window.cmsAddTag = function (w) {
        var input = w.querySelector('.cms-other-input');
        var raw   = (input.value || '').trim();
        if (!raw) return;

        raw.split(',').map(function (v) { return v.trim(); }).filter(Boolean).forEach(function (val) {
            // Match against a preset checkbox (case-insensitive)
            var matched = false;
            w.querySelectorAll('.cms-cb').forEach(function (cb) {
                if (cb.value.toLowerCase() === val.toLowerCase()) {
                    cb.checked = true;
                    var opt = cb.closest('.cms-opt');
                    if (opt) opt.classList.add('is-checked');
                    matched = true;
                }
            });
            if (matched) return;

            // Check not already an other-tag
            var exists = false;
            w.querySelectorAll('.cms-other-tag').forEach(function (t) {
                if (t.dataset.value.toLowerCase() === val.toLowerCase()) exists = true;
            });
            if (exists) return;

            var tagsEl = w.querySelector('.cms-other-tags');
            var span   = document.createElement('span');
            span.className   = 'cms-other-tag';
            span.dataset.value = val;
            span.innerHTML   = val + ' <button type="button" onclick="cmsRemoveTag(this)" aria-label="Remove">×</button>';
            tagsEl.appendChild(span);
        });

        input.value = '';
        syncWrap(w);
    };

    window.cmsRemoveTag = function (btn) {
        var w = wrap(btn);
        btn.closest('.cms-other-tag').remove();
        syncWrap(w);
    };

    // Close panels when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.cms-wrap')) closeAll(null);
    });
}());
</script>
@endonce
