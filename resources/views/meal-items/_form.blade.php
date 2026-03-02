{{-- Shared form fields for create & edit --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">

    {{-- Name --}}
    <div style="grid-column:1/-1">
        <label class="form-label">Name <span style="color:#ef4444">*</span></label>
        <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}"
               class="form-input" placeholder="e.g. Whole fresh fruit" required>
        @error('name')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Category --}}
    <div>
        <label class="form-label">Category <span style="color:#ef4444">*</span></label>
        <select name="category" class="form-input" required>
            <option value="">— select —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" @selected(old('category', $item->category ?? '') === $cat)>{{ $cat }}</option>
            @endforeach
            <option value="Other" @selected(old('category', $item->category ?? '') === 'Other')>Other</option>
        </select>
        @error('category')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Serving size --}}
    <div>
        <label class="form-label">Serving Size</label>
        <input type="text" name="serving_size" value="{{ old('serving_size', $item->serving_size ?? '') }}"
               class="form-input" placeholder="e.g. One medium fillet (150g)">
        @error('serving_size')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Macros --}}
    <div>
        <label class="form-label">Carbohydrate (g per serving)</label>
        <input type="number" name="cho_g" value="{{ old('cho_g', $item->cho_g ?? '') }}"
               class="form-input" min="0" step="0.1" placeholder="0">
        @error('cho_g')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Protein (g per serving)</label>
        <input type="number" name="protein_g" value="{{ old('protein_g', $item->protein_g ?? '') }}"
               class="form-input" min="0" step="0.1" placeholder="0">
        @error('protein_g')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Fat (g per serving)</label>
        <input type="number" name="fat_g" value="{{ old('fat_g', $item->fat_g ?? '') }}"
               class="form-input" min="0" step="0.1" placeholder="0">
        @error('fat_g')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Energy (auto-calculated if blank) --}}
    <div>
        <label class="form-label">
            Energy (kJ)
            <span style="font-size:.7rem;color:var(--text-muted);font-weight:400"> — auto-calculated if left blank</span>
        </label>
        <input type="number" name="energy_kj" value="{{ old('energy_kj', $item->energy_kj ?? '') }}"
               class="form-input" min="0" step="0.1" placeholder="auto">
        @error('energy_kj')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">
            Energy (kcal)
            <span style="font-size:.7rem;color:var(--text-muted);font-weight:400"> — auto-calculated if left blank</span>
        </label>
        <input type="number" name="energy_kcal" value="{{ old('energy_kcal', $item->energy_kcal ?? '') }}"
               class="form-input" min="0" step="0.1" placeholder="auto">
        @error('energy_kcal')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Fruit & Veg portions --}}
    <div>
        <label class="form-label">Fruit &amp; Vegetable Portions</label>
        <select name="fruit_veg_portions" class="form-input">
            @foreach([0,1,2,3,4,5] as $n)
                <option value="{{ $n }}" @selected((int)old('fruit_veg_portions', $item->fruit_veg_portions ?? 0) === $n)>
                    {{ $n === 0 ? '0 — does not count' : '+' . $n }}
                </option>
            @endforeach
        </select>
        @error('fruit_veg_portions')<p class="form-error">{{ $message }}</p>@enderror
    </div>

</div>

{{-- Live energy preview --}}
<div id="energy-preview"
     style="margin-top:1rem;padding:.65rem 1rem;background:#fafafa;border:1px solid var(--border);border-radius:6px;font-size:.8rem;color:var(--text-muted);display:none">
    <strong style="color:var(--text-primary)">Calculated energy:</strong>
    <span id="prev-kj">—</span> kJ &nbsp;/&nbsp; <span id="prev-kcal">—</span> kcal
</div>

<script>
(function(){
    const cho  = document.querySelector('[name="cho_g"]');
    const pro  = document.querySelector('[name="protein_g"]');
    const fat  = document.querySelector('[name="fat_g"]');
    const prev = document.getElementById('energy-preview');
    const pKj  = document.getElementById('prev-kj');
    const pKc  = document.getElementById('prev-kcal');
    function calc(){
        const c=parseFloat(cho.value)||0, p=parseFloat(pro.value)||0, f=parseFloat(fat.value)||0;
        if(c+p+f===0){ prev.style.display='none'; return; }
        pKj.textContent  = ((c*17)+(p*17)+(f*37)).toFixed(1);
        pKc.textContent  = ((c*4) +(p*4) +(f*9) ).toFixed(1);
        prev.style.display='block';
    }
    [cho,pro,fat].forEach(el=>el.addEventListener('input',calc));
    calc();
})();
</script>
