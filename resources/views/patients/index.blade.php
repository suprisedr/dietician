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
                    <p>{{ $patients->count() }} patient{{ $patients->count() !== 1 ? 's' : '' }} registered in your practice.</p>
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
        $total   = $patients->count();
        $males   = $patients->where('gender','male')->count();
        $females = $patients->where('gender','female')->count();
        $avgBmi  = $total > 0 ? round($patients->filter(fn($p)=>$p->bmi)->avg(fn($p)=>$p->bmi), 1) : null;
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
         PATIENT TABLE CARD
    ═══════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="dash-section">
            <div class="dash-section-header">
                <span class="dash-section-title">Patient Records</span>
                <span style="font-size:.75rem;color:var(--text-muted)">Click <strong>Edit</strong> to update a row inline</span>
            </div>

            @forelse($patients as $patient)
                <form id="form-{{ $patient->id }}" method="POST" action="{{ route('patients.update', $patient) }}" class="hidden">
                    @csrf
                    @method('PATCH')
                </form>
            @empty
            @endforelse

            <div class="overflow-x-auto">
                <table class="pt-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Age</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>Act. Factor</th>
                            <th>BMI</th>
                            <th>TEE (kcal)</th>
                            <th style="text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            @php
                                $initials = collect(explode(' ', $patient->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
                                $bmiCat   = strtolower($patient->bmi_category ?? 'normal');
                                $tee      = $patient->tee ? round($patient->tee / 4.184) : null;
                            @endphp
                            <tr data-patient-id="{{ $patient->id }}">
                                {{-- Name --}}
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="pt-avatar {{ $patient->gender }}">{{ $initials }}</div>
                                        <div>
                                            <div class="display-mode font-semibold" style="color:var(--text-primary)">{{ $patient->name }}</div>
                                            <input type="text" name="name" value="{{ $patient->name }}" form="form-{{ $patient->id }}"
                                                   class="edit-mode hidden edit-input" style="max-width:140px">
                                            <div class="text-xs" style="color:var(--text-muted)">{{ ucfirst($patient->gender) }}</div>
                                        </div>
                                    </div>
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
                                        <span class="bmi-pill {{ $bmiCat }}">{{ number_format($patient->bmi, 1) }}</span>
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
                                {{-- Actions --}}
                                <td style="text-align:right">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        <button type="button" class="edit-btn tbl-btn edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/></svg>
                                            Edit
                                        </button>
                                        <button type="submit" class="save-btn hidden tbl-btn save" form="form-{{ $patient->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            Save
                                        </button>
                                        <button type="button" class="cancel-btn hidden tbl-btn cancel">✕</button>
                                        <a href="{{ route('patients.show', $patient) }}" class="tbl-btn view">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
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
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function () { toggleEdit(this.closest('tr'), true); });
            });
            document.querySelectorAll('.cancel-btn').forEach(btn => {
                btn.addEventListener('click', function () { toggleEdit(this.closest('tr'), false); });
            });
        });

        function toggleEdit(row, edit) {
            row.querySelectorAll('.display-mode').forEach(el => el.classList.toggle('hidden', edit));
            row.querySelectorAll('.edit-mode').forEach(el => el.classList.toggle('hidden', !edit));
            row.querySelector('.edit-btn').classList.toggle('hidden', edit);
            row.querySelector('.save-btn').classList.toggle('hidden', !edit);
            row.querySelector('.cancel-btn').classList.toggle('hidden', !edit);
        }
    </script>
</x-app-layout>
