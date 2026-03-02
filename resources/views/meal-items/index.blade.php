<x-app-layout>

    @php
        $catColors = [
            'Fruit & Vegetables'       => ['bg'=>'#dcfce7','text'=>'#15803d','dot'=>'#22c55e'],
            'Starchy Foods'            => ['bg'=>'#fef9c3','text'=>'#854d0e','dot'=>'#eab308'],
            'Protein'                  => ['bg'=>'#e0e7ff','text'=>'#3730a3','dot'=>'#6366f1'],
            'Milk & Dairy'             => ['bg'=>'#e0f2fe','text'=>'#0369a1','dot'=>'#0ea5e9'],
            'Spreading Fat, Oil & Sauce' => ['bg'=>'#ffedd5','text'=>'#c2410c','dot'=>'#f97316'],
        ];
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
            <div>
                <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0">Meal Item Library</h1>
                <p style="font-size:.82rem;color:var(--text-muted);margin:.25rem 0 0">
                    {{ $items->total() }} items &mdash; system standard + your custom items
                </p>
            </div>
            <a href="{{ route('meal-items.create') }}"
               style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Custom Item
            </a>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div style="padding:.65rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Filters --}}
        <form method="GET" action="{{ route('meal-items.index') }}"
              style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.25rem;align-items:center">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name…"
                   style="flex:1;min-width:180px;padding:.45rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;outline:none">
            <select name="category"
                    style="padding:.45rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;background:#fff;outline:none">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit"
                    style="padding:.45rem 1rem;background:var(--primary);color:#fff;font-weight:700;font-size:.83rem;border:none;border-radius:6px;cursor:pointer">
                Filter
            </button>
            @if($search || $category)
                <a href="{{ route('meal-items.index') }}"
                   style="font-size:.82rem;color:var(--text-muted);text-decoration:none;padding:.45rem .5rem">✕ Clear</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="dash-section" style="padding:0">
            <div class="overflow-x-auto">
                <table class="exchange-table">
                    <thead>
                        <tr>
                            <th style="min-width:180px">Name</th>
                            <th>Category</th>
                            <th>Serving Size</th>
                            <th style="text-align:right">CHO (g)</th>
                            <th style="text-align:right">Protein (g)</th>
                            <th style="text-align:right">Fat (g)</th>
                            <th style="text-align:right">Energy (kJ)</th>
                            <th style="text-align:right">Energy (kcal)</th>
                            <th style="text-align:center">F&amp;V</th>
                            <th style="text-align:center">Source</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        @php $col = $catColors[$item->category] ?? ['bg'=>'#f1f5f9','text'=>'#475569','dot'=>'#94a3b8']; @endphp
                        <tr>
                            <td style="font-weight:600;color:var(--text-primary)">{{ $item->name }}</td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:999px;background:{{ $col['bg'] }};color:{{ $col['text'] }}">
                                    <span style="width:.45rem;height:.45rem;border-radius:50%;background:{{ $col['dot'] }};flex-shrink:0"></span>
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td style="color:var(--text-muted);font-size:.8rem">{{ $item->serving_size ?? '—' }}</td>
                            <td style="text-align:right">{{ $item->cho_g ?? '—' }}</td>
                            <td style="text-align:right">{{ $item->protein_g ?? '—' }}</td>
                            <td style="text-align:right">{{ $item->fat_g ?? '—' }}</td>
                            <td style="text-align:right">{{ $item->energy_kj ? number_format($item->energy_kj, 1) : '—' }}</td>
                            <td style="text-align:right">{{ $item->energy_kcal ? number_format($item->energy_kcal, 1) : '—' }}</td>
                            <td style="text-align:center">
                                @if($item->fruit_veg_portions > 0)
                                    <span style="font-size:.75rem;font-weight:700;padding:.15rem .45rem;border-radius:999px;background:#dcfce7;color:#15803d">
                                        +{{ $item->fruit_veg_portions }}
                                    </span>
                                @else
                                    <span style="color:#cbd5e1">—</span>
                                @endif
                            </td>
                            <td style="text-align:center">
                                @if($item->is_system)
                                    <span style="font-size:.7rem;font-weight:700;padding:.15rem .45rem;border-radius:999px;background:#e0f2fe;color:#0369a1">System</span>
                                @else
                                    <span style="font-size:.7rem;font-weight:700;padding:.15rem .45rem;border-radius:999px;background:#fef9c3;color:#854d0e">Custom</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap">
                                @if(!$item->is_system && $item->created_by === auth()->id())
                                    <a href="{{ route('meal-items.edit', $item) }}"
                                       style="font-size:.75rem;color:var(--primary);font-weight:600;text-decoration:none;margin-right:.5rem">Edit</a>
                                    <form method="POST" action="{{ route('meal-items.destroy', $item) }}"
                                          style="display:inline"
                                          onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                style="font-size:.75rem;color:#b91c1c;font-weight:600;background:none;border:none;cursor:pointer;padding:0">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" style="text-align:center;color:var(--text-muted);padding:2rem">
                                No items found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($items->hasPages())
            <div style="padding:1rem 1.25rem;border-top:1px solid var(--border)">
                {{ $items->links() }}
            </div>
            @endif
        </div>
    </div>

</x-app-layout>
