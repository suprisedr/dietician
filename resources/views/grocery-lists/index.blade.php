<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
            <div>
                <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0">🛒 Grocery Lists</h1>
                <p style="font-size:.82rem;color:var(--text-muted);margin:.25rem 0 0">Organise your shopping by category</p>
            </div>
            <a href="{{ route('grocery-lists.create') }}"
               style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New List
            </a>
        </div>

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
                    <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
                        <div>
                            <div style="font-weight:700;color:var(--text-primary);font-size:.92rem">
                                {{ $list->name ?: 'Grocery List #' . $list->id }}
                            </div>
                            <div style="font-size:.77rem;color:var(--text-muted);margin-top:.2rem;display:flex;gap:.75rem;flex-wrap:wrap">
                                <span>{{ $list->items->count() }} items</span>
                                <span>{{ $list->items->where('checked', true)->count() }} checked</span>
                                @if($list->patient)<span>👤 {{ $list->patient->name }}</span>@endif
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
