<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div style="margin-bottom:1.5rem">
            <a href="{{ route('recipes.index') }}" style="font-size:.82rem;color:var(--primary);text-decoration:none">← Back to Recipes</a>
            <h1 style="font-size:1.3rem;font-weight:800;color:var(--text-primary);margin:.5rem 0 0">Edit Recipe</h1>
        </div>
        <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.75rem">
            <form method="POST" action="{{ route('recipes.update', $recipe) }}">
                @csrf @method('PUT')
                @include('recipes._form')
                <div style="display:flex;gap:.75rem;margin-top:1.5rem">
                    <button type="submit" style="padding:.55rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border:none;border-radius:6px;cursor:pointer">Update Recipe</button>
                    <a href="{{ route('recipes.index') }}" style="padding:.55rem 1.25rem;background:#f1f5f9;color:var(--text-primary);font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
