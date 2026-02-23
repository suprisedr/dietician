<x-app-layout>
    {{-- suppress the default header slot so we control the full page --}}

    {{-- ═══════════════════════════════════════════
         HERO BANNER
    ═══════════════════════════════════════════ --}}
    <div class="dash-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                {{-- Greeting --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest" style="color:rgba(255,255,255,.55)">
                        {{ now()->format('l, d F Y') }}
                    </p>
                    <h1>
                        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                        {{ explode(' ', Auth::user()->name)[0] }} 👋
                    </h1>
                    <p>Here's a snapshot of your practice today.</p>
                </div>

                {{-- Search bar --}}
                <form method="GET" action="{{ route('patients.index') }}" class="dash-search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input name="search" type="text" placeholder="Search patients…" autocomplete="off"/>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         FLOATING STAT CARDS
    ═══════════════════════════════════════════ --}}
    @php
        $total     = isset($patients) ? $patients->count() : 0;
        $males     = isset($patients) ? $patients->where('gender','male')->count() : 0;
        $females   = isset($patients) ? $patients->where('gender','female')->count() : 0;
        $avgBmi    = $total > 0
            ? round($patients->filter(fn($p)=>$p->bmi)->avg(fn($p)=>$p->bmi), 1)
            : null;
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 stat-cards-row">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Total patients --}}
            <div class="stat-card">
                <div class="stat-icon orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-value">{{ $total }}</div>
                    <div class="stat-label">Total Patients</div>
                    <span class="stat-change up">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        Active
                    </span>
                </div>
            </div>

            {{-- Male patients --}}
            <div class="stat-card">
                <div class="stat-icon indigo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M16 20a4 4 0 0 0-8 0"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-value">{{ $males }}</div>
                    <div class="stat-label">Male</div>
                    <span class="stat-change neu">{{ $total > 0 ? round($males/$total*100) : 0 }}% of total</span>
                </div>
            </div>

            {{-- Female patients --}}
            <div class="stat-card">
                <div class="stat-icon rose">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M16 20a4 4 0 0 0-8 0"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-value">{{ $females }}</div>
                    <div class="stat-label">Female</div>
                    <span class="stat-change neu">{{ $total > 0 ? round($females/$total*100) : 0 }}% of total</span>
                </div>
            </div>

            {{-- Avg BMI --}}
            <div class="stat-card">
                <div class="stat-icon teal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-value">{{ $avgBmi ?? '—' }}</div>
                    <div class="stat-label">Avg BMI</div>
                    <span class="stat-change neu">kg/m²</span>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT AREA
    ═══════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── LEFT / MAIN COLUMN ──────────────────── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Patient table card --}}
                <div class="dash-section">
                    <div class="dash-section-header">
                        <span class="dash-section-title">Patient List</span>
                        <a href="{{ route('patients.create') }}" class="btn-add">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Patient
                        </a>
                    </div>

                    @if(isset($patients) && $patients->count())
                        <div class="overflow-x-auto">
                            <table class="pt-table">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Age</th>
                                        <th>BMI</th>
                                        <th>TEE (kcal)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        @php
                                            $initials = collect(explode(' ', $patient->name))
                                                ->map(fn($w)=>strtoupper(substr($w,0,1)))
                                                ->take(2)->implode('');
                                            $bmiCat   = strtolower($patient->bmi_category ?? 'normal');
                                            $tee      = $patient->tee ? round($patient->tee / 4.184) : null;
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
                                            <td>
                                                @if($patient->bmi)
                                                    <span class="bmi-pill {{ $bmiCat }}">
                                                        {{ number_format($patient->bmi, 1) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tee)
                                                    <span class="font-semibold" style="color:var(--text-primary)">{{ number_format($tee) }}</span>
                                                    <span class="text-xs" style="color:var(--text-muted)"> kcal</span>
                                                @else
                                                    <span style="color:var(--text-muted)">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('patients.show', $patient) }}"
                                                   style="font-size:.8rem;font-weight:600;color:var(--primary);text-decoration:none;white-space:nowrap">
                                                    View →
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0"/>
                            </svg>
                            <p class="font-semibold" style="color:var(--text-primary)">No patients yet</p>
                            <p class="text-sm mt-1" style="color:var(--text-muted)">Add your first patient to get started.</p>
                            <a href="{{ route('patients.create') }}" class="btn-add inline-flex mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Add First Patient
                            </a>
                        </div>
                    @endif
                </div>

            </div>

            {{-- ── RIGHT / SIDEBAR COLUMN ──────────────── --}}
            <div class="space-y-6">

                {{-- Quick actions --}}
                <div class="dash-section">
                    <div class="dash-section-header">
                        <span class="dash-section-title">Quick Actions</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 p-4">
                        <a href="{{ route('patients.create') }}" class="quick-action">
                            <div class="qa-icon" style="background:linear-gradient(135deg,#fff7ed,#fed7aa)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#f97316" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM3 20a6 6 0 0 1 12 0v1H3v-1z"/>
                                </svg>
                            </div>
                            New Patient
                        </a>
                        <a href="{{ route('patients.index') }}" class="quick-action">
                            <div class="qa-icon" style="background:linear-gradient(135deg,#eff6ff,#bfdbfe)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                                </svg>
                            </div>
                            All Patients
                        </a>
                        <a href="{{ route('profile.edit') }}" class="quick-action">
                            <div class="qa-icon" style="background:linear-gradient(135deg,#f0fdf4,#bbf7d0)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#22c55e" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0 1 12 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                </svg>
                            </div>
                            Profile
                        </a>
                        <a href="#" class="quick-action">
                            <div class="qa-icon" style="background:linear-gradient(135deg,#fdf4ff,#e9d5ff)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color:#a855f7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                                </svg>
                            </div>
                            Reports
                        </a>
                    </div>
                </div>

                {{-- BMI distribution mini-chart --}}
                @if(isset($patients) && $patients->count())
                @php
                    $bmiGroups = [
                        'Underweight' => $patients->filter(fn($p)=>$p->bmi_category==='Underweight')->count(),
                        'Normal'      => $patients->filter(fn($p)=>$p->bmi_category==='Normal')->count(),
                        'Overweight'  => $patients->filter(fn($p)=>$p->bmi_category==='Overweight')->count(),
                        'Obese'       => $patients->filter(fn($p)=>$p->bmi_category==='Obese')->count(),
                    ];
                    $bmiColors = [
                        'Underweight' => ['bg'=>'#eff6ff','bar'=>'#3b82f6','text'=>'#1d4ed8'],
                        'Normal'      => ['bg'=>'#dcfce7','bar'=>'#22c55e','text'=>'#15803d'],
                        'Overweight'  => ['bg'=>'#fef9c3','bar'=>'#eab308','text'=>'#854d0e'],
                        'Obese'       => ['bg'=>'#fee2e2','bar'=>'#ef4444','text'=>'#b91c1c'],
                    ];
                    $bmiTotal = array_sum($bmiGroups) ?: 1;
                @endphp
                <div class="dash-section">
                    <div class="dash-section-header">
                        <span class="dash-section-title">BMI Distribution</span>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($bmiGroups as $label => $count)
                        @php $pct = round($count / $bmiTotal * 100); $c = $bmiColors[$label]; @endphp
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-1">
                                <span style="color:{{ $c['text'] }}">{{ $label }}</span>
                                <span style="color:var(--text-muted)">{{ $count }} ({{ $pct }}%)</span>
                            </div>
                            <div style="height:6px;background:#f1f5f9;border-radius:999px;overflow:hidden">
                                <div style="height:100%;width:{{ $pct }}%;background:{{ $c['bar'] }};border-radius:999px;transition:width .5s ease"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Dietician info card --}}
                <div class="dash-section" style="overflow:visible">
                    <div style="background:linear-gradient(135deg,#1e1b4b,#f97316);border-radius:1.5rem;padding:1.5rem;color:#fff;position:relative;overflow:hidden">
                        <div style="position:absolute;top:-1rem;right:-1rem;width:6rem;height:6rem;background:rgba(255,255,255,.08);border-radius:50%"></div>
                        <div style="position:absolute;bottom:-2rem;left:-1rem;width:8rem;height:8rem;background:rgba(255,255,255,.06);border-radius:50%"></div>
                        <div style="position:relative;z-index:1">
                            <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:.1em;opacity:.65">Dietician ID</div>
                            <div style="font-size:1.2rem;font-weight:800;letter-spacing:.05em;margin-top:.2rem">{{ Auth::user()->dietician_number }}</div>
                            <div style="margin-top:.75rem;font-size:.95rem;font-weight:700">{{ Auth::user()->name }}</div>
                            <div style="margin-top:.25rem;opacity:.65;font-size:.8rem">{{ $total }} patient{{ $total !== 1 ? 's' : '' }} registered</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
