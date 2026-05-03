<x-app-layout>
@php
    $bmi      = $patient->bmi ?? 0;
    $actualWt = (float) $patient->weight;
    $age      = (int)($patient->age ?? 0);

    // Devine IBW (ClinCalc standard)
    // Male: 50.0 + 2.3 × (height_in − 60) | Female: 45.5 + 2.3 × (height_in − 60)
    $heightCm  = (float)($patient->height ?? 0);
    $heightIn  = $heightCm / 2.54;
    $isMale    = strtolower($patient->gender ?? 'male') !== 'female';
    $devineIbw = max(0, round(($isMale ? 50.0 : 45.5) + 2.3 * max(0, $heightIn - 60), 1));

    // Obesity threshold: BMI ≥ 30
    $isObese = $bmi >= 30;

    // NDW = IBW + 0.25 × (Actual − IBW), only for obese
    $ndw = $devineIbw > 0 ? round($devineIbw + 0.25 * ($actualWt - $devineIbw), 1) : null;

    $weightOptions = [
        'actual' => ['label' => 'Actual Body Weight',                'val' => $actualWt],
        'ibw'    => ['label' => 'Ideal Body Weight — Devine (IBW)',  'val' => $devineIbw],
        'abw'    => ['label' => 'Nutritional Dosing Weight (NDW)',   'val' => $ndw],
    ];
    $recommended     = $isObese ? 'abw' : 'actual';
    $initialWeightKg = $weightOptions[$recommended]['val'] ?? $actualWt;

    // Mifflin-St Jeor REE (kcal/day) — most validated predictive equation
    // Male: (10×W) + (6.25×H) − (5×A) + 5 | Female: … − 161
    $msj_ree = ($heightCm > 0 && $age > 0)
        ? (int) round((10 * $initialWeightKg) + (6.25 * $heightCm) - (5 * $age) + ($isMale ? 5 : -161))
        : 0;

    // EN formula database: macronutrients per 1 000 mL (generic standard formulas)
    $formulaDb = [
        '1.0' => ['label' => '1.0 kcal/mL', 'kcalPerMl' => 1.0, 'proteinGL' => 40.0, 'carbsGL' => 127.0, 'fatGL' => 35.4, 'freeWater' => 0.85],
        '1.2' => ['label' => '1.2 kcal/mL', 'kcalPerMl' => 1.2, 'proteinGL' => 55.5, 'carbsGL' => 169.4, 'fatGL' => 39.3, 'freeWater' => 0.80],
        '1.5' => ['label' => '1.5 kcal/mL', 'kcalPerMl' => 1.5, 'proteinGL' => 62.0, 'carbsGL' => 200.0, 'fatGL' => 50.0, 'freeWater' => 0.70],
    ];

    // Stress / activity factors (applied to MSJ REE → TEE)
    $stressFactors = [
        '1.0'  => 'Sedentary / Bed-rest',
        '1.15' => 'Ambulatory — limited activity',
        '1.25' => 'Mild stress — minor surgery / mild infection',
        '1.35' => 'Moderate stress — major surgery / sepsis',
        '1.5'  => 'Severe stress — major trauma / burns < 40% BSA',
        '1.75' => 'Very severe — burns 40–70% BSA',
        '2.0'  => 'Hypermetabolic — burns > 70% BSA',
    ];

    $densities = [
        '1.0' => ['label' => '1.0 kcal/mL', 'desc' => 'Standard',          'water' => '~85%'],
        '1.2' => ['label' => '1.2 kcal/mL', 'desc' => 'Concentrated',      'water' => '~80%'],
        '1.5' => ['label' => '1.5 kcal/mL', 'desc' => 'High-Energy Dense', 'water' => '~70%'],
    ];
@endphp

