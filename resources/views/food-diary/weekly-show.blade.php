<x-app-layout>
<style>
/* ── Weekly diary container (matches weekly.html aesthetic) ─────── */
.wfd-container {
    background:#d9d3cf;
    border:4px solid #1a1a1a;
    border-radius:22px;
    padding:28px 28px 24px;
}
.wfd-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:20px;
    flex-wrap:wrap;
    gap:1rem;
}
.wfd-title {
    font-size:2.6rem;
    font-weight:900;
    color:#2f5d50;
    line-height:.9;
}
.wfd-week-label {
    font-size:.88rem;
    font-weight:700;
    color:#2f5d50;
    margin-top:.75rem;
}
.wfd-week-val {
    border-bottom:2px dashed #333;
    min-width:200px;
    margin-top:4px;
    font-size:.82rem;
    color:#1a3d2b;
    padding-bottom:2px;
}
/* ── 2-col grid ──────────────────────────────────────────────────── */
.wfd-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}
/* ── Day box ─────────────────────────────────────────────────────── */
.wfd-day {
    border:2px solid #1a1a1a;
    padding:12px 12px 10px;
    background:transparent;
    border-radius:3px;
}
.wfd-day-header {
    display:flex;
    justify-content:space-between;
    align-items:baseline;
    margin-bottom:9px;
    border-bottom:1.5px solid #1a1a1a;
    padding-bottom:5px;
}
.wfd-day-name {
    font-size:1rem;
    font-weight:800;
    color:#2f5d50;
    text-transform:uppercase;
    letter-spacing:.06em;
}
.wfd-day-date {
    font-size:.72rem;
    color:#555;
    font-weight:600;
}
/* ── Meal rows ───────────────────────────────────────────────────── */
.wfd-meal { margin-bottom:9px; }
.wfd-meal-label {
    font-size:.75rem;
    font-weight:700;
    color:#1a3d2b;
    margin-bottom:2px;
}
.wfd-meal-line {
    border-bottom:2px dashed #333;
    min-height:18px;
    padding-bottom:2px;
    font-size:.78rem;
    color:#1a3d2b;
    line-height:1.4;
    white-space:pre-wrap;
    word-break:break-word;
}
.wfd-meal-line.empty { color:#9ca3af; font-style:italic; }
/* ── Rating row ─────────────────────────────────────────────────── */
.wfd-rate-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:10px;
    padding-top:8px;
    border-top:1px solid #aaa;
}
.wfd-rate-label { font-size:.72rem; font-weight:700; color:#2f5d50; }
.wfd-circles { display:flex; gap:5px; }
.wfd-circle {
    width:15px;
    height:15px;
    border:2px solid #1a1a1a;
    border-radius:50%;
}
.wfd-circle.filled { background:#2f5d50; }
/* ── Notes box ───────────────────────────────────────────────────── */
.wfd-notes {
    border-top:2px solid #1a1a1a;
    padding-top:10px;
}
.wfd-notes-title {
    font-size:1rem;
    font-weight:800;
    color:#2f5d50;
    text-transform:uppercase;
    letter-spacing:.08em;
    margin-bottom:8px;
}
.wfd-notes-line {
    border-bottom:2px dashed #333;
    margin:10px 0;
}
.wfd-notes-text {
    font-size:.8rem;
    color:#1a3d2b;
    line-height:1.6;
    white-space:pre-wrap;
}
/* ── Empty day ───────────────────────────────────────────────────── */
.wfd-empty-day {
    display:flex;
    align-items:center;
    justify-content:center;
    min-height:120px;
    font-size:.75rem;
    color:#9ca3af;
    font-style:italic;
    flex-direction:column;
    gap:.35rem;
}
/* ── Nav bar ─────────────────────────────────────────────────────── */
.wfd-nav {
    display:flex;
    align-items:center;
    justify-content:space-between;
    background:#fff;
    border:1px solid var(--border);
    border-radius:10px;
    padding:.6rem 1rem;
    margin-bottom:1.25rem;
    flex-wrap:wrap;
    gap:.5rem;
}
.wfd-nav-week {
    font-size:.88rem;
    font-weight:800;
    color:var(--text-primary);
}
.wfd-nav-link {
    padding:.3rem .75rem;
    background:#f1f5f9;
    color:var(--text-primary);
    font-size:.78rem;
    font-weight:700;
    border-radius:6px;
    text-decoration:none;
    white-space:nowrap;
}
.wfd-nav-link:hover { background:#e2e8f0; }
@media(max-width:640px) {
    .wfd-grid { grid-template-columns:1fr; }
    .wfd-title { font-size:1.8rem; }
}
</style>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ── Page header ─────────────────────────────────────────────── --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem">
        <div>
            <h1 style="font-size:1.4rem;font-weight:800;color:var(--text-primary);margin:0">&#x1F4C5; Weekly Food Diary</h1>
            <p style="font-size:.82rem;color:var(--text-muted);margin:.25rem 0 0">7-day overview of a patient's food diary entries</p>
        </div>
        <a href="{{ route('food-diary.index') }}"
           style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:var(--text-muted);text-decoration:none">
            &#8592; Daily Diaries
        </a>
    </div>

    {{-- ── Picker form ──────────────────────────────────────────────── --}}
    <div style="background:#fff;border:1px solid var(--border);border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.25rem">
        <form method="GET" action="{{ route('food-diary.weekly') }}" style="display:flex;gap:.65rem;flex-wrap:wrap;align-items:flex-end">
            <div style="flex:1;min-width:200px">
                <label style="display:block;font-size:.73rem;font-weight:700;color:var(--text-muted);margin-bottom:.3rem">PATIENT</label>
                <select name="patient_id" required
                        style="width:100%;padding:.42rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;background:#fff;color:var(--text-primary)">
                    <option value="">— Select patient —</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}" @selected($p->id == $patientId)>
                            {{ $p->name }} {{ $p->surname }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:.73rem;font-weight:700;color:var(--text-muted);margin-bottom:.3rem">WEEK STARTING (MONDAY)</label>
                <input type="date" name="week" value="{{ $weekStart->format('Y-m-d') }}"
                       style="padding:.42rem .7rem;border:1px solid var(--border);border-radius:6px;font-size:.83rem;background:#fff;color:var(--text-primary)">
            </div>
            <button type="submit"
                    style="padding:.45rem 1.25rem;background:var(--primary);color:#fff;font-weight:700;font-size:.83rem;border:none;border-radius:6px;cursor:pointer;white-space:nowrap">
                View Week
            </button>
            @if($patient)
                <a href="{{ route('food-diary.weekly.pdf', ['patient_id' => $patientId, 'week' => $weekStart->format('Y-m-d')]) }}"
                   target="_blank"
                   style="padding:.45rem 1.25rem;background:#2d5a43;color:#fff;font-weight:700;font-size:.83rem;border-radius:6px;text-decoration:none;white-space:nowrap">
                    &#x1F441; Preview / Download PDF
                </a>
            @endif
        </form>
    </div>

    @if($patient)

        {{-- ── Week navigation ─────────────────────────────────────── --}}
        @php
            $prevWeek = $weekStart->copy()->subWeek()->format('Y-m-d');
            $nextWeek = $weekStart->copy()->addWeek()->format('Y-m-d');
            $weekEnd  = $weekStart->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
        @endphp
        <div class="wfd-nav">
            <a class="wfd-nav-link"
               href="{{ route('food-diary.weekly', ['patient_id' => $patientId, 'week' => $prevWeek]) }}">
                &#8592; Previous Week
            </a>
            <span class="wfd-nav-week">
                {{ $weekStart->format('d M Y') }} &mdash; {{ $weekEnd->format('d M Y') }}
                &nbsp;·&nbsp; {{ $patient->name }} {{ $patient->surname }}
            </span>
            <a class="wfd-nav-link"
               href="{{ route('food-diary.weekly', ['patient_id' => $patientId, 'week' => $nextWeek]) }}">
                Next Week &#8594;
            </a>
        </div>

        {{-- ── Weekly diary card ───────────────────────────────────── --}}
        @php
            $notes = collect($weekDays)
                ->filter(fn($d) => $d['diary']?->improvement)
                ->map(fn($d) => $d['date']->format('D') . ': ' . $d['diary']->improvement)
                ->implode("\n\n");
        @endphp
        <div class="wfd-container">

            {{-- Header --}}
            <div class="wfd-header">
                <div class="wfd-title">
                    WEEKLY FOOD<br>DIARY
                </div>
                <div>
                    <div class="wfd-week-label">WEEK:</div>
                    <div class="wfd-week-val">
                        {{ $weekStart->format('d M Y') }} &ndash; {{ $weekEnd->format('d M Y') }}
                    </div>
                    <div style="font-size:.75rem;font-weight:700;color:#4a7a60;margin-top:.5rem">
                        {{ $patient->name }} {{ $patient->surname }}
                    </div>
                </div>
            </div>

            {{-- 7-day grid + notes --}}
            <div class="wfd-grid">
                @foreach($weekDays as $day)
                @php
                    $diary   = $day['diary'];
                    $date    = $day['date'];
                    $snacks  = collect(['snack1','snack2','snack3'])
                                ->map(fn($s) => $diary?->{$s})
                                ->filter()
                                ->implode(' · ');
                @endphp
                <div class="wfd-day">
                    <div class="wfd-day-header">
                        <span class="wfd-day-name">{{ $date->format('l') }}</span>
                        <span class="wfd-day-date">{{ $date->format('d M') }}</span>
                    </div>

                    @foreach(['breakfast'=>'Breakfast','lunch'=>'Lunch','supper'=>'Dinner'] as $slot => $label)
                    <div class="wfd-meal">
                        <div class="wfd-meal-label">{{ $label }}</div>
                        <div class="wfd-meal-line @if(!$diary?->{$slot}) empty @endif">
                            {{ $diary?->{$slot} ?: '—' }}
                        </div>
                    </div>
                    @endforeach

                    <div class="wfd-meal">
                        <div class="wfd-meal-label">Snacks</div>
                        <div class="wfd-meal-line @if(!$snacks) empty @endif">
                            {{ $snacks ?: '—' }}
                        </div>
                    </div>

                    <div class="wfd-rate-row">
                        <span class="wfd-rate-label">Rate your day</span>
                        <div class="wfd-circles">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="wfd-circle @if($diary?->rating && $i <= $diary->rating) filled @endif"></div>
                            @endfor
                        </div>
                    </div>

                    @if($diary)
                        <div style="margin-top:.5rem;text-align:right">
                            <a href="{{ route('food-diary.show', $diary) }}"
                               style="font-size:.68rem;color:#2d5a43;font-weight:700;text-decoration:none">
                                View full entry &#8599;
                            </a>
                        </div>
                    @else
                        <div class="wfd-empty-day" style="min-height:0;margin-top:.5rem">
                            <span style="font-size:.68rem;color:#9ca3af;font-style:italic">No entry for this day</span>
                        </div>
                    @endif
                </div>
                @endforeach

                {{-- Notes box (8th item) --}}
                <div class="wfd-notes">
                    <div class="wfd-notes-title">Notes:</div>
                    @if($notes)
                        <p class="wfd-notes-text">{{ $notes }}</p>
                    @else
                        <div class="wfd-notes-line"></div>
                        <div class="wfd-notes-line"></div>
                        <div class="wfd-notes-line"></div>
                        <div class="wfd-notes-line"></div>
                    @endif
                </div>

            </div>{{-- /wfd-grid --}}
        </div>{{-- /wfd-container --}}

    @else
        <div style="text-align:center;padding:4rem 2rem;color:var(--text-muted)">
            <div style="font-size:3rem;margin-bottom:1rem">&#x1F4C5;</div>
            <p style="font-weight:600;margin:0 0 .4rem">Select a patient and week above</p>
            <p style="font-size:.82rem">The 7-day food diary grid will appear here.</p>
        </div>
    @endif

</div>
</x-app-layout>
