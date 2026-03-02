<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('meal-items.index') }}"
           style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:var(--text-muted);text-decoration:none;margin-bottom:1.25rem">
            ← Back to library
        </a>

        <div class="dash-section">
            <div class="dash-section-header">
                <span class="dash-section-title">Add Custom Meal Item</span>
            </div>
            <div style="padding:1.25rem">
                <form method="POST" action="{{ route('meal-items.store') }}">
                    @csrf

                    @include('meal-items._form')

                    <div style="margin-top:1.5rem;display:flex;gap:.75rem">
                        <button type="submit"
                                style="padding:.5rem 1.5rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border:none;border-radius:6px;cursor:pointer">
                            Save Item
                        </button>
                        <a href="{{ route('meal-items.index') }}"
                           style="padding:.5rem 1rem;background:#f1f5f9;color:var(--text-primary);font-weight:600;font-size:.85rem;border-radius:6px;text-decoration:none">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
