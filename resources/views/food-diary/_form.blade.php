{{-- Patient --}}
<div style="margin-bottom:1.1rem">
    <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">Patient <span style="color:var(--text-muted);font-weight:400">(optional)</span></label>
    <select name="patient_id" style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:#fff;color:var(--text-primary)">
        <option value="">— No patient —</option>
        @foreach($patients as $p)
            <option value="{{ $p->id }}" @selected(old('patient_id', $foodDiary->patient_id ?? '') == $p->id)>
                {{ $p->name }} {{ $p->surname }}
            </option>
        @endforeach
    </select>
</div>

{{-- Date --}}
<div style="margin-bottom:1.1rem">
    <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">Date <span style="color:#dc2626">*</span></label>
    <input type="date" name="diary_date"
           value="{{ old('diary_date', isset($foodDiary) ? $foodDiary->diary_date->format('Y-m-d') : date('Y-m-d')) }}"
           required
           style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;box-sizing:border-box">
    @error('diary_date')<p style="color:#dc2626;font-size:.75rem;margin-top:.3rem">{{ $message }}</p>@enderror
</div>

{{-- Meal slots --}}
@foreach(['breakfast' => 'Breakfast', 'snack1' => 'Snack (Morning)', 'lunch' => 'Lunch', 'snack2' => 'Snack (Afternoon)', 'supper' => 'Supper', 'snack3' => 'Snack (Evening)'] as $slot => $label)
<div style="margin-bottom:1.1rem">
    <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">{{ $label }}</label>
    <textarea name="{{ $slot }}" rows="2"
              style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;resize:vertical;box-sizing:border-box"
              placeholder="What was eaten...">{{ old($slot, $foodDiary->{$slot} ?? '') }}</textarea>
</div>
@endforeach

{{-- Rating --}}
@php $initRating = old('rating', $foodDiary->rating ?? null); @endphp
<div style="margin-bottom:1.1rem"
     x-data="{ rating: {{ $initRating ?? 'null' }}, hover: 0, labels: ['','Poor','Fair','Good','Great','Excellent'] }">
    <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.5rem">
        Rate your day <span style="color:var(--text-muted);font-weight:400">(optional)</span>
    </label>
    <div style="display:flex;gap:0;align-items:center">
        @for($i = 1; $i <= 5; $i++)
        <span
            @mouseenter="hover = {{ $i }}"
            @mouseleave="hover = 0"
            @click="rating = (rating === {{ $i }} ? null : {{ $i }})"
            :style="(hover || rating) >= {{ $i }} ? 'color:#2d5a43;transform:scale(1.12)' : 'color:#d1d5db;transform:scale(1)'"
            style="font-size:2rem;cursor:pointer;line-height:1;transition:color .1s,transform .12s;user-select:none;padding:0 .1rem"
        >&#9733;</span>
        @endfor
        <input type="hidden" name="rating" :value="rating !== null ? rating : ''">
        <span x-text="hover ? labels[hover] : (rating ? labels[rating] : '')"
              :style="(hover || rating) ? 'opacity:1' : 'opacity:0'"
              style="font-size:.78rem;font-weight:700;color:#2d5a43;margin-left:.6rem;min-width:64px;transition:opacity .15s;text-transform:uppercase;letter-spacing:.05em"></span>
    </div>
</div>

{{-- Improvement --}}
<div style="margin-bottom:1.1rem">
    <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">What can I improve on?</label>
    <textarea name="improvement" rows="3"
              style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;resize:vertical;box-sizing:border-box"
              placeholder="Reflections...">{{ old('improvement', $foodDiary->improvement ?? '') }}</textarea>
</div>
