<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem">
            <div>
                <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0">Weekly Meal Planner</h1>
                <p style="font-size:.82rem;color:var(--text-muted);margin:.25rem 0 0">Plan meals day-by-day for your patients</p>
            </div>
            <a href="{{ route('meal-planner.create') }}"
               style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Week
            </a>
        </div>

        {{-- Search bar --}}
        <form method="GET" action="{{ route('meal-planner.index') }}" style="margin-bottom:1.25rem">
            <div style="display:flex;gap:.5rem;max-width:360px">
                <div style="position:relative;flex:1">
                    <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:.65rem;top:50%;transform:translateY(-50%);width:.85rem;height:.85rem;color:#94a3b8;pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
                    <input type="text" name="q" value="{{ $search }}"
                           placeholder="Search patient…"
                           style="width:100%;padding:.42rem .75rem .42rem 2rem;font-size:.82rem;border:1px solid var(--border);border-radius:6px;outline:none;box-sizing:border-box;background:#fff"
                           onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                </div>
                @if($search)
                    <a href="{{ route('meal-planner.index') }}"
                       style="padding:.42rem .75rem;background:#f1f5f9;color:var(--text-muted);font-size:.8rem;font-weight:600;border-radius:6px;text-decoration:none;white-space:nowrap;border:1px solid var(--border)">✕ Clear</a>
                @else
                    <button type="submit" style="padding:.42rem .9rem;background:var(--primary);color:#fff;font-size:.8rem;font-weight:700;border-radius:6px;border:none;cursor:pointer">Search</button>
                @endif
            </div>
        </form>

        {{-- Flash --}}
        @if(session('success'))
            <div style="padding:.65rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">✓ {{ session('success') }}</div>
        @endif

        @if($pagedGrouped->isEmpty())
            <div style="text-align:center;padding:4rem 2rem;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:1rem">🗓️</div>
                @if($search)
                    <p style="font-weight:600;margin:0 0 .5rem">No results for &ldquo;{{ $search }}&rdquo;</p>
                    <a href="{{ route('meal-planner.index') }}" style="font-size:.85rem;color:var(--primary)">Clear search</a>
                @else
                    <p style="font-weight:600;margin:0 0 .5rem">No meal plans yet</p>
                    <p style="font-size:.85rem;margin:0 0 1.25rem">Create your first weekly meal plan to get started.</p>
                    <a href="{{ route('meal-planner.create') }}" style="display:inline-block;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">Create Plan</a>
                @endif
            </div>
        @else
            {{-- Result count --}}
            @if($search)
                <p style="font-size:.78rem;color:var(--text-muted);margin-bottom:.75rem">
                    {{ $totalPatients }} {{ Str::plural('patient', $totalPatients) }} matching &ldquo;{{ $search }}&rdquo;
                </p>
            @endif

            <div style="display:flex;flex-direction:column;gap:.75rem">
                @foreach($pagedGrouped as $patientName => $patientWeeks)
                    @php $groupId = 'mpg-' . Str::slug($patientName, '-') . '-' . $loop->index; @endphp
                    <div style="background:#fff;border:1px solid var(--border);border-radius:10px;overflow:hidden">

                        {{-- Collapsible patient header --}}
                        <button type="button"
                                onclick="toggleGroup('{{ $groupId }}')"
                                style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:.55rem 1rem;background:#f8fafc;border:none;cursor:pointer;text-align:left;gap:.75rem">
                            <div style="display:flex;align-items:center;gap:.6rem;min-width:0">
                                <div style="width:1.6rem;height:1.6rem;flex-shrink:0;border-radius:50%;background:var(--primary);color:#fff;font-size:.62rem;font-weight:800;display:flex;align-items:center;justify-content:center">
                                    {{ collect(explode(' ', $patientName))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('') }}
                                </div>
                                <span style="font-weight:700;font-size:.85rem;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $patientName }}</span>
                                <span style="background:#e0e7ff;color:#3730a3;font-size:.66rem;font-weight:700;padding:.1rem .45rem;border-radius:20px;flex-shrink:0">
                                    {{ $patientWeeks->count() }} {{ Str::plural('plan', $patientWeeks->count()) }}
                                </span>
                            </div>
                            <svg id="{{ $groupId }}-chevron" xmlns="http://www.w3.org/2000/svg"
                                 style="width:.9rem;height:.9rem;flex-shrink:0;color:#94a3b8;transition:transform .2s;transform:rotate(-90deg)"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Week rows (collapsed by default) --}}
                        <div id="{{ $groupId }}" style="display:none">
                            @foreach($patientWeeks as $i => $week)
                                <div style="display:flex;align-items:center;justify-content:space-between;padding:.4rem 1rem;gap:.75rem;{{ $i > 0 ? 'border-top:1px solid #f1f5f9' : 'border-top:1px solid var(--border)' }}">
                                    <div style="display:flex;align-items:center;gap:.6rem;flex:1;min-width:0">
                                        <span style="font-size:.8rem;font-weight:700;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px">
                                            {{ $week->label ?: 'Week of ' . $week->week_start->format('d M Y') }}
                                        </span>
                                        <span style="background:#f1f5f9;color:#475569;font-size:.67rem;font-weight:600;padding:.1rem .42rem;border-radius:20px;white-space:nowrap;flex-shrink:0">
                                            {{ $week->week_start->format('d M') }}–{{ $week->week_start->copy()->addDays(6)->format('d M Y') }}
                                        </span>
                                        <span style="font-size:.7rem;color:var(--text-muted);flex-shrink:0">
                                            {{ $week->entries->count() }} {{ Str::plural('entry', $week->entries->count()) }}
                                        </span>
                                    </div>
                                    <div style="display:flex;gap:.35rem;flex-shrink:0">
                                        <a href="{{ route('meal-planner.show', [$week->patient_id ?? 0, $week]) }}"
                                           style="padding:.28rem .7rem;background:var(--primary);color:#fff;font-size:.73rem;font-weight:700;border-radius:5px;text-decoration:none;white-space:nowrap">Open</a>
                                        <form method="POST" action="{{ route('meal-planner.destroy', [$week->patient_id ?? 0, $week]) }}" onsubmit="return confirm('Delete this plan?')" style="margin:0">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="padding:.28rem .7rem;background:#fee2e2;color:#b91c1c;font-size:.73rem;font-weight:700;border-radius:5px;border:none;cursor:pointer;white-space:nowrap">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Paginator (only when > 10 patients) --}}
            @if($totalPatients > 10)
                <div style="margin-top:1.25rem">
                    {{ $paginator->appends(['q' => $search])->links() }}
                </div>
            @endif
        @endif
    </div>

    <script>
    function toggleGroup(id) {
        var body    = document.getElementById(id);
        var chevron = document.getElementById(id + '-chevron');
        if (!body) return;
        var open = body.style.display !== 'none';
        body.style.display    = open ? 'none' : '';
        if (chevron) chevron.style.transform = open ? 'rotate(-90deg)' : 'rotate(0deg)';
    }
    </script>
</x-app-layout>

