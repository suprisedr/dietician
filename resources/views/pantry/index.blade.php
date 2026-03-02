<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
            <div>
                <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0">
                    {{ $type === 'freezer' ? '🧊 Freezer' : '📦 Pantry' }} Inventory
                </h1>
                <p style="font-size:.82rem;color:var(--text-muted);margin:.25rem 0 0">Track what you have in stock</p>
            </div>
            {{-- Type toggle --}}
            <div style="display:flex;gap:.4rem">
                <a href="{{ route('pantry.index', ['type'=>'pantry']) }}"
                   style="padding:.4rem .9rem;font-size:.8rem;font-weight:700;border-radius:6px;text-decoration:none;background:{{ $type==='pantry'?'var(--primary)':'#f1f5f9' }};color:{{ $type==='pantry'?'#fff':'var(--text-primary)' }}">📦 Pantry</a>
                <a href="{{ route('pantry.index', ['type'=>'freezer']) }}"
                   style="padding:.4rem .9rem;font-size:.8rem;font-weight:700;border-radius:6px;text-decoration:none;background:{{ $type==='freezer'?'var(--primary)':'#f1f5f9' }};color:{{ $type==='freezer'?'#fff':'var(--text-primary)' }}">🧊 Freezer</a>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div style="padding:.65rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">✓ {{ session('success') }}</div>
        @endif

        {{-- Add item form --}}
        <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.25rem;margin-bottom:1.5rem">
            <h2 style="font-size:.88rem;font-weight:800;color:var(--text-primary);margin:0 0 .9rem">Add Item</h2>
            <form method="POST" action="{{ route('pantry.store') }}" style="display:grid;grid-template-columns:1fr 1fr auto auto auto;gap:.65rem;align-items:end;flex-wrap:wrap">
                @csrf
                <input type="hidden" name="storage_type" value="{{ $type }}">

                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;color:var(--text-muted);margin-bottom:.2rem">Item *</label>
                    <input type="text" name="item" required placeholder="e.g. Brown rice"
                           style="width:100%;padding:.45rem .65rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;color:var(--text-muted);margin-bottom:.2rem">Quantity</label>
                    <input type="text" name="quantity" placeholder="e.g. 2kg"
                           style="width:100%;padding:.45rem .65rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;color:var(--text-muted);margin-bottom:.2rem">Date Added</label>
                    <input type="date" name="date_added"
                           style="padding:.45rem .65rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem">
                </div>
                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;color:var(--text-muted);margin-bottom:.2rem">Expiry</label>
                    <input type="date" name="expiry_date"
                           style="padding:.45rem .65rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem">
                </div>
                <div>
                    <button type="submit"
                            style="padding:.45rem 1rem;background:var(--primary);color:#fff;font-weight:700;font-size:.83rem;border:none;border-radius:6px;cursor:pointer;white-space:nowrap">
                        + Add
                    </button>
                </div>
            </form>
        </div>

        {{-- Inventory list --}}
        @if($items->isEmpty())
            <div style="text-align:center;padding:3rem 2rem;color:var(--text-muted)">
                <p style="margin:0">No {{ $type }} items yet. Add your first item above.</p>
            </div>
        @else
            <div style="background:#fff;border:1px solid var(--border);border-radius:10px;overflow:hidden">
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="background:#f8fafc;border-bottom:2px solid var(--border)">
                            <th style="padding:.65rem .9rem;font-size:.75rem;font-weight:700;color:var(--text-muted);text-align:left">Item</th>
                            <th style="padding:.65rem .9rem;font-size:.75rem;font-weight:700;color:var(--text-muted);text-align:left">Quantity</th>
                            <th style="padding:.65rem .9rem;font-size:.75rem;font-weight:700;color:var(--text-muted);text-align:left">Date Added</th>
                            <th style="padding:.65rem .9rem;font-size:.75rem;font-weight:700;color:var(--text-muted);text-align:left">Expiry</th>
                            <th style="padding:.65rem .9rem;font-size:.75rem;font-weight:700;color:var(--text-muted);text-align:center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            @php
                                $expiringSoon = $item->expiry_date && $item->expiry_date->diffInDays(now()) <= 7 && $item->expiry_date->isFuture();
                                $expired      = $item->expiry_date && $item->expiry_date->isPast();
                            @endphp
                            <tr style="border-bottom:1px solid var(--border);{{ $expired?'background:#fff7f7':'($expiringSoon?background:#fffbeb:'')' }}">
                                <td style="padding:.6rem .9rem;font-size:.83rem;font-weight:600;color:var(--text-primary)">
                                    {{ $item->item }}
                                    @if($expired)
                                        <span style="background:#fee2e2;color:#b91c1c;font-size:.65rem;font-weight:700;padding:.1rem .4rem;border-radius:20px;margin-left:.4rem">EXPIRED</span>
                                    @elseif($expiringSoon)
                                        <span style="background:#fef3c7;color:#92400e;font-size:.65rem;font-weight:700;padding:.1rem .4rem;border-radius:20px;margin-left:.4rem">SOON</span>
                                    @endif
                                </td>
                                <td style="padding:.6rem .9rem;font-size:.82rem;color:var(--text-muted)">{{ $item->quantity ?? '—' }}</td>
                                <td style="padding:.6rem .9rem;font-size:.82rem;color:var(--text-muted)">{{ $item->date_added?->format('d M Y') ?? '—' }}</td>
                                <td style="padding:.6rem .9rem;font-size:.82rem;color:{{ $expired?'#b91c1c':($expiringSoon?'#92400e':'var(--text-muted)') }}">
                                    {{ $item->expiry_date?->format('d M Y') ?? '—' }}
                                </td>
                                <td style="padding:.6rem .9rem;text-align:center">
                                    <form method="POST" action="{{ route('pantry.destroy', $item) }}" onsubmit="return confirm('Remove this item?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="padding:.3rem .7rem;background:#fee2e2;color:#b91c1c;font-size:.75rem;font-weight:700;border-radius:5px;border:none;cursor:pointer">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:1rem">{{ $items->links() }}</div>
        @endif
    </div>
</x-app-layout>
