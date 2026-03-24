<x-app-layout>
    @php
        $totalItems   = $groceryList->items->count();
        $checkedItems = $groceryList->items->where('checked', true)->count();
        $pct          = $totalItems > 0 ? round($checkedItems / $totalItems * 100) : 0;
        // All items: unchecked first, then checked
        $sortedItems  = $groceryList->items->sortBy('checked')->values();
    @endphp

    <style>
        .gl-wrap   { max-width:560px; margin:0 auto; padding:2rem 1rem 4rem; }
        .gl-paper  {
            background:#fffef9;
            border:1px solid #e5e3d8;
            border-radius:14px;
            box-shadow:0 4px 24px rgba(0,0,0,.07), 0 1px 3px rgba(0,0,0,.04);
            overflow:hidden;
        }
        /* ruled lines feel */
        .gl-body   {
            padding:0;
            background-image:repeating-linear-gradient(
                transparent, transparent 44px, #ece9df 44px, #ece9df 45px
            );
        }
        .gl-item   {
            display:flex; align-items:center; gap:.85rem;
            padding:.72rem 1.25rem;
            border-bottom:none;
            min-height:45px;
            transition:background .15s;
        }
        .gl-item:hover { background:rgba(0,0,0,.025); }
        .gl-item.is-checked { opacity:.55; }

        /* Custom checkbox */
        .gl-cb-wrap { display:contents; }
        .gl-cb {
            flex-shrink:0; width:20px; height:20px;
            border:2px solid #94a3b8; border-radius:5px;
            background:transparent;
            cursor:pointer; display:flex; align-items:center; justify-content:center;
            padding:0; transition:border-color .15s, background .15s;
        }
        .gl-cb.checked { border-color:#16a34a; background:#16a34a; }

        .gl-item-name {
            flex:1; font-size:.92rem; font-weight:600; color:#1e293b;
            line-height:1.3;
            transition:color .15s;
        }
        .gl-item-name.crossed { text-decoration:line-through; color:#94a3b8; font-weight:400; }

        .gl-del {
            border:none; background:none; cursor:pointer;
            color:#cbd5e1; font-size:.8rem; padding:.2rem .3rem;
            border-radius:4px; opacity:0; transition:opacity .15s, color .15s;
            flex-shrink:0;
        }
        .gl-item:hover .gl-del { opacity:1; }
        .gl-del:hover { color:#ef4444; }

        /* Add form */
        .gl-add-row {
            display:flex; gap:.6rem; align-items:center;
            padding:.75rem 1.25rem;
            border-top:2px dashed #e2e0d5;
            background:#fffef9;
        }
        .gl-add-input {
            flex:1; border:none; background:transparent;
            font-size:.9rem; color:#1e293b; outline:none;
            font-weight:500;
        }
        .gl-add-input::placeholder { color:#cbd5e1; font-weight:400; }
        .gl-add-btn {
            flex-shrink:0; width:28px; height:28px;
            background:var(--primary); color:#fff;
            border:none; border-radius:6px; cursor:pointer;
            font-size:1.1rem; font-weight:700; line-height:1;
            display:flex; align-items:center; justify-content:center;
        }
    </style>

    <div class="gl-wrap">

        {{-- Back + header --}}
        <a href="{{ route('grocery-lists.index') }}" style="font-size:.82rem;color:var(--primary);text-decoration:none;display:inline-block;margin-bottom:1.1rem">← Grocery Lists</a>

        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.1rem;gap:.75rem;flex-wrap:wrap">
            <div>
                <h1 style="font-size:1.25rem;font-weight:800;color:var(--text-primary);margin:0 0 .3rem">
                    🛒 {{ $groceryList->name ?: 'Grocery List #' . $groceryList->id }}
                </h1>
                <div style="display:flex;gap:.45rem;flex-wrap:wrap;align-items:center">
                    @if($groceryList->patient)
                        <span style="background:#ffedd5;color:#c2410c;font-size:.72rem;font-weight:700;padding:.15rem .55rem;border-radius:20px">
                            👤 {{ $groceryList->patient->name }}
                        </span>
                    @endif
                    @if($groceryList->week)
                        <a href="{{ route('meal-planner.show', [$groceryList->week->patient_id ?? 0, $groceryList->week]) }}"
                           style="background:#e0e7ff;color:#3730a3;font-size:.72rem;font-weight:700;padding:.15rem .55rem;border-radius:20px;text-decoration:none">
                            📅 {{ $groceryList->week->label ?: 'Week of ' . $groceryList->week->week_start->format('d M Y') }}
                        </a>
                    @endif
                </div>
            </div>
            {{-- Progress pill + email --}}
            @if($totalItems > 0)
                <div style="text-align:right;flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;gap:.5rem">
                    <div style="font-size:.78rem;font-weight:700;color:{{ $pct===100?'#15803d':'var(--text-muted)' }}">
                        {{ $checkedItems }}/{{ $totalItems }}
                        @if($pct===100) ✓ All done! @endif
                    </div>
                    <div style="width:120px;height:5px;background:#e5e7eb;border-radius:10px;overflow:hidden;margin-top:.3rem">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $pct===100?'#16a34a':'var(--primary)' }};border-radius:10px;transition:width .3s"></div>
                    </div>
                    @if($groceryList->patient?->email)
                        <form method="POST" action="{{ route('grocery-lists.email', $groceryList) }}" style="margin:0">
                            @csrf
                            <button type="submit"
                                    style="display:flex;align-items:center;gap:.3rem;padding:.3rem .75rem;background:#16a34a;color:#fff;border:none;border-radius:20px;font-size:.72rem;font-weight:700;cursor:pointer;white-space:nowrap"
                                    onclick="this.disabled=true;this.textContent='Sending…';this.form.submit()">
                                ✉ Email to Patient
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        @if(session('success'))
            <div style="padding:.6rem 1rem;background:#dcfce7;color:#15803d;border-radius:8px;font-size:.82rem;font-weight:600;margin-bottom:1rem">✓ {{ session('success') }}</div>
        @endif

        {{-- The list --}}
        <div class="gl-paper">
            <div class="gl-body">
                @forelse($sortedItems as $item)
                    <div class="gl-item {{ $item->checked ? 'is-checked' : '' }}">
                        {{-- Check toggle --}}
                        <form method="POST" action="{{ route('grocery-lists.items.toggle', [$groceryList, $item]) }}" class="gl-cb-wrap">
                            @csrf @method('PATCH')
                            <button type="submit" class="gl-cb {{ $item->checked ? 'checked' : '' }}" title="{{ $item->checked ? 'Uncheck' : 'Check off' }}">
                                @if($item->checked)
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </button>
                        </form>

                        <span class="gl-item-name {{ $item->checked ? 'crossed' : '' }}">{{ $item->item }}</span>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('grocery-lists.items.remove', [$groceryList, $item]) }}" style="display:contents">
                            @csrf @method('DELETE')
                            <button type="submit" class="gl-del" title="Remove">✕</button>
                        </form>
                    </div>
                @empty
                    <div style="padding:2.5rem 1.25rem;text-align:center;color:#94a3b8;font-size:.85rem">
                        No items yet — add one below.
                    </div>
                @endforelse
            </div>

            {{-- Inline add form --}}
            <form method="POST" action="{{ route('grocery-lists.items.add', $groceryList) }}" class="gl-add-row">
                @csrf
                {{-- hidden category — default to pantry; user can't see it --}}
                <input type="hidden" name="category" value="pantry">
                <input type="text" name="item" required
                       placeholder="Add an item…"
                       class="gl-add-input"
                       autocomplete="off">
                <button type="submit" class="gl-add-btn" title="Add item">+</button>
            </form>
        </div>

        {{-- Delete list --}}
        <div style="margin-top:1.5rem;text-align:center">
            <form method="POST" action="{{ route('grocery-lists.destroy', $groceryList) }}" onsubmit="return confirm('Delete this grocery list?')">
                @csrf @method('DELETE')
                <button type="submit" style="background:none;border:none;cursor:pointer;font-size:.78rem;color:#94a3b8;text-decoration:underline">
                    Delete list
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

