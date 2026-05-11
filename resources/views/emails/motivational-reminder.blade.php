<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $variant === 1 ? 'Keep Going — Your Meal Plan Is Working' : 'Midweek Motivation — Stay On Track' }}</title>
<style>
    body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
    body { margin:0; padding:0; background:#f0f7f4; font-family:'Segoe UI',Arial,sans-serif; color:#1e293b; }
    .wrap { max-width:600px; margin:0 auto; }

    /* ── Variant 1: deep forest green ── */
    .hd-v1 { background:linear-gradient(135deg,#1a3d2b 0%,#2d5a43 55%,#4a7a60 100%); border-radius:14px 14px 0 0; }
    /* ── Variant 2: ocean blue-teal ── */
    .hd-v2 { background:linear-gradient(135deg,#0f3460 0%,#16537e 55%,#1a7a8a 100%); border-radius:14px 14px 0 0; }

    .hd-inner { padding:2.2rem 2rem 1.8rem; text-align:center; }
    .hd-icon  { font-size:2.8rem; display:block; margin-bottom:.6rem; }
    .hd h1    { color:#fff; font-size:1.35rem; font-weight:800; margin:0 0 .3rem; font-family:'Segoe UI',Arial,sans-serif; }
    .hd p     { color:rgba(255,255,255,.8); font-size:.88rem; margin:0; }

    /* ── Variant badges ── */
    .badge-v1 { background:#fff; border-bottom:2px solid #e6f2ec; padding:.55rem 2rem; text-align:center; font-size:.7rem; font-weight:700; color:#2d5a43; letter-spacing:.09em; text-transform:uppercase; }
    .badge-v2 { background:#fff; border-bottom:2px solid #e0eff8; padding:.55rem 2rem; text-align:center; font-size:.7rem; font-weight:700; color:#16537e; letter-spacing:.09em; text-transform:uppercase; }

    .bd { background:#fff; padding:2rem 2.5rem 1.5rem; }
    .bd p { font-size:.92rem; color:#2e3d30; line-height:1.8; margin:0 0 1rem; }
    .bd strong { color:#1a3d2b; }
    .divider { border:none; border-top:1px solid #e6f2ec; margin:1.4rem 0; }

    /* ── Highlight boxes ── */
    .info-v1 { background:#f0f9f4; border:1px solid #b7dfc9; border-left:4px solid #2d5a43; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.9rem; color:#1e4030; margin:1.2rem 0; line-height:1.7; }
    .info-v2 { background:#eef6fd; border:1px solid #b3d8f0; border-left:4px solid #16537e; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.9rem; color:#0f2d4a; margin:1.2rem 0; line-height:1.7; }

    /* ── Quote block ── */
    .quote-v1 { background:#f0f9f4; border-radius:10px; padding:1.1rem 1.3rem; margin:1.2rem 0; font-size:.95rem; font-style:italic; color:#1a3d2b; line-height:1.75; text-align:center; }
    .quote-v2 { background:#eef6fd; border-radius:10px; padding:1.1rem 1.3rem; margin:1.2rem 0; font-size:.95rem; font-style:italic; color:#0f3460; line-height:1.75; text-align:center; }
    .quote-attr { display:block; font-style:normal; font-size:.77rem; font-weight:700; margin-top:.55rem; opacity:.65; }

    /* ── Tip cards ── */
    .tips-grid { display:table; width:100%; border-collapse:separate; border-spacing:0; margin:1.2rem 0; }
    .tip-card  { display:table-cell; width:33.33%; vertical-align:top; padding:.75rem .65rem; text-align:center; }
    .tip-icon  { font-size:1.5rem; display:block; margin-bottom:.4rem; }
    .tip-title { font-size:.78rem; font-weight:800; color:#1a3d2b; display:block; margin-bottom:.2rem; }
    .tip-body  { font-size:.72rem; color:#4a5568; line-height:1.55; }
    .tip-card-blue .tip-title { color:#0f3460; }

    /* ── CTA button ── */
    .cta-wrap { text-align:center; margin:1.4rem 0; }
    .cta-v1 { display:inline-block; background:linear-gradient(135deg,#2d5a43,#4a7a60); color:#fff !important; text-decoration:none; padding:.75rem 2rem; border-radius:8px; font-size:.9rem; font-weight:700; letter-spacing:.02em; }
    .cta-v2 { display:inline-block; background:linear-gradient(135deg,#16537e,#1a7a8a); color:#fff !important; text-decoration:none; padding:.75rem 2rem; border-radius:8px; font-size:.9rem; font-weight:700; letter-spacing:.02em; }

    /* ── Progress pill ── */
    .prog-pill { display:inline-block; padding:.3rem .85rem; border-radius:999px; font-size:.72rem; font-weight:800; letter-spacing:.04em; text-transform:uppercase; }
    .prog-v1 { background:#dcfce7; color:#15803d; }
    .prog-v2 { background:#dbeafe; color:#1d4ed8; }

    /* ── Footer ── */
    .ft { background:#e8f5ec; padding:1.2rem 2.5rem 1.5rem; border-radius:0 0 14px 14px; text-align:center; font-size:.76rem; color:#5a7a62; line-height:1.7; }
    .ft-v2 { background:#e8f1f8; color:#3a5a72; }
    .ft a { color:#1e5c3d; text-decoration:none; font-weight:600; }
    .ft-v2 a { color:#16537e; }
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f7f4">
<tr><td align="center" style="padding:2rem 1rem">
<div class="wrap">

@if($variant === 1)
{{-- ═══════════════════════════════════════════
     VARIANT 1 — "Fuel Your Week"
     Sent on odd ISO-week numbers
═══════════════════════════════════════════ --}}

    <div class="hd hd-v1">
        <div class="hd-inner">
            <span class="hd-icon">🥗</span>
            <h1>{{ $template?->heading ?: 'Keep Going — Your Plan Is Working!' }}</h1>
            <p>Sent by {{ $dietician->name }} &mdash; {{ config('app.name') }}</p>
        </div>
    </div>

    <div class="badge-v1">&#x2665; Personalised Nutrition Reminder — Week A</div>

    <div class="bd">
        @php $tplVars = ['patient_name' => $patient->name, 'patient_full_name' => $patient->full_name, 'dietician_name' => $dietician->name]; @endphp
        @if($template?->body_html)
            {!! $template->resolveBody($tplVars) !!}
            @if($template->cta_text && $template->cta_url)
            <div class="cta-wrap" style="margin-top:1.4rem">
                <a href="{{ $template->cta_url }}" class="cta-v1">{{ $template->cta_text }}</a>
            </div>
            @endif
        @else
        <p>Hi <strong>{{ $patient->full_name }}</strong>,</p>

        <p>
            Another week, another opportunity to nourish your body and move closer to your goals.
            Every meal you plan ahead is one less decision to make under pressure &mdash; and that
            consistency is exactly what creates lasting results.
        </p>

        <div class="info-v1">
            💡 <strong>This week&rsquo;s focus:</strong> Consistency over perfection.
            You don&rsquo;t need to eat perfectly &mdash; you just need to eat <em>intentionally</em>.
            Each mindful choice builds the habit that changes your health long-term.
        </div>

        <hr class="divider">

        {{-- Tip cards --}}
        <table class="tips-grid">
            <tr>
                <td class="tip-card">
                    <span class="tip-icon">🍳</span>
                    <span class="tip-title">Start with Breakfast</span>
                    <span class="tip-body">A balanced breakfast kickstarts your metabolism and reduces mid-morning cravings.</span>
                </td>
                <td class="tip-card">
                    <span class="tip-icon">🥦</span>
                    <span class="tip-title">Colour Your Plate</span>
                    <span class="tip-body">Aim for 3 different colours per meal — variety means more micronutrients.</span>
                </td>
                <td class="tip-card">
                    <span class="tip-icon">📋</span>
                    <span class="tip-title">Prep One Meal</span>
                    <span class="tip-body">Batch-prepping even one meal ahead of time saves you from poor choices when you&rsquo;re tired.</span>
                </td>
            </tr>
        </table>

        <hr class="divider">

        <div class="quote-v1">
            &ldquo;It&rsquo;s not about being perfect. It&rsquo;s about making better choices, more often, and celebrating the small wins.&rdquo;
            <span class="quote-attr">— Your Dietitian, {{ $dietician->name }}</span>
        </div>

        <p style="font-size:.83rem;color:#64748b;margin-top:1rem">
            If you have any questions about your meal plan, or need adjustments made, please reach out to
            <strong>{{ $dietician->name }}</strong> directly.
        </p>
        @endif
    </div>

    <div class="ft">
        <p>
            You are receiving this because your dietician enabled motivational reminders for you.<br>
            &copy; {{ date('Y') }} {{ config('app.name') }} &mdash; All rights reserved.
        </p>
    </div>

@else
{{-- ═══════════════════════════════════════════
     VARIANT 2 — "Stay Strong"
     Sent on even ISO-week numbers
═══════════════════════════════════════════ --}}

    <div class="hd hd-v2">
        <div class="hd-inner">
            <span class="hd-icon">💧</span>
            <h1>{{ $template?->heading ?: 'Hydrate, Rest, Repeat — You\'ve Got This!' }}</h1>
            <p>Sent by {{ $dietician->name }} &mdash; {{ config('app.name') }}</p>
        </div>
    </div>

    <div class="badge-v2">&#x2665; Personalised Nutrition Reminder — Week B</div>

    <div class="bd">
        @php $tplVars = ['patient_name' => $patient->name, 'patient_full_name' => $patient->full_name, 'dietician_name' => $dietician->name]; @endphp
        @if($template?->body_html)
            {!! $template->resolveBody($tplVars) !!}
            @if($template->cta_text && $template->cta_url)
            <div class="cta-wrap" style="margin-top:1.4rem">
                <a href="{{ $template->cta_url }}" class="cta-v2">{{ $template->cta_text }}</a>
            </div>
            @endif
        @else
        <p style="color:#1e2d40">Hi <strong>{{ $patient->full_name }}</strong>,</p>

        <p style="color:#2e3d50">
            Halfway through another week &mdash; take a moment to acknowledge the effort you&rsquo;ve put in.
            Progress isn&rsquo;t always visible on the scale, but every good food choice, every glass of water,
            and every night of good sleep is building a healthier you.
        </p>

        <div class="info-v2">
            💧 <strong>This week&rsquo;s focus:</strong> Hydration is nutrition too.
            Water supports digestion, regulates appetite, and keeps your energy stable.
            Aim for <strong>8&ndash;10 glasses</strong> daily — more if you are active or in warm weather.
        </div>

        <hr class="divider">

        {{-- Tip cards --}}
        <table class="tips-grid">
            <tr>
                <td class="tip-card tip-card-blue">
                    <span class="tip-icon">🛌</span>
                    <span class="tip-title" style="color:#0f3460">Prioritise Sleep</span>
                    <span class="tip-body">Poor sleep increases hunger hormones (ghrelin). 7–8 hours helps regulate appetite naturally.</span>
                </td>
                <td class="tip-card tip-card-blue">
                    <span class="tip-icon">🍽️</span>
                    <span class="tip-title" style="color:#0f3460">Mindful Eating</span>
                    <span class="tip-body">Eat slowly and without screens. It takes 20 minutes for your brain to register fullness.</span>
                </td>
                <td class="tip-card tip-card-blue">
                    <span class="tip-icon">🏃</span>
                    <span class="tip-title" style="color:#0f3460">Move Your Body</span>
                    <span class="tip-body">Even a 20-minute walk after dinner improves blood sugar response and mood.</span>
                </td>
            </tr>
        </table>

        <hr class="divider">

        <div class="quote-v2">
            &ldquo;Small steps every day add up to big changes. Trust the process, honour your body, and keep going.&rdquo;
            <span class="quote-attr" style="color:#16537e;opacity:.7">— Your Dietitian, {{ $dietician->name }}</span>
        </div>

        <p style="font-size:.83rem;color:#64748b;margin-top:1rem">
            Questions about your nutrition journey? Don&rsquo;t hesitate to contact
            <strong>{{ $dietician->name }}</strong> — your dietician is here to support you.
        </p>
        @endif
    </div>

    <div class="ft ft-v2">
        <p>
            You are receiving this because your dietician enabled motivational reminders for you.<br>
            &copy; {{ date('Y') }} {{ config('app.name') }} &mdash; All rights reserved.
        </p>
    </div>

@endif

</div>
</td></tr>
</table>
</body>
</html>
