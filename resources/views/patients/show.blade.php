<x-app-layout>

    {{-- ═══════════════════════════════════════════
         PATIENT PROFILE HERO
    ═══════════════════════════════════════════ --}}
    @php
        $initials = collect(explode(' ', $patient->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
        $bmiCat   = strtolower($patient->bmi_category ?? 'normal');
        $teeKcal  = $patient->tee ? round($patient->tee / 4.184) : null;
        $teeKj    = $patient->tee ?? 0;

        // Obesity BMR adjustments (only relevant when BMI > 30)
        $isObese          = ($patient->bmi ?? 0) > 30;
        $bmrActual        = $patient->bmr_actual;           // kcal — actual weight, no correction
        $bmrAbwAdj        = $patient->bmr;                  // kcal — IBW + 0.25×(actual−IBW)
        $bmrBmiAdj        = $patient->bmr_bmi_adjusted;     // kcal — capped at BMI 25 weight
        $weightForBmr     = $patient->weight_for_bmr;       // kg used in primary BMR

        // TEE variants (kJ) for the two additional estimates
        $teeAbwAdjKj      = ($bmrAbwAdj  && $patient->activity_factor) ? $bmrAbwAdj  * $patient->activity_factor * 4.184 : null;
        $teeBmiAdjKj      = ($bmrBmiAdj  && $patient->activity_factor) ? $bmrBmiAdj  * $patient->activity_factor * 4.184 : null;
        $teeActualKj      = ($bmrActual  && $patient->activity_factor) ? $bmrActual  * $patient->activity_factor * 4.184 : null;

        $macroColors = [
            'carbohydrate'  => ['dot'=>'#f97316','bg'=>'rgba(249,115,22,.12)','text'=>'#c2410c'],
            'carbohydrates' => ['dot'=>'#f97316','bg'=>'rgba(249,115,22,.12)','text'=>'#c2410c'],
            'protein'       => ['dot'=>'#6366f1','bg'=>'rgba(99,102,241,.12)', 'text'=>'#4338ca'],
            'proteins'      => ['dot'=>'#6366f1','bg'=>'rgba(99,102,241,.12)', 'text'=>'#4338ca'],
            'fat'           => ['dot'=>'#14b8a6','bg'=>'rgba(20,184,166,.12)',  'text'=>'#0f766e'],
            'fats'          => ['dot'=>'#14b8a6','bg'=>'rgba(20,184,166,.12)',  'text'=>'#0f766e'],
        ];

        // Recommended intakes derived from TEE + macro targets
        // kJ factors: CHO=17, Protein=17, Fat=38
        $macroByType = $patient->macronutrients->keyBy('type');
        $recCho_g    = null; $recPro_g  = null; $recFat_g  = null;
        $recCho_kj   = null; $recPro_kj = null; $recFat_kj = null;
        if ($teeKj > 0) {
            $choPct  = optional($macroByType->get('carbohydrate') ?? $macroByType->get('carbohydrates'))->selected_percentage ?? 0;
            $proPct  = optional($macroByType->get('protein')      ?? $macroByType->get('proteins'))->selected_percentage      ?? 0;
            $fatPct  = optional($macroByType->get('fat')          ?? $macroByType->get('fats'))->selected_percentage          ?? 0;
            $recCho_kj  = round($teeKj * $choPct / 100);
            $recPro_kj  = round($teeKj * $proPct / 100);
            $recFat_kj  = round($teeKj * $fatPct / 100);
            $recCho_g   = $recCho_kj > 0 ? round($recCho_kj / 17) : 0;
            $recPro_g   = $recPro_kj > 0 ? round($recPro_kj / 17) : 0;
            $recFat_g   = $recFat_kj > 0 ? round($recFat_kj / 38) : 0;
        }
    @endphp

    <div class="dash-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Back nav --}}
            <a href="{{ route('patients.index') }}" class="btn-back mb-5 inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                All Patients
            </a>

            {{-- Patient identity --}}
            <div class="flex items-center gap-4 flex-wrap">
                <div class="patient-avatar-lg {{ $patient->gender }}">{{ $initials }}</div>
                <div style="flex:1;min-width:0">
                    <h1 style="font-size:clamp(1.5rem,3vw,2.2rem);font-weight:800;letter-spacing:-.03em;line-height:1.1">
                        {{ $patient->full_name }}
                    </h1>
                    <p style="opacity:.7;font-size:.9rem;margin-top:.2rem">
                        {{ ucfirst($patient->gender) }} · {{ $patient->age }} years · Registered {{ $patient->created_at->format('M d, Y') }}
                    </p>
                    @if($patient->reason_for_assessment)
                        <p style="opacity:.85;font-size:.82rem;margin-top:.3rem;font-style:italic">
                            📋 {{ $patient->reason_for_assessment }}
                        </p>
                    @endif
                </div>
                <a href="{{ route('patients.report', $patient->id) }}" target="_blank"
                   style="display:inline-flex;align-items:center;gap:.45rem;padding:.5rem 1.1rem;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.3);border-radius:7px;color:#fff;font-size:.8rem;font-weight:700;text-decoration:none;white-space:nowrap;transition:background .2s"
                   onmouseover="this.style.background='rgba(255,255,255,.22)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:.95rem;height:.95rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 0 0 2-2V9.414a1 1 0 0 0-.293-.707l-5.414-5.414A1 1 0 0 0 12.586 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z"/></svg>
                    Patient Report
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         FLOATING METRIC CARDS
    ═══════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 metric-cards-row">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">

            <div class="metric-card">
                <div class="mc-val">{{ $patient->weight ?? '—' }}</div>
                <div class="mc-label">Weight</div>
                <div class="mc-sub">kg</div>
            </div>
            <div class="metric-card">
                <div class="mc-val">{{ $patient->height ?? '—' }}</div>
                <div class="mc-label">Height</div>
                <div class="mc-sub">cm</div>
            </div>
            <div class="metric-card">
                @if($patient->bmi)
                    <div class="mc-val">{{ number_format($patient->bmi, 2) }}</div>
                    <div class="mc-label">BMI</div>
                    <div class="mc-sub"><span class="bmi-pill {{ $bmiCat }}">{{ $patient->bmi_category }}</span></div>
                @else
                    <div class="mc-val">—</div>
                    <div class="mc-label">BMI</div>
                @endif
            </div>
            <div class="metric-card">
                <div class="mc-val">{{ $patient->bmr ? number_format($patient->bmr * 4.184, 0) : '—' }}</div>
                <div class="mc-label">RMR @if($isObese)<span style="font-size:.6rem;font-weight:700;padding:.05rem .35rem;background:#fff7ed;color:#c2410c;border-radius:999px;vertical-align:middle">Adj.</span>@endif</div>
                <div class="mc-sub">kJ/day</div>
            </div>
            <div class="metric-card">
                <div class="mc-val">{{ $teeKj ? number_format($teeKj) : '—' }}</div>
                <div class="mc-label">TEE</div>
                <div class="mc-sub">kJ/day</div>
            </div>
            <div class="metric-card">
                <div class="mc-val" id="hero-ibw-val">{{ $patient->ibw ? number_format($patient->ibw, 2) : '—' }}</div>
                <div class="mc-label">IBW <span style="font-size:.55rem;opacity:.7" id="hero-ibw-label">(BMI {{ $patient->ibw_bmi_target ?? 22 }})</span></div>
                <div class="mc-sub">kg</div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert-success mb-4">✓ {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-error mb-4">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">

        {{-- ═══════════════════════════════════════════
             DIET PRESETS
        ═══════════════════════════════════════════ --}}
        <x-plan-gate min="package_1">
        <div class="dash-section" id="diet-preset-section">
            <div class="dash-section-header" style="cursor:pointer;user-select:none" onclick="toggleSection('preset-body','preset-chevron')">
                <span class="dash-section-title">Diet Presets</span>
                <svg id="preset-chevron" xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--text-muted);transition:transform .25s;transform:rotate(0deg)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </div>
            <div id="preset-body" style="display:block;padding:1.25rem">
                <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:1rem">
                    Select a standard diet template — the exchange items &amp; meal plan slots will be previewed below.
                    Click <strong>Apply Preset</strong> to save it to this patient.
                </p>

                {{-- Preset selector cards --}}
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:.75rem;margin-bottom:1.25rem">
                    @php $dbPresets = \App\Models\DietPreset::all(); @endphp
                    @foreach($dbPresets as $dp)
                    <label class="preset-card {{ $patient->diet_preset_id === $dp->id ? 'selected' : '' }}" data-preset="{{ $dp->key }}" for="preset-{{ $dp->key }}"
                        style="cursor:pointer;border:2px solid var(--border);border-radius:.75rem;padding:.9rem 1rem;background:#fff;transition:border-color .15s,background .15s;display:block">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.35rem">
                            <input type="radio" id="preset-{{ $dp->key }}" name="diet_preset" value="{{ $dp->key }}"
                                {{ $patient->diet_preset_id === $dp->id ? 'checked' : '' }}
                                style="accent-color:var(--primary)">
                            <span style="font-weight:700;font-size:.88rem;color:var(--text-primary)">{{ $dp->name }}</span>
                        </div>
                        <p style="font-size:.75rem;color:var(--text-muted);margin:0 0 .4rem 1.4rem;line-height:1.4">{{ $dp->description }}</p>
                        <span style="display:inline-block;margin-left:1.4rem;font-size:.72rem;font-weight:600;padding:.15rem .55rem;border-radius:999px;background:#f0fdf4;color:var(--primary);border:1px solid #bbf7d0">
                            ~{{ $dp->kcal_target }} kcal
                        </span>
                    </label>
                    @endforeach
                </div>

                {{-- Live preview panel (populated via JS fetch) --}}
                <div id="preset-preview" style="display:none;margin-bottom:1.25rem">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                        <span id="preset-preview-title" style="font-size:.82rem;font-weight:700;color:var(--text-primary)"></span>
                        <span style="font-size:.75rem;color:var(--text-muted)">Preview — not saved yet</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table style="width:100%;border-collapse:collapse;font-size:.8rem">
                            <thead>
                                <tr style="background:#f0fdf4;color:var(--primary)">
                                    <th style="text-align:left;padding:.4rem .7rem;font-weight:700">Item</th>
                                    <th style="text-align:center;padding:.4rem .7rem">Nu</th>
                                    <th style="text-align:center;padding:.4rem .7rem">Breakf</th>
                                    <th style="text-align:center;padding:.4rem .7rem">Snack</th>
                                    <th style="text-align:center;padding:.4rem .7rem">Lunch</th>
                                    <th style="text-align:center;padding:.4rem .7rem">Snack</th>
                                    <th style="text-align:center;padding:.4rem .7rem">Supper</th>
                                    <th style="text-align:center;padding:.4rem .7rem">Snack</th>
                                    <th style="text-align:right;padding:.4rem .7rem;color:#c2410c">CHO</th>
                                    <th style="text-align:right;padding:.4rem .7rem;color:#4338ca">Prot</th>
                                    <th style="text-align:right;padding:.4rem .7rem;color:#0f766e">Fat</th>
                                    <th style="text-align:right;padding:.4rem .7rem">kJ</th>
                                </tr>
                            </thead>
                            <tbody id="preset-preview-body"></tbody>
                        </table>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:1rem">
                    <button id="apply-preset-btn" onclick="applyPreset()"
                        style="padding:.5rem 1.4rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border:none;border-radius:6px;cursor:pointer;opacity:.5;pointer-events:none">
                        Apply Preset
                    </button>
                    <span id="preset-status" style="font-size:.82rem"></span>
                </div>
            </div>
        </div>

        <style>
        .preset-card.selected { border-color:var(--primary)!important; background:#f0fdf4!important; }
        #preset-preview-body tr:nth-child(even) { background:#f8fafc; }
        #preset-preview-body td { padding:.35rem .7rem; border-bottom:1px solid #e5e7eb; }
        </style>

        <script>
        (function () {
            var _selectedPreset = null;
            var _previewCache   = {};

            var previewGetUrl = '{{ url('diet-presets') }}/';
            var applyUrl      = '{{ route('patients.apply-preset', $patient->id) }}';
            var csrfToken     = document.querySelector('meta[name="csrf-token"]').content;

            /* Initialise from any already-checked radio (preset previously applied) */
            var _checkedRadio = document.querySelector('input[name="diet_preset"]:checked');
            if (_checkedRadio) {
                _selectedPreset = _checkedRadio.value;
                var _btn = document.getElementById('apply-preset-btn');
                _btn.style.opacity = '1';
                _btn.style.pointerEvents = 'auto';
            }

            function fmt(v) { return (v !== null && v !== undefined && v !== '') ? v : '—'; }

            function renderPreview(data) {
                var panel   = document.getElementById('preset-preview');
                var title   = document.getElementById('preset-preview-title');
                var tbody   = document.getElementById('preset-preview-body');
                title.textContent = data.name + '  (~' + data.kcal_target + ' kcal)';
                tbody.innerHTML   = '';
                data.items.forEach(function (i) {
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td style="font-weight:600">' + i.name + '</td>' +
                        '<td style="text-align:center;font-weight:700">' + i.nu + '</td>' +
                        '<td style="text-align:center">' + fmt(i.slot_breakfast) + '</td>' +
                        '<td style="text-align:center">' + fmt(i.slot_snack1)    + '</td>' +
                        '<td style="text-align:center">' + fmt(i.slot_lunch)     + '</td>' +
                        '<td style="text-align:center">' + fmt(i.slot_snack2)    + '</td>' +
                        '<td style="text-align:center">' + fmt(i.slot_supper)    + '</td>' +
                        '<td style="text-align:center">' + fmt(i.slot_snack3)    + '</td>' +
                        '<td style="text-align:right;color:#c2410c">' + (i.cho_g        !== null ? i.nu * i.cho_g        : '—') + '</td>' +
                        '<td style="text-align:right;color:#4338ca">' + (i.protein_min_g !== null ? i.nu * i.protein_min_g : '—') + '</td>' +
                        '<td style="text-align:right;color:#0f766e">' + (i.fat_min_g    !== null ? i.nu * i.fat_min_g    : '—') + '</td>' +
                        '<td style="text-align:right">'               + (i.kj           !== null ? i.nu * i.kj           : '—') + '</td>';
                    tbody.appendChild(tr);
                });
                panel.style.display = 'block';
            }

            /* Listen to radio changes — fetch preset from DB and show preview */
            document.querySelectorAll('input[name="diet_preset"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    _selectedPreset = this.value;
                    document.querySelectorAll('.preset-card').forEach(function (c) {
                        c.classList.toggle('selected', c.dataset.preset === _selectedPreset);
                    });
                    document.getElementById('apply-preset-btn').style.opacity      = '1';
                    document.getElementById('apply-preset-btn').style.pointerEvents = 'auto';
                    document.getElementById('preset-status').textContent = '';

                    /* Use cache to avoid repeated fetches */
                    if (_previewCache[_selectedPreset]) {
                        renderPreview(_previewCache[_selectedPreset]);
                        return;
                    }

                    fetch(previewGetUrl + _selectedPreset, {
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json' },
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        _previewCache[_selectedPreset] = data;
                        renderPreview(data);
                    })
                    .catch(function () {
                        document.getElementById('preset-preview').style.display = 'none';
                    });
                });
            });

            /* Apply: POST to patient, save to DB, then reload so all sections reflect new values */
            window.applyPreset = function () {
                if (!_selectedPreset) return;
                var btn    = document.getElementById('apply-preset-btn');
                var status = document.getElementById('preset-status');
                btn.disabled = true;
                btn.textContent = 'Applying…';
                status.textContent = '';

                fetch(applyUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ preset: _selectedPreset }),
                })
                .then(function (r) {
                    if (!r.ok) {
                        return r.json().then(function (e) { throw new Error(e.error || ('HTTP ' + r.status)); });
                    }
                    return r.json();
                })
                .then(function (data) {
                    if (data.error) throw new Error(data.error);

                    /* Show success status then reload the page so every section
                       (exchange template, meal plan) reflects the saved preset. */
                    status.style.color = '#15803d';
                    status.textContent = '✓ ' + data.preset_name + ' applied — reloading…';

                    setTimeout(function () {
                        window.location.reload();
                    }, 900);
                })
                .catch(function (err) {
                    status.style.color = '#b91c1c';
                    status.textContent = '⚠ ' + (err.message || 'Something went wrong');
                    btn.textContent = 'Apply Preset';
                    btn.disabled = false;
                });
            };
        })();
        </script>
        </x-plan-gate>

                {{-- Anthropometrics card --}}
                <div class="dash-section">
                    <div class="dash-section-header" style="cursor:pointer;user-select:none" onclick="toggleSection('anthro-body','anthro-chevron')">
                        <span class="dash-section-title">Anthropometrics</span>
                        <svg id="anthro-chevron" xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--text-muted);transition:transform .25s;transform:rotate(-90deg)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <div id="anthro-body" style="display:none">
                    <dl class="info-grid">
                        <div class="info-item"><dt>Weight</dt><dd>{{ $patient->weight }} kg</dd></div>
                        <div class="info-item"><dt>Height</dt><dd>{{ $patient->height }} cm</dd></div>
                        <div class="info-item"><dt>ABW <span style="font-size:.65rem;color:var(--text-muted);font-weight:500">(0.25 factor)</span></dt><dd>{{ $patient->abw ? number_format($patient->abw, 2).' kg' : '—' }}</dd></div>
                        {{-- Target / Ideal Weights at three BMI benchmarks --}}
                        <div class="info-item" style="grid-column:1/-1">
                            <dt style="margin-bottom:.45rem">Target Weight (IBW) — select active target</dt>
                            <dd style="padding:0">
                                <table id="ibw-table" style="width:100%;border-collapse:collapse;font-size:.82rem">
                                    <thead>
                                        <tr style="background:#f3f4f6">
                                            <th style="padding:.3rem .5rem;width:2rem;border-bottom:1px solid #e5e7eb"></th>
                                            <th style="padding:.3rem .6rem;text-align:left;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">BMI Target</th>
                                            <th style="padding:.3rem .6rem;text-align:center;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Meaning</th>
                                            <th style="padding:.3rem .6rem;text-align:right;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Weight</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $ibwRows = [
                                                22 => ['label'=>'BMI 22','meaning'=>'Medical ideal',        'val'=>$patient->ibw22,  'color'=>'var(--text-primary)'],
                                                25 => ['label'=>'BMI 25','meaning'=>'Healthy upper limit',  'val'=>$patient->ibw25,  'color'=>'var(--text-primary)'],
                                                30 => ['label'=>'BMI 30','meaning'=>'Obesity threshold',    'val'=>$patient->ibw30,  'color'=>'#c2410c'],
                                            ];
                                            $activeTarget = (int) ($patient->ibw_bmi_target ?? 22);
                                        @endphp
                                        @foreach($ibwRows as $bmiVal => $row)
                                            @php $isActive = ($bmiVal === $activeTarget); @endphp
                                            <tr data-bmi="{{ $bmiVal }}"
                                                style="{{ $loop->even ? 'background:#f9fafb' : 'background:#fff' }};{{ $isActive ? 'outline:2px solid var(--primary);outline-offset:-2px;' : '' }}cursor:pointer"
                                                onclick="selectIbwTarget({{ $bmiVal }})">
                                                <td style="padding:.4rem .5rem;text-align:center;border-bottom:1px solid #f3f4f6">
                                                    <span id="ibw-radio-{{ $bmiVal }}"
                                                          style="display:inline-block;width:.9rem;height:.9rem;border-radius:50%;border:2px solid {{ $isActive ? 'var(--primary)' : '#9ca3af' }};background:{{ $isActive ? 'var(--primary)' : '#fff' }};vertical-align:middle"></span>
                                                </td>
                                                <td style="padding:.35rem .6rem;font-weight:700;color:{{ $row['color'] }};border-bottom:1px solid #f3f4f6">{{ $row['label'] }}</td>
                                                <td style="padding:.35rem .6rem;color:var(--text-muted);font-size:.75rem;text-align:center;border-bottom:1px solid #f3f4f6">{{ $row['meaning'] }}</td>
                                                <td style="padding:.35rem .6rem;text-align:right;font-weight:700;color:{{ $row['color'] }};border-bottom:1px solid #f3f4f6"
                                                    id="ibw-weight-{{ $bmiVal }}">
                                                    {{ $row['val'] ? number_format($row['val'], 2).' kg' : '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p id="ibw-save-msg" style="font-size:.72rem;color:#15803d;margin-top:.35rem;display:none">✓ Target saved</p>
                            </dd>
                        </div>
                        <div class="info-item"><dt>Activity Factor</dt><dd>{{ $patient->activity_factor }}</dd></div>
                        <div class="info-item">
                            <dt>RMR (kcal)
                                @if($isObese)
                                    <span style="font-size:.65rem;font-weight:700;padding:.1rem .45rem;background:#fff7ed;color:#c2410c;border-radius:999px;margin-left:.3rem">Adj.</span>
                                @endif
                            </dt>
                            <dd>{{ $patient->bmr ? number_format(round($patient->bmr), 0).' kcal/day' : '—' }}</dd>
                        </div>
                        <div class="info-item">
                            <dt>RMR (kJ)
                                @if($isObese)
                                    <span style="font-size:.65rem;font-weight:700;padding:.1rem .45rem;background:#fff7ed;color:#c2410c;border-radius:999px;margin-left:.3rem">Adj.</span>
                                @endif
                            </dt>
                            <dd>{{ $patient->bmr ? number_format(round($patient->bmr * 4.184), 0).' kJ/day' : '—' }}</dd>
                        </div>
                        <div class="info-item"><dt>TEE</dt><dd>{{ $patient->tee ? number_format(round($patient->tee), 0).' kJ/day' : '—' }}</dd></div>
                        <div class="info-item"><dt>TEE (kcal)</dt><dd>{{ $teeKcal ? number_format($teeKcal).' kcal' : '—' }}</dd></div>
                    </dl>

                    @if($isObese)
                    {{-- ── Obesity energy adjustment panel ── --}}
                    <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);background:#fff7ed">
                        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:#f97316;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            </svg>
                            <span style="font-size:.78rem;font-weight:700;color:#c2410c">Obesity adjustment active (BMI {{ number_format($patient->bmi, 1) }} &gt; 30)</span>
                        </div>
                        <p style="font-size:.75rem;color:#92400e;line-height:1.6;margin-bottom:1rem">
                            Using <strong>actual body weight</strong> in Mifflin-St Jeor overestimates
                            energy needs in obesity. The primary RMR above uses
                            <strong>{{ number_format($weightForBmr, 1) }} kg</strong>
                            (IBW&nbsp;+&nbsp;0.25&nbsp;×&nbsp;excess).
                            Two alternative estimates are shown below for comparison.
                        </p>

                        <table style="width:100%;font-size:.78rem;border-collapse:collapse">
                            <thead>
                                <tr style="border-bottom:1px solid #fed7aa">
                                    <th style="text-align:left;font-weight:700;color:#92400e;padding:.35rem .5rem .35rem 0">Method</th>
                                    <th style="text-align:right;font-weight:700;color:#92400e;padding:.35rem 0">Weight used</th>
                                    <th style="text-align:right;font-weight:700;color:#92400e;padding:.35rem 0">RMR (kJ)</th>
                                    <th style="text-align:right;font-weight:700;color:#92400e;padding:.35rem 0">TEE (kJ)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background:rgba(249,115,22,.06)">
                                    <td style="padding:.45rem .5rem .45rem 0;font-weight:700;color:#c2410c">
                                        ABW-adjusted
                                        <span style="display:block;font-size:.65rem;font-weight:500;color:#92400e">IBW + 0.25 × (actual − IBW)</span>
                                    </td>
                                    <td style="text-align:right;font-weight:700;color:#0f172a">{{ number_format($weightForBmr, 1) }} kg</td>
                                    <td style="text-align:right;font-weight:700;color:#0f172a">{{ $bmrAbwAdj ? number_format($bmrAbwAdj * 4.184, 0) : '—' }}</td>
                                    <td style="text-align:right;font-weight:700;color:#0f172a">{{ $teeAbwAdjKj ? number_format($teeAbwAdjKj, 0) : '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:.45rem .5rem .45rem 0;color:#374151">
                                        BMI-adjusted cap
                                        <span style="display:block;font-size:.65rem;color:#6b7280">Weight capped at BMI&nbsp;25&nbsp;({{ number_format(25 * pow($patient->height / 100, 2), 1) }}&nbsp;kg)</span>
                                    </td>
                                    <td style="text-align:right;color:#374151">{{ number_format(min((float)$patient->weight, 25 * pow($patient->height / 100, 2)), 1) }} kg</td>
                                    <td style="text-align:right;color:#374151">{{ $bmrBmiAdj ? number_format($bmrBmiAdj * 4.184, 0) : '—' }}</td>
                                    <td style="text-align:right;color:#374151">{{ $teeBmiAdjKj ? number_format($teeBmiAdjKj, 0) : '—' }}</td>
                                </tr>
                                <tr style="border-top:1px solid #fed7aa">
                                    <td style="padding:.45rem .5rem .45rem 0;color:#6b7280">
                                        Actual weight (no correction)
                                        <span style="display:block;font-size:.65rem;color:#9ca3af">May overestimate in obesity</span>
                                    </td>
                                    <td style="text-align:right;color:#6b7280">{{ $patient->weight }} kg</td>
                                    <td style="text-align:right;color:#6b7280">{{ $bmrActual ? number_format($bmrActual * 4.184, 0) : '—' }}</td>
                                    <td style="text-align:right;color:#6b7280">{{ $teeActualKj ? number_format($teeActualKj, 0) : '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                    </div>{{-- /anthro-body --}}
                </div>{{-- /dash-section anthro --}}

            <x-plan-gate min="package_1">
            {{-- ── Macronutrients ──────────────────────────── --}}
                <div class="dash-section">
                    <div class="dash-section-header" style="cursor:pointer;user-select:none" onclick="toggleSection('macro-body','macro-chevron')">
                        <span class="dash-section-title">Macronutrient Distribution</span>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <span id="total-badge" style="font-size:.75rem;font-weight:700;padding:.25rem .7rem;border-radius:999px;background:#f1f5f9;color:#64748b;transition:all .2s">
                                Total: <span id="macros-total">{{ number_format($patient->macronutrients->sum('selected_percentage'), 0) }}%</span>
                            </span>
                            <svg id="macro-chevron" xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--text-muted);transition:transform .25s;transform:rotate(-90deg)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div id="macro-body" style="display:none">

                    <form method="POST" action="{{ route('patients.macronutrients.update', $patient->id) }}" id="macro-form">
                        @csrf
                        @method('PATCH')

                        {{-- Column headers --}}
                        <div style="display:grid;grid-template-columns:1fr 90px 100px 80px;gap:1rem;padding:.6rem 1.5rem;border-bottom:1px solid var(--border)">
                            <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted)">Macro · Range</div>
                            <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);text-align:center">% Select</div>
                            <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);text-align:right">kJ</div>
                            <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);text-align:right">g</div>
                        </div>

                        @foreach($patient->macronutrients as $macro)
                            @php
                                $selected    = $macro->selected_percentage;
                                $computedKj  = $teeKj * ($selected / 100);
                                $computedG   = $computedKj > 0 ? round($computedKj / 17) : 0;
                                $mc          = $macroColors[$macro->type] ?? ['dot'=>'#94a3b8','bg'=>'#f1f5f9','text'=>'#64748b'];
                            @endphp
                            <div class="macro-row" data-macro-id="{{ $macro->id }}" style="background:{{ $mc['bg'] }};border-left:3px solid {{ $mc['dot'] }}">
                                {{-- Label --}}
                                <div>
                                    <div class="macro-type-badge">
                                        <span class="dot" style="background:{{ $mc['dot'] }}"></span>
                                        <span style="font-size:.83rem">{{ ucfirst($macro->type) }}</span>
                                    </div>
                                    <div style="font-size:.7rem;color:var(--text-muted);margin-top:.2rem;padding-left:1rem">
                                        Range: {{ (int)$macro->range_min }}–{{ (int)$macro->range_max }}%
                                    </div>
                                </div>
                                {{-- Selector --}}
                                <div style="text-align:center">
                                    <select name="macronutrients[{{ $macro->id }}]" class="macro-select">
                                        @for($i = (int) $macro->range_min; $i <= (int) $macro->range_max; $i++)
                                            <option value="{{ $i }}" @selected((int)$selected === $i)>{{ $i }}%</option>
                                        @endfor
                                    </select>
                                </div>
                                {{-- kJ --}}
                                <div class="macro-val macro-kj" style="text-align:right">
                                    {{ $macro->kj ?: number_format($computedKj, 1) }}
                                </div>
                                {{-- grams --}}
                                <div class="macro-val macro-grams" style="text-align:right">
                                    {{ $macro->grams ?: $computedG }}<span style="font-size:.65rem;color:var(--text-muted);margin-left:.2rem">g</span>
                                </div>
                            </div>
                        @endforeach

                        {{-- Total + Save footer --}}
                        <div class="macro-total">
                            <div class="flex items-center gap-3">
                                <span id="macros-error" style="display:none;font-size:.78rem;font-weight:600;color:#b91c1c">
                                    ✕ Total must equal 100%
                                </span>
                            </div>
                            <button id="macros-save" type="submit" class="btn-save">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Save Macros
                            </button>
                        </div>
                    </form>
                    </div>{{-- /macro-body --}}
                </div>{{-- /dash-section macros --}}

                {{-- Nutrient Analysis --}}
                @if($patient->exchangeTemplate && $teeKj > 0)
                <div class="dash-section" id="nutrient-analysis">
                    <div class="dash-section-header" style="cursor:pointer;user-select:none" onclick="toggleSection('na-body','na-chevron')">
                        <span class="dash-section-title">Nutrient Analysis</span>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <span style="font-size:.72rem;color:var(--text-muted);font-weight:500">Updates live as you adjust the exchange template</span>
                            <svg id="na-chevron" xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--text-muted);transition:transform .25s;transform:rotate(-90deg)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div id="na-body" style="display:none">
                    <div class="overflow-x-auto" style="padding:0 1.25rem 1.25rem">
                        <table style="width:100%;border-collapse:collapse;font-size:.84rem">
                            <thead>
                                <tr style="border-bottom:2px solid var(--border)">
                                    <th style="text-align:left;padding:.55rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);background:#f8fafc"></th>
                                    <th style="text-align:right;padding:.55rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);background:#f8fafc">Recommended</th>
                                    <th style="text-align:right;padding:.55rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);background:#f8fafc">Actual</th>
                                    <th style="text-align:right;padding:.55rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);background:#f8fafc">Difference</th>
                                    <th style="text-align:right;padding:.55rem .75rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);background:#f8fafc">% of TEE</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Carbohydrates --}}
                                <tr class="na-row" style="border-bottom:1px solid #f1f5f9">
                                    <td style="padding:.6rem .75rem;font-weight:700;color:#c2410c;background:rgba(249,115,22,.12)">
                                        <span style="display:inline-block;width:.55rem;height:.55rem;border-radius:50%;background:#f97316;margin-right:.4rem;vertical-align:.05rem"></span>
                                        Carbs (g)
                                    </td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:600;background:rgba(249,115,22,.12);color:#c2410c" id="na-cho-rec">{{ $recCho_g ?? '—' }}</td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:700;background:rgba(249,115,22,.12);color:#c2410c" id="na-cho-act">—</td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:600;background:rgba(249,115,22,.12);color:#c2410c" id="na-cho-diff">—</td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:600;background:rgba(249,115,22,.12);color:#c2410c" id="na-cho-pct">—</td>
                                </tr>
                                {{-- Protein --}}
                                <tr class="na-row" style="border-bottom:1px solid #f1f5f9">
                                    <td style="padding:.6rem .75rem;font-weight:700;color:#4338ca;background:rgba(99,102,241,.12)">
                                        <span style="display:inline-block;width:.55rem;height:.55rem;border-radius:50%;background:#6366f1;margin-right:.4rem;vertical-align:.05rem"></span>
                                        Protein (g)
                                    </td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:600;background:rgba(99,102,241,.12);color:#4338ca" id="na-pro-rec">{{ $recPro_g ?? '—' }}</td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:700;background:rgba(99,102,241,.12);color:#4338ca" id="na-pro-act">—</td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:600;background:rgba(99,102,241,.12);color:#4338ca" id="na-pro-diff">—</td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:600;background:rgba(99,102,241,.12);color:#4338ca" id="na-pro-pct">—</td>
                                </tr>
                                {{-- Fat --}}
                                <tr class="na-row" style="border-bottom:1px solid #f1f5f9">
                                    <td style="padding:.6rem .75rem;font-weight:700;color:#0f766e;background:rgba(20,184,166,.12)">
                                        <span style="display:inline-block;width:.55rem;height:.55rem;border-radius:50%;background:#14b8a6;margin-right:.4rem;vertical-align:.05rem"></span>
                                        Fat (g)
                                    </td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:600;background:rgba(20,184,166,.12);color:#0f766e" id="na-fat-rec">{{ $recFat_g ?? '—' }}</td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:700;background:rgba(20,184,166,.12);color:#0f766e" id="na-fat-act">—</td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:600;background:rgba(20,184,166,.12);color:#0f766e" id="na-fat-diff">—</td>
                                    <td style="text-align:right;padding:.6rem .75rem;font-weight:600;background:rgba(20,184,166,.12);color:#0f766e" id="na-fat-pct">—</td>
                                </tr>
                                {{-- Energy --}}
                                <tr style="background:#f8fafc;border-top:2px solid var(--border)">
                                    <td style="padding:.65rem .75rem;font-weight:800;color:var(--text-primary)">
                                        Energy (kJ)
                                    </td>
                                    <td style="text-align:right;padding:.65rem .75rem;font-weight:700" id="na-kj-rec">{{ $teeKj ? round($teeKj) : '—' }}</td>
                                    <td style="text-align:right;padding:.65rem .75rem;font-weight:800;color:var(--primary)" id="na-kj-act">—</td>
                                    <td style="text-align:right;padding:.65rem .75rem;font-weight:700" id="na-kj-diff">—</td>
                                    <td style="text-align:right;padding:.65rem .75rem;font-weight:700" id="na-kj-pct">—</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>{{-- /na-body --}}
                </div>{{-- /nutrient-analysis dash-section --}}
                @endif

            </x-plan-gate>

        <x-plan-gate min="package_1">
        {{-- ═══════════════════════════════════════════
             EXCHANGE TEMPLATE
        ═══════════════════════════════════════════ --}}
        @if($patient->exchangeTemplate)
        <div class="dash-section exchange-template-section mt-6">
            <div class="dash-section-header" style="cursor:pointer;user-select:none" onclick="toggleSection('et-body','et-chevron')">
                <span class="dash-section-title">Exchange Template</span>
                <div style="display:flex;align-items:center;gap:.5rem">
                    <span style="font-size:.72rem;font-weight:600;padding:.2rem .65rem;border-radius:999px;background:#fff7ed;color:#c2410c">
                        {{ $patient->exchangeTemplate->name }}
                    </span>
                    <svg id="et-chevron" xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--text-muted);transition:transform .25s;transform:rotate(0deg)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
            <div id="et-body" style="display:block">
            <div class="overflow-x-auto" style="max-height:420px;overflow-y:auto">
                <table class="exchange-table" id="exchange-table">
                    <thead>
                        <tr>
                            <th style="min-width:160px">Item</th>
                            <th style="text-align:center;min-width:110px">nu</th>
                            <th style="text-align:right;color:#c2410c;background:rgba(249,115,22,.12)">CHO (g)</th>
                            <th style="text-align:right;color:#4338ca;background:rgba(99,102,241,.12)">Protein (g)</th>
                            <th style="text-align:right;color:#0f766e;background:rgba(20,184,166,.12)">Fat (g)</th>
                            <th style="text-align:right">kJ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->exchangeTemplate->items as $item)
                        @php $nu = $item->nu; @endphp
                        <tr data-item-id="{{ $item->id }}"
                            data-nu="{{ $nu }}"
                            data-cho="{{ $item->cho_g }}"
                            data-pro-min="{{ $item->protein_min_g }}"
                            data-fat-min="{{ $item->fat_min_g }}"
                            data-kj="{{ $item->kj }}">
                            <td class="font-semibold">{{ $item->name }}</td>
                            <td style="text-align:center">
                                <div style="display:inline-flex;align-items:center;gap:.4rem">
                                    <form method="POST"
                                          action="{{ route('patients.exchange-items.nu', [$patient->id, $item->id]) }}"
                                          class="nu-form">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="delta" value="-1">
                                        <button type="submit" class="nu-btn" {{ $nu <= 0 ? 'disabled' : '' }}>−</button>
                                    </form>
                                    <input type="number" min="0" step="1" class="nu-input" value="{{ $nu }}" style="width:3rem;text-align:center;font-weight:700;" />
                                    <form method="POST"
                                          action="{{ route('patients.exchange-items.nu', [$patient->id, $item->id]) }}"
                                          class="nu-form">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="delta" value="1">
                                        <button type="submit" class="nu-btn">+</button>
                                    </form>
                                </div>
                            </td>
                            <td class="et-cho"  style="text-align:right;background:rgba(249,115,22,.12);color:#c2410c;font-weight:600">{{ $item->cho_g          !== null ? $nu * $item->cho_g          : '—' }}</td>
                            <td class="et-pmin" style="text-align:right;background:rgba(99,102,241,.12);color:#4338ca;font-weight:600">{{ $item->protein_min_g  !== null ? $nu * $item->protein_min_g  : '—' }}</td>
                            <td class="et-fmin" style="text-align:right;background:rgba(20,184,166,.12);color:#0f766e;font-weight:600">{{ $item->fat_min_g      !== null ? $nu * $item->fat_min_g      : '—' }}</td>
                            <td class="et-kj"   style="text-align:right;font-weight:600">{{ $item->kj !== null ? $nu * $item->kj : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <!-- grams totals row -->
                        <tr style="background:var(--bg-page);border-top:2px solid var(--border)">
                            <td colspan="2" style="font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Total&nbsp;(g)</td>
                            <td id="tot-cho"  style="text-align:right;font-weight:800;color:#c2410c;background:rgba(249,115,22,.12)">—</td>
                            <td id="tot-pmin" style="text-align:right;font-weight:800;color:#4338ca;background:rgba(99,102,241,.12)">—</td>
                            <td id="tot-fmin" style="text-align:right;font-weight:800;color:#0f766e;background:rgba(20,184,166,.12)">—</td>
                            <td id="tot-kj"   style="text-align:right;font-weight:700;color:var(--primary)">—</td>
                        </tr>
                        <!-- kJ conversion row -->
                        <tr style="background:var(--bg-page);border-top:1px solid var(--border)">
                            <td colspan="2" style="font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Total&nbsp;(kJ)</td>
                            <td id="tot-kj-cho"  style="text-align:right;font-weight:800;color:#c2410c;background:rgba(249,115,22,.12)">—</td>
                            <td id="tot-kj-pmin" style="text-align:right;font-weight:800;color:#4338ca;background:rgba(99,102,241,.12)">—</td>
                            <td id="tot-kj-fmin" style="text-align:right;font-weight:800;color:#0f766e;background:rgba(20,184,166,.12)">—</td>
                            <td id="tot-kj-total"   style="text-align:right;font-weight:700;color:var(--primary)">—</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>{{-- /et-body --}}
        </div>{{-- /exchange-template-section --}}
        @else
        <div class="dash-section mt-6 p-5">
            <div style="text-align:center;padding:1.5rem 1rem;color:var(--text-muted)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:2.5rem;height:2.5rem;margin:0 auto .75rem;opacity:.35" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                </svg>
                <p style="font-size:.82rem;font-weight:600">No exchange template linked</p>
                <form method="POST" action="{{ route('patients.exchange-template.create', $patient->id) }}" style="margin-top:1.5rem;">
                    @csrf
                    <button type="submit" class="btn-add" style="font-size:1rem;padding:.6rem 1.5rem;border-radius:.7rem;margin-top:.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1.2em;height:1.2em;vertical-align:-.2em;margin-right:.5em" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Create Exchange Template
                    </button>
                </form>
            </div>
        </div>
        @endif

        </x-plan-gate>
        <x-plan-gate min="package_1">
        @if($patient->exchangeTemplate)
        <div class="dash-section mt-6" id="meal-plan-details">            <div class="dash-section-header" style="cursor:pointer;user-select:none" onclick="toggleSection('mp-body','mp-chevron')">
                <span class="dash-section-title">Meal Plan Distribution</span>
                <div style="display:flex;align-items:center;gap:.5rem">
                    <span style="font-size:.75rem;color:var(--text-muted)">Enter serving exchanges per meal — each row must sum to the total (No)</span>
                    <svg id="mp-chevron" xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--text-muted);transition:transform .25s;transform:rotate(0deg)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
            <div id="mp-body" style="display:block">

                @if(session('success') && str_contains(session('success'), 'Meal plan'))
                    <div style="margin:0 1.25rem .75rem;padding:.6rem 1rem;background:#dcfce7;color:#15803d;border-radius:6px;font-size:.82rem;font-weight:600">
                        ✓ {{ session('success') }}
                    </div>
                @endif
                @error('meal_plan')
                    <div style="margin:0 1.25rem .75rem;padding:.6rem 1rem;background:#fee2e2;color:#b91c1c;border-radius:6px;font-size:.82rem">
                        ⚠ {{ $message }}
                    </div>
                @enderror

                <form method="POST" action="{{ route('patients.meal-plan.save', $patient) }}" id="meal-plan-form">
                    @csrf @method('PATCH')
                    <div class="overflow-x-auto" style="padding:0 1.25rem 1.25rem;max-height:420px;overflow-y:auto;position:relative">
                        <table class="exchange-table" id="meal-plan-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>No</th>
                                    <th>Breakf</th>
                                    <th>Snack</th>
                                    <th>Lunch</th>
                                    <th>Snack</th>
                                    <th>Supper</th>
                                    <th>Snack</th>
                                    <th>Sum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patient->exchangeTemplate->items as $item)
                                <tr data-nu="{{ $item->nu }}" data-item-id="{{ $item->id }}">
                                    <td style="font-weight:600">{{ $item->name }}</td>
                                    <td style="font-weight:700">{{ $item->nu }}</td>
                                    @foreach(['breakfast','snack1','lunch','snack2','supper','snack3'] as $slot)
                                    <td>
                                        <input
                                            type="number"
                                            name="items[{{ $item->id }}][{{ $slot }}]"
                                            value="{{ $item->{'slot_'.$slot} > 0 ? $item->{'slot_'.$slot} + 0 : '' }}"
                                            min="0"
                                            step="0.5"
                                            placeholder="—"
                                            class="meal-slot-input"
                                            data-row="{{ $item->id }}"
                                        >
                                    </td>
                                    @endforeach
                                    <td>
                                        <span class="row-sum" data-row="{{ $item->id }}"
                                            style="font-weight:700;font-size:.85rem;display:block">0</span>
                                        <span class="row-status" data-row="{{ $item->id }}"
                                            style="display:block;font-size:.65rem;font-weight:700"></span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top:2px solid var(--border);background:#f8fafc">
                                    <td style="font-weight:700;font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);padding:.6rem 1rem">Total</td>
                                    <td id="mp-tot-no"  style="font-weight:700;padding:.6rem 1rem">—</td>
                                    <td id="mp-tot-bf"  style="font-weight:700;padding:.6rem 1rem">—</td>
                                    <td id="mp-tot-sn1" style="font-weight:700;padding:.6rem 1rem">—</td>
                                    <td id="mp-tot-ln"  style="font-weight:700;padding:.6rem 1rem">—</td>
                                    <td id="mp-tot-sn2" style="font-weight:700;padding:.6rem 1rem">—</td>
                                    <td id="mp-tot-sup" style="font-weight:700;padding:.6rem 1rem">—</td>
                                    <td id="mp-tot-sn3" style="font-weight:700;padding:.6rem 1rem">—</td>
                                    <td id="mp-tot-sum" style="font-weight:700;padding:.6rem 1rem">—</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div style="padding:.75rem 1.25rem 1.25rem;display:flex;align-items:center;gap:1rem">
                        <button type="submit" id="meal-plan-save"
                            style="padding:.5rem 1.5rem;background:var(--primary);color:#fff;font-weight:700;font-size:.85rem;border:none;border-radius:6px;cursor:pointer">
                            Save Meal Plan
                        </button>
                        <span id="meal-plan-status" style="font-size:.8rem;color:var(--text-muted)"></span>
                    </div>
                </form>

