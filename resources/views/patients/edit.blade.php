<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Back link --}}
        <a href="{{ route('patients.show', $patient) }}"
           style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:var(--text-muted);text-decoration:none;margin-bottom:1.25rem">
            ← Back to patient
        </a>

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

                    {{-- ── Clinical Details ─────────────────────────────────── --}}
                    <p style="font-size:.67rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--primary-dark);padding-bottom:.3rem;border-bottom:1.5px solid var(--border);margin:.6rem 0 .9rem">Clinical Details</p>

                    {{-- Reason for Assessment --}}
                    <div style="margin-bottom:1rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Reason for Assessment</label>
                        <textarea name="reason_for_assessment" rows="2"
                                  placeholder="e.g. Weight management, diabetes follow-up…"
                                  style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;resize:vertical;transition:border-color .15s;box-sizing:border-box"
                                  onfocus="this.style.borderColor='var(--primary)'"
                                  onblur="this.style.borderColor='#d1d5db'">{{ old('reason_for_assessment', $patient->reason_for_assessment) }}</textarea>
                        @error('reason_for_assessment')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                    </div>

                    {{-- Weight + Height --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Weight (kg)</label>
                            <div style="position:relative">
                                <input type="number" step="0.1" name="weight" value="{{ old('weight', $patient->weight) }}"
                                       min="0"
                                       style="width:100%;padding:.5rem 2.75rem .5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s;box-sizing:border-box"
                                       onfocus="this.style.borderColor='var(--primary)'"
                                       onblur="this.style.borderColor='#d1d5db'"
                                       required>
                                <span style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);font-size:.75rem;color:var(--text-muted);pointer-events:none">kg</span>
                            </div>
                            @error('weight')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">Height (cm)</label>
                            <div style="position:relative">
                                <input type="number" step="0.1" name="height" value="{{ old('height', $patient->height) }}"
                                       min="0"
                                       style="width:100%;padding:.5rem 2.75rem .5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s;box-sizing:border-box"
                                       onfocus="this.style.borderColor='var(--primary)'"
                                       onblur="this.style.borderColor='#d1d5db'"
                                       required>
                                <span style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);font-size:.75rem;color:var(--text-muted);pointer-events:none">cm</span>
                            </div>
                            @error('height')<p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>@enderror
                        </div>
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

                    {{-- IBW BMI Target --}}
                    <div style="margin-bottom:1rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.5rem">IBW BMI Target</label>
                        <p style="font-size:.75rem;color:var(--text-muted);margin-bottom:.55rem">Choose which BMI value to use when calculating Ideal Body Weight.</p>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.6rem">
                            @foreach([22=>'Medical ideal',25=>'Healthy upper',30=>'Obesity threshold'] as $bmiVal=>$bmiLabel)
                                <label style="display:flex;flex-direction:column;align-items:center;padding:.6rem .5rem;border:1.5px solid {{ old('ibw_bmi_target',$patient->ibw_bmi_target??22)==$bmiVal ? 'var(--primary)' : '#d1d5db' }};border-radius:7px;cursor:pointer;transition:border-color .15s;background:{{ old('ibw_bmi_target',$patient->ibw_bmi_target??22)==$bmiVal ? 'rgba(103,159,95,.07)' : '#fff' }}">
                                    <input type="radio" name="ibw_bmi_target" value="{{ $bmiVal }}"
                                           {{ old('ibw_bmi_target', $patient->ibw_bmi_target ?? 22) == $bmiVal ? 'checked' : '' }}
                                           style="accent-color:var(--primary);margin-bottom:.25rem">
                                    <span style="font-size:.82rem;font-weight:700;color:var(--text-primary)">BMI {{ $bmiVal }}</span>
                                    <span style="font-size:.7rem;color:var(--text-muted);text-align:center">{{ $bmiLabel }}</span>
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
        </div>
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
            if (type !== 'sa_id') document.getElementById('dob-hint').style.display = 'none';
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

        document.addEventListener('DOMContentLoaded', function() { styleGenderLabels(); onIdTypeChange(); });
    </script>
</x-app-layout>