{{-- HERO --}}
<div class="dash-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('patients.show', $patient) }}" class="btn-back mb-5 inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            {{ $patient->full_name }}
        </a>
        <div class="flex items-center gap-4 flex-wrap">
            <div>
                <h1>Enteral Nutrition Calculator</h1>
                <p style="font-size:.82rem;opacity:.8;font-weight:600;letter-spacing:.03em;text-transform:uppercase;margin-bottom:.2rem">Tube Feeding Analysis Tool</p>
                <p>{{ $patient->full_name }} &middot; {{ ucfirst($patient->gender) }} &middot; {{ $patient->age }}&nbsp;yrs &middot; {{ $patient->weight }}&nbsp;kg &middot; BMI&nbsp;{{ $patient->bmi ? number_format($patient->bmi,1) : '—' }}</p>
            </div>
            <div style="margin-left:auto;display:flex;gap:.5rem;flex-wrap:wrap;align-items:center">
                <span style="padding:.3rem .7rem;background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.28);border-radius:999px;font-size:.7rem;font-weight:700;letter-spacing:.04em;color:rgba(255,255,255,.9)">SASPEN / ESPEN</span>
                <span style="padding:.3rem .7rem;background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.28);border-radius:999px;font-size:.7rem;font-weight:700;letter-spacing:.04em;color:rgba(255,255,255,.9)">Package 3</span>
                @if($calculations->isNotEmpty())
                <a href="{{ route('patients.enteral-nutrition.pdf', $patient) }}"
                   target="_blank"
                   style="display:inline-flex;align-items:center;gap:.35rem;padding:.38rem .95rem;background:#fff;color:#15803d;font-size:.78rem;font-weight:700;border-radius:6px;text-decoration:none;border:1.5px solid rgba(255,255,255,.6);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download PDF
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(session('success'))
        <div class="alert-success mb-5">&#x2713; {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error mb-5">
            <ul style="margin:0;padding-left:1rem">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    <div style="display:flex;flex-direction:column;gap:1.5rem">

            {{-- Patient Parameters — combined inputs card --}}
            <div class="dash-section">
                <div class="dash-section-header">
                    <span class="dash-section-title">Patient Parameters</span>
                </div>
                <form id="en-form" method="POST" action="{{ route('patients.enteral-nutrition.store', $patient) }}"
                      style="padding:1.5rem;display:grid;grid-template-columns:1fr 1fr;gap:1.25rem 2rem">
                @csrf

                {{-- Static patient info --}}
                <div style="grid-column:1/-1">
                    <dl class="info-grid" style="grid-template-columns:repeat(4,1fr);gap:.6rem;margin:0">
                        <div class="info-item" style="margin:0">
                            <dt>Gender</dt>
                            <dd>{{ ucfirst($patient->gender ?? '—') }}</dd>
                        </div>
                        <div class="info-item" style="margin:0">
                            <dt>Height</dt>
                            <dd>{{ $patient->height ? $patient->height.' cm' : '—' }}</dd>
                        </div>
                        <div class="info-item" style="margin:0">
                            <dt>Body Weight</dt>
                            <dd id="ref-body-weight">{{ number_format($initialWeightKg, 1) }} kg</dd>
                            <div id="ref-body-weight-type" style="font-size:.72rem;color:var(--text-muted);margin-top:.1rem">{{ ucfirst($recommended) }}</div>
                        </div>
                        <div class="info-item" style="margin:0">
                            <dt>BMI</dt>
                            <dd>{{ number_format($bmi, 1) }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Weight Selection --}}
                <div style="grid-column:1/-1">
                    <label class="en-label">Weight for Calculation</label>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem">
                        @foreach($weightOptions as $type => $opt)
                            @php
                                $avail   = $opt['val'] !== null && (float)$opt['val'] > 0;
                                $checked = (old('weight_type', $recommended) === $type);
                            @endphp
                            <label style="display:flex;align-items:center;gap:.75rem;padding:.55rem .8rem;border:1.5px solid {{ $checked ? 'var(--primary)' : 'var(--border)' }};background:{{ $checked ? '#f0fdf4' : '#fff' }};cursor:{{ $avail ? 'pointer' : 'not-allowed' }};opacity:{{ $avail ? 1 : .4 }};transition:all .15s"
                                   id="wt-row-{{ $type }}"
                                   onclick="{{ $avail ? "selectWeight('$type',".($avail ? number_format((float)$opt['val'],2,'.','') : 0).")" : '' }}">
                                <span id="wt-dot-{{ $type }}"
                                      style="width:1rem;height:1rem;border-radius:50%;border:2px solid {{ $checked ? 'var(--primary)' : '#9ca3af' }};background:{{ $checked ? 'var(--primary)' : '#fff' }};flex-shrink:0;transition:all .15s"></span>
                                <span>
                                    <span style="font-size:.85rem;font-weight:700;color:var(--text-primary)">{{ $opt['label'] }}</span>
                                    @if($type === $recommended && $avail)
                                        <span style="margin-left:.35rem;padding:.1rem .4rem;background:#dbeafe;color:#1d4ed8;font-size:.65rem;font-weight:700">Recommended</span>
                                    @endif
                                    <br>
                                    <span style="font-size:.77rem;color:var(--text-muted)">{{ $avail ? number_format((float)$opt['val'], 2).' kg' : 'Not available' }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <input type="hidden" name="weight_type" id="wt-type-input" value="{{ old('weight_type', $recommended) }}">
                    <input type="hidden" name="weight_kg"   id="wt-kg-input"   value="{{ old('weight_kg', number_format((float)$initialWeightKg, 2, '.', '')) }}">
                    @if($isObese && $ndw)
                        <div style="font-size:.72rem;color:#92400e;margin-top:.5rem;padding:.55rem .75rem;background:#fffbeb;border:1px solid #fde68a;line-height:1.6">
                            <strong>&#x26A0;&#xFE0F; Obesity (BMI &ge; 30) — Nutritional Dosing Weight recommended</strong> (ClinCalc / ASPEN).<br>
                            <strong>NDW formula:</strong> IBW + 0.25 &times; (Actual &minus; IBW)<br>
                            &nbsp;&nbsp;&nbsp;= {{ number_format($devineIbw, 1) }} + 0.25 &times; ({{ number_format($actualWt, 1) }} &minus; {{ number_format($devineIbw, 1) }})
                            &nbsp;= <strong>{{ number_format($ndw, 1) }} kg</strong><br>
                            Using actual weight risks overfeeding; using IBW alone may underestimate needs.
                        </div>
                    @endif
                </div>

                {{-- Fluid Restriction --}}
                <div>
                    <label class="en-label">Fluid Restriction</label>
                    <select id="fluid-restrict-input" name="fluid_restriction_ml" class="en-input" style="max-width:220px">
                        <option value=""   {{ old('fluid_restriction_ml') === ''     ? 'selected' : '' }}>None</option>
                        <option value="1500" {{ old('fluid_restriction_ml') == '1500' ? 'selected' : '' }}>Moderate &mdash; 1 500 mL/day</option>
                        <option value="1000" {{ old('fluid_restriction_ml') == '1000' ? 'selected' : '' }}>Severe &mdash; 1 000 mL/day</option>
                    </select>
                    <p style="font-size:.72rem;color:var(--text-muted);margin-top:.25rem">
                        If set, formula volume is capped to this value and caloric delivery may be reduced.
                    </p>
                </div>

                {{-- Formula Caloric Density --}}
                <div>
                    <label class="en-label">Formulation &mdash; Caloric Density</label>
                    @php $selD = old('formula_density', '1.0'); @endphp
                    <select id="density-select" class="en-input" onchange="selectDensity(this.value)">
                        @foreach($densities as $dVal => $d)
                            <option value="{{ $dVal }}" {{ $selD === $dVal ? 'selected' : '' }}>
                                {{ $d['label'] }} &mdash; {{ $d['desc'] }} ({{ $d['water'] }} free water)
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="formula_density" id="density-input" value="{{ $selD }}">
                </div>

                {{-- kcal/kg + Protein side-by-side --}}
                <div>
                    <label class="en-label">Goal kcal/kg/day <span style="font-weight:400;opacity:.65">auto-filled from MSJ</span></label>
                    <input type="number" id="energy-input" name="energy_kcal_per_kg"
                           step="0.5" min="15" max="35" required value="{{ old('energy_kcal_per_kg', 25) }}"
                           class="en-input">
                </div>

                <div>
                    <label class="en-label">Total Protein/kg/day</label>
                    <input type="number" id="protein-input" name="protein_g_per_kg"
                           step="0.05" min="0.5" max="2.5" required value="{{ old('protein_g_per_kg', 1.2) }}"
                           class="en-input">
                </div>

                {{-- Feed Duration --}}
                <div>
                    <label class="en-label">Feed Duration</label>
                    <select id="hours-input" name="feeding_hours_per_day" class="en-input" style="max-width:220px">
                        @foreach([8, 12, 16, 18, 20, 22, 24] as $h)
                            <option value="{{ $h }}" {{ old('feeding_hours_per_day', 24) == $h ? 'selected' : '' }}>
                                {{ $h }} hrs{{ $h === 24 ? ' — continuous' : ($h === 16 ? ' — nocturnal' : '') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="grid-column:1/-1;display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;padding-top:.25rem">
                    <button type="button" onclick="calculate()"
                            style="display:inline-flex;align-items:center;gap:.4rem;padding:.55rem 1.4rem;background:var(--primary);color:#fff;font-size:.85rem;font-weight:700;border:none;cursor:pointer;letter-spacing:.02em;transition:background .15s"
                            onmouseover="this.style.background='var(--primary-dark)'"
                            onmouseout="this.style.background='var(--primary)'">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Calculate
                    </button>
                    <button type="submit" class="btn-save">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Save Calculation
                    </button>
                </div>
                </form>
            </div>

            {{-- Results panel --}}
            <div class="dash-section" id="results-panel">
                <div class="dash-section-header">
                    <span class="dash-section-title">Tube Feed Recommendations</span>
                    <span style="font-size:.72rem;color:var(--text-muted)">Press Calculate to update</span>
                </div>

                {{-- Summary banner --}}
                <div id="res-summary-banner" style="display:none;padding:1.1rem 1.5rem;background:#f0fdf4;border-bottom:2px solid var(--primary)">
                    <div id="res-banner-formula" style="font-size:1.05rem;font-weight:800;color:var(--primary);margin-bottom:.55rem"></div>
                    <ul style="margin:0 0 0 1.1rem;padding:0;list-style:disc;display:flex;flex-direction:column;gap:.3rem">
                        <li style="font-size:.82rem;color:var(--text-primary)">Start at 20 mL/hr, titrate by 10&ndash;20 mL/hr every 4 hours to goal rate</li>
                        <li id="res-banner-protein" style="font-size:.82rem;color:var(--text-primary)"></li>
                        <li id="res-banner-flush" style="font-size:.82rem;color:var(--text-primary)"></li>
                    </ul>
                    <div id="res-vol-restrict" style="margin-top:.55rem;font-size:.78rem;font-weight:700"></div>
                </div>

                {{-- 3-col grid: Macros | Fluid | Anthropometrics --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;border-bottom:1px solid var(--border)">

                    {{-- Macronutrients --}}
                    <div style="padding:1rem 1.25rem;border-right:1px solid var(--border)">
                        <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);margin-bottom:.8rem">Macronutrients</div>
                        <dl style="display:flex;flex-direction:column;gap:.7rem;margin:0">
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Feed Calories</dt>
                                <dd id="res-kcal" style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">&#x2014;</dd>
                                <div id="res-kcal-sub" style="font-size:.72rem;color:var(--text-muted)">&#x2014;</div>
                            </div>
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Total Protein</dt>
                                <dd id="res-protein-goal" style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">&#x2014;</dd>
                                <div id="res-protein-sub" style="font-size:.72rem;color:var(--text-muted)">&#x2014;</div>
                            </div>
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Total Carbohydrates</dt>
                                <dd id="res-fml-carbs" style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">&#x2014;</dd>
                            </div>
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Total Fat</dt>
                                <dd id="res-fml-fat" style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">&#x2014;</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Fluid --}}
                    <div style="padding:1rem 1.25rem;border-right:1px solid var(--border)">
                        <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);margin-bottom:.8rem">Fluid</div>
                        <dl style="display:flex;flex-direction:column;gap:.7rem;margin:0">
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Feed Water</dt>
                                <dd id="res-free-water" style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">&#x2014;</dd>
                            </div>
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Water Flushes</dt>
                                <dd id="res-extra-water" style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">&#x2014;</dd>
                                <div id="res-flush-sub" style="font-size:.72rem;color:var(--text-muted)">&#x2014;</div>
                            </div>
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Total Fluids</dt>
                                <dd id="res-total-fluid" style="font-size:1rem;font-weight:700;color:var(--primary);margin:0">&#x2014;</dd>
                                <div id="res-total-fluid-sub" style="font-size:.72rem;color:var(--text-muted)">&#x2014;</div>
                            </div>
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Daily Needs <span style="font-weight:400">(35 mL/kg)</span></dt>
                                <dd id="res-fluid-std" style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">&#x2014;</dd>
                                <div style="font-size:.72rem;color:var(--text-muted)">35 mL/kg/day</div>
                            </div>
                        </dl>
                    </div>

                    {{-- Anthropometrics --}}
                    <div style="padding:1rem 1.25rem">
                        <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);margin-bottom:.8rem">Anthropometrics</div>
                        <dl style="display:flex;flex-direction:column;gap:.7rem;margin:0">
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Ideal Body Weight <span style="font-weight:400">(Devine)</span></dt>
                                <dd style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">{{ $devineIbw > 0 ? number_format($devineIbw,1).' kg' : '&mdash;' }}</dd>
                            </div>
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Actual Body Weight</dt>
                                <dd style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">{{ $actualWt > 0 ? number_format($actualWt,1).' kg' : '&mdash;' }}</dd>
                                @if($devineIbw > 0 && $actualWt > 0)
                                    <div style="font-size:.72rem;color:{{ $actualWt > $devineIbw * 1.2 ? '#92400e' : 'var(--text-muted)' }}">
                                        {{ round(($actualWt - $devineIbw) / $devineIbw * 100) }}% {{ $actualWt >= $devineIbw ? 'above' : 'below' }} IBW
                                    </div>
                                @endif
                            </div>
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">Nutritional Weight Used</dt>
                                <dd id="res-nutri-wt" style="font-size:1rem;font-weight:700;color:var(--primary);margin:0">&#x2014;</dd>
                                <div id="res-nutri-wt-type" style="font-size:.72rem;color:var(--text-muted)">&#x2014;</div>
                            </div>
                            <div>
                                <dt style="font-size:.72rem;color:var(--text-muted)">BMI</dt>
                                <dd style="font-size:1rem;font-weight:700;color:var(--text-primary);margin:0">{{ $bmi > 0 ? number_format($bmi,1).' kg/m&sup2;' : '&mdash;' }}</dd>
                                @php
                                    $bmiClass = $bmi >= 40 ? 'Obese class III' : ($bmi >= 35 ? 'Obese class II' : ($bmi >= 30 ? 'Obese class I' : ($bmi >= 25 ? 'Overweight' : ($bmi >= 18.5 ? 'Normal weight' : 'Underweight'))));
                                @endphp
                                <div style="font-size:.72rem;color:var(--text-muted)">{{ $bmiClass }}</div>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Formula info table --}}
                <div style="border-bottom:1px solid var(--border)">
                    <div style="padding:.65rem 1.5rem;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);background:#f8fafc">
                        Formula Information &nbsp;<span id="res-formula-label" style="color:var(--primary);font-size:.75rem;text-transform:none;font-weight:600;letter-spacing:0"></span>
                    </div>
                    <div style="padding:.75rem 1.5rem 0">
                        <table style="width:100%;border-collapse:collapse;font-size:.82rem">
                            <thead>
                                <tr style="border-bottom:2px solid var(--border)">
                                    <th style="text-align:left;padding:.3rem .5rem;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Macronutrient</th>
                                    <th style="text-align:right;padding:.3rem .5rem;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Per 1 000 mL</th>
                                    <th style="text-align:right;padding:.3rem .5rem;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Daily Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom:1px solid var(--border)">
                                    <td style="padding:.4rem .5rem;color:var(--text-muted)">Energy</td>
                                    <td id="res-ftbl-kcal-ml"  style="text-align:right;padding:.4rem .5rem;color:var(--text-primary)">&#x2014;</td>
                                    <td id="res-kcal2"          style="text-align:right;padding:.4rem .5rem;font-weight:700;color:var(--primary)">&#x2014;</td>
                                </tr>
                                <tr style="border-bottom:1px solid var(--border)">
                                    <td style="padding:.4rem .5rem;color:var(--text-muted)">Free Water</td>
                                    <td id="res-ftbl-fw-ml"    style="text-align:right;padding:.4rem .5rem;color:var(--text-primary)">&#x2014;</td>
                                    <td id="res-ftbl-fw-daily" style="text-align:right;padding:.4rem .5rem;font-weight:700;color:var(--text-primary)">&#x2014;</td>
                                </tr>
                                <tr style="border-bottom:1px solid var(--border)">
                                    <td style="padding:.4rem .5rem;color:var(--text-muted)">Protein</td>
                                    <td id="res-ftbl-pro-ml"   style="text-align:right;padding:.4rem .5rem;color:var(--text-primary)">&#x2014;</td>
                                    <td id="res-fml-protein"   style="text-align:right;padding:.4rem .5rem;font-weight:700;color:var(--text-primary)">&#x2014;</td>
                                </tr>
                                <tr style="border-bottom:1px solid var(--border)">
                                    <td style="padding:.4rem .5rem;color:var(--text-muted)">Carbohydrates</td>
                                    <td id="res-ftbl-carbs-ml" style="text-align:right;padding:.4rem .5rem;color:var(--text-primary)">&#x2014;</td>
                                    <td id="res-ftbl-carbs-daily" style="text-align:right;padding:.4rem .5rem;font-weight:700;color:var(--text-primary)">&#x2014;</td>
                                </tr>
                                <tr>
                                    <td style="padding:.4rem .5rem;color:var(--text-muted)">Fat</td>
                                    <td id="res-ftbl-fat-ml"   style="text-align:right;padding:.4rem .5rem;color:var(--text-primary)">&#x2014;</td>
                                    <td id="res-ftbl-fat-daily" style="text-align:right;padding:.4rem .5rem;font-weight:700;color:var(--text-primary)">&#x2014;</td>
                                </tr>
                            </tbody>
                        </table>
                        <p style="font-size:.68rem;color:var(--text-muted);margin:.65rem 0 .5rem;line-height:1.55">
                            Manufacturers may change a formulation at any time. Nutrition information is based on generic formula data per 1 000 mL; verify against the manufacturer's current product data sheet.
                        </p>
                    </div>
                    <div id="res-protein-adequacy" style="padding:.1rem 1rem .75rem;font-size:.78rem;font-weight:700"></div>
                </div>

                {{-- Footer: REE / TEE / NPN --}}
                <div style="padding:.6rem 1.5rem;background:#f8fafc;display:flex;gap:2rem;flex-wrap:wrap">
                    <span style="font-size:.72rem;color:var(--text-muted)">REE (MSJ): <strong id="res-ree" style="color:var(--text-primary)">&#x2014;</strong></span>
                    <span style="font-size:.72rem;color:var(--text-muted)">TEE: <strong id="res-tee" style="color:var(--text-primary)">&#x2014;</strong></span>
                    <span style="font-size:.72rem;color:var(--text-muted)">NPN&thinsp;:&thinsp;N: <strong id="res-npn" style="color:var(--text-primary)">&#x2014;</strong> <span style="font-weight:400">(100&ndash;150:1 standard)</span></span>
                    <span style="font-size:.72rem;color:var(--text-muted)">Nitrogen: <strong id="res-nitrogen" style="color:var(--text-primary)">&#x2014;</strong></span>
                    <span style="font-size:.72rem;color:var(--text-muted)">Volume: <strong id="res-volume" style="color:var(--text-primary)">&#x2014;</strong></span>
                </div>
            </div>

    </div>

    {{-- Saved Calculations History --}}
    @if($calculations->isNotEmpty())
    <div class="dash-section" style="margin-top:1.5rem">
        <div class="dash-section-header" style="cursor:pointer;user-select:none"
             onclick="toggleSection('hist-body','hist-chev')">
            <span class="dash-section-title">Saved Calculations ({{ $calculations->count() }})</span>
            <svg id="hist-chev" xmlns="http://www.w3.org/2000/svg"
                 style="width:1rem;height:1rem;color:var(--text-muted);transition:transform .25s"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <div id="hist-body">
            <div class="overflow-x-auto">
                <table class="pt-table">
                    <thead>
                        <tr>
                            <th>Date / Label</th>
                            <th>Condition</th>
                            <th style="text-align:right">Weight</th>
                            <th style="text-align:right">Energy</th>
                            <th style="text-align:right">Protein</th>
                            <th style="text-align:center">Formula</th>
                            <th style="text-align:right">Volume</th>
                            <th style="text-align:right">Rate</th>
                            <th style="text-align:right">Add. Water</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calculations as $calc)
                        <tr>
                            <td>
                                <div style="font-weight:700;color:var(--text-primary)">{{ $calc->label ?: '—' }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted)">{{ $calc->created_at->format('d M Y H:i') }}</div>
                            </td>
                            <td style="font-size:.8rem;color:var(--text-muted)">{{ $calc->condition_label }}</td>
                            <td style="text-align:right">
                                <div style="font-weight:700">{{ number_format($calc->weight_kg,1) }} kg</div>
                                <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase">{{ $calc->weight_type }}</div>
                            </td>
                            <td style="text-align:right">
                                <div style="font-weight:700">{{ number_format($calc->energy_target_kcal,0) }} kcal</div>
                                <div style="font-size:.7rem;color:var(--text-muted)">{{ $calc->energy_kcal_per_kg }} kcal/kg</div>
                            </td>
                            <td style="text-align:right">
                                <div style="font-weight:700">{{ number_format($calc->protein_target_g,0) }} g</div>
                                <div style="font-size:.7rem;color:var(--text-muted)">{{ $calc->protein_g_per_kg }} g/kg</div>
                            </td>
                            <td style="text-align:center">
                                <span class="tbl-btn view">{{ number_format($calc->formula_density,1) }} kcal/mL</span>
                            </td>
                            <td style="text-align:right;font-weight:700">{{ number_format($calc->daily_volume_ml,0) }} mL</td>
                            <td style="text-align:right">
                                <div style="font-weight:700">{{ number_format($calc->rate_ml_per_hour,1) }} mL/hr</div>
                                <div style="font-size:.7rem;color:var(--text-muted)">{{ $calc->feeding_hours_per_day }}h/day</div>
                            </td>
                            <td style="text-align:right;font-weight:700">{{ number_format($calc->additional_water_ml,0) }} mL</td>
                            <td>
                                <form method="POST"
                                      action="{{ route('patients.enteral-nutrition.destroy', [$patient, $calc]) }}"
                                      onsubmit="return confirm('Delete this calculation?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="tbl-btn delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @if($calc->notes)
                        <tr>
                            <td colspan="10" style="font-size:.75rem;color:var(--text-muted);font-style:italic;padding-top:.15rem;padding-bottom:.6rem">
                                &#x1F4CB; {{ $calc->notes }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif


