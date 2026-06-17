<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Back link --}}
        <a href="{{ route('patients.show', $patient) }}"
           style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:var(--text-muted);text-decoration:none;margin-bottom:1.25rem">
            ← Back to patient
        </a>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start">

        {{-- ── LEFT: Patient Details ─────────────────────────── --}}
        <div class="dash-section">
            <div class="dash-section-header">
                <span class="dash-section-title">Edit: {{ $patient->full_name }}</span>
            </div>

            <div style="padding:1.5rem">

                @if($errors->any())
                    <div style="margin-bottom:1.25rem;padding:.875rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;font-size:.83rem;color:#b91c1c">
                        <strong>Please fix the following errors:</strong>
                        <ul style="margin:.4rem 0 0 1.1rem;padding:0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('patients.update', $patient) }}">
                    @csrf @method('PUT')

                    {{-- ── Personal Details ─────────────────────────────────── --}}
                    <p style="font-size:.67rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--primary-dark);padding-bottom:.3rem;border-bottom:1.5px solid var(--border);margin-bottom:.9rem">Personal Details</p>

                    {{-- Title + Name + Surname --}}
                    <div style="display:grid;grid-template-columns:100px 1fr 1fr;gap:1rem;margin-bottom:1rem">
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Title</label>
                            <select name="title"
                                    style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;background:#fff"
                                    onfocus="this.style.borderColor='var(--primary)'"
                                    onblur="this.style.borderColor='#d1d5db'">
                                <option value="">—</option>
                                @foreach(['Mr','Mrs','Ms','Miss','Dr','Prof','Rev'] as $t)
                                    <option value="{{ $t }}" {{ old('title', $patient->title) === $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">First Name</label>
                            <input type="text" name="name" value="{{ old('name', $patient->name) }}"
                                   placeholder="e.g. Jane"
                                   style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s"
                                   onfocus="this.style.borderColor='var(--primary)'"
                                   onblur="this.style.borderColor='#d1d5db'"
                                   required>
                            @error('name')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Surname</label>
                            <input type="text" name="surname" value="{{ old('surname', $patient->surname) }}"
                                   placeholder="e.g. Doe"
                                   style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s"
                                   onfocus="this.style.borderColor='var(--primary)'"
                                   onblur="this.style.borderColor='#d1d5db'">
                            @error('surname')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Age + Gender --}}
                    <div style="display:grid;grid-template-columns:160px 1fr;gap:1rem;margin-bottom:1rem">
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Age (years)</label>
                            <input type="number" name="age" value="{{ old('age', $patient->age) }}"
                                   min="0" max="150"
                                   style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s"
                                   onfocus="this.style.borderColor='var(--primary)'"
                                   onblur="this.style.borderColor='#d1d5db'"
                                   required>
                            @error('age')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Gender</label>
                            <div style="display:flex;gap:.75rem">
                                @foreach(['male','female'] as $g)
                                    <label style="display:flex;align-items:center;gap:.5rem;padding:.5rem .875rem;border:1px solid #d1d5db;border-radius:6px;cursor:pointer;font-size:.875rem;transition:all .15s"
                                           id="lbl-{{ $g }}">
                                        <input type="radio" name="gender" value="{{ $g }}"
                                               {{ old('gender', $patient->gender) === $g ? 'checked' : '' }}
                                               onchange="styleGenderLabels()"
                                               style="accent-color:var(--primary)">
                                        {{ ucfirst($g) }}
                                    </label>
                                @endforeach
                            </div>
                            @error('gender')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div style="margin-bottom:1rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $patient->email) }}"
                               placeholder="e.g. patient@email.com"
                               style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s;box-sizing:border-box"
                               onfocus="this.style.borderColor='var(--primary)'"
                               onblur="this.style.borderColor='#d1d5db'">
                        @error('email')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                    </div>

                    {{-- ID Type + Number --}}
                    <div style="display:grid;grid-template-columns:160px 1fr;gap:1rem;margin-bottom:1rem">
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">ID Type</label>
                            <select name="id_type" id="id_type"
                                    style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;background:#fff"
                                    onchange="onIdTypeChange()">
                                <option value="">— none —</option>
                                <option value="sa_id"    {{ old('id_type', $patient->id_type) === 'sa_id'    ? 'selected' : '' }}>SA ID Number</option>
                                <option value="passport" {{ old('id_type', $patient->id_type) === 'passport' ? 'selected' : '' }}>Passport</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">ID / Passport Number</label>
                            <input type="text" name="id_number" id="id_number"
                                   value="{{ old('id_number', $patient->id_number) }}"
                                   maxlength="50"
                                   style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s;box-sizing:border-box"
                                   onfocus="this.style.borderColor='var(--primary)'"
                                   onblur="this.style.borderColor='#d1d5db'"
                                   oninput="onIdNumberInput()">
                            @error('id_number')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Date of Birth --}}
                    <div style="margin-bottom:1rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">
                            Date of Birth
                            <span id="dob-hint" style="font-weight:400;font-size:.7rem;color:var(--primary);margin-left:.4rem;display:none">auto-filled from ID</span>
                        </label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                               value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}"
                               style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s;box-sizing:border-box"
                               onfocus="this.style.borderColor='var(--primary)'"
                               onblur="this.style.borderColor='#d1d5db'">
                        @error('date_of_birth')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                    </div>

                    {{-- Address --}}
                    <div style="margin-bottom:1rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Address</label>
                        <textarea name="address" rows="2"
                                  placeholder="e.g. 12 Main Street, Cape Town, 8001"
                                  style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;resize:vertical;transition:border-color .15s;box-sizing:border-box"
                                  onfocus="this.style.borderColor='var(--primary)'"
                                  onblur="this.style.borderColor='#d1d5db'">{{ old('address', $patient->address) }}</textarea>
                        @error('address')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                    </div>

                    {{-- Weight + Height --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Weight (kg)</label>
                            <div style="position:relative">
                                <input type="number" step="0.1" name="weight" id="weight" value="{{ old('weight', $patient->weight) }}"
                                       min="0"
                                       style="width:100%;padding:.5rem 2.75rem .5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s;box-sizing:border-box"
                                       onfocus="this.style.borderColor='var(--primary)'"
                                       onblur="this.style.borderColor='#d1d5db'"
                                       oninput="updateBmi()"
                                       required>
                                <span style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);font-size:.75rem;color:var(--text-muted);pointer-events:none">kg</span>
                            </div>
                            @error('weight')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Height (cm)</label>
                            <div style="position:relative">
                                <input type="number" step="0.1" name="height" id="height" value="{{ old('height', $patient->height) }}"
                                       min="0"
                                       style="width:100%;padding:.5rem 2.75rem .5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s;box-sizing:border-box"
                                       onfocus="this.style.borderColor='var(--primary)'"
                                       onblur="this.style.borderColor='#d1d5db'"
                                       oninput="updateBmi()"
                                       required>
                                <span style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);font-size:.75rem;color:var(--text-muted);pointer-events:none">cm</span>
                            </div>
                            @error('height')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Live BMI display --}}
                    <div id="bmi-display" style="margin-bottom:1rem;padding:.55rem .75rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;display:flex;align-items:center;gap:.6rem;font-size:.875rem">
                        <span style="font-weight:600;color:var(--text-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.04em">BMI</span>
                        <span id="bmi-value" style="font-weight:700;font-size:1rem;color:var(--text-primary)">—</span>
                        <span id="bmi-category" style="font-size:.72rem;font-weight:600;padding:.15rem .55rem;border-radius:999px;background:#e5e7eb;color:#374151"></span>
                        <span style="font-size:.72rem;color:var(--text-muted);margin-left:auto">kg/m²</span>
                    </div>

                    {{-- Activity Factor --}}
                    <div style="margin-bottom:1.5rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Activity Factor</label>
                        <input type="number" step="0.001" name="activity_factor" value="{{ old('activity_factor', $patient->activity_factor) }}"
                               min="1" max="3"
                               style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s"
                               onfocus="this.style.borderColor='var(--primary)'"
                               onblur="this.style.borderColor='#d1d5db'"
                               required>
                        <p style="margin-top:.4rem;font-size:.74rem;color:var(--text-muted)">Typically between 1.2 (sedentary) and 1.9 (very active).</p>
                        @error('activity_factor')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                    </div>

                    {{-- Ideal Body Weight (IBW) BMI Target --}}
                    <div style="margin-bottom:1rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.5rem">Ideal Body Weight (IBW) BMI Target</label>
                        <p style="font-size:.75rem;color:var(--text-muted);margin-bottom:.55rem">Choose which BMI value to use when calculating Ideal Body Weight.</p>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.6rem">
                            @foreach([22, 25, 30] as $bmiVal)
                                <label style="display:flex;flex-direction:column;align-items:center;padding:.6rem .5rem;border:1.5px solid {{ old('ibw_bmi_target',$patient->ibw_bmi_target??22)==$bmiVal ? 'var(--primary)' : '#d1d5db' }};border-radius:7px;cursor:pointer;transition:border-color .15s;background:{{ old('ibw_bmi_target',$patient->ibw_bmi_target??22)==$bmiVal ? 'rgba(103,159,95,.07)' : '#fff' }}">
                                    <input type="radio" name="ibw_bmi_target" value="{{ $bmiVal }}"
                                           {{ old('ibw_bmi_target', $patient->ibw_bmi_target ?? 22) == $bmiVal ? 'checked' : '' }}
                                           style="accent-color:var(--primary);margin-bottom:.25rem">
                                    <span style="font-size:.82rem;font-weight:700;color:var(--text-primary)">BMI {{ $bmiVal }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('ibw_bmi_target')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:.75rem;align-items:center;margin-top:1.5rem">
                        <button type="submit"
                                style="padding:.55rem 1.5rem;background:var(--primary);color:#fff;font-weight:700;font-size:.875rem;border:none;border-radius:6px;cursor:pointer"
                                onmouseover="this.style.opacity='.85'"
                                onmouseout="this.style.opacity='1'">
                            Save Changes
                        </button>
                        <a href="{{ route('patients.show', $patient) }}"
                           style="padding:.55rem 1rem;background:#f1f5f9;color:var(--text-primary);font-weight:600;font-size:.875rem;border-radius:6px;text-decoration:none">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>{{-- /left col --}}

        {{-- ── RIGHT: Clinical / Subjective Assessment ──────── --}}
        <div class="dash-section">
            <div class="dash-section-header">
                <span class="dash-section-title">Clinical / Subjective Assessment</span>
            </div>
            <div style="padding:1.5rem">
                <form method="POST" action="{{ route('patients.clinical-assessment.update', $patient->id) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Reason for Assessment --}}
                    <x-chip-multiselect
                        name="reason_for_assessment"
                        label="Chief Complaint / Reason for Assessment"
                        :options="config('patient_clinical.reasons')"
                        :value="old('reason_for_assessment', $patient->reason_for_assessment ?? '')"
                        placeholder="Add a custom reason"/>
                    @error('reason_for_assessment')<p style="margin-top:-.5rem;margin-bottom:.75rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror

                    {{-- Referred By --}}
                    <x-chip-multiselect
                        name="referred_by"
                        label="Referred By"
                        :options="config('patient_clinical.referrers')"
                        :value="old('referred_by', $patient->referred_by ?? '')"
                        placeholder="e.g. Dr. Smith (GP)"/>
                    @error('referred_by')<p style="margin-top:-.5rem;margin-bottom:.75rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror

                    {{-- Allergies --}}
                    <x-chip-multiselect
                        name="allergies"
                        :label="$patient->allergies ? 'Allergies / Intolerances ⚠ On file' : 'Allergies / Intolerances'"
                        :options="config('patient_clinical.allergies')"
                        :value="old('allergies', $patient->allergies ?? '')"
                        placeholder="Add another allergy"/>
                    @error('allergies')<p style="margin-top:-.5rem;margin-bottom:.75rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror

                    {{-- Medical History / Conditions --}}
                    <x-chip-multiselect
                        name="medical_history"
                        label="Medical History / Conditions"
                        :options="config('patient_clinical.conditions')"
                        :value="old('medical_history', $patient->medical_history ?? '')"
                        placeholder="Add another condition"/>
                    @error('medical_history')<p style="margin-top:-.5rem;margin-bottom:.75rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror

                    {{-- Medications --}}
                    <x-chip-multiselect
                        name="medications"
                        label="Current Medications"
                        :options="config('patient_clinical.medications')"
                        :value="old('medications', $patient->medications ?? '')"
                        placeholder="e.g. Metformin 500mg BD"/>
                    @error('medications')<p style="margin-top:-.5rem;margin-bottom:.75rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror

                    {{-- Dietary History --}}
                    <div style="margin-bottom:1rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Dietary History</label>
                        <textarea name="dietary_history" rows="2"
                                  placeholder="e.g. Follows a low-carb diet, vegetarian since 2020…"
                                  style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;resize:vertical;transition:border-color .15s;box-sizing:border-box"
                                  onfocus="this.style.borderColor='var(--primary)'"
                                  onblur="this.style.borderColor='#d1d5db'">{{ old('dietary_history', $patient->dietary_history) }}</textarea>
                        @error('dietary_history')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                    </div>

                    {{-- Appetite --}}
                    <div style="margin-bottom:1.5rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Appetite</label>
                        <div style="display:flex;gap:.75rem;flex-wrap:wrap">
                            @foreach(['good' => 'Good', 'fair' => 'Fair', 'poor' => 'Poor'] as $val => $lbl)
                            @php
                                $sel   = old('appetite', $patient->appetite) === $val;
                                $aClr  = $val === 'good' ? 'var(--primary)' : ($val === 'fair' ? '#f59e0b' : '#ef4444');
                                $aBg   = $val === 'good' ? 'rgba(103,159,95,.07)' : ($val === 'fair' ? '#fffbeb' : '#fef2f2');
                            @endphp
                            <label style="display:flex;align-items:center;gap:.4rem;padding:.45rem .875rem;border:1.5px solid {{ $sel ? $aClr : '#d1d5db' }};border-radius:6px;cursor:pointer;font-size:.875rem;font-weight:600;color:{{ $sel ? $aClr : 'var(--text-muted)' }};background:{{ $sel ? $aBg : '#fff' }}">
                                <input type="radio" name="appetite" value="{{ $val }}"
                                       {{ $sel ? 'checked' : '' }}
                                       style="accent-color:{{ $aClr }}">
                                {{ $lbl }}
                            </label>
                            @endforeach
                        </div>
                        @error('appetite')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                    </div>

                    {{-- Oedema --}}
                    <div style="margin-bottom:1.5rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.5rem">Oedema / Fluid Overload</label>
                        @php $hasOedema = old('oedema', $patient->oedema ? '1' : '0') === '1'; @endphp
                        <div style="display:flex;gap:.75rem;flex-wrap:wrap">
                            <label style="display:flex;align-items:center;gap:.4rem;padding:.45rem .875rem;border:1.5px solid {{ !$hasOedema ? 'var(--primary)' : '#d1d5db' }};border-radius:6px;cursor:pointer;font-size:.875rem;font-weight:600;color:{{ !$hasOedema ? 'var(--primary)' : 'var(--text-muted)' }};background:{{ !$hasOedema ? 'rgba(103,159,95,.07)' : '#fff' }}">
                                <input type="radio" name="oedema" value="0" {{ !$hasOedema ? 'checked' : '' }} style="accent-color:var(--primary)">
                                No oedema
                            </label>
                            <label style="display:flex;align-items:center;gap:.4rem;padding:.45rem .875rem;border:1.5px solid {{ $hasOedema ? '#f59e0b' : '#d1d5db' }};border-radius:6px;cursor:pointer;font-size:.875rem;font-weight:600;color:{{ $hasOedema ? '#92400e' : 'var(--text-muted)' }};background:{{ $hasOedema ? '#fffbeb' : '#fff' }}">
                                <input type="radio" name="oedema" value="1" {{ $hasOedema ? 'checked' : '' }} style="accent-color:#f59e0b">
                                Oedema present
                            </label>
                        </div>
                        @if($patient->oedema_changed_at)
                            <p style="font-size:.7rem;color:var(--text-muted);margin:.35rem 0 0">Last changed {{ $patient->oedema_changed_at->diffForHumans() }}</p>
                        @endif
                    </div>

                    <div style="display:flex;justify-content:flex-end;padding-top:.5rem;border-top:1px solid var(--border)">
                        <button type="submit"
                            style="display:inline-flex;align-items:center;gap:.45rem;padding:.55rem 1.35rem;background:var(--primary);color:#fff;border:none;border-radius:8px;font-size:.85rem;font-weight:700;cursor:pointer;transition:background .15s"
                            onmouseover="this.style.background='var(--primary-dark)'"
                            onmouseout="this.style.background='var(--primary)'">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Save Clinical Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>{{-- /right col --}}

        </div>{{-- /two-col grid --}}
    </div>

    <script>
        function styleGenderLabels() {
            const male   = document.querySelector('input[name="gender"][value="male"]');
            const female = document.querySelector('input[name="gender"][value="female"]');
            const lblM   = document.getElementById('lbl-male');
            const lblF   = document.getElementById('lbl-female');
            if (!male || !female) return;
            lblM.style.borderColor = male.checked   ? 'var(--primary)' : '#d1d5db';
            lblM.style.background  = male.checked   ? 'rgba(103,159,95,.07)' : '';
            lblF.style.borderColor = female.checked ? 'var(--primary)' : '#d1d5db';
            lblF.style.background  = female.checked ? 'rgba(103,159,95,.07)' : '';
        }

        function onIdTypeChange() {
            const type = document.getElementById('id_type').value;
            const numEl = document.getElementById('id_number');
            numEl.placeholder = type === 'sa_id' ? 'e.g. 9001015800086 (13 digits)' : 'e.g. A12345678';
            if (type !== 'sa_id') {
                document.getElementById('dob-hint').style.display = 'none';
            } else {
                onIdNumberInput();
            }
        }

        function onIdNumberInput() {
            const type = document.getElementById('id_type').value;
            if (type !== 'sa_id') return;
            const val = document.getElementById('id_number').value.replace(/\D/g, '');
            if (val.length !== 13) { document.getElementById('dob-hint').style.display = 'none'; return; }

            const yy   = parseInt(val.substring(0, 2), 10);
            const mm   = val.substring(2, 4);
            const dd   = val.substring(4, 6);
            const seq  = parseInt(val.substring(6, 10), 10);
            const currentYY = new Date().getFullYear() % 100;
            const yyyy = yy > currentYY ? 1900 + yy : 2000 + yy;

            const dobStr = yyyy + '-' + mm + '-' + dd;
            document.getElementById('date_of_birth').value = dobStr;
            document.getElementById('dob-hint').style.display = 'inline';

            const isMale = seq >= 5000;
            const maleRad  = document.querySelector('input[name="gender"][value="male"]');
            const femaleRad= document.querySelector('input[name="gender"][value="female"]');
            if (maleRad && femaleRad) { maleRad.checked = isMale; femaleRad.checked = !isMale; styleGenderLabels(); }

            const today = new Date(), dob = new Date(dobStr);
            let age = today.getFullYear() - dob.getFullYear();
            const mo = today.getMonth() - dob.getMonth();
            if (mo < 0 || (mo === 0 && today.getDate() < dob.getDate())) age--;
            const ageEl = document.querySelector('input[name="age"]');
            if (ageEl && age >= 0 && age <= 150) ageEl.value = age;
        }

        function updateBmi() {
            const w = parseFloat(document.getElementById('weight')?.value);
            const h = parseFloat(document.getElementById('height')?.value);
            const valEl = document.getElementById('bmi-value');
            const catEl = document.getElementById('bmi-category');
            if (!valEl || !catEl) return;
            if (!w || !h || h <= 0) { valEl.textContent = '\u2014'; catEl.textContent = ''; catEl.style.background='#e5e7eb'; catEl.style.color='#374151'; return; }
            const bmi = w / Math.pow(h / 100, 2);
            valEl.textContent = bmi.toFixed(1);
            let cat, bg, col;
            if (bmi < 18.5)      { cat='Underweight'; bg='#dbeafe'; col='#1d4ed8'; }
            else if (bmi < 25)   { cat='Normal';      bg='#dcfce7'; col='#15803d'; }
            else if (bmi < 30)   { cat='Overweight';  bg='#fef9c3'; col='#92400e'; }
            else                 { cat='Obese';        bg='#fee2e2'; col='#b91c1c'; }
            catEl.textContent = cat;
            catEl.style.background = bg;
            catEl.style.color = col;

            // Auto-select IBW BMI target based on BMI category
            const target = bmi < 25 ? 22 : (bmi < 30 ? 25 : 30);
            document.querySelectorAll('input[name="ibw_bmi_target"]').forEach(function(radio) {
                const val = parseInt(radio.value, 10);
                radio.checked = (val === target);
                const lbl = radio.closest('label');
                if (lbl) {
                    lbl.style.borderColor = radio.checked ? 'var(--primary)' : '#d1d5db';
                    lbl.style.background  = radio.checked ? 'rgba(103,159,95,.07)' : '#fff';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() { styleGenderLabels(); onIdTypeChange(); updateBmi(); });
    </script>
</x-app-layout>
