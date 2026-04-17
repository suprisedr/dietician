<x-app-layout>
<style>
.diary-card { background:#f3e9e9;border-radius:8px;padding:2rem 2rem 1.5rem; }
.diary-header { display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #2d5a43;padding-bottom:1rem;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem; }
.diary-title { color:#2d5a43;font-family:Georgia,serif; }
.diary-title h1 { font-size:2rem;font-weight:normal;margin:0;line-height:.95;text-transform:uppercase;letter-spacing:2px; }
.diary-title h1 span { display:block;font-style:italic;font-size:1.6rem;text-transform:capitalize;letter-spacing:0; }
.diary-meta { color:#2d5a43;font-weight:bold;font-size:.88rem;text-align:right; }
.meal-grid { display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem; }
.meal-box { border:1px solid #2d5a43;border-radius:5px;overflow:hidden; }
.meal-label { background:#2d5a43;color:#fff;padding:5px 12px;font-size:.8rem;letter-spacing:1px;text-transform:uppercase;font-weight:700; }
.meal-content { min-height:60px;padding:.65rem .85rem;background:rgba(255,255,255,.35);font-size:.85rem;color:#1a3d2b;line-height:1.55;white-space:pre-wrap; }
.meal-content.empty { color:#9ca3af;font-style:italic; }
.reflection { color:#2d5a43; }
.reflection-label { font-weight:700;font-size:.85rem;margin-bottom:.5rem;display:block; }
.rating-stars span { font-size:1.4rem;margin-right:.25rem; }
.improvement-box { min-height:70px;border:1.5px solid #2d5a43;border-radius:4px;padding:.65rem .85rem;background:rgba(255,255,255,.35);font-size:.85rem;color:#1a3d2b;white-space:pre-wrap; }
.improvement-box.empty { color:#9ca3af;font-style:italic; }
@media (max-width:640px) { .meal-grid { grid-template-columns:1fr; } }
</style>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem">
            <a href="{{ route('food-diary.index') }}"
               style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:var(--text-muted);text-decoration:none">
                &#8592; Back to Diaries
            </a>
            <div style="display:flex;gap:.5rem">
                <a href="{{ route('food-diary.edit', $foodDiary) }}"
                   style="padding:.35rem .9rem;background:#f1f5f9;color:var(--text-primary);font-size:.8rem;font-weight:700;border-radius:6px;text-decoration:none">
                    &#9998; Edit
                </a>
                <a href="{{ route('food-diary.pdf', $foodDiary) }}" target="_blank"
                   style="padding:.35rem .9rem;background:#2d5a43;color:#fff;font-size:.8rem;font-weight:700;border-radius:6px;text-decoration:none">
                    &#x1F4C4; Download PDF
                </a>
            </div>
        </div>

        @if(session('success'))
            <div style="padding:.65rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600;margin-bottom:1rem">
                &#x2713; {{ session('success') }}
            </div>
        @endif

        <div class="diary-card">
            <div class="diary-header">
                <div class="diary-title">
                    <h1><span>Daily food</span> diary</h1>
                </div>
                <div class="diary-meta">
                    <div>Date: {{ $foodDiary->diary_date->format('d M Y') }}</div>
                    <div>{{ $foodDiary->diary_date->format('l') }}</div>
                    @if($foodDiary->patient)
                        <div style="margin-top:.35rem;color:#4a7a60">{{ $foodDiary->patient->name }}</div>
                    @endif
                </div>
            </div>

            <div class="meal-grid">
                @foreach(['breakfast' => 'Breakfast', 'snack1' => 'Snack (Morning)', 'lunch' => 'Lunch', 'snack2' => 'Snack (Afternoon)', 'supper' => 'Supper', 'snack3' => 'Snack (Evening)'] as $slot => $label)
                <div class="meal-box">
                    <div class="meal-label">{{ $label }}</div>
                    <div class="meal-content @if(!$foodDiary->{$slot}) empty @endif">
                        {{ $foodDiary->{$slot} ?: '—' }}
                    </div>
                </div>
                @endforeach
            </div>

            <div class="reflection">
                <div style="margin-bottom:1rem">
                    <span class="reflection-label">Rate your day:</span>
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span style="color:{{ $foodDiary->rating && $i <= $foodDiary->rating ? '#2d5a43' : '#d1d5db' }}">&#9733;</span>
                        @endfor
                        @if(!$foodDiary->rating)
                            <span style="font-size:.8rem;color:#9ca3af;font-style:italic">Not rated</span>
                        @endif
                    </div>
                </div>
                <div>
                    <span class="reflection-label">What can I improve on?</span>
                    <div class="improvement-box @if(!$foodDiary->improvement) empty @endif">
                        {{ $foodDiary->improvement ?: '—' }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
