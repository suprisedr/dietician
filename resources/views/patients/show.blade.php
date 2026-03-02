<x-app-layout>

    {{-- ═══════════════════════════════════════════
         PATIENT PROFILE HERO
    ═══════════════════════════════════════════ --}}
    @php
        $initials = collect(explode(' ', $patient->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
        $bmiCat   = strtolower($patient->bmi_category ?? 'normal');
        $teeKcal  = $patient->tee ? round($patient->tee / 4.184) : null;
        $teeKj    = ($patient->tee ?? 0) * 4.184;

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
            'carbohydrate' => ['dot'=>'#f97316','bg'=>'rgba(249,115,22,.12)','text'=>'#c2410c'],
            'protein'      => ['dot'=>'#6366f1','bg'=>'rgba(99,102,241,.12)', 'text'=>'#4338ca'],
            'fat'          => ['dot'=>'#14b8a6','bg'=>'rgba(20,184,166,.12)',  'text'=>'#0f766e'],
        ];

        // Recommended intakes derived from TEE + macro targets
        // kJ factors: CHO=17, Protein=17, Fat=37 (Atwater)
        $macroByType = $patient->macronutrients->keyBy('type');
        $recCho_g    = null; $recPro_g  = null; $recFat_g  = null;
        $recCho_kj   = null; $recPro_kj = null; $recFat_kj = null;
        if ($teeKj > 0) {
            $choPct  = optional($macroByType->get('carbohydrate'))->selected_percentage ?? 0;
            $proPct  = optional($macroByType->get('protein'))->selected_percentage      ?? 0;
            $fatPct  = optional($macroByType->get('fat'))->selected_percentage          ?? 0;
            $recCho_kj  = round($teeKj * $choPct / 100);
            $recPro_kj  = round($teeKj * $proPct / 100);
            $recFat_kj  = round($teeKj * $fatPct / 100);
            $recCho_g   = $recCho_kj > 0 ? round($recCho_kj / 17) : 0;
            $recPro_g   = $recPro_kj > 0 ? round($recPro_kj / 17) : 0;
            $recFat_g   = $recFat_kj > 0 ? round($recFat_kj / 37) : 0;
        }
    @endphp

    <div class="patient-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Back nav --}}
            <a href="{{ route('patients.index') }}" class="btn-back mb-5 inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                All Patients
            </a>

            {{-- Patient identity --}}
            <div class="flex items-center gap-4">
                <div class="patient-avatar-lg {{ $patient->gender }}">{{ $initials }}</div>
                <div>
                    <h1 style="font-size:clamp(1.5rem,3vw,2.2rem);font-weight:800;letter-spacing:-.03em;line-height:1.1">
                        {{ $patient->name }}
                    </h1>
                    <p style="opacity:.7;font-size:.9rem;margin-top:.2rem">
                        {{ ucfirst($patient->gender) }} · {{ $patient->age }} years · Registered {{ $patient->created_at->format('M d, Y') }}
                    </p>
                </div>
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
                    <div class="mc-val">{{ number_format($patient->bmi, 1) }}</div>
                    <div class="mc-label">BMI</div>
                    <div class="mc-sub"><span class="bmi-pill {{ $bmiCat }}">{{ $patient->bmi_category }}</span></div>
                @else
                    <div class="mc-val">—</div>
                    <div class="mc-label">BMI</div>
                @endif
            </div>
            <div class="metric-card">
                <div class="mc-val">{{ $patient->bmr ? number_format($patient->bmr * 4.184, 0) : '—' }}</div>
                <div class="mc-label">BMR @if($isObese)<span style="font-size:.6rem;font-weight:700;padding:.05rem .35rem;background:#fff7ed;color:#c2410c;border-radius:999px;vertical-align:middle">Adj.</span>@endif</div>
                <div class="mc-sub">kJ/day</div>
            </div>
            <div class="metric-card">
                <div class="mc-val">{{ $teeKcal ? number_format($teeKcal) : '—' }}</div>
                <div class="mc-label">TEE</div>
                <div class="mc-sub">kcal/day</div>
            </div>
            <div class="metric-card">
                <div class="mc-val">{{ $patient->ibw ? number_format($patient->ibw, 1) : '—' }}</div>
                <div class="mc-label">IBW</div>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── LEFT: Body Details ─────────────────────── --}}
            <div class="space-y-6">

                {{-- Anthropometrics card --}}
                <div class="dash-section">
                    <div class="dash-section-header">
                        <span class="dash-section-title">Anthropometrics</span>
                    </div>
                    <dl class="info-grid">
                        <div class="info-item"><dt>Weight</dt><dd>{{ $patient->weight }} kg</dd></div>
                        <div class="info-item"><dt>Height</dt><dd>{{ $patient->height }} cm</dd></div>
                        <div class="info-item"><dt>IBW</dt><dd>{{ $patient->ibw ? number_format($patient->ibw, 2).' kg' : '—' }}</dd></div>
                        <div class="info-item"><dt>ABW <span style="font-size:.65rem;color:var(--text-muted);font-weight:500">(0.4 factor)</span></dt><dd>{{ $patient->abw ? number_format($patient->abw, 2).' kg' : '—' }}</dd></div>
                        <div class="info-item"><dt>Activity Factor</dt><dd>{{ $patient->activity_factor }}</dd></div>
                        <div class="info-item">
                            <dt>BMR / RMR
                                @if($isObese)
                                    <span style="font-size:.65rem;font-weight:700;padding:.1rem .45rem;background:#fff7ed;color:#c2410c;border-radius:999px;margin-left:.3rem">Adj.</span>
                                @endif
                            </dt>
                            <dd>{{ $patient->bmr ? number_format($patient->bmr * 4.184, 0).' kJ/day' : '—' }}</dd>
                        </div>
                        <div class="info-item"><dt>TEE</dt><dd>{{ $patient->tee ? number_format($patient->tee, 0).' kJ/day' : '—' }}</dd></div>
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
                            energy needs in obesity. The primary BMR above uses
                            <strong>{{ number_format($weightForBmr, 1) }} kg</strong>
                            (IBW&nbsp;+&nbsp;0.25&nbsp;×&nbsp;excess).
                            Two alternative estimates are shown below for comparison.
                        </p>

                        <table style="width:100%;font-size:.78rem;border-collapse:collapse">
                            <thead>
                                <tr style="border-bottom:1px solid #fed7aa">
                                    <th style="text-align:left;font-weight:700;color:#92400e;padding:.35rem .5rem .35rem 0">Method</th>
                                    <th style="text-align:right;font-weight:700;color:#92400e;padding:.35rem 0">Weight used</th>
                                    <th style="text-align:right;font-weight:700;color:#92400e;padding:.35rem 0">BMR (kJ)</th>
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
                </div>


            </div>

            {{-- ── RIGHT: Macronutrients ──────────────────── --}}
            <div class="lg:col-span-2">
                <div class="dash-section">
                    <div class="dash-section-header">
                        <span class="dash-section-title">Macronutrient Distribution</span>
                        <span id="total-badge" style="font-size:.75rem;font-weight:700;padding:.25rem .7rem;border-radius:999px;background:#f1f5f9;color:#64748b;transition:all .2s">
                            Total: <span id="macros-total">{{ number_format($patient->macronutrients->sum('selected_percentage'), 0) }}%</span>
                        </span>
                    </div>

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
                            <div class="macro-row" data-macro-id="{{ $macro->id }}">
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
                </div>

                {{-- Macro breakdown visual --}}
                @if($patient->macronutrients->count())
                @php
                    $macroTotal = $patient->macronutrients->sum('selected_percentage') ?: 1;
                @endphp
                <div class="dash-section mt-6">
                    <div class="dash-section-header">
                        <span class="dash-section-title">Energy Breakdown</span>
                    </div>
                    <div class="p-5 space-y-4">
                        {{-- Stacked bar --}}
                        <div style="display:flex;height:1.25rem;border-radius:999px;overflow:hidden;gap:2px" id="macro-stacked-bar">
                            @foreach($patient->macronutrients as $macro)
                                @php
                                    $mc  = $macroColors[$macro->type] ?? ['dot'=>'#94a3b8'];
                                    $pct = round($macro->selected_percentage / $macroTotal * 100);
                                @endphp
                                <div style="width:{{ $pct }}%;background:{{ $mc['dot'] }};transition:width .4s ease;border-radius:2px" title="{{ ucfirst($macro->type) }} {{ $macro->selected_percentage }}%"></div>
                            @endforeach
                        </div>
                        {{-- Legend --}}
                        <div class="flex flex-wrap gap-3 mt-3">
                            @foreach($patient->macronutrients as $macro)
                                @php $mc = $macroColors[$macro->type] ?? ['dot'=>'#94a3b8','text'=>'#64748b']; @endphp
                                <div style="display:flex;align-items:center;gap:.4rem;font-size:.75rem;font-weight:600">
                                    <span style="width:.6rem;height:.6rem;border-radius:50%;background:{{ $mc['dot'] }};flex-shrink:0"></span>
                                    <span style="color:{{ $mc['text'] }}">{{ ucfirst($macro->type) }}</span>
                                    <span style="color:var(--text-muted)">{{ $macro->selected_percentage }}%</span>
                                </div>
                            @endforeach
                        </div>
                        {{-- kJ summary --}}
                        <div class="grid grid-cols-3 gap-3 mt-4">
                            @foreach($patient->macronutrients as $macro)
                                @php
                                    $mc  = $macroColors[$macro->type] ?? ['dot'=>'#94a3b8','bg'=>'#f1f5f9','text'=>'#64748b'];
                                    $kj  = $teeKj * ($macro->selected_percentage / 100);
                                @endphp
                                <div style="background:{{ $mc['bg'] }};border-radius:.75rem;padding:.85rem;text-align:center">
                                    <div style="font-size:1.1rem;font-weight:800;color:{{ $mc['text'] }}">{{ number_format($kj, 0) }}</div>
                                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:{{ $mc['text'] }};opacity:.7;margin-top:.15rem">{{ ucfirst($macro->type) }} kJ</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             EXCHANGE TEMPLATE — full width
        ═══════════════════════════════════════════ --}}
        @if($patient->exchangeTemplate)
        <details open class="mt-6"> <!-- exchange template collapsible -->
            <summary class="font-semibold cursor-pointer py-2">Exchange Template ▾</summary>
            <div class="dash-section exchange-template-section">
            <div class="dash-section-header">
                <span class="dash-section-title">Exchange Template</span>
                <span style="font-size:.72rem;font-weight:600;padding:.2rem .65rem;border-radius:999px;background:#fff7ed;color:#c2410c">
                    {{ $patient->exchangeTemplate->name }}
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="exchange-table" id="exchange-table">
                    <thead>
                        <tr>
                            <th style="min-width:160px">Item</th>
                            <th style="text-align:center;min-width:110px">nu</th>
                            <th style="text-align:right">CHO (g)</th>
                            <th style="text-align:right">Protein min (g)</th>
                            <th style="text-align:right">Protein max (g)</th>
                            <th style="text-align:right">Fat min (g)</th>
                            <th style="text-align:right">Fat max (g)</th>
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
                            data-pro-max="{{ $item->protein_max_g }}"
                            data-fat-min="{{ $item->fat_min_g }}"
                            data-fat-max="{{ $item->fat_max_g }}"
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
                            <td class="et-cho"  style="text-align:right">{{ $item->cho_g          !== null ? $nu * $item->cho_g          : '—' }}</td>
                            <td class="et-pmin" style="text-align:right">{{ $item->protein_min_g  !== null ? $nu * $item->protein_min_g  : '—' }}</td>
                            <td class="et-pmax" style="text-align:right">{{ $item->protein_max_g  !== null ? $nu * $item->protein_max_g  : '—' }}</td>
                            <td class="et-fmin" style="text-align:right">{{ $item->fat_min_g      !== null ? $nu * $item->fat_min_g      : '—' }}</td>
                            <td class="et-fmax" style="text-align:right">{{ $item->fat_max_g      !== null ? $nu * $item->fat_max_g      : '—' }}</td>
                            <td class="et-kj"   style="text-align:right;font-weight:600">{{ $item->kj !== null ? $nu * $item->kj : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <!-- grams totals row -->
                        <tr style="background:var(--bg-page);border-top:2px solid var(--border)">
                            <td colspan="2" style="font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Total&nbsp;(g)</td>
                            <td id="tot-cho"  style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-pmin" style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-pmax" style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-fmin" style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-fmax" style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-kj"   style="text-align:right;font-weight:700;color:var(--primary)">—</td>
                        </tr>
                        <!-- kJ conversion row -->
                        <tr style="background:var(--bg-page);border-top:1px solid var(--border)">
                            <td colspan="2" style="font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted)">Total&nbsp;(kJ)</td>
                            <td id="tot-kj-cho"  style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-kj-pmin" style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-kj-pmax" style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-kj-fmin" style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-kj-fmax" style="text-align:right;font-weight:700;color:var(--text)">—</td>
                            <td id="tot-kj-total"   style="text-align:right;font-weight:700;color:var(--primary)">—</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </details>
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
        </details>
        @endif

        {{-- ═══════════════════════════════════════════
             NUTRIENT ANALYSIS SUMMARY
        ═══════════════════════════════════════════ --}}
        @if($patient->exchangeTemplate && $teeKj > 0)
        <details class="mt-4" id="nutrient-analysis-details" open>
            <summary class="font-semibold cursor-pointer py-2">Nutrient Analysis ▾</summary>
            <div class="dash-section" id="nutrient-analysis">
            <div class="dash-section-header">
                <span class="dash-section-title">Nutrient Analysis</span>
                <span style="font-size:.72rem;color:var(--text-muted);font-weight:500">Updates live as you adjust the exchange template</span>
            </div>
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
                            <td style="padding:.6rem .75rem;font-weight:700;color:#c2410c">
                                <span style="display:inline-block;width:.55rem;height:.55rem;border-radius:50%;background:#f97316;margin-right:.4rem;vertical-align:.05rem"></span>
                                Carbs (g)
                            </td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:600" id="na-cho-rec">{{ $recCho_g ?? '—' }}</td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:700" id="na-cho-act">—</td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:600" id="na-cho-diff">—</td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:600" id="na-cho-pct">—</td>
                        </tr>
                        {{-- Protein --}}
                        <tr class="na-row" style="border-bottom:1px solid #f1f5f9">
                            <td style="padding:.6rem .75rem;font-weight:700;color:#4338ca">
                                <span style="display:inline-block;width:.55rem;height:.55rem;border-radius:50%;background:#6366f1;margin-right:.4rem;vertical-align:.05rem"></span>
                                Protein (g)
                            </td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:600" id="na-pro-rec">{{ $recPro_g ?? '—' }}</td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:700" id="na-pro-act">—</td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:600" id="na-pro-diff">—</td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:600" id="na-pro-pct">—</td>
                        </tr>
                        {{-- Fat --}}
                        <tr class="na-row" style="border-bottom:1px solid #f1f5f9">
                            <td style="padding:.6rem .75rem;font-weight:700;color:#0f766e">
                                <span style="display:inline-block;width:.55rem;height:.55rem;border-radius:50%;background:#14b8a6;margin-right:.4rem;vertical-align:.05rem"></span>
                                Fat (g)
                            </td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:600" id="na-fat-rec">{{ $recFat_g ?? '—' }}</td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:700" id="na-fat-act">—</td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:600" id="na-fat-diff">—</td>
                            <td style="text-align:right;padding:.6rem .75rem;font-weight:600" id="na-fat-pct">—</td>
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
            </div>
        </details>
        @endif (uses nu values)
        ═══════════════════════════════════════════ --}}
        @if($patient->exchangeTemplate)
        <details class="mt-6" id="meal-plan-details">
            <summary class="font-semibold cursor-pointer py-2">Meal Plan ▾</summary>
            <div class="dash-section">
                <div class="dash-section-header" style="justify-content:space-between;align-items:center">
                    <span class="dash-section-title">Meal Plan Distribution</span>
                    <span style="font-size:.75rem;color:var(--text-muted)">Enter serving exchanges per meal — each row must sum to the total (No)</span>
                </div>

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
                    <div class="overflow-x-auto" style="padding:0 1.25rem 1.25rem">
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
            </div>
        </details>
        @endif

    </div>

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
            const pmax = Number(row.dataset.proMax)  || 0;
            const fmin = Number(row.dataset.fatMin)  || 0;
            const fmax = Number(row.dataset.fatMax)  || 0;
            const kj   = Number(row.dataset.kj)   || 0;

            row.querySelector('.et-cho').textContent  = cho  ? (cho*nu)  : '—';
            row.querySelector('.et-pmin').textContent = pmin ? (pmin*nu) : '—';
            row.querySelector('.et-pmax').textContent = pmax ? (pmax*nu) : '—';
            row.querySelector('.et-fmin').textContent = fmin ? (fmin*nu) : '—';
            row.querySelector('.et-fmax').textContent = fmax ? (fmax*nu) : '—';
            row.querySelector('.et-kj').textContent   = kj   ? (kj*nu)   : '—';
        }

        function recalcTotals() {
            const rows = Array.from(document.querySelectorAll('#exchange-table tbody tr'));
            const sums = {cho:0,pmin:0,pmax:0,fmin:0,fmax:0,kj:0};
            rows.forEach(r=>{
                const nu = Number(r.dataset.nu)||0;
                sums.cho  += (Number(r.dataset.cho)  ||0) * nu;
                sums.pmin += (Number(r.dataset.proMin)||0) * nu;
                sums.pmax += (Number(r.dataset.proMax)||0) * nu;
                sums.fmin += (Number(r.dataset.fatMin)||0) * nu;
                sums.fmax += (Number(r.dataset.fatMax)||0) * nu;
                sums.kj   += (Number(r.dataset.kj)   ||0) * nu;
            });
            document.getElementById('tot-cho').textContent  = sums.cho  || '—';
            document.getElementById('tot-pmin').textContent = sums.pmin || '—';
            document.getElementById('tot-pmax').textContent = sums.pmax || '—';
            document.getElementById('tot-fmin').textContent = sums.fmin || '—';
            document.getElementById('tot-fmax').textContent = sums.fmax || '—';
            document.getElementById('tot-kj').textContent   = sums.kj   || '—';

            // now compute kJ conversions for grams totals
            const factor = {cho:17, pmin:17, pmax:17, fmin:19, fmax:19};
            const kjCho  = Math.round((sums.cho  || 0) * factor.cho);
            const kjPmin = Math.round((sums.pmin || 0) * factor.pmin);
            const kjPmax = Math.round((sums.pmax || 0) * factor.pmax);
            const kjFmin = Math.round((sums.fmin || 0) * factor.fmin);
            const kjFmax = Math.round((sums.fmax || 0) * factor.fmax);
            const kjTotalMacros = kjCho + kjPmin + kjPmax + kjFmin + kjFmax;
            document.getElementById('tot-kj-cho').textContent  = kjCho || '—';
            document.getElementById('tot-kj-pmin').textContent = kjPmin || '—';
            document.getElementById('tot-kj-pmax').textContent = kjPmax || '—';
            document.getElementById('tot-kj-fmin').textContent = kjFmin || '—';
            document.getElementById('tot-kj-fmax').textContent = kjFmax || '—';
            document.getElementById('tot-kj-total').textContent = kjTotalMacros || '—';

            // ── Nutrient Analysis Summary ──────────────────────────────
            const teeKjVal  = {{ $teeKj ?: 0 }};
            const recChoG   = {{ $recCho_g  ?? 'null' }};
            const recProG   = {{ $recPro_g  ?? 'null' }};
            const recFatG   = {{ $recFat_g  ?? 'null' }};

            // actual grams: use midpoint of min/max for protein & fat
            const actChoG  = sums.cho;
            const actProG  = sums.pmin && sums.pmax ? Math.round((sums.pmin + sums.pmax) / 2) : (sums.pmin || sums.pmax || 0);
            const actFatG  = sums.fmin && sums.fmax ? Math.round((sums.fmin + sums.fmax) / 2) : (sums.fmin || sums.fmax || 0);
            // actual kJ from exchange template
            const actKj    = sums.kj;
            // % of TEE
            const actChoKj = actChoG * 17;
            const actProKj = actProG * 17;
            const actFatKj = actFatG * 37;

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

            function naPct(id, actKj, tee) {
                const el = document.getElementById(id);
                if (!el || !tee) { if(el) el.textContent = '—'; return; }
                const pct = actKj / tee * 100;
                const diff = pct - 100;
                const sign  = diff >= 0 ? '+' : '';
                const color  = diff < -0.5 ? '#b91c1c' : diff > 0.5 ? '#c2410c' : '#15803d';
                const bgColor= diff < -0.5 ? '#fee2e2' : diff > 0.5 ? '#fff7ed' : '#dcfce7';
                el.innerHTML = '<span style="font-weight:700">'+pct.toFixed(1)+'%</span>'
                             + ' <span style="display:inline-block;padding:.1rem .4rem;border-radius:999px;font-weight:700;font-size:.72rem;background:'+bgColor+';color:'+color+'">'+sign+diff.toFixed(1)+'%</span>';
            }

            naSet('na-cho-act',  actChoG,  recChoG,  false);
            naSet('na-pro-act',  actProG,  recProG,  false);
            naSet('na-fat-act',  actFatG,  recFatG,  false);
            naSet('na-kj-act',   actKj,    teeKjVal, true);

            naDiff('na-cho-diff', actChoG, recChoG);
            naDiff('na-pro-diff', actProG, recProG);
            naDiff('na-fat-diff', actFatG, recFatG);
            naDiff('na-kj-diff',  actKj,   teeKjVal);

            naPct('na-cho-pct', actChoKj, teeKjVal);
            naPct('na-pro-pct', actProKj, teeKjVal);
            naPct('na-fat-pct', actFatKj, teeKjVal);
            naPct('na-kj-pct',  actKj,    teeKjVal);
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
            updateSaveButton();
        });

        // prevent negative values
        form.addEventListener('change', function(e) {
            if (!e.target.classList.contains('meal-slot-input')) return;
            if (parseFloat(e.target.value) < 0) e.target.value = 0;
            updateRow(e.target.dataset.row);
            updateSaveButton();
        });

        // submit always allowed — partial saves are permitted
    })();
    </script>

</x-app-layout>
