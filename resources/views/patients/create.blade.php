<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Back link --}}
        <a href="{{ route('patients.index') }}"
           style="display:inline-flex;align-items:center;gap:.35rem;font-size:.82rem;color:var(--text-muted);text-decoration:none;margin-bottom:1.25rem">
            ← Back to patients
        </a>

        <div class="dash-section">
            <div class="dash-section-header">
                <span class="dash-section-title">Add New Patient</span>
                <span style="font-size:.75rem;color:var(--text-muted)">All fields are required</span>
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

                <form method="POST" action="{{ route('patients.store') }}">
                    @csrf

                    {{-- Name + Age --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">
                                Full Name
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   placeholder="e.g. Jane Doe"
                                   style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s"
                                   onfocus="this.style.borderColor='var(--primary)'"
                                   onblur="this.style.borderColor='#d1d5db'"
                                   required>
                            @error('name')
                                <p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">
                                Age (years)
                            </label>
                            <input type="number" name="age" value="{{ old('age') }}"
                                   placeholder="e.g. 35"
                                   min="0" max="150"
                                   style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s"
                                   onfocus="this.style.borderColor='var(--primary)'"
                                   onblur="this.style.borderColor='#d1d5db'"
                                   required>
                            @error('age')
                                <p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div style="margin-bottom:1rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">
                            Gender
                        </label>
                        <div style="display:flex;gap:.75rem">
                            <label style="display:flex;align-items:center;gap:.5rem;padding:.5rem .875rem;border:1px solid #d1d5db;border-radius:6px;cursor:pointer;font-size:.875rem;transition:all .15s"
                                   id="lbl-male">
                                <input type="radio" name="gender" value="male"
                                       {{ old('gender','male')==='male' ? 'checked' : '' }}
                                       onchange="styleGenderLabels()"
                                       style="accent-color:var(--primary)">
                                Male
                            </label>
                            <label style="display:flex;align-items:center;gap:.5rem;padding:.5rem .875rem;border:1px solid #d1d5db;border-radius:6px;cursor:pointer;font-size:.875rem;transition:all .15s"
                                   id="lbl-female">
                                <input type="radio" name="gender" value="female"
                                       {{ old('gender')==='female' ? 'checked' : '' }}
                                       onchange="styleGenderLabels()"
                                       style="accent-color:var(--primary)">
                                Female
                            </label>
                        </div>
                        @error('gender')
                            <p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Weight + Height --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">
                                Weight (kg)
                            </label>
                            <div style="position:relative">
                                <input type="number" step="0.1" name="weight" value="{{ old('weight') }}"
                                       placeholder="e.g. 72.5"
                                       min="0"
                                       style="width:100%;padding:.5rem 2.75rem .5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s;box-sizing:border-box"
                                       onfocus="this.style.borderColor='var(--primary)'"
                                       onblur="this.style.borderColor='#d1d5db'"
                                       required>
                                <span style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);font-size:.75rem;color:var(--text-muted);pointer-events:none">kg</span>
                            </div>
                            @error('weight')
                                <p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">
                                Height (cm)
                            </label>
                            <div style="position:relative">
                                <input type="number" step="0.1" name="height" value="{{ old('height') }}"
                                       placeholder="e.g. 168"
                                       min="0"
                                       style="width:100%;padding:.5rem 2.75rem .5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s;box-sizing:border-box"
                                       onfocus="this.style.borderColor='var(--primary)'"
                                       onblur="this.style.borderColor='#d1d5db'"
                                       required>
                                <span style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);font-size:.75rem;color:var(--text-muted);pointer-events:none">cm</span>
                            </div>
                            @error('height')
                                <p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Activity Factor --}}
                    <div style="margin-bottom:1.5rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem">
                            Activity Factor
                        </label>
                        <input type="number" step="0.001" name="activity_factor" value="{{ old('activity_factor') }}"
                               placeholder="e.g. 1.55"
                               min="1" max="3"
                               style="width:100%;padding:.5rem .75rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;transition:border-color .15s"
                               onfocus="this.style.borderColor='var(--primary)'"
                               onblur="this.style.borderColor='#d1d5db'"
                               required>
                        <p style="margin-top:.4rem;font-size:.74rem;color:var(--text-muted)">
                            Typically between 1.2 (sedentary) and 1.9 (very active). Used to calculate TEE.
                        </p>
                        @error('activity_factor')
                            <p style="margin-top:.3rem;font-size:.75rem;color:#dc2626">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:.75rem;align-items:center">
                        <button type="submit"
                                style="padding:.55rem 1.5rem;background:var(--primary);color:#fff;font-weight:700;font-size:.875rem;border:none;border-radius:6px;cursor:pointer;transition:opacity .15s"
                                onmouseover="this.style.opacity='.85'"
                                onmouseout="this.style.opacity='1'">
                            Add Patient
                        </button>
                        <a href="{{ route('patients.index') }}"
                           style="padding:.55rem 1rem;background:#f1f5f9;color:var(--text-primary);font-weight:600;font-size:.875rem;border-radius:6px;text-decoration:none;transition:background .15s"
                           onmouseover="this.style.background='#e2e8f0'"
                           onmouseout="this.style.background='#f1f5f9'">
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
            lblM.style.background  = male.checked   ? 'var(--primary-light, #eef2ff)' : '';
            lblF.style.borderColor = female.checked ? 'var(--primary)' : '#d1d5db';
            lblF.style.background  = female.checked ? 'var(--primary-light, #eef2ff)' : '';
        }
        document.addEventListener('DOMContentLoaded', styleGenderLabels);
    </script>
</x-app-layout>