{{-- ── Meal plan save: AJAX + 3-second redirect toast ── --}}
<div id="mp-save-toast" style="display:none;position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);
    background:#0d1f0c;color:#fff;padding:.9rem 1.6rem;border-radius:12px;
    box-shadow:0 6px 32px rgba(0,0,0,.22);z-index:9999;
    font-size:.88rem;font-weight:600;min-width:280px;text-align:center;line-height:1.5">
    ✓ Meal plan saved!<br>
    <span style="font-weight:400;opacity:.85">Redirecting to meal planner in <strong id="mp-countdown">3</strong>s…</span>
</div>
<script>
(function () {
    var form   = document.getElementById('meal-plan-form');
    var toast  = document.getElementById('mp-save-toast');
    var cdEl   = document.getElementById('mp-countdown');
    var saveBtn = document.getElementById('meal-plan-save');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving…';

        var fd = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function (data) {
            /* Show toast + countdown */
            toast.style.display = 'block';
            var secs = 3;
            cdEl.textContent = secs;
            var iv = setInterval(function () {
                secs--;
                cdEl.textContent = secs;
                if (secs <= 0) {
                    clearInterval(iv);
                    window.location.href = data.redirect_url;
                }
            }, 1000);
        })
        .catch(function (err) {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Meal Plan';
            alert('Save failed: ' + err.message + '. Please try again.');
        });
    });
})();
</script>
            </div>{{-- /mp-body --}}
        </div>{{-- /meal-plan-details --}}
        @endif
        </x-plan-gate>

    </div>

    {{-- ═══════════════════════════════════════════
         VISIT HISTORY / MONITORING
    ═══════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-10">
        <div class="dash-card" style="padding:1.5rem">

            @if(session('visit_success'))
                <div style="margin-bottom:1rem;padding:.6rem 1rem;background:#dcfce7;border:1px solid #86efac;border-radius:6px;color:#15803d;font-size:.85rem;font-weight:600">
                    {{ session('visit_success') }}
                </div>
            @endif

            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.25rem">
                <span class="dash-section-title">Visit History &amp; Monitoring</span>
                <button onclick="document.getElementById('add-visit-form').classList.toggle('hidden')"
                        style="display:inline-flex;align-items:center;gap:.4rem;padding:.45rem 1rem;background:var(--primary);color:#fff;border:none;border-radius:7px;font-size:.8rem;font-weight:700;cursor:pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Log Visit
                </button>
            </div>

            {{-- Add visit form (hidden by default, shown if validation errors) --}}
            <div id="add-visit-form" class="{{ $errors->has('visited_at') || $errors->has('weight') || $errors->has('height') || $errors->has('notes') ? '' : 'hidden' }}" style="background:#f8faf8;border:1px solid #d1d5db;border-radius:8px;padding:1.1rem;margin-bottom:1.5rem">
                <p style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);margin-bottom:.9rem">New Visit Entry</p>
                <form method="POST" action="{{ route('patients.visits.store', $patient) }}">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.9rem;margin-bottom:.9rem">
                        <div>
                            <label style="display:block;font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.3rem">Date *</label>
                            <input type="date" name="visited_at" required
                                   value="{{ old('visited_at', date('Y-m-d')) }}"
                                   style="width:100%;padding:.45rem .7rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;box-sizing:border-box"
                                   onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d1d5db'">
                            @error('visited_at') <p style="color:#dc2626;font-size:.72rem;margin-top:.2rem">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="display:block;font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.3rem">Weight (kg) *</label>
                            <input type="number" name="weight" required min="1" max="500" step="0.1"
                                   value="{{ old('weight') }}"
                                   placeholder="{{ $patient->weight }}"
                                   style="width:100%;padding:.45rem .7rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;box-sizing:border-box"
                                   onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d1d5db'">
                            @error('weight') <p style="color:#dc2626;font-size:.72rem;margin-top:.2rem">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="display:block;font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.3rem">Height (cm)</label>
                            <input type="number" name="height" min="50" max="250" step="0.1"
                                   value="{{ old('height') }}"
                                   placeholder="{{ $patient->height }} (optional)"
                                   style="width:100%;padding:.45rem .7rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;box-sizing:border-box"
                                   onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d1d5db'">
                            @error('height') <p style="color:#dc2626;font-size:.72rem;margin-top:.2rem">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div style="margin-bottom:.9rem">
                        <label style="display:block;font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.3rem">Clinical Notes</label>
                        <textarea name="notes" rows="2" maxlength="1000"
                                  placeholder="Observations, dietary compliance, clinical notes…"
                                  style="width:100%;padding:.45rem .7rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:6px;outline:none;resize:vertical;box-sizing:border-box"
                                  onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d1d5db'">{{ old('notes') }}</textarea>
                        @error('notes') <p style="color:#dc2626;font-size:.72rem;margin-top:.2rem">{{ $message }}</p> @enderror
                    </div>
                    <div style="display:flex;gap:.6rem">
                        <button type="submit"
                                style="padding:.45rem 1.2rem;background:var(--primary);color:#fff;border:none;border-radius:6px;font-size:.82rem;font-weight:700;cursor:pointer">
                            Save Visit
                        </button>
                        <button type="button" onclick="document.getElementById('add-visit-form').classList.add('hidden')"
                                style="padding:.45rem 1rem;background:#f3f4f6;color:var(--text-muted);border:1px solid #d1d5db;border-radius:6px;font-size:.82rem;font-weight:600;cursor:pointer">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            {{-- Visit history table --}}
            @if($patient->visits->isEmpty())
                <p style="text-align:center;color:var(--text-muted);font-size:.85rem;padding:2rem 0">
                    No visits recorded yet. Click <strong>Log Visit</strong> to start tracking progress.
                </p>
            @else
                @php
                    $visits = $patient->visits;   // already sorted desc by visited_at
                @endphp
                <div style="max-height:420px;overflow-y:auto;border-radius:8px;border:1px solid #e5e7eb">
                    <table style="width:100%;border-collapse:separate;border-spacing:0;font-size:.82rem">
                        <thead>
                            <tr style="background:#f9fafb;position:sticky;top:0;z-index:1">
                                <th style="padding:.55rem .9rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Date</th>
                                <th style="padding:.55rem .9rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Weight&nbsp;(kg)</th>
                                <th style="padding:.55rem .9rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">BMI</th>
                                <th style="padding:.55rem .9rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Change</th>
                                <th style="padding:.55rem .9rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);border-bottom:1px solid #e5e7eb">Notes</th>
                                <th style="padding:.55rem .9rem;border-bottom:1px solid #e5e7eb"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visits as $vi => $visit)
                                @php
                                    $prevVisit  = $visits->get($vi + 1);   // next in desc order = previous chronologically
                                    $weightDiff = $prevVisit ? round($visit->weight - $prevVisit->weight, 1) : null;
                                    $bmi        = $visit->bmi;   // uses PatientVisit::getBmiAttribute()
                                @endphp
                                <tr style="{{ $vi % 2 === 0 ? 'background:#fff' : 'background:#f9fafb' }}">
                                    <td style="padding:.6rem .9rem;font-weight:600;color:var(--text-primary);border-bottom:1px solid #f3f4f6">
                                        {{ $visit->visited_at->format('d M Y') }}
                                    </td>
                                    <td style="padding:.6rem .9rem;text-align:right;font-weight:700;color:var(--text-primary);border-bottom:1px solid #f3f4f6">
                                        {{ number_format($visit->weight, 1) }}
                                    </td>
                                    <td style="padding:.6rem .9rem;text-align:right;color:var(--text-muted);border-bottom:1px solid #f3f4f6">
                                        {{ $bmi ?? '—' }}
                                    </td>
                                    <td style="padding:.6rem .9rem;text-align:right;border-bottom:1px solid #f3f4f6">
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
                                    <td style="padding:.6rem .9rem;color:var(--text-muted);max-width:260px;border-bottom:1px solid #f3f4f6">
                                        <span title="{{ $visit->notes }}">
                                            {{ $visit->notes ? \Illuminate\Support\Str::limit($visit->notes, 60) : '—' }}
                                        </span>
                                    </td>
                                    <td style="padding:.6rem .9rem;text-align:right;border-bottom:1px solid #f3f4f6">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p style="font-size:.72rem;color:var(--text-muted);margin-top:.5rem">
                    {{ $patient->visits->count() }} visit{{ $patient->visits->count() === 1 ? '' : 's' }} recorded
                    &nbsp;·&nbsp; Weight change overall:
                    @php
                        $first = $visits->last();
                        $last  = $visits->first();
                        $total = ($first && $last) ? round($last->weight - $first->weight, 1) : null;
                    @endphp
                    @if($total !== null)
                        <strong style="color:{{ $total <= 0 ? '#15803d' : '#b91c1c' }}">
                            {{ $total > 0 ? '+' : '' }}{{ $total }} kg
                        </strong>
                        since {{ $first->visited_at->format('d M Y') }}
                    @endif
                </p>
            @endif

        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         IBW BMI TARGET SELECTOR
    ═══════════════════════════════════════════ --}}
    <script>
    function toggleSection(bodyId, chevronId) {
        const body    = document.getElementById(bodyId);
        const chevron = document.getElementById(chevronId);
        const hidden  = body.style.display === 'none';
        body.style.display    = hidden ? '' : 'none';
        chevron.style.transform = hidden ? 'rotate(0deg)' : 'rotate(-90deg)';
    }
    </script>

    <script>
    (function () {
        const ibwValues = {
            22: {{ $patient->ibw22 ?? 'null' }},
            25: {{ $patient->ibw25 ?? 'null' }},
            30: {{ $patient->ibw30 ?? 'null' }},
        };

        window.selectIbwTarget = function(bmi) {
            const token   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url     = '{{ route("patients.ibw-target.update", $patient->id) }}';
            const saveMsg = document.getElementById('ibw-save-msg');

            // Optimistic UI: update radio buttons and row highlight
            [22, 25, 30].forEach(function(b) {
                const radio = document.getElementById('ibw-radio-' + b);
                const row   = document.querySelector('#ibw-table tr[data-bmi="' + b + '"]');
                if (radio) {
                    radio.style.background   = (b === bmi) ? 'var(--primary)' : '#fff';
                    radio.style.borderColor  = (b === bmi) ? 'var(--primary)' : '#9ca3af';
                }
                if (row) {
                    row.style.outline       = (b === bmi) ? '2px solid var(--primary)' : 'none';
                    row.style.outlineOffset = (b === bmi) ? '-2px' : '0';
                }
            });

            // Update hero card
            const heroVal   = document.getElementById('hero-ibw-val');
            const heroLabel = document.getElementById('hero-ibw-label');
            if (heroVal && ibwValues[bmi] !== null) {
                heroVal.textContent   = ibwValues[bmi].toFixed(2);
                heroLabel.textContent = '(BMI ' + bmi + ')';
            }

            // Persist to server
            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ibw_bmi_target: bmi }),
            }).then(function(res) {
                if (res.ok) {
                    if (saveMsg) {
                        saveMsg.style.display = 'block';
                        setTimeout(function() { saveMsg.style.display = 'none'; }, 2500);
                    }
                }
            }).catch(function(err) { console.error('IBW target save failed', err); });
        };
    })();
    </script>

    {{-- ═══════════════════════════════════════════
         CLIENT-SIDE MACRO CALCULATOR
    ═══════════════════════════════════════════ --}}
    <script>
    (function () {
        const teeKj   = {{ $teeKj }};
        const selects = Array.from(document.querySelectorAll('select[name^="macronutrients"]'));
        const totalEl = document.getElementById('macros-total');
        const badgeEl = document.getElementById('total-badge');
        const errorEl = document.getElementById('macros-error');
        const saveBtn = document.getElementById('macros-save');

        function fmt(v, dec) { return Number(v).toFixed(dec); }

        function updateRow(sel) {
            const pct  = Number(sel.value);
            const row  = sel.closest('[data-macro-id]');
            if (!row) return;
            const kj   = teeKj * (pct / 100);
            const g    = kj > 0 ? Math.round(kj / 17) : 0;
            const kjEl = row.querySelector('.macro-kj');
            const gEl  = row.querySelector('.macro-grams');
            if (kjEl) kjEl.innerHTML = fmt(kj, 1);
            if (gEl)  gEl.innerHTML  = g + '<span style="font-size:.65rem;color:var(--text-muted);margin-left:.2rem">g</span>';
        }

        function updateTotal() {
            const total = selects.reduce((s, el) => s + Number(el.value), 0);
            const ok    = Math.abs(total - 100) <= 0.01;
            totalEl.textContent = Math.round(total) + '%';
            badgeEl.style.background = ok ? '#dcfce7' : '#fee2e2';
            badgeEl.style.color      = ok ? '#15803d' : '#b91c1c';
            errorEl.style.display    = ok ? 'none' : 'inline';
            saveBtn.disabled         = !ok;
        }

        selects.forEach(sel => {
            sel.addEventListener('change', e => { updateRow(e.target); updateTotal(); });
        });

        updateTotal();
    })();
    </script>

    {{-- live nu multiplier handler --}}
    <script>
    (function () {
        const token = '{{ csrf_token() }}';

        function recalcRow(row) {
            const nu = Number(row.dataset.nu) || 0;
            const cho  = Number(row.dataset.cho)  || 0;
            const pmin = Number(row.dataset.proMin)  || 0;
            const fmin = Number(row.dataset.fatMin)  || 0;
            const kj   = Number(row.dataset.kj)   || 0;

            row.querySelector('.et-cho').textContent  = cho  ? (cho*nu)  : '—';
            row.querySelector('.et-pmin').textContent = pmin ? (pmin*nu) : '—';
            row.querySelector('.et-fmin').textContent = fmin ? (fmin*nu) : '—';
            row.querySelector('.et-kj').textContent   = kj   ? (kj*nu)   : '—';
        }

        function recalcTotals() {
            const rows = Array.from(document.querySelectorAll('#exchange-table tbody tr'));
            const sums = {cho:0,pmin:0,fmin:0,kj:0};
            rows.forEach(r=>{
                const nu = Number(r.dataset.nu)||0;
                sums.cho  += (Number(r.dataset.cho)  ||0) * nu;
                sums.pmin += (Number(r.dataset.proMin)||0) * nu;
                sums.fmin += (Number(r.dataset.fatMin)||0) * nu;
                sums.kj   += (Number(r.dataset.kj)   ||0) * nu;
            });
            document.getElementById('tot-cho').textContent  = sums.cho  || '—';
            document.getElementById('tot-pmin').textContent = sums.pmin || '—';
            document.getElementById('tot-fmin').textContent = sums.fmin || '—';
            document.getElementById('tot-kj').textContent   = sums.kj   || '—';

            // now compute kJ conversions for grams totals
            const factor = {cho:17, pmin:17, fmin:19};
            const kjCho  = Math.round((sums.cho  || 0) * factor.cho);
            const kjPmin = Math.round((sums.pmin || 0) * factor.pmin);
            const kjFmin = Math.round((sums.fmin || 0) * factor.fmin);
            const kjTotalMacros = kjCho + kjPmin + kjFmin;
            document.getElementById('tot-kj-cho').textContent  = kjCho || '—';
            document.getElementById('tot-kj-pmin').textContent = kjPmin || '—';
            document.getElementById('tot-kj-fmin').textContent = kjFmin || '—';
            document.getElementById('tot-kj-total').textContent = kjTotalMacros || '—';

            // ── Nutrient Analysis Summary ──────────────────────────────
            const teeKjVal  = {{ $teeKj ?: 0 }};
            const recChoG   = {{ $recCho_g  ?? 'null' }};
            const recProG   = {{ $recPro_g  ?? 'null' }};
            const recFatG   = {{ $recFat_g  ?? 'null' }};

            // actual grams: use midpoint of min/max for protein & fat
            const actChoG  = sums.cho;
            const actProG  = sums.pmin || 0;
            const actFatG  = sums.fmin || 0;
            // actual kJ from exchange template
            const actKj    = sums.kj;
            // % of TEE
            const actChoKj = actChoG * 17;
            const actProKj = actProG * 17;
            const actFatKj = actFatG * 38;

            function naSet(id, val, refVal, isKj) {
                const el = document.getElementById(id);
                if (!el) return;
                if (val === null || val === 0) { el.textContent = '—'; el.style.color = ''; return; }
                el.textContent = isKj ? Math.round(val) : val;
                if (refVal !== null) {
                    el.style.color = Math.abs(val - refVal) < 0.5 ? '#15803d'
                                   : val < refVal ? '#b91c1c' : '#c2410c';
                }
            }

            function naDiff(id, act, rec) {
                const el = document.getElementById(id);
                if (!el || rec === null) { if(el) el.textContent = '—'; return; }
                const d = Math.round(act - rec);
                const sign = d >= 0 ? '+' : '';
                const color  = d < -0.5 ? '#b91c1c' : d > 0.5 ? '#c2410c' : '#15803d';
                const bgColor= d < -0.5 ? '#fee2e2' : d > 0.5 ? '#fff7ed' : '#dcfce7';
                el.innerHTML = '<span style="display:inline-block;padding:.15rem .5rem;border-radius:999px;font-weight:700;font-size:.8rem;background:'+bgColor+';color:'+color+'">'+sign+d+'</span>';
            }

            function naPct(id, actMacroKj, totalEtKj) {
                const el = document.getElementById(id);
                if (!el || !totalEtKj) { if(el) el.textContent = '—'; return; }
                const pct = actMacroKj / totalEtKj * 100;
                el.innerHTML = '<span style="font-weight:700">'+pct.toFixed(1)+'%</span>';
            }

            naSet('na-cho-act',  actChoG,  recChoG,  false);
            naSet('na-pro-act',  actProG,  recProG,  false);
            naSet('na-fat-act',  actFatG,  recFatG,  false);
            naSet('na-kj-act',   actKj,    teeKjVal, true);

            naDiff('na-cho-diff', actChoG, recChoG);
            naDiff('na-pro-diff', actProG, recProG);
            naDiff('na-fat-diff', actFatG, recFatG);
            naDiff('na-kj-diff',  actKj,   teeKjVal);

            naPct('na-cho-pct', actChoKj, actKj);
            naPct('na-pro-pct', actProKj, actKj);
            naPct('na-fat-pct', actFatKj, actKj);
            naPct('na-kj-pct',  actKj,    actKj);
        }

        // handle +/- forms
        document.querySelectorAll('.nu-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const delta = Number(this.querySelector('input[name="delta"]').value) || 0;
                const row = this.closest('tr');
                let nu = Number(row.dataset.nu)||0;
                nu = Math.max(0, nu + delta);
                row.dataset.nu = nu;
                const input = row.querySelector('.nu-input');
                if (input) input.value = nu;
                recalcRow(row);
                recalcTotals();

                // background PATCH with delta
                fetch(this.action, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ delta: delta })
                }).catch(err=>console.error('exchange update failed',err));
            });
        });

        // allow direct editing of nu input
        document.querySelectorAll('.nu-input').forEach(input => {
            input.addEventListener('change', function() {
                const row = this.closest('tr');
                const old = Number(row.dataset.nu) || 0;
                const nu = Math.max(0, Number(this.value) || 0);
                row.dataset.nu = nu;
                recalcRow(row);
                recalcTotals();

                // send absolute nu to server
                const action = row.querySelector('.nu-form')?.action; // use first form action
                if (action) {
                    fetch(action, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ nu: nu })
                    }).catch(err=>console.error('exchange update failed',err));
                }
            });
        });

        // initialize totals on page load
        if(document.getElementById('exchange-table')) {
            document.querySelectorAll('#exchange-table tbody tr').forEach(recalcRow);
            recalcTotals();
        }
    })();

    // ═══════════════════════════════════════════
    //  MEAL PLAN SLOT VALIDATOR
    // ═══════════════════════════════════════════
    (function () {
        const form    = document.getElementById('meal-plan-form');
        const saveBtn = document.getElementById('meal-plan-save');
        const statusEl = document.getElementById('meal-plan-status');
        if (!form) return;

        function rowSum(rowId) {
            let s = 0;
            form.querySelectorAll('.meal-slot-input[data-row="' + rowId + '"]').forEach(function(inp) {
                s += parseFloat(inp.value) || 0;
            });
            return Math.round(s * 1000) / 1000; // avoid float dust
        }

        function updateRow(rowId) {
            const tr   = form.querySelector('tr[data-item-id="' + rowId + '"]');
            if (!tr) return;
            const nu   = parseFloat(tr.dataset.nu) || 0;
            const sum  = rowSum(rowId);
            const ok   = Math.abs(sum - nu) < 0.01;

            const sumEl    = form.querySelector('.row-sum[data-row="' + rowId + '"]');
            const statusEl = form.querySelector('.row-status[data-row="' + rowId + '"]');

            if (sumEl) {
                sumEl.textContent = sum % 1 === 0 ? sum : sum.toFixed(2);
                sumEl.style.color = ok ? '#15803d' : '#b91c1c';
            }
            if (statusEl) {
                statusEl.textContent = ok ? '✓' : (sum < nu ? '↑ need ' + (nu - sum).toFixed(2) : '↓ over by ' + (sum - nu).toFixed(2));
                statusEl.style.color = ok ? '#15803d' : '#b91c1c';
            }

            // highlight inputs in this row — green when balanced, neutral when incomplete
            form.querySelectorAll('.meal-slot-input[data-row="' + rowId + '"]').forEach(function(inp) {
                inp.style.borderColor = ok ? '#86efac' : '';
                inp.style.background  = ok ? '#f0fdf4' : '';
            });
        }

        function allRowsValid() {
            let valid = true;
            form.querySelectorAll('tr[data-item-id]').forEach(function(tr) {
                const nu  = parseFloat(tr.dataset.nu) || 0;
                const sum = rowSum(tr.dataset.itemId);
                if (Math.abs(sum - nu) >= 0.01) valid = false;
            });
            return valid;
        }

        function updateSaveButton() {
            const valid = allRowsValid();
            if (statusEl) {
                statusEl.textContent = valid ? 'All rows balance.' : 'Some rows don\'t sum to their total yet.';
                statusEl.style.color = valid ? '#15803d' : '#92400e';
            }
        }

        // initialise on load
        form.querySelectorAll('tr[data-item-id]').forEach(function(tr) {
            updateRow(tr.dataset.itemId);
        });
        updateSaveButton();

        // live update on any input change
        form.addEventListener('input', function(e) {
            if (!e.target.classList.contains('meal-slot-input')) return;
            updateRow(e.target.dataset.row);
            updateColTotals();
            updateSaveButton();
        });

        // prevent negative values
        form.addEventListener('change', function(e) {
            if (!e.target.classList.contains('meal-slot-input')) return;
            if (parseFloat(e.target.value) < 0) e.target.value = 0;
            updateRow(e.target.dataset.row);
            updateColTotals();
            updateSaveButton();
        });

        function updateColTotals() {
            const slots = ['breakfast','snack1','lunch','snack2','supper','snack3'];
            const ids   = ['mp-tot-bf','mp-tot-sn1','mp-tot-ln','mp-tot-sn2','mp-tot-sup','mp-tot-sn3'];
            let grandTotal = 0;
            slots.forEach(function(slot, i) {
                let col = 0;
                form.querySelectorAll('input[name*="[' + slot + ']"]').forEach(function(inp) {
                    col += parseFloat(inp.value) || 0;
                });
                const el = document.getElementById(ids[i]);
                if (el) el.textContent = col > 0 ? col : '—';
                grandTotal += col;
            });
            // No column — sum of all item nu values
            let noTotal = 0;
            form.querySelectorAll('tr[data-nu]').forEach(function(row) {
                noTotal += parseFloat(row.dataset.nu) || 0;
            });
            const noEl = document.getElementById('mp-tot-no');
            if (noEl) noEl.textContent = noTotal > 0 ? noTotal : '—';
            // Grand sum column
            const sumEl = document.getElementById('mp-tot-sum');
            if (sumEl) sumEl.textContent = grandTotal > 0 ? grandTotal : '—';
        }

        updateColTotals();

        // submit always allowed — partial saves are permitted
    })();
    </script>

</x-app-layout>
