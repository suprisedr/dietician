<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div style="margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem">
            <div>
                <a href="{{ route('recipes.index') }}" style="font-size:.82rem;color:var(--primary);text-decoration:none">← Recipes</a>
                <h1 style="font-size:1.3rem;font-weight:800;color:var(--text-primary);margin:.35rem 0 0">{{ $recipe->name }}</h1>
                @if($recipe->category)
                    <span style="background:#faf5ff;color:#7e22ce;font-size:.72rem;font-weight:700;padding:.15rem .6rem;border-radius:20px">{{ $recipe->category }}</span>
                @endif
            </div>
            @if(!$recipe->is_system)
                <a href="{{ route('recipes.edit', $recipe) }}" style="padding:.4rem .9rem;background:#f1f5f9;color:var(--text-primary);font-size:.8rem;font-weight:700;border-radius:6px;text-decoration:none">✏ Edit</a>
            @endif
        </div>

        {{-- Meta row --}}
        @if($recipe->servings || $recipe->prep_time_min || $recipe->cook_time_min)
            <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1.25rem;padding:.75rem 1rem;background:#f8fafc;border-radius:8px">
                @if($recipe->servings)
                    <div style="text-align:center">
                        <div style="font-size:1.1rem;font-weight:800;color:var(--primary)">{{ $recipe->servings }}</div>
                        <div style="font-size:.7rem;color:var(--text-muted)">Servings</div>
                    </div>
                @endif
                @if($recipe->prep_time_min)
                    <div style="text-align:center">
                        <div style="font-size:1.1rem;font-weight:800;color:var(--primary)">{{ $recipe->prep_time_min }}<span style="font-size:.65rem">min</span></div>
                        <div style="font-size:.7rem;color:var(--text-muted)">Prep</div>
                    </div>
                @endif
                @if($recipe->cook_time_min)
                    <div style="text-align:center">
                        <div style="font-size:1.1rem;font-weight:800;color:var(--primary)">{{ $recipe->cook_time_min }}<span style="font-size:.65rem">min</span></div>
                        <div style="font-size:.7rem;color:var(--text-muted)">Cook</div>
                    </div>
                @endif
                @if($recipe->prep_time_min && $recipe->cook_time_min)
                    <div style="text-align:center">
                        <div style="font-size:1.1rem;font-weight:800;color:var(--indigo)">{{ $recipe->prep_time_min + $recipe->cook_time_min }}<span style="font-size:.65rem">min</span></div>
                        <div style="font-size:.7rem;color:var(--text-muted)">Total</div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Ingredients --}}
        @if($recipe->ingredients)
            <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.25rem;margin-bottom:1rem">
                <h2 style="font-size:.9rem;font-weight:800;color:var(--text-primary);margin:0 0 .75rem">🧂 Ingredients</h2>
                <div style="font-size:.84rem;color:var(--text-primary);white-space:pre-wrap;line-height:1.6">{{ $recipe->ingredients }}</div>
            </div>
        @endif

        {{-- Directions --}}
        @if($recipe->directions)
            <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.25rem;margin-bottom:1rem">
                <h2 style="font-size:.9rem;font-weight:800;color:var(--text-primary);margin:0 0 .75rem">📝 Directions</h2>
                <div style="font-size:.84rem;color:var(--text-primary);white-space:pre-wrap;line-height:1.7">{{ $recipe->directions }}</div>
            </div>
        @endif

        {{-- Notes --}}
        @if($recipe->notes)
            <div style="background:#fffbeb;border:1px solid #fef3c7;border-radius:10px;padding:1.25rem">
                <h2 style="font-size:.9rem;font-weight:800;color:#92400e;margin:0 0 .5rem">💡 Notes</h2>
                <div style="font-size:.83rem;color:#78350f;white-space:pre-wrap;line-height:1.6">{{ $recipe->notes }}</div>
            </div>
        @endif
    </div>
</x-app-layout>
