<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div style="margin-bottom:1.5rem">
            <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0">🛒 Grocery Lists</h1>
            <p style="font-size:.82rem;color:var(--text-muted);margin:.25rem 0 0">Organise your shopping by category</p>
        </div>

        {{-- Generate from a meal plan --}}
        @if($availableWeeks->isNotEmpty())
            <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;color:#0369a1;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/></svg>
                <span style="font-size:.82rem;font-weight:700;color:#0369a1;white-space:nowrap">Generate from meal plan:</span>
                <form method="POST" id="generate-form"
                      action="{{ route('grocery-lists.generate-from-plan', '__WEEK__') }}"
                      style="display:flex;gap:.5rem;flex:1;flex-wrap:wrap;align-items:center">
                    @csrf
                    <select id="generate-week-select"
                            style="flex:1;min-width:200px;padding:.4rem .7rem;border:1px solid #bae6fd;border-radius:6px;font-size:.82rem;background:#fff;color:var(--text-primary)">
                        <option value="">— Select a week —</option>
                        @foreach($availableWeeks as $w)
                            <option value="{{ $w->id }}">
                                {{ $w->patient?->name ? $w->patient->name . ' — ' : '' }}{{ $w->label ?: 'Week of ' . $w->week_start->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" id="generate-btn" disabled
                            style="padding:.4rem 1rem;background:#0369a1;color:#fff;font-weight:700;font-size:.82rem;border:none;border-radius:6px;cursor:pointer;white-space:nowrap;opacity:.5">
                        🛒 Generate
                    </button>
                </form>
            </div>
            <script>
            (function(){
                var sel = document.getElementById('generate-week-select');
                var btn = document.getElementById('generate-btn');
                var form = document.getElementById('generate-form');
                var base = form.action.replace('__WEEK__','');
                sel.addEventListener('change', function(){
                    if (this.value) {
                        form.action = '{{ url("grocery-lists/generate-from-plan") }}/' + this.value;
                        btn.disabled = false;
                        btn.style.opacity = '1';
                    } else {
                        btn.disabled = true;
                        btn.style.opacity = '.5';
                    }
                });
            })();
            </script>
        @endif

        @if(session('success'))
            <div style="padding:.65rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">✓ {{ session('success') }}</div>
        @endif

        @if($lists->isEmpty())
            <div style="text-align:center;padding:4rem 2rem;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:1rem">🛒</div>
                <p style="font-weight:600;margin:0 0 .5rem">No grocery lists yet</p>
                <a href="{{ route('grocery-lists.create') }}" style="display:inline-block;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">Create First List</a>
            </div>
        @else
            <div style="display:grid;gap:.75rem">
                @foreach($lists as $list)
                    <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:.85rem 1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
                        <div>
                            <div style="font-weight:700;color:var(--text-primary);font-size:.9rem">
                                {{ $list->name ?: 'Grocery List #' . $list->id }}
                            </div>
                            <div style="font-size:.75rem;color:var(--text-muted);margin-top:.25rem;display:flex;gap:.75rem;flex-wrap:wrap;align-items:center">
                                <span>{{ $list->items->count() }} items &middot; {{ $list->items->where('checked', true)->count() }} checked</span>
                                @if($list->patient)<span style="background:#ffedd5;color:#c2410c;font-weight:700;padding:.1rem .45rem;border-radius:20px">👤 {{ $list->patient->name }}</span>@endif
                                @if($list->week)
                                    <a href="{{ route('meal-planner.show', [$list->week->patient_id ?? 0, $list->week]) }}"
                                       style="background:#e0e7ff;color:#3730a3;font-weight:700;padding:.1rem .45rem;border-radius:20px;text-decoration:none;font-size:.7rem">
                                       📅 {{ $list->week->label ?: 'Week of ' . $list->week->week_start->format('d M Y') }}
                                    </a>
                                @endif
                                <span>{{ $list->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div style="display:flex;gap:.5rem">
                            <a href="{{ route('grocery-lists.show', $list) }}"
                               style="padding:.4rem .9rem;background:var(--primary);color:#fff;font-size:.8rem;font-weight:700;border-radius:6px;text-decoration:none">Open</a>
                            <form method="POST" action="{{ route('grocery-lists.destroy', $list) }}" onsubmit="return confirm('Delete this list?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:.4rem .9rem;background:#fee2e2;color:#b91c1c;font-size:.8rem;font-weight:700;border-radius:6px;border:none;cursor:pointer">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="margin-top:1.25rem">{{ $lists->links() }}</div>
        @endif
    </div>
</x-app-layout>
