<x-app-layout>

    {{-- HERO --}}
    <div class="dash-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest" style="color:rgba(255,255,255,.55)">Clinical Reports</p>
                <h1>Patient Reports</h1>
                <p>{{ $patients->count() }} patient{{ $patients->count() !== 1 ? 's' : '' }} — click <strong>View Report</strong> to open the full clinical report.</p>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    @php
        $total   = $patients->count();
        $males   = $patients->where('gender','male')->count();
        $females = $patients->where('gender','female')->count();
        $avgBmi  = $total > 0 ? round($patients->filter(fn($p)=>$p->bmi)->avg(fn($p)=>$p->bmi), 1) : null;
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 stat-cards-row">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                </div>
                <div><div class="stat-value">{{ $total }}</div><div class="stat-label">Total Reports</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon indigo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M16 20a4 4 0 0 0-8 0"/></svg>
                </div>
                <div><div class="stat-value">{{ $males }}</div><div class="stat-label">Male</div><span class="stat-change neu">{{ $total > 0 ? round($males/$total*100) : 0 }}%</span></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rose">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M16 20a4 4 0 0 0-8 0"/></svg>
                </div>
                <div><div class="stat-value">{{ $females }}</div><div class="stat-label">Female</div><span class="stat-change neu">{{ $total > 0 ? round($females/$total*100) : 0 }}%</span></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/></svg>
                </div>
                <div><div class="stat-value">{{ $avgBmi ?? '—' }}</div><div class="stat-label">Avg BMI</div><span class="stat-change neu">kg/m²</span></div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="dash-section">
            <div class="dash-section-header">
                <span class="dash-section-title">Reports</span>
            </div>

            <div class="overflow-x-auto">
                <table class="pt-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Age</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>BMI</th>
                            <th>BMR (kJ)</th>
                            <th>TEE (kJ)</th>
                            <th>TEE (kcal)</th>
                            <th style="text-align:right">Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            @php
                                $initials = collect(explode(' ', $patient->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
                                $bmiCat   = strtolower($patient->bmi_category ?? 'normal');
                                $teeKj    = $patient->tee ? round($patient->tee * 4.184) : null;
                                $teeKcal  = $patient->tee ? round($patient->tee) : null;
                                $bmrKj    = $patient->bmr ? round($patient->bmr * 4.184) : null;
                            @endphp
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="pt-avatar {{ $patient->gender }}">{{ $initials }}</div>
                                        <div>
                                            <div class="font-semibold" style="color:var(--text-primary)">{{ $patient->name }}</div>
                                            <div class="text-xs" style="color:var(--text-muted)">{{ ucfirst($patient->gender) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $patient->age }} yrs</td>
                                <td>{{ $patient->weight }} kg</td>
                                <td>{{ $patient->height }} cm</td>
                                <td>
                                    @if($patient->bmi)
                                        <span class="bmi-pill {{ $bmiCat }}">{{ number_format($patient->bmi, 1) }}</span>
                                    @else
                                        <span style="color:var(--text-muted)">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bmrKj)
                                        <span class="font-semibold" style="color:var(--text-primary)">{{ number_format($bmrKj) }}</span>
                                    @else
                                        <span style="color:var(--text-muted)">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($teeKj)
                                        <span class="font-semibold" style="color:var(--text-primary)">{{ number_format($teeKj) }}</span>
                                    @else
                                        <span style="color:var(--text-muted)">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($teeKcal)
                                        <span class="font-semibold" style="color:var(--text-primary)">{{ number_format($teeKcal) }}</span>
                                    @else
                                        <span style="color:var(--text-muted)">—</span>
                                    @endif
                                </td>
                                <td style="text-align:right">
                                    <a href="{{ route('patients.report', $patient->id) }}" target="_blank" class="tbl-btn view">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                                        View Report
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg>
                                        <p class="font-semibold" style="color:var(--text-primary)">No patients yet</p>
                                        <a href="{{ route('patients.create') }}" class="btn-add inline-flex mt-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                            Add First Patient
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
