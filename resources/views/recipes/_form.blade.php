{{-- Shared recipe form partial --}}
<div style="display:grid;gap:1.1rem">

    {{-- Name --}}
    <div>
        <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">
            Recipe Name <span style="color:#dc2626">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name', $recipe->name ?? '') }}" required
               style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;box-sizing:border-box">
        @error('name')<p style="color:#dc2626;font-size:.75rem;margin:.25rem 0 0">{{ $message }}</p>@enderror
    </div>

    {{-- Category + servings row --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.75rem">
        <div>
            <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">Category</label>
            <select name="category" style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:#fff;box-sizing:border-box">
                <option value="">— Select —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(old('category', $recipe->category ?? '') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">Servings</label>
            <input type="number" name="servings" min="1" value="{{ old('servings', $recipe->servings ?? '') }}"
                   style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;box-sizing:border-box">
        </div>
        <div>
            <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">Prep (min)</label>
            <input type="number" name="prep_time_min" min="0" value="{{ old('prep_time_min', $recipe->prep_time_min ?? '') }}"
                   style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;box-sizing:border-box">
        </div>
    </div>

    <div>
        <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">Cook Time (min)</label>
        <input type="number" name="cook_time_min" min="0" value="{{ old('cook_time_min', $recipe->cook_time_min ?? '') }}"
               style="width:220px;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem">
    </div>

    {{-- Ingredients --}}
    <div>
        <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">Ingredients</label>
        <textarea name="ingredients" rows="6" placeholder="List ingredients, one per line…"
                  style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.84rem;resize:vertical;box-sizing:border-box;font-family:inherit">{{ old('ingredients', $recipe->ingredients ?? '') }}</textarea>
    </div>

    {{-- Directions --}}
    <div>
        <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">Directions</label>
        <textarea name="directions" rows="8" placeholder="Step-by-step instructions…"
                  style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.84rem;resize:vertical;box-sizing:border-box;font-family:inherit">{{ old('directions', $recipe->directions ?? '') }}</textarea>
    </div>

    {{-- Notes --}}
    <div>
        <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">Notes <span style="color:var(--text-muted);font-weight:400">(optional)</span></label>
        <textarea name="notes" rows="3"
                  style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.84rem;resize:vertical;box-sizing:border-box;font-family:inherit">{{ old('notes', $recipe->notes ?? '') }}</textarea>
    </div>

</div>
