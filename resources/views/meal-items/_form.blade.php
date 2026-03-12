{{-- Shared form fields for create & edit --}}
<style>
.mi-field { display:flex; flex-direction:column; gap:.3rem; }
.mi-label {
    font-size:.75rem; font-weight:700; color:var(--text-primary);
    letter-spacing:.03em; display:block;
}
.mi-label .mi-hint { font-weight:400; color:var(--text-muted); font-size:.7rem; margin-left:.3rem; }
.mi-label .mi-req  { color:#ef4444; margin-left:.2rem; }
.mi-input {
    width:100%; padding:.52rem .8rem;
    border:1.5px solid var(--border); border-radius:8px;
    font-size:.85rem; color:var(--text-primary);
    background:#fff; outline:none; box-sizing:border-box;
    transition:border-color .15s, box-shadow .15s;
}
.mi-input:focus {
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(103,159,95,.13);
}
select.mi-input {
    background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2352705e' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position:right .65rem center;
    background-repeat:no-repeat;
    background-size:1.15em 1.15em;
    padding-right:2.2rem;
    appearance:none;
}
.mi-error { font-size:.71rem; color:#dc2626; margin-top:.05rem; }
.mi-section-title {
    font-size:.67rem; font-weight:800; text-transform:uppercase;
    letter-spacing:.08em; color:var(--primary-dark);
    padding-bottom:.3rem; border-bottom:1.5px solid var(--border);
    margin-bottom:.9rem;
}
</style>

{{-- ── Identity ─────────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:.85rem">

    <div class="mi-field" style="grid-column:1/-1">
        <label class="mi-label">Name<span class="mi-req">*</span></label>
        <input type="text" name="name" id="f-name"
               value="{{ old('name', $item->name ?? '') }}"
               class="mi-input" placeholder="e.g. Spinach" required>
        @error('name')<p class="mi-error">{{ $message }}</p>@enderror
    </div>

    <div class="mi-field">
        <label class="mi-label">Category<span class="mi-req">*</span></label>
        <select name="category" class="mi-input" required>
            <option value="">— select —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(old('category', $item->category ?? '') === $cat)>{{ $cat }}</option>
            @endforeach
            <option value="Other" @selected(old('category', $item->category ?? '') === 'Other')>Other</option>
        </select>
        @error('category')<p class="mi-error">{{ $message }}</p>@enderror
    </div>

    <div class="mi-field">
        <label class="mi-label">Serving Size</label>
        <input type="text" name="serving_size" id="f-serving"
               value="{{ old('serving_size', $item->serving_size ?? '') }}"
               class="mi-input" placeholder="e.g. 1 cup (30 g)">
        @error('serving_size')<p class="mi-error">{{ $message }}</p>@enderror
    </div>

</div>

{{-- ── Live preview card ────────────────────────────────────────── --}}
<div style="margin-top:1.1rem;padding:.85rem 1.1rem;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:1.5px solid var(--border);border-radius:10px;font-size:.8rem">
    <div style="font-weight:700;color:var(--text-primary);margin-bottom:.5rem;font-size:.83rem">
        <span id="card-name" style="color:var(--primary-dark)">—</span>
        <span id="card-serving" style="font-weight:400;color:var(--text-muted);margin-left:.4rem"></span>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:.45rem .85rem;align-items:center">
        <span style="display:inline-flex;align-items:center;gap:.28rem;font-weight:700;color:#b45309">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:.72rem;height:.72rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span id="card-kcal">—</span> kcal
        </span>
        <span style="color:#d1d5db">|</span>
        <span style="color:var(--text-muted)">Fat: <strong id="card-fat" style="color:#0f766e">—</strong> g</span>
        <span style="color:#d1d5db">|</span>
        <span style="color:var(--text-muted)">Carbs: <strong id="card-carbs" style="color:#c2410c">—</strong> g</span>
        <span style="color:#d1d5db">|</span>
        <span style="color:var(--text-muted)">Prot: <strong id="card-prot" style="color:#4338ca">—</strong> g</span>
        <span style="color:#d1d5db">|</span>
        <span style="color:var(--text-muted)">Fiber: <strong id="card-fiber" style="color:#15803d">—</strong> g</span>
    </div>
</div>

{{-- ── Nutritional info ─────────────────────────────────────────── --}}
<div style="margin-top:1.25rem">
    <div class="mi-section-title">Nutritional Information (per serving)</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.85rem">

        <div class="mi-field">
            <label class="mi-label">Calories (kcal)<span class="mi-hint">auto-calculated if blank</span></label>
            <input type="number" name="energy_kcal" id="f-kcal"
                   value="{{ old('energy_kcal', $item->energy_kcal ?? '') }}"
                   class="mi-input" min="0" step="0.1" placeholder="auto">
            @error('energy_kcal')<p class="mi-error">{{ $message }}</p>@enderror
        </div>

        <div class="mi-field">
            <label class="mi-label">Energy (kJ)<span class="mi-hint">auto-calculated if blank</span></label>
            <input type="number" name="energy_kj" id="f-kj"
                   value="{{ old('energy_kj', $item->energy_kj ?? '') }}"
                   class="mi-input" min="0" step="0.1" placeholder="auto">
            @error('energy_kj')<p class="mi-error">{{ $message }}</p>@enderror
        </div>

        <div class="mi-field">
            <label class="mi-label">Fat (g)</label>
            <input type="number" name="fat_g" id="f-fat"
                   value="{{ old('fat_g', $item->fat_g ?? '') }}"
                   class="mi-input" min="0" step="0.01" placeholder="0.00">
            @error('fat_g')<p class="mi-error">{{ $message }}</p>@enderror
        </div>

        <div class="mi-field">
            <label class="mi-label">Carbohydrate (g)</label>
            <input type="number" name="cho_g" id="f-carbs"
                   value="{{ old('cho_g', $item->cho_g ?? '') }}"
                   class="mi-input" min="0" step="0.01" placeholder="0.00">
            @error('cho_g')<p class="mi-error">{{ $message }}</p>@enderror
        </div>

        <div class="mi-field">
            <label class="mi-label">Protein (g)</label>
            <input type="number" name="protein_g" id="f-prot"
                   value="{{ old('protein_g', $item->protein_g ?? '') }}"
                   class="mi-input" min="0" step="0.01" placeholder="0.00">
            @error('protein_g')<p class="mi-error">{{ $message }}</p>@enderror
        </div>

        <div class="mi-field">
            <label class="mi-label">Fiber (g)</label>
            <input type="number" name="fiber_g" id="f-fiber"
                   value="{{ old('fiber_g', $item->fiber_g ?? '') }}"
                   class="mi-input" min="0" step="0.01" placeholder="0.00">
            @error('fiber_g')<p class="mi-error">{{ $message }}</p>@enderror
        </div>

    </div>
</div>

{{-- ── Other ─────────────────────────────────────────────────────── --}}
<div style="margin-top:1.1rem">
    <div class="mi-section-title">Other</div>
    <div style="max-width:15rem">
        <div class="mi-field">
            <label class="mi-label">Fruit &amp; Vegetable Portions</label>
            <select name="fruit_veg_portions" class="mi-input">
                @foreach([0,1,2,3,4,5] as $n)
                    <option value="{{ $n }}" @selected((int)old('fruit_veg_portions', $item->fruit_veg_portions ?? 0) === $n)>
                        {{ $n === 0 ? '0 — does not count' : '+' . $n }}
                    </option>
                @endforeach
            </select>
            @error('fruit_veg_portions')<p class="mi-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<script>
(function(){
    const nameEl    = document.getElementById('f-name');
    const servingEl = document.getElementById('f-serving');
    const kcalEl    = document.getElementById('f-kcal');
    const fatEl     = document.getElementById('f-fat');
    const carbsEl   = document.getElementById('f-carbs');
    const protEl    = document.getElementById('f-prot');
    const fiberEl   = document.getElementById('f-fiber');

    function fmt(el, dec) {
        const n = parseFloat(el ? el.value : '');
        return isNaN(n) || el.value === '' ? '—' : n.toFixed(dec);
    }

    function update() {
        const c = parseFloat(carbsEl.value) || 0;
        const p = parseFloat(protEl.value)  || 0;
        const f = parseFloat(fatEl.value)   || 0;

        let kcal = parseFloat(kcalEl.value);
        if (isNaN(kcal) || kcalEl.value === '') kcal = (c * 4) + (p * 4) + (f * 9);

        document.getElementById('card-name').textContent    = (nameEl.value.trim())    || '—';
        document.getElementById('card-serving').textContent = servingEl.value.trim() ? '· ' + servingEl.value.trim() : '';
        document.getElementById('card-kcal').textContent    = kcal > 0 ? Math.round(kcal) : '—';
        document.getElementById('card-fat').textContent     = fmt(fatEl,   2);
        document.getElementById('card-carbs').textContent   = fmt(carbsEl, 2);
        document.getElementById('card-prot').textContent    = fmt(protEl,  2);
        document.getElementById('card-fiber').textContent   = fmt(fiberEl, 2);
    }

    [nameEl, servingEl, kcalEl, fatEl, carbsEl, protEl, fiberEl]
        .filter(Boolean).forEach(el => el.addEventListener('input', update));

    update();
})();
</script>
