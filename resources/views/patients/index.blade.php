<x-app-layout>

    {{-- ═══════════════════════════════════════════
         HERO BANNER
    ═══════════════════════════════════════════ --}}
    <div class="dash-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest" style="color:rgba(255,255,255,.55)">
                        Patient Management
                    </p>
                    <h1>All Patients</h1>
                    <p>{{ $patients->total() }} patient{{ $patients->total() !== 1 ? 's' : '' }} registered in your practice.</p>
                </div>
                <a href="{{ route('patients.create') }}" class="btn-add self-start sm:self-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Patient
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         FLOATING STAT CARDS
    ═══════════════════════════════════════════ --}}
    @php
        $total   = $stats['total'];
        $males   = $stats['males'];
        $females = $stats['females'];
        $avgBmi  = $stats['avg_bmi'] ? round($stats['avg_bmi'], 2) : null;
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 stat-cards-row">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0"/></svg>
                </div>
                <div><div class="stat-value">{{ $total }}</div><div class="stat-label">Total</div></div>
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

    {{-- ═══════════════════════════════════════════
         PATIENT TABLE
    ═══════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ── Search & Filter Bar ──────────────────────────────── --}}
        <form method="GET" action="{{ route('patients.index') }}" id="filter-form">
            <input type="hidden" name="sort"      value="{{ request('sort', 'created_at') }}">
            <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
            <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end">

                {{-- Search --}}
                <div style="flex:1;min-width:200px">
                    <label style="display:block;font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Search</label>
                    <div style="position:relative">
                        <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:.65rem;top:50%;transform:translateY(-50%);width:.9rem;height:.9rem;color:var(--text-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Name, surname or email…"
                               style="width:100%;padding:.45rem .75rem .45rem 2rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;outline:none;background:#fafafa"
                               onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>

                {{-- Gender --}}
                <div style="min-width:130px">
                    <label style="display:block;font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Gender</label>
                    <select name="gender" onchange="this.form.submit()"
                            style="width:100%;padding:.45rem .75rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;background:#fafafa;outline:none">
                        <option value="">All</option>
                        <option value="male"   {{ request('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                {{-- BMI Category --}}
                <div style="min-width:150px">
                    <label style="display:block;font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">BMI Category</label>
                    <select name="bmi_category" onchange="this.form.submit()"
                            style="width:100%;padding:.45rem .75rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;background:#fafafa;outline:none">
                        <option value="">All</option>
                        <option value="underweight" {{ request('bmi_category') === 'underweight' ? 'selected' : '' }}>Underweight (&lt;18.5)</option>
                        <option value="normal"      {{ request('bmi_category') === 'normal'      ? 'selected' : '' }}>Normal (18.5–24.9)</option>
                        <option value="overweight"  {{ request('bmi_category') === 'overweight'  ? 'selected' : '' }}>Overweight (25–29.9)</option>
                        <option value="obese"       {{ request('bmi_category') === 'obese'       ? 'selected' : '' }}>Obese (≥30)</option>
                    </select>
                </div>

                {{-- Consent Status --}}
                <div style="min-width:150px">
                    <label style="display:block;font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Consent</label>
                    <select name="consent" onchange="this.form.submit()"
                            style="width:100%;padding:.45rem .75rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;background:#fafafa;outline:none">
                        <option value="">All</option>
                        <option value="consented" {{ request('consent') === 'consented' ? 'selected' : '' }}>Consented</option>
                        <option value="declined"  {{ request('consent') === 'declined'  ? 'selected' : '' }}>Declined</option>
                        <option value="pending"   {{ request('consent') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                {{-- Age Range --}}
                <div style="min-width:160px">
                    <label style="display:block;font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.3rem">Age Range</label>
                    <div style="display:flex;align-items:center;gap:.4rem">
                        <input type="number" name="age_min" value="{{ request('age_min') }}" placeholder="Min"
                               min="0" max="150"
                               style="width:60px;padding:.45rem .5rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;background:#fafafa;outline:none"
                               onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                        <span style="font-size:.8rem;color:var(--text-muted)">–</span>
                        <input type="number" name="age_max" value="{{ request('age_max') }}" placeholder="Max"
                               min="0" max="150"
                               style="width:60px;padding:.45rem .5rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;background:#fafafa;outline:none"
                               onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>

                {{-- Search button + Clear --}}
                <div style="display:flex;align-items:flex-end;gap:.5rem">
                    <button type="submit"
                            style="padding:.45rem 1rem;background:var(--primary);color:#fff;border:none;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer">
                        Search
                    </button>
                    @if(request()->hasAny(['search','gender','bmi_category','consent','age_min','age_max']))
                        <a href="{{ route('patients.index') }}"
                           style="padding:.45rem .9rem;border:1px solid var(--border);border-radius:8px;font-size:.85rem;font-weight:600;color:var(--text-muted);text-decoration:none;white-space:nowrap">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <div style="background:#fff;border:1px solid var(--border);border-radius:12px">

        <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem">
            <span class="dash-section-title">Patient Records</span>
            <span style="font-size:.75rem;color:var(--text-muted)">Click &#8942; to edit or view a patient</span>
        </div>

            @forelse($patients as $patient)
                <form id="form-{{ $patient->id }}" method="POST" action="{{ route('patients.update', $patient) }}" class="hidden">
                    @csrf
                    @method('PATCH')
                </form>
            @empty
            @endforelse

            @php
                $cs  = request('sort', 'created_at');
                $cd  = request('direction', 'desc');

                $thLink = function(string $col, string $label) use ($cs, $cd): string {
                    $isActive = $cs === $col;
                    $newDir   = ($isActive && $cd === 'asc') ? 'desc' : 'asc';
                    $params   = array_merge(request()->query(), ['sort' => $col, 'direction' => $newDir]);
                    unset($params['page']);
                    $url     = route('patients.index', $params);
                    $upColor = ($isActive && $cd === 'asc')  ? 'var(--primary)' : '#cbd5e1';
                    $dnColor = ($isActive && $cd === 'desc') ? 'var(--primary)' : '#cbd5e1';
                    $labelStyle = $isActive ? 'color:var(--primary);font-weight:800' : '';
                    return '<a href="' . e($url) . '" style="display:inline-flex;align-items:center;gap:.3rem;text-decoration:none;color:inherit;white-space:nowrap">'
                        . '<span style="' . $labelStyle . '">' . e($label) . '</span>'
                        . '<span style="display:inline-flex;flex-direction:column;gap:1px;flex-shrink:0">'
                        . '<svg xmlns="http://www.w3.org/2000/svg" style="width:.5rem;height:.5rem;color:' . $upColor . '" viewBox="0 0 10 6" fill="currentColor"><path d="M5 0L10 6H0L5 0Z"/></svg>'
                        . '<svg xmlns="http://www.w3.org/2000/svg" style="width:.5rem;height:.5rem;color:' . $dnColor . '" viewBox="0 0 10 6" fill="currentColor"><path d="M5 6L0 0H10L5 6Z"/></svg>'
                        . '</span>'
                        . '</a>';
                };
            @endphp
            <table class="pt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>{!! $thLink('name', 'Patient') !!}</th>
                        <th>{!! $thLink('consent_status', 'Consent') !!}</th>
                        <th>{!! $thLink('gender', 'Gender') !!}</th>
                        <th>{!! $thLink('age', 'Age') !!}</th>
                        <th>{!! $thLink('weight', 'Weight') !!}</th>
                        <th>{!! $thLink('height', 'Height') !!}</th>
                        <th>{!! $thLink('activity_factor', 'Act. Factor') !!}</th>
                        <th>{!! $thLink('bmi', 'BMI') !!}</th>
                        <th>TEE (kJ)</th>
                        <th style="text-align:right;width:3rem"></th>
                    </tr>
                </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            @php
                                $initials = collect(explode(' ', $patient->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
                                $bmiCat   = strtolower($patient->bmi_category ?? 'normal');
                                $tee      = $patient->tee ? round($patient->tee) : null;
                            @endphp
                            <tr data-patient-id="{{ $patient->id }}">
                                {{-- Name --}}
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="pt-avatar {{ $patient->gender }}">{{ $initials }}</div>
                                        <div>
                                            <div class="display-mode font-semibold" style="color:var(--text-primary)">{{ $patient->full_name }}</div>
                                            <input type="text" name="name" value="{{ $patient->name }}" form="form-{{ $patient->id }}"
                                                   class="edit-mode hidden edit-input" style="max-width:140px">
                                        </div>
                                    </div>
                                </td>
                                {{-- Consent status --}}
                                <td class="display-mode">
                                    @if(! $patient->email)
                                        <span style="font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:20px;background:#f3f4f6;color:#6b7280">No email</span>
                                    @elseif($patient->hasConsented())
                                        <span style="font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:20px;background:#dcfce7;color:#15803d">&#x2714; Consented</span>
                                    @elseif($patient->consentDeclined())
                                        <span style="font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:20px;background:#fee2e2;color:#b91c1c">&#x2717; Declined</span>
                                    @elseif($patient->consentTokenExpired())
                                        <span style="font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:20px;background:#fef3c7;color:#92400e">&#x23F0; Link Expired</span>
                                    @else
                                        <span style="font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:20px;background:#fef3c7;color:#92400e">&#x23F3; Pending</span>
                                    @endif
                                </td>
                                {{-- Gender --}}
                                <td>
                                    <span class="display-mode">{{ ucfirst($patient->gender) }}</span>
                                    <select name="gender" form="form-{{ $patient->id }}"
                                            class="edit-mode hidden edit-input" style="width:90px">
                                        <option value="male"   {{ $patient->gender === 'male'   ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $patient->gender === 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </td>
                                {{-- Age --}}
                                <td>
                                    <span class="display-mode">{{ $patient->age }} yrs</span>
                                    <input type="number" name="age" value="{{ $patient->age }}" form="form-{{ $patient->id }}"
                                           class="edit-mode hidden edit-input" style="width:70px" min="0" max="150">
                                </td>
                                {{-- Weight --}}
                                <td>
                                    <span class="display-mode">{{ $patient->weight }} kg</span>
                                    <input type="number" step="0.01" name="weight" value="{{ $patient->weight }}" form="form-{{ $patient->id }}"
                                           class="edit-mode hidden edit-input" style="width:80px">
                                </td>
                                {{-- Height --}}
                                <td>
                                    <span class="display-mode">{{ $patient->height }} cm</span>
                                    <input type="number" step="0.01" name="height" value="{{ $patient->height }}" form="form-{{ $patient->id }}"
                                           class="edit-mode hidden edit-input" style="width:80px">
                                </td>
                                {{-- Activity Factor --}}
                                <td>
                                    <span class="display-mode">{{ $patient->activity_factor }}</span>
                                    <input type="number" step="0.01" name="activity_factor" value="{{ $patient->activity_factor }}" form="form-{{ $patient->id }}"
                                           class="edit-mode hidden edit-input" style="width:75px">
                                </td>
                                {{-- BMI --}}
                                <td>
                                    @if($patient->bmi)
                                        <span class="bmi-pill {{ $bmiCat }}">{{ number_format($patient->bmi, 2) }}</span>
                                    @else
                                        <span style="color:var(--text-muted)">—</span>
                                    @endif
                                </td>
                                {{-- TEE --}}
                                <td>
                                    @if($tee)
                                        <span class="font-semibold" style="color:var(--text-primary)">{{ number_format($tee) }}</span>
                                    @else
                                        <span style="color:var(--text-muted)">—</span>
                                    @endif
                                </td>
                                {{-- Three-dot action menu --}}
                                <td style="text-align:right;position:relative">
                                    {{-- Save/Cancel row (visible while editing) --}}
                                    <div class="edit-mode hidden" style="display:none;align-items:center;justify-content:flex-end;gap:.375rem">
                                        <button type="submit" class="tbl-btn save" form="form-{{ $patient->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            Save
                                        </button>
                                        <button type="button" class="cancel-btn tbl-btn cancel">✕</button>
                                    </div>

                                    {{-- Three-dot trigger --}}
                                    <div class="display-mode" style="position:relative;display:inline-block">
                                        <button type="button" class="dots-btn"
                                                onclick="toggleMenu(this)"
                                                style="display:inline-flex;align-items:center;justify-content:center;width:2rem;height:2rem;border-radius:6px;border:1px solid #e5e7eb;background:#fff;color:#6b7280;cursor:pointer;font-size:1.1rem;line-height:1;transition:background .15s"
                                                onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#fff'">
                                            &#8942;
                                        </button>
                                        <div class="dot-menu hidden"
                                             style="position:absolute;right:0;top:calc(100% + 4px);z-index:50;min-width:130px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,.1);overflow:hidden">
                                            <button type="button" class="edit-btn"
                                                    style="display:flex;align-items:center;gap:.55rem;width:100%;padding:.55rem .9rem;background:none;border:none;font-size:.82rem;font-weight:600;color:var(--text-primary);cursor:pointer;text-align:left"
                                                    onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='none'">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/></svg>
                                                Edit
                                            </button>
                                            <a href="{{ route('patients.show', $patient) }}"
                                               style="display:flex;align-items:center;gap:.55rem;width:100%;padding:.55rem .9rem;font-size:.82rem;font-weight:600;color:var(--text-primary);text-decoration:none"
                                               onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='none'">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                View
                                            </a>
                                            @if($patient->email && !$patient->hasConsented())
                                            <form id="resend-{{ $patient->id }}" method="POST" action="{{ route('patients.resend-consent', $patient) }}" style="margin:0">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Resend consent email to {{ addslashes($patient->email) }}?')"
                                                        style="display:flex;align-items:center;gap:.55rem;width:100%;padding:.55rem .9rem;background:none;border:none;font-size:.82rem;font-weight:600;color:#d97706;cursor:pointer;text-align:left"
                                                        onmouseover="this.style.background='#fffbeb'" onmouseout="this.style.background='none'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                    Resend Consent
                                                </button>
                                            </form>
                                            @endif
                                            <div style="border-top:1px solid #f3f4f6"></div>
                                            <button type="button"
                                                    onclick="if(confirm('Delete {{ addslashes($patient->full_name) }}? This cannot be undone.')) document.getElementById('del-{{ $patient->id }}').submit()"
                                                    style="display:flex;align-items:center;gap:.55rem;width:100%;padding:.55rem .9rem;background:none;border:none;font-size:.82rem;font-weight:600;color:#dc2626;cursor:pointer;text-align:left"
                                                    onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='none'">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 00-1-1h-4a1 1 0 00-1 1m-4 0h10"/></svg>
                                                Delete
                                            </button>
                                            <form id="del-{{ $patient->id }}" method="POST" action="{{ route('patients.destroy', $patient) }}" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0"/></svg>
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
            @if($patients->hasPages())
                <div style="padding:.9rem 1.25rem;border-top:1px solid var(--border)">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>{{-- end white card --}}
    </div>

    <script>
        // ── Three-dot menu toggle ──────────────────────────────────
        function toggleMenu(btn) {
            const menu = btn.nextElementSibling;
            const isOpen = !menu.classList.contains('hidden');
            // close all other open menus
            document.querySelectorAll('.dot-menu').forEach(m => m.classList.add('hidden'));
            if (!isOpen) menu.classList.remove('hidden');
        }

        // Close menus when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dots-btn') && !e.target.closest('.dot-menu')) {
                document.querySelectorAll('.dot-menu').forEach(m => m.classList.add('hidden'));
            }
        });

        // ── Inline edit toggle ────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const row = this.closest('tr');
                    // close the dropdown
                    row.querySelectorAll('.dot-menu').forEach(m => m.classList.add('hidden'));
                    toggleEdit(row, true);
                });
            });
            document.querySelectorAll('.cancel-btn').forEach(btn => {
                btn.addEventListener('click', function () { toggleEdit(this.closest('tr'), false); });
            });
        });

        function toggleEdit(row, edit) {
            row.querySelectorAll('.display-mode').forEach(el => {
                el.style.display = edit ? 'none' : '';
                el.classList.toggle('hidden', edit);
            });
            row.querySelectorAll('.edit-mode').forEach(el => {
                el.classList.toggle('hidden', !edit);
                // restore inline-flex for the save/cancel bar
                if (el.style.display !== undefined) {
                    el.style.display = edit ? 'flex' : 'none';
                }
            });
        }
    </script>
</x-app-layout>
