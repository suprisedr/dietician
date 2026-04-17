<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('food-diary.index') }}"
           style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:var(--text-muted);text-decoration:none;margin-bottom:1.25rem">
            &#8592; Back to Diaries
        </a>

        <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1.75rem">
            <div style="font-size:1.1rem;font-weight:800;color:var(--text-primary);margin-bottom:1.25rem">New Diary Entry</div>

            @if($errors->any())
                <div style="padding:.75rem 1rem;background:#fee2e2;color:#b91c1c;border-radius:6px;font-size:.82rem;margin-bottom:1rem">
                    <ul style="margin:0;padding-left:1rem">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('food-diary.store') }}">
                @csrf
                @include('food-diary._form')
                <div style="margin-top:1.5rem;display:flex;gap:.75rem">
                    <button type="submit"
                            style="padding:.5rem 1.5rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border:none;border-radius:6px;cursor:pointer">
                        Save Entry
                    </button>
                    <a href="{{ route('food-diary.index') }}"
                       style="padding:.5rem 1.25rem;background:#f1f5f9;color:var(--text-primary);font-weight:700;font-size:.85rem;border-radius:6px;text-decoration:none">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
