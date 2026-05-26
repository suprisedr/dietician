<x-app-layout>

    {{-- ═══════════════════════════════════════════
         HERO BANNER
    ═══════════════════════════════════════════ --}}
    <div class="dash-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest" style="color:rgba(255,255,255,.55)">
                        <a href="{{ route('patients.index') }}" style="color:rgba(255,255,255,.55);text-decoration:none">All Patients</a>
                        &rsaquo;
                        <a href="{{ route('patients.show', $patient) }}" style="color:rgba(255,255,255,.7);text-decoration:none">{{ $patient->full_name }}</a>
                        &rsaquo; Visit Log
                    </p>
                    <h1>Visit History &amp; Monitoring</h1>
                    <p>{{ $visits->total() }} visit{{ $visits->total() !== 1 ? 's' : '' }} recorded for {{ $patient->full_name }}.</p>
                </div>
                <div style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center">
                    <a href="{{ route('patients.show', $patient) }}"
                       style="display:inline-flex;align-items:center;gap:.4rem;padding:.55rem 1.1rem;background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:8px;font-size:.82rem;font-weight:600;text-decoration:none;backdrop-filter:blur(4px)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Back to Patient
                    </a>
                    <button type="button"
                            onclick="openPdfPreviewModal('{{ route('patients.visits.pdf', $patient) }}?stream=1','{{ route('patients.visits.pdf', $patient) }}')"
                            style="display:inline-flex;align-items:center;gap:.4rem;padding:.55rem 1.1rem;background:rgba(255,255,255,.9);color:#1a4a36;border:1px solid rgba(255,255,255,.6);border-radius:8px;font-size:.82rem;font-weight:700;cursor:pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview / Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="padding-top:1.5rem;padding-bottom:3rem">

        {{-- Flash --}}
        @if(session('visit_success'))
            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:.75rem 1rem;margin-bottom:1.25rem;font-size:.85rem;color:#15803d;display:flex;align-items:center;gap:.5rem">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('visit_success') }}
            </div>
        @endif

        <div style="background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden">
            <table style="width:100%;border-collapse:separate;border-spacing:0;font-size:.84rem">
                <thead>
                    <tr style="background:#f9fafb">
                        <th style="padding:.7rem 1rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">#</th>
                        <th style="padding:.7rem 1rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Date &amp; Time</th>
                        <th style="padding:.7rem 1rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Weight (kg)</th>
                        <th style="padding:.7rem 1rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Height (cm)</th>
                        <th style="padding:.7rem 1rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">BMI</th>
                        <th style="padding:.7rem 1rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Change</th>
                        <th style="padding:.7rem 1rem;text-align:center;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Oedema</th>
                        <th style="padding:.7rem 1rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Notes</th>
                        <th style="padding:.7rem 1rem;border-bottom:1px solid #e5e7eb"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visits as $vi => $visit)
                        @php
                            $prevVisit  = $visits->items()[$vi + 1] ?? null;
                            $weightDiff = $prevVisit ? round($visit->weight - $prevVisit->weight, 1) : null;
                            $bmi        = $visit->bmi;
                            $rowNum     = $visits->firstItem() + $vi;
                        @endphp
                        <tr style="{{ $vi % 2 === 0 ? 'background:#fff' : 'background:#f9fafb' }}">
                            <td style="padding:.65rem 1rem;color:var(--text-muted);font-size:.75rem;border-bottom:1px solid #f3f4f6">
                                {{ $rowNum }}
                            </td>
                            <td style="padding:.65rem 1rem;font-weight:600;color:var(--text-primary);border-bottom:1px solid #f3f4f6;white-space:nowrap">
                                <span style="display:block">{{ $visit->visited_at->format('d M Y') }}</span>
                                <span style="font-size:.71rem;font-weight:400;color:var(--text-muted)">{{ $visit->visited_at->format('H:i') }}</span>
                            </td>
                            <td style="padding:.65rem 1rem;text-align:right;font-weight:700;color:var(--text-primary);border-bottom:1px solid #f3f4f6">
                                {{ number_format($visit->weight, 1) }}
                            </td>
                            <td style="padding:.65rem 1rem;text-align:right;color:var(--text-muted);border-bottom:1px solid #f3f4f6">
                                {{ $visit->height ? number_format($visit->height, 1) : '—' }}
                            </td>
                            <td style="padding:.65rem 1rem;text-align:right;color:var(--text-muted);border-bottom:1px solid #f3f4f6">
                                {{ $bmi ?? '—' }}
                            </td>
                            <td style="padding:.65rem 1rem;text-align:right;border-bottom:1px solid #f3f4f6">
                                @if($weightDiff !== null)
                                    @if($weightDiff < 0)
                                        <span style="color:#15803d;font-weight:700">{{ $weightDiff }} kg</span>
                                    @elseif($weightDiff > 0)
                                        <span style="color:#b91c1c;font-weight:700">+{{ $weightDiff }} kg</span>
                                    @else
                                        <span style="color:var(--text-muted)">— no change</span>
                                    @endif
                                @else
                                    <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>
                            <td style="padding:.65rem 1rem;text-align:center;border-bottom:1px solid #f3f4f6">
                                @if($visit->oedema === null)
                                    <span style="color:var(--text-muted);font-size:.75rem">—</span>
                                @elseif($visit->oedema)
                                    <span title="Since {{ $visit->oedema_changed_at?->format('d M Y H:i') ?? '—' }}"
                                          style="display:inline-flex;align-items:center;gap:.25rem;font-size:.72rem;font-weight:700;padding:.2rem .55rem;background:#fef3c7;color:#92400e;border-radius:999px;cursor:default">
                                        &#9679; Yes
                                    </span>
                                @else
                                    <span title="Since {{ $visit->oedema_changed_at?->format('d M Y H:i') ?? '—' }}"
                                          style="display:inline-flex;align-items:center;gap:.25rem;font-size:.72rem;font-weight:700;padding:.2rem .55rem;background:#dcfce7;color:#15803d;border-radius:999px;cursor:default">
                                        &#9679; No
                                    </span>
                                @endif
                            </td>
                            <td style="padding:.65rem 1rem;color:var(--text-muted);max-width:280px;border-bottom:1px solid #f3f4f6">
                                {{ $visit->notes ?: '—' }}
                            </td>
                            <td style="padding:.65rem 1rem;text-align:right;border-bottom:1px solid #f3f4f6">
                                <form method="POST" action="{{ route('patients.visits.destroy', [$patient, $visit]) }}"
                                      onsubmit="return confirm('Delete this visit record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="padding:.25rem .55rem;background:none;border:1px solid #fca5a5;border-radius:5px;color:#dc2626;font-size:.72rem;cursor:pointer"
                                            title="Delete visit">✕</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding:3rem 1rem;text-align:center;color:var(--text-muted);font-size:.875rem">
                                No visits recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($visits->hasPages())
            <div style="margin-top:1.25rem">
                {{ $visits->links() }}
            </div>
        @endif

    </div>

</x-app-layout>
