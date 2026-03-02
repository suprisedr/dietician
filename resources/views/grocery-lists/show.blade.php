<x-app-layout>
    @php
        $catIcons = [
            'pantry'    => '🥫',
            'produce'   => '🥬',
            'meat'      => '🥩',
            'dairy'     => '🧀',
            'bakery'    => '🍞',
            'household' => '🧹',
        ];
        $catColors = [
            'pantry'    => ['bg'=>'#fff7ed','border'=>'#fed7aa','head'=>'#c2410c'],
            'produce'   => ['bg'=>'#f0fdf4','border'=>'#bbf7d0','head'=>'#15803d'],
            'meat'      => ['bg'=>'#fff1f2','border'=>'#fecdd3','head'=>'#be123c'],
            'dairy'     => ['bg'=>'#eff6ff','border'=>'#bfdbfe','head'=>'#1d4ed8'],
            'bakery'    => ['bg'=>'#fffbeb','border'=>'#fde68a','head'=>'#92400e'],
            'household' => ['bg'=>'#f5f3ff','border'=>'#ddd6fe','head'=>'#6d28d9'],
        ];
        $totalItems   = $groceryList->items->count();
        $checkedItems = $groceryList->items->where('checked', true)->count();
        $pct          = $totalItems > 0 ? round($checkedItems / $totalItems * 100) : 0;
    @endphp

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem">
            <div>
                <a href="{{ route('grocery-lists.index') }}" style="font-size:.82rem;color:var(--primary);text-decoration:none">← Grocery Lists</a>
                <h1 style="font-size:1.3rem;font-weight:800;color:var(--text-primary);margin:.35rem 0 .2rem">
                    🛒 {{ $groceryList->name ?: 'Grocery List #' . $groceryList->id }}
                </h1>
                @if($groceryList->patient)
                    <span style="background:#ffedd5;color:#c2410c;font-size:.72rem;font-weight:700;padding:.15rem .55rem;border-radius:20px">{{ $groceryList->patient->name }}</span>
                @endif
            </div>
            {{-- Progress --}}
            @if($totalItems > 0)
                <div style="text-align:right">
                    <div style="font-size:.8rem;font-weight:700;color:var(--text-muted)">{{ $checkedItems }}/{{ $totalItems }} items</div>
                    <div style="width:150px;height:6px;background:#e5e7eb;border-radius:10px;overflow:hidden;margin-top:.3rem">
                        <div style="height:100%;width:{{ $pct }}%;background:var(--primary);border-radius:10px;transition:width .3s"></div>
                    </div>
                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:.2rem">{{ $pct }}% complete</div>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div style="padding:.65rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">✓ {{ session('success') }}</div>
        @endif

        {{-- Add item form --}}
        <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.1rem 1.25rem;margin-bottom:1.5rem">
            <form method="POST" action="{{ route('grocery-lists.items.add', $groceryList) }}"
                  style="display:flex;gap:.65rem;align-items:flex-end;flex-wrap:wrap">
                @csrf
                <div style="flex:1;min-width:160px">
                    <label style="display:block;font-size:.75rem;font-weight:700;color:var(--text-muted);margin-bottom:.2rem">Item *</label>
                    <input type="text" name="item" required placeholder="e.g. Skinless chicken"
                           style="width:100%;padding:.45rem .65rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;color:var(--text-muted);margin-bottom:.2rem">Category *</label>
                    <select name="category" required
                            style="padding:.45rem .65rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;background:#fff">
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $catIcons[$key] }} {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        style="padding:.45rem 1rem;background:var(--primary);color:#fff;font-weight:700;font-size:.83rem;border:none;border-radius:6px;cursor:pointer;white-space:nowrap">
                    + Add
                </button>
            </form>
        </div>

        {{-- Grocery columns --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem">
            @foreach($categories as $key => $label)
                @php $catItems = $byCategory[$key]; @endphp
                <div style="background:{{ $catColors[$key]['bg'] }};border:1px solid {{ $catColors[$key]['border'] }};border-radius:10px;overflow:hidden">
                    {{-- Category header --}}
                    <div style="padding:.6rem .9rem;display:flex;align-items:center;gap:.4rem;border-bottom:1px solid {{ $catColors[$key]['border'] }}">
                        <span style="font-size:1rem">{{ $catIcons[$key] }}</span>
                        <span style="font-size:.82rem;font-weight:800;color:{{ $catColors[$key]['head'] }}">{{ $label }}</span>
                        <span style="margin-left:auto;font-size:.7rem;color:{{ $catColors[$key]['head'] }};background:#fff8;padding:.1rem .4rem;border-radius:20px">
                            {{ $catItems->where('checked',true)->count() }}/{{ $catItems->count() }}
                        </span>
                    </div>
                    {{-- Items --}}
                    <div style="padding:.5rem .75rem">
                        @forelse($catItems as $item)
                            <div style="display:flex;align-items:center;gap:.5rem;padding:.3rem 0;border-bottom:1px solid {{ $catColors[$key]['border'] }}">
                                {{-- Toggle check --}}
                                <form method="POST" action="{{ route('grocery-lists.items.toggle', [$groceryList, $item]) }}" style="display:contents">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            style="flex-shrink:0;width:18px;height:18px;border:2px solid {{ $catColors[$key]['head'] }};border-radius:4px;background:{{ $item->checked?$catColors[$key]['head']:'transparent' }};cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0">
                                        @if($item->checked)
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <span style="flex:1;font-size:.82rem;font-weight:{{ $item->checked?'400':'600' }};color:{{ $item->checked?'var(--text-muted)':'var(--text-primary)' }};text-decoration:{{ $item->checked?'line-through':'none' }}">
                                    {{ $item->item }}
                                </span>
                                {{-- Remove --}}
                                <form method="POST" action="{{ route('grocery-lists.items.remove', [$groceryList, $item]) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="border:none;background:none;cursor:pointer;color:{{ $catColors[$key]['head'] }};font-size:.7rem;padding:0 .2rem;opacity:.6" title="Remove">✕</button>
                                </form>
                            </div>
                        @empty
                            <p style="font-size:.78rem;color:var(--text-muted);text-align:center;padding:.75rem 0;margin:0">No items</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
