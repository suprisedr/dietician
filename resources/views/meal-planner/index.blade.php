<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
            <div>
                <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0">Weekly Meal Planner</h1>
                <p style="font-size:.82rem;color:var(--text-muted);margin:.25rem 0 0">
                    Plan meals day-by-day for your patients
                </p>
            </div>
            <a href="{{ route('meal-planner.create') }}"
               style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Week
            </a>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div style="padding:.65rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">✓ {{ session('success') }}</div>
        @endif

        @if($weeks->isEmpty())
            <div style="text-align:center;padding:4rem 2rem;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:1rem">🗓️</div>
                <p style="font-weight:600;margin:0 0 .5rem">No meal plans yet</p>
                <p style="font-size:.85rem;margin:0 0 1.25rem">Create your first weekly meal plan to get started.</p>
                <a href="{{ route('meal-planner.create') }}" style="display:inline-block;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">Create Plan</a>
            </div>
        @else
            <div style="display:grid;gap:1rem">
                @foreach($weeks as $week)
                    <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
                        <div>
                            <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap">
                                <span style="font-weight:700;color:var(--text-primary);font-size:.95rem">
                                    {{ $week->label ?: 'Week of ' . $week->week_start->format('d M Y') }}
                                </span>
                                <span style="background:#e0e7ff;color:#3730a3;font-size:.72rem;font-weight:700;padding:.15rem .55rem;border-radius:20px">
                                    {{ $week->week_start->format('d M') }} – {{ $week->week_start->addDays(6)->format('d M Y') }}
                                </span>
                                @if($week->patient)
                                    <span style="background:#ffedd5;color:#c2410c;font-size:.72rem;font-weight:700;padding:.15rem .55rem;border-radius:20px">
                                        {{ $week->patient->name }}
                                    </span>
                                @endif
                            </div>
                            <div style="font-size:.78rem;color:var(--text-muted);margin-top:.25rem">
                                {{ $week->entries->count() }} meal entries
                            </div>
                        </div>
                        <div style="display:flex;gap:.5rem">
                            <a href="{{ route('meal-planner.show', $week) }}"
                               style="padding:.4rem .9rem;background:var(--primary);color:#fff;font-size:.8rem;font-weight:700;border-radius:6px;text-decoration:none">Open</a>
                            <form method="POST" action="{{ route('meal-planner.destroy', $week) }}" onsubmit="return confirm('Delete this plan?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:.4rem .9rem;background:#fee2e2;color:#b91c1c;font-size:.8rem;font-weight:700;border-radius:6px;border:none;cursor:pointer">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="margin-top:1.25rem">{{ $weeks->links() }}</div>
        @endif
    </div>
</x-app-layout>
