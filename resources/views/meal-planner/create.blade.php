<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div style="margin-bottom:1.5rem">
            <a href="{{ route('meal-planner.index') }}" style="font-size:.82rem;color:var(--primary);text-decoration:none">← Back to Planner</a>
            <h1 style="font-size:1.3rem;font-weight:800;color:var(--text-primary);margin:.5rem 0 0">New Weekly Plan</h1>
        </div>

        <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.75rem">
            <form method="POST" action="{{ route('meal-planner.store') }}">
                @csrf

                {{-- Patient --}}
                <div style="margin-bottom:1.1rem">
                    <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">
                        Patient <span style="color:var(--text-muted);font-weight:400">(optional)</span>
                    </label>
                    <select name="patient_id" style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;background:#fff">
                        <option value="">— General / unassigned —</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" @selected(old('patient_id') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id')<p style="color:#dc2626;font-size:.75rem;margin:.25rem 0 0">{{ $message }}</p>@enderror
                </div>

                {{-- Week start --}}
                <div style="margin-bottom:1.1rem">
                    <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">
                        Week Starting (Monday) <span style="color:#dc2626">*</span>
                    </label>
                    <input type="date" name="week_start" value="{{ old('week_start', $weekStart) }}"
                           style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;box-sizing:border-box">
                    @error('week_start')<p style="color:#dc2626;font-size:.75rem;margin:.25rem 0 0">{{ $message }}</p>@enderror
                </div>

                {{-- Label --}}
                <div style="margin-bottom:1.5rem">
                    <label style="display:block;font-size:.82rem;font-weight:700;color:var(--text-primary);margin-bottom:.35rem">
                        Label <span style="color:var(--text-muted);font-weight:400">(optional)</span>
                    </label>
                    <input type="text" name="label" value="{{ old('label') }}" placeholder="e.g. Low-carb week"
                           style="width:100%;padding:.5rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.85rem;box-sizing:border-box">
                </div>

                <div style="display:flex;gap:.75rem">
                    <button type="submit" style="padding:.55rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border:none;border-radius:6px;cursor:pointer">Create Plan</button>
                    <a href="{{ route('meal-planner.index') }}" style="padding:.55rem 1.25rem;background:#f1f5f9;color:var(--text-primary);font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
