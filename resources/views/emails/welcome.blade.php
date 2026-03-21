<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Mindfulnutrico Dietitians App</title>
    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
        table, td { mso-table-lspace:0; mso-table-rspace:0; }
        img { -ms-interpolation-mode:bicubic; border:0; outline:none; text-decoration:none; }
        /* General */
        body { margin:0; padding:0; background:#f0f7f4; font-family:'Segoe UI',Arial,sans-serif; }
        .wrap { max-width:600px; margin:0 auto; }
        /* Header */
        .hd { background:linear-gradient(135deg,#0d3320 0%,#1e5c3d 55%,#3a8c5f 100%); padding:0; text-align:center; border-radius:14px 14px 0 0; }
        .hd-inner { padding:2.4rem 2rem 2rem; }
        .hd-logo { margin-bottom:1.1rem; }
        .hd-logo img { height:56px; width:auto; display:block; margin:0 auto; }
        .hd h1 { color:#fff; font-size:1.45rem; font-weight:800; margin:0 0 .3rem; letter-spacing:-.02em; line-height:1.25; }
        .hd p  { color:rgba(255,255,255,.78); font-size:.88rem; margin:0; }
        /* Badge strip */
        .badge-strip { background:#fff; border-bottom:2px solid #e6f2ec; padding:.6rem 2rem; text-align:center; font-size:.72rem; font-weight:700; color:#429677; letter-spacing:.09em; text-transform:uppercase; }
        /* Body */
        .bd { background:#fff; padding:2rem 2.5rem 1.5rem; }
        .bd p { font-size:.93rem; color:#2e3d30; line-height:1.78; margin:0 0 1rem; }
        .bd strong { color:#0d3320; }
        .divider { border:none; border-top:1px solid #e6f2ec; margin:1.4rem 0; }
        /* Section label */
        .sec-label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#3a8c5f; margin:0 0 .9rem; }
        /* Feature cards */
        .feat-table { width:100%; border-collapse:separate; border-spacing:0 .5rem; margin-bottom:.5rem; }
        .feat-table td.icon-cell { width:42px; vertical-align:top; }
        .feat-icon { width:38px; height:38px; border-radius:50%; background:#e8f5ec; text-align:center; line-height:38px; font-size:1.1rem; }
        .feat-table td.text-cell { padding-left:.8rem; vertical-align:top; font-size:.88rem; color:#2e3d30; line-height:1.6; }
        .feat-table td.text-cell strong { color:#0d3320; display:block; margin-bottom:.1rem; }
        /* Steps */
        .steps-table { width:100%; border-collapse:separate; border-spacing:0 .4rem; margin-bottom:1rem; }
        .step-num { width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg,#3a8c5f,#429677); color:#fff; font-size:.8rem; font-weight:800; text-align:center; line-height:28px; flex-shrink:0; }
        .steps-table td.num-cell { width:36px; vertical-align:top; text-align:center; }
        .steps-table td.step-text { padding-left:.75rem; font-size:.88rem; color:#2e3d30; line-height:1.6; vertical-align:top; padding-top:3px; }
        .steps-table td.step-text strong { color:#0d3320; }
        /* Tip box */
        .tip { background:#f0f9f4; border:1px solid #b7dfc9; border-left:4px solid #3a8c5f; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.875rem; color:#1e4030; margin:1.2rem 0; line-height:1.65; }
        /* CTA button */
        .cta-wrap { text-align:center; margin:1.6rem 0 1rem; }
        .cta-btn { display:inline-block; padding:.9rem 2.4rem; background:linear-gradient(135deg,#1e5c3d,#3a8c5f); color:#fff !important; text-decoration:none; border-radius:9px; font-weight:800; font-size:.97rem; letter-spacing:.01em; }
        /* Logo badge */
        .logo-badge { text-align:center; padding:1.2rem 2rem .5rem; }
        .logo-badge img { height:36px; width:auto; opacity:.55; }
        /* Footer */
        .ft { background:#e8f5ec; padding:1.2rem 2.5rem 1.5rem; border-radius:0 0 14px 14px; text-align:center; font-size:.76rem; color:#5a7a62; line-height:1.7; }
        .ft a { color:#1e5c3d; text-decoration:none; font-weight:600; }
        .ft .support { display:inline-block; margin-top:.5rem; background:#fff; border:1px solid #b7dfc9; border-radius:6px; padding:.3rem .9rem; font-weight:700; color:#1e5c3d; }
    </style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f7f4">
<tr><td align="center" style="padding:2rem 1rem">
<div class="wrap">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="hd">
        <div class="hd-inner">
            <div class="hd-logo">
                <img src="{{ $logoUrl }}" alt="Mindfulnutrico logo">
            </div>
            <h1>Welcome to Mindfulnutrico<br>Dietitians App</h1>
            <p>Hello, Dietitian! Mindfulnutrico welcomes you!</p>
        </div>
    </div>

    <div class="badge-strip">✦ Your account is ready ✦</div>

    {{-- ── Body ───────────────────────────────────────────────── --}}
    <div class="bd">
        <p>Hi <strong>{{ $userName }}</strong>,</p>
        <p>
            We're <strong>thrilled</strong> to have you with us! Mindfulnutrico is your
            ultimate tool to streamline your practice and deliver quality care to your clients.
        </p>

        <hr class="divider">

        {{-- What the app offers --}}
        <p class="sec-label">⭐ What the App Offers</p>
        <table class="feat-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="icon-cell"><div class="feat-icon">🧮</div></td>
                <td class="text-cell"><strong>Nutritional Requirements</strong>Quick, accurate calculations based on client information.</td>
            </tr>
            <tr>
                <td class="icon-cell"><div class="feat-icon">🥗</div></td>
                <td class="text-cell"><strong>Personalised Meal Plans</strong>Tailored meal plans suitable for any condition or preference.</td>
            </tr>
            <tr>
                <td class="icon-cell"><div class="feat-icon">📋</div></td>
                <td class="text-cell"><strong>Nutrition Prescriptions</strong>Professional, safe and custom to each client's needs.</td>
            </tr>
            <tr>
                <td class="icon-cell"><div class="feat-icon">📈</div></td>
                <td class="text-cell"><strong>Track Client Progress</strong>Monitor outcomes and keep track of data with ease.</td>
            </tr>
            <tr>
                <td class="icon-cell"><div class="feat-icon">🎨</div></td>
                <td class="text-cell"><strong>Your Brand, Your Logo</strong>Add your own logo to customise all materials shared with clients.</td>
            </tr>
        </table>

        <hr class="divider">

        {{-- How to use --}}
        <p class="sec-label">🚀 How to Get Started</p>
        <p style="font-size:.82rem;color:#5a7a62;margin-top:-.4rem">Register for your preferred package, then follow these steps:</p>
        <table class="steps-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="num-cell"><div class="step-num">1</div></td>
                <td class="step-text"><strong>Add a Client</strong> — Enter basic info to start.</td>
            </tr>
            <tr>
                <td class="num-cell"><div class="step-num">2</div></td>
                <td class="step-text"><strong>Calculate Requirements</strong> — Get nutrition needs instantly.</td>
            </tr>
            <tr>
                <td class="num-cell"><div class="step-num">3</div></td>
                <td class="step-text"><strong>Build a Meal Plan</strong> — Generate meals based on calculations.</td>
            </tr>
            <tr>
                <td class="num-cell"><div class="step-num">4</div></td>
                <td class="step-text"><strong>Create a Prescription</strong> — Print or share nutrition plans easily.</td>
            </tr>
            <tr>
                <td class="num-cell"><div class="step-num">5</div></td>
                <td class="step-text"><strong>Track Progress</strong> — View client progress and updates all in one place.</td>
            </tr>
        </table>

        <div class="tip">
            💡 <strong>Tip:</strong> Mindfulnutrico is designed for <em>efficiency, accuracy, and ease</em>.
            Explore the features and let us support you in providing the best care for your clients!
        </div>

        {{-- CTA --}}
        <div class="cta-wrap">
            <a href="{{ $dashboardUrl }}" class="cta-btn">Go to My Dashboard →</a>
        </div>

        <hr class="divider">
        <p style="font-size:.84rem;color:#5a7a62;text-align:center;margin:0">
            Need help? Our support team is always here for you.
        </p>
    </div>

    {{-- ── Footer ─────────────────────────────────────────────── --}}
    <div class="ft">
        <div class="logo-badge">
            <img src="{{ $logoUrl }}" alt="Mindfulnutrico">
        </div>
        <p style="margin:.4rem 0 .2rem">
            <strong>The Mindfulnutrico Team!</strong><br>
            We can't wait for you to get started!
        </p>
        <p style="margin:.6rem 0 .2rem">Questions? Contact us:</p>
        <a href="mailto:support@mindfulnutrico.co.za" class="support">support@mindfulnutrico.co.za</a>
        <p style="margin:.9rem 0 0;font-size:.72rem;color:#8aaa90">
            You received this email because you registered at
            <a href="{{ $dashboardUrl }}">mindfulnutrico.co.za</a>.<br>
            © {{ date('Y') }} Mindfulnutrico · All rights reserved.
        </p>
    </div>

</div>
</td></tr>
</table>
</body>
</html>
