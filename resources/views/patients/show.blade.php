<x-app-layout>

    {{-- ═══════════════════════════════════════════
         PATIENT PROFILE HERO
    ═══════════════════════════════════════════ --}}
    @php
        $initials = collect(explode(' ', $patient->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
        $bmiCat   = strtolower($patient->bmi_category ?? 'normal');
        $teeKcal  = $patient->tee ? round($patient->tee / 4.184) : null;
        $teeKj    = ($patient->tee ?? 0) * 4.184;

        $macroColors = [
            'carbohydrate' => ['dot'=>'#f97316','bg'=>'rgba(249,115,22,.12)','text'=>'#c2410c'],
            'protein'      => ['dot'=>'#6366f1','bg'=>'rgba(99,102,241,.12)', 'text'=>'#4338ca'],
            'fat'          => ['dot'=>'#14b8a6','bg'=>'rgba(20,184,166,.12)',  'text'=>'#0f766e'],
        ];
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
                <div class="mc-val">{{ $patient->bmr ? number_format($patient->bmr, 0) : '—' }}</div>
                <div class="mc-label">BMR</div>
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
                        <div class="info-item"><dt>ABW</dt><dd>{{ $patient->abw ? number_format($patient->abw, 2).' kg' : '—' }}</dd></div>
                        <div class="info-item"><dt>Activity Factor</dt><dd>{{ $patient->activity_factor }}</dd></div>
                        <div class="info-item"><dt>BMR / RMR</dt><dd>{{ $patient->bmr ? number_format($patient->bmr, 0).' kJ/day' : '—' }}</dd></div>
                        <div class="info-item"><dt>TEE</dt><dd>{{ $patient->tee ? number_format($patient->tee, 0).' kJ/day' : '—' }}</dd></div>
                        <div class="info-item"><dt>TEE (kcal)</dt><dd>{{ $teeKcal ? number_format($teeKcal).' kcal' : '—' }}</dd></div>
                    </dl>
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
        </div>
        @endif

        {{-- ═══════════════════════════════════════════
             MEAL PLAN DISTRIBUTION (uses nu values)
        ═══════════════════════════════════════════ --}}
        @if($patient->exchangeTemplate)
        <details class="mt-6">
            <summary class="font-semibold cursor-pointer py-2">Meal Plan ▾</summary>
            <div class="dash-section">
            <div class="dash-section-header">
                <span class="dash-section-title">Meal Plan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="exchange-table">
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
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->exchangeTemplate->items as $item)
                        @php
                            // simple sequential allocation of nu across six meal slots
                            $slots = ['b','s1','l','s2','su','s3'];
                            $remain = $item->nu;
                            $plan = array_fill_keys($slots, 0);
                            foreach($slots as $slot) {
                                if($remain <= 0) break;
                                if($remain >= 1) {
                                    $plan[$slot] = 1;
                                    $remain -= 1;
                                } else {
                                    $plan[$slot] = $remain;
                                    $remain = 0;
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ Str::limit($item->name, 5) }}</td>
                            <td>{{ $item->nu }}</td>
                            <td>{{ $plan['b'] ?: '' }}</td>
                            <td>{{ $plan['s1'] ?: '' }}</td>
                            <td>{{ $plan['l'] ?: '' }}</td>
                            <td>{{ $plan['s2'] ?: '' }}</td>
                            <td>{{ $plan['su'] ?: '' }}</td>
                            <td>{{ $plan['s3'] ?: '' }}</td>
                            <td>{{ $item->nu }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
    </script>

</x-app-layout>