</div>

<style>
.en-label {
    display: block;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--text-muted);
    margin-bottom: .35rem;
}
.en-input {
    display: block;
    width: 100%;
    padding: .5rem .75rem;
    font-size: .875rem;
    border: 1.5px solid var(--border);
    outline: none;
    background: #fff;
    color: var(--text-primary);
    font-family: inherit;
    transition: border-color .15s;
}
.en-input:focus { border-color: var(--primary); }
select.en-input { cursor: pointer; }
@media (max-width: 900px) {
    .en-two-col { grid-template-columns: 1fr !important; }
}
</style>

<script>
(function () {
    'use strict';

    // ── Constants passed from PHP ─────────────────────────────────────
    var ENERGY_RANGES   = @json($energyRanges);
    var PROTEIN_RANGES  = @json($proteinRanges);
    var FORMULA_DB      = @json($formulaDb);      // macros per 1 000 mL per density
    var PATIENT_HEIGHT  = {{ (float)($patient->height ?? 0) }};  // cm
    var PATIENT_AGE     = {{ (int)($patient->age ?? 0) }};
    var PATIENT_IS_MALE = {{ $isMale ? 'true' : 'false' }};
    var currentDensity  = document.getElementById('density-input').value || '1.0';

    // ── Collapsible sections ──────────────────────────────────────────
    window.toggleSection = function (bodyId, chevId) {
        var body = document.getElementById(bodyId);
        var chev = document.getElementById(chevId);
        if (!body) return;
        var open = body.style.display !== 'none';
        body.style.display = open ? 'none' : '';
        if (chev) chev.style.transform = open ? 'rotate(-90deg)' : 'rotate(0deg)';
    };

    // ── Condition change — update guideline hints & protein default ───
    window.onCondition = function (slug) {
        var er = ENERGY_RANGES[slug]  || [25, 30];
        var pr = PROTEIN_RANGES[slug] || [0.8, 1.2];
        document.getElementById('hint-energy').textContent  = er[0] + ' \u2013 ' + er[1] + ' kcal/kg/day';
        document.getElementById('hint-protein').textContent = pr[0] + ' \u2013 ' + pr[1] + ' g/kg/day';
        document.getElementById('protein-input').value = ((pr[0] + pr[1]) / 2).toFixed(2);
        // Energy is driven by MSJ+SF; only fall back if MSJ not available
        if (!PATIENT_HEIGHT || !PATIENT_AGE) {
            document.getElementById('energy-input').value = ((er[0] + er[1]) / 2).toFixed(1);
        }
    };

    // ── Weight tile selection ─────────────────────────────────────────
    window.selectWeight = function (type, kg) {
        ['actual', 'ibw', 'abw'].forEach(function (t) {
            var dot = document.getElementById('wt-dot-' + t);
            var row = document.getElementById('wt-row-' + t);
            if (!dot || !row) return;
            dot.style.border     = '2px solid #9ca3af';
            dot.style.background = '#fff';
            row.style.border     = '1.5px solid var(--border)';
            row.style.background = '#fff';
        });
        var activeDot = document.getElementById('wt-dot-' + type);
        var activeRow = document.getElementById('wt-row-' + type);
        if (activeDot) { activeDot.style.border = '2px solid var(--primary)'; activeDot.style.background = 'var(--primary)'; }
        if (activeRow) { activeRow.style.border = '1.5px solid var(--primary)'; activeRow.style.background = '#f0fdf4'; }
        document.getElementById('wt-type-input').value = type;
        document.getElementById('wt-kg-input').value   = kg;
        updateMSJ();   // recalculate REE/TEE display with new weight
        updatePatientSummary();
    };

    // ── Density tile selection ────────────────────────────────────────
    window.selectDensity = function (val) {
        currentDensity = val;
        document.getElementById('density-input').value = val;
        var sel = document.getElementById('density-select');
        if (sel) sel.value = val;
        updatePatientSummary();
    };

    // ── Mifflin-St Jeor → auto-fill kcal/kg ────────────────────────
    window.updateMSJ = function () {
        var wt = parseFloat(document.getElementById('wt-kg-input').value) || 0;
        if (!wt || !PATIENT_HEIGHT || !PATIENT_AGE) return;
        var ree = PATIENT_IS_MALE
            ? (10 * wt) + (6.25 * PATIENT_HEIGHT) - (5 * PATIENT_AGE) + 5
            : (10 * wt) + (6.25 * PATIENT_HEIGHT) - (5 * PATIENT_AGE) - 161;
        if (wt > 0) {
            var teeKcalKg = Math.min(35, Math.max(15, ree / wt));
            document.getElementById('energy-input').value = teeKcalKg.toFixed(1);
        }
    };

    // ── Calculate button handler ──────────────────────────────────────
    window.calculate = function () {
        updateMSJ();   // ensure kcal/kg is current
        updatePatientSummary();
        recalc();
        var panel = document.getElementById('results-panel');
        if (panel) panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    // ── Update body-weight display in Patient Parameters card ─────────
    function updatePatientSummary() {
        var wt     = parseFloat(document.getElementById('wt-kg-input').value) || 0;
        var wtType = document.getElementById('wt-type-input').value || '';
        var wtLabels = { actual: 'Actual', ibw: 'IBW (Devine)', abw: 'NDW' };
        set('ref-body-weight',      wt > 0 ? wt.toFixed(1) + ' kg' : '—');
        set('ref-body-weight-type', wtLabels[wtType] || wtType);
    }

    // ── Full calculation & display ────────────────────────────────────
    window.recalc = function () {
        var wt       = parseFloat(document.getElementById('wt-kg-input').value) || 0;
        var kcalKg   = parseFloat(document.getElementById('energy-input').value) || 0;
        var proKg    = parseFloat(document.getElementById('protein-input').value) || 0;
        var hours    = parseInt(document.getElementById('hours-input').value) || 24;
        var density  = parseFloat(currentDensity) || 1.0;
        var fluidEl  = document.getElementById('fluid-restrict-input');
        var restrict = fluidEl ? (parseFloat(fluidEl.value) || null) : null;

        if (!wt || !kcalKg || !density) return;

        // ── REE / TEE (SF fixed at 1.0) ──────────────────────────────
        var ree = 0;
        if (PATIENT_HEIGHT && PATIENT_AGE) {
            ree = PATIENT_IS_MALE
                ? (10 * wt) + (6.25 * PATIENT_HEIGHT) - (5 * PATIENT_AGE) + 5
                : (10 * wt) + (6.25 * PATIENT_HEIGHT) - (5 * PATIENT_AGE) - 161;
        }
        var tee = ree;

        // ── Energy & Volume ──────────────────────────────────────────
        var energyKcal  = wt * kcalKg;
        var proteinG    = wt * proKg;
        var nitrogenG   = proteinG / 6.25;
        var proteinKcal = proteinG * 4;
        var nonProKcal  = energyKcal - proteinKcal;

        var volumeMl;
        var restricted = false;
        if (restrict && restrict < (energyKcal / density)) {
            volumeMl   = restrict;
            restricted = true;
        } else {
            volumeMl = energyKcal / density;
        }
        var rateMlHr = volumeMl / hours;

        // ── Fluid ────────────────────────────────────────────────────
        var formula    = FORMULA_DB[currentDensity] || FORMULA_DB['1.0'];
        var fwFrac     = formula.freeWater || 0.85;
        var fwMl       = volumeMl * fwFrac;
        var fluidStd   = wt * 35;
        var extraWater = Math.max(0, fluidStd - fwMl);
        var totalFluid = fwMl + extraWater;

        // ── Macros from Formula ──────────────────────────────────────
        var volumeL     = volumeMl / 1000;
        var fmlProteinG = volumeL * (formula.proteinGL || 0);
        var fmlCarbsG   = volumeL * (formula.carbsGL   || 0);
        var fmlFatG     = volumeL * (formula.fatGL     || 0);

        // ── Other metrics ─────────────────────────────────────────────
        var npnRatio = nitrogenG > 0 ? Math.round(nonProKcal / nitrogenG) : null;
        var flushMl  = extraWater > 0 ? Math.round(extraWater / 6) : 0;

        function n(v, dec) {
            if (!v && v !== 0) return '\u2014';
            return parseFloat(v).toLocaleString('en-ZA', { minimumFractionDigits: dec || 0, maximumFractionDigits: dec || 0 });
        }

        // ── Show banner ───────────────────────────────────────────────
        var banner = document.getElementById('res-summary-banner');
        if (banner) banner.style.display = 'block';

        // ── Banner ────────────────────────────────────────────────────
        set('res-banner-formula', 'Formula ' + currentDensity + ' kcal/mL \u2014 Goal rate: ' + n(rateMlHr, 1) + ' mL/hr over ' + hours + 'h');
        set('res-banner-protein', n(proteinG, 1) + ' g protein/day (' + n(proKg, 2) + ' g/kg)');
        set('res-banner-flush',   n(extraWater) + ' mL water flushes (' + n(flushMl) + ' mL \u00d7 6 per day)');

        // ── Macros section ────────────────────────────────────────────
        set('res-kcal',         n(energyKcal) + ' kcal');
        set('res-kcal-sub',     '(' + n(kcalKg, 1) + ' kcal/kg/day)');
        set('res-protein-goal', n(proteinG, 1) + ' g');
        set('res-protein-sub',  '(' + n(proKg, 2) + ' g/kg/day)');
        set('res-fml-carbs',    n(fmlCarbsG, 1) + ' g');
        set('res-fml-fat',      n(fmlFatG, 1) + ' g');

        // ── Fluid section ─────────────────────────────────────────────
        set('res-free-water',      n(fwMl) + ' mL');
        set('res-extra-water',     n(extraWater) + ' mL');
        set('res-flush-sub',       extraWater > 0 ? '(' + n(flushMl) + ' mL every 4 hrs)' : '(no supplemental flush needed)');
        set('res-total-fluid',     n(totalFluid) + ' mL');
        set('res-total-fluid-sub', '(' + n(totalFluid / wt, 1) + ' mL/kg/day)');
        set('res-fluid-std',       n(fluidStd) + ' mL');

        // ── Nutritional weight ────────────────────────────────────────
        var wtLabels = { actual: 'Actual body weight', ibw: 'IBW (Devine)', abw: 'Adjusted body weight (NDW)' };
        var wtType   = document.getElementById('wt-type-input').value || '';
        set('res-nutri-wt',      n(wt, 1) + ' kg');
        set('res-nutri-wt-type', wtLabels[wtType] || wtType);

        // ── Formula label & table ─────────────────────────────────────
        set('res-formula-label',    currentDensity + ' kcal/mL');
        set('res-ftbl-kcal-ml',     density + ' kcal');
        set('res-kcal2',            n(energyKcal) + ' kcal');
        set('res-ftbl-fw-ml',       Math.round((formula.freeWater || 0.85) * 1000) + ' mL');
        set('res-ftbl-fw-daily',    n(fwMl) + ' mL');
        set('res-ftbl-pro-ml',      (formula.proteinGL || 0) + ' g');
        set('res-fml-protein',      n(fmlProteinG, 1) + ' g');
        set('res-ftbl-carbs-ml',    (formula.carbsGL || 0) + ' g');
        set('res-ftbl-carbs-daily', n(fmlCarbsG, 1) + ' g');
        set('res-ftbl-fat-ml',      (formula.fatGL || 0) + ' g');
        set('res-ftbl-fat-daily',   n(fmlFatG, 1) + ' g');

        // ── Footer ────────────────────────────────────────────────────
        set('res-ree',      ree > 0 ? n(ree) + ' kcal/day' : '\u2014');
        set('res-tee',      tee > 0 ? n(tee) + ' kcal/day' : '\u2014');
        set('res-nitrogen', n(nitrogenG, 1) + ' g/day');
        set('res-npn',      npnRatio ? npnRatio + '\u00a0:\u00a01' : '\u2014');
        set('res-volume',   n(volumeMl) + ' mL');

        // ── Protein adequacy flag ─────────────────────────────────────
        var adequate = fmlProteinG >= proteinG;
        var adeqEl   = document.getElementById('res-protein-adequacy');
        if (adeqEl) {
            adeqEl.textContent = adequate
                ? '\u2713 Formula meets protein goal.'
                : '\u26A0\uFE0F Formula protein (' + n(fmlProteinG, 1) + ' g) is below goal (' + n(proteinG, 1) + ' g) \u2014 consider higher-protein formula or supplementation.';
            adeqEl.style.color      = adequate ? '#15803d' : '#b91c1c';
            adeqEl.style.fontWeight = '700';
        }

        // ── Fluid restriction status ──────────────────────────────────
        var volEl = document.getElementById('res-vol-restrict');
        if (volEl) {
            if (restrict) {
                volEl.textContent = restricted
                    ? '\u26A0\uFE0F Restriction (' + n(restrict) + ' mL) limits volume \u2014 caloric goal NOT met at this density'
                    : '\u2713 Volume (' + n(volumeMl) + ' mL) fits within restriction';
                volEl.style.color = restricted ? '#b91c1c' : '#15803d';
            } else {
                volEl.textContent = 'No fluid restriction';
                volEl.style.color = 'var(--text-muted)';
            }
        }
    };

    function set(id, val) {
        var el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    // ── Initialise UI state (no auto-recalc on load) ─────────────────
    var condEl = document.getElementById('cond-select');
    if (condEl) onCondition(condEl.value);
    selectDensity(currentDensity);
    var initType = document.getElementById('wt-type-input').value || '{{ $recommended }}';
    var initKg   = parseFloat(document.getElementById('wt-kg-input').value) || 0;
    if (initType && initKg) {
        // Highlight the pre-selected weight tile without triggering recalc
        ['actual', 'ibw', 'abw'].forEach(function (t) {
            var dot = document.getElementById('wt-dot-' + t);
            var row = document.getElementById('wt-row-' + t);
            if (!dot || !row) return;
            dot.style.border     = '2px solid #9ca3af';
            dot.style.background = '#fff';
            row.style.border     = '1.5px solid var(--border)';
            row.style.background = '#fff';
        });
        var dot = document.getElementById('wt-dot-' + initType);
        var row = document.getElementById('wt-row-' + initType);
        if (dot) { dot.style.border = '2px solid var(--primary)'; dot.style.background = 'var(--primary)'; }
        if (row) { row.style.border = '1.5px solid var(--primary)'; row.style.background = '#f0fdf4'; }
    }
    updateMSJ();   // populate REE/TEE display & kcal/kg on load
    updatePatientSummary();
})();
</script>

</x-app-layout>
