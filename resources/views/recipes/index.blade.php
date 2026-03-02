<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
            <div>
                <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0">Recipe Cards</h1>
                <p style="font-size:.82rem;color:var(--text-muted);margin:.25rem 0 0">Save and organise your recipes</p>
            </div>
            <a href="{{ route('recipes.create') }}"
               style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Recipe
            </a>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div style="padding:.65rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">✓ {{ session('success') }}</div>
        @endif

        {{-- Filters --}}
        <form method="GET" action="{{ route('recipes.index') }}"
              style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.25rem;align-items:center">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search recipes…"
                   style="flex:1;min-width:180px;padding:.45rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;outline:none">
            <select name="category"
                    style="padding:.45rem .75rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;background:#fff;outline:none">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit"
                    style="padding:.45rem 1rem;background:var(--primary);color:#fff;font-weight:700;font-size:.83rem;border:none;border-radius:6px;cursor:pointer">Search</button>
        </form>

        @if($recipes->isEmpty())
            <div style="text-align:center;padding:4rem 2rem;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:1rem">📋</div>
                <p style="font-weight:600;margin:0 0 .5rem">No recipes yet</p>
                <a href="{{ route('recipes.create') }}" style="display:inline-block;padding:.5rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">Add First Recipe</a>
            </div>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem">
                @foreach($recipes as $recipe)
                    <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.1rem;display:flex;flex-direction:column;gap:.5rem">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.5rem">
                            <a href="{{ route('recipes.show', $recipe) }}" style="font-weight:700;color:var(--text-primary);text-decoration:none;font-size:.92rem;flex:1">{{ $recipe->name }}</a>
                            @if($recipe->category)
                                <span style="background:#faf5ff;color:#7e22ce;font-size:.68rem;font-weight:700;padding:.15rem .5rem;border-radius:20px;white-space:nowrap">{{ $recipe->category }}</span>
                            @endif
                        </div>

                        @if($recipe->servings || $recipe->prep_time_min || $recipe->cook_time_min)
                            <div style="font-size:.75rem;color:var(--text-muted);display:flex;gap:.75rem;flex-wrap:wrap">
                                @if($recipe->servings)<span>👥 {{ $recipe->servings }} servings</span>@endif
                                @if($recipe->prep_time_min)<span>⏱ Prep {{ $recipe->prep_time_min }}min</span>@endif
                                @if($recipe->cook_time_min)<span>🔥 Cook {{ $recipe->cook_time_min }}min</span>@endif
                            </div>
                        @endif

                        @if($recipe->ingredients)
                            <p style="font-size:.77rem;color:var(--text-muted);margin:0;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">
                                {{ Str::limit($recipe->ingredients, 100) }}
                            </p>
                        @endif

                        <div style="display:flex;gap:.4rem;margin-top:auto;padding-top:.4rem">
                            <a href="{{ route('recipes.show', $recipe) }}" style="flex:1;text-align:center;padding:.35rem;background:#eff6ff;color:#1e40af;font-size:.75rem;font-weight:700;border-radius:5px;text-decoration:none">View</a>
                            @if(!$recipe->is_system)
                                <a href="{{ route('recipes.edit', $recipe) }}" style="padding:.35rem .75rem;background:#f1f5f9;color:var(--text-primary);font-size:.75rem;font-weight:700;border-radius:5px;text-decoration:none">Edit</a>
                                <form method="POST" action="{{ route('recipes.destroy', $recipe) }}" onsubmit="return confirm('Delete this recipe?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:.35rem .75rem;background:#fee2e2;color:#b91c1c;font-size:.75rem;font-weight:700;border-radius:5px;border:none;cursor:pointer">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="margin-top:1.25rem">{{ $recipes->links() }}</div>
        @endif
    </div>
</x-app-layout>
