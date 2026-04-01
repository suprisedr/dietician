<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Mindfulnutrico Account Has Been Activated</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
        table, td { mso-table-lspace:0; mso-table-rspace:0; }
        img { -ms-interpolation-mode:bicubic; border:0; outline:none; text-decoration:none; }
        body { margin:0; padding:0; background:#f0f7f4; font-family:'Segoe UI',Arial,sans-serif; }
        .wrap { max-width:600px; margin:0 auto; }
        .hd { background:linear-gradient(135deg,#0d3320 0%,#1e5c3d 55%,#3a8c5f 100%); padding:0; text-align:center; border-radius:14px 14px 0 0; }
        .hd-inner { padding:2.2rem 2rem 1.8rem; }
        .hd h1 { color:#fff; font-size:1.35rem; font-weight:800; margin:0 0 .3rem; letter-spacing:-.02em; line-height:1.25; }
        .hd p  { color:rgba(255,255,255,.78); font-size:.88rem; margin:0; }
        .badge-strip { background:#d1fae5; border-bottom:2px solid #34d399; padding:.6rem 2rem; text-align:center; font-size:.72rem; font-weight:700; color:#065f46; letter-spacing:.09em; text-transform:uppercase; }
        .bd { background:#fff; padding:2rem 2.5rem 1.5rem; }
        .bd p { font-size:.93rem; color:#2e3d30; line-height:1.78; margin:0 0 1rem; }
        .bd strong { color:#0d3320; }
        .divider { border:none; border-top:1px solid #e6f2ec; margin:1.4rem 0; }
        .sec-label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#3a8c5f; margin:0 0 .9rem; }
        .info-table { width:100%; border-collapse:collapse; margin-bottom:1rem; border:1px solid #e6f2ec; border-radius:8px; overflow:hidden; }
        .info-table td { padding:.65rem 1rem; font-size:.9rem; border-bottom:1px solid #e6f2ec; vertical-align:top; }
        .info-table td:first-child { font-weight:700; color:#1e4030; background:#f0f9f4; width:38%; white-space:nowrap; }
        .info-table td:last-child { color:#2e3d30; word-break:break-all; }
        .info-table tr:last-child td { border-bottom:none; }
        .cta-wrap { text-align:center; margin:1.6rem 0 .8rem; }
        .cta-btn { display:inline-block; padding:.9rem 2.4rem; background:linear-gradient(135deg,#1e5c3d,#3a8c5f); color:#fff !important; text-decoration:none; border-radius:9px; font-weight:800; font-size:.95rem; }
        .success-box { background:#d1fae5; border:1px solid #34d399; border-left:4px solid #059669; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.875rem; color:#064e3b; margin:1.2rem 0; line-height:1.65; }
        .ft { background:#e8f5ec; padding:1.2rem 2.5rem 1.5rem; border-radius:0 0 14px 14px; text-align:center; font-size:.76rem; color:#5a7a62; line-height:1.7; }
        .ft a { color:#1e5c3d; text-decoration:none; font-weight:600; }
    </style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f7f4">
<tr><td align="center" style="padding:2rem 1rem">
<div class="wrap">

    {{-- Header --}}
    <div class="hd">
        <div class="hd-inner">
            <h1>Account Activated ✓</h1>
            <p>Your Mindfulnutrico dietician account is now unlocked</p>
        </div>
    </div>

    <div class="badge-strip">✅ HPCSA Number Verified — Account Unlocked</div>

    {{-- Body --}}
    <div class="bd">
        <p>Hi <strong>{{ $dietician->name }}</strong>,</p>

        <p>Great news! Our admin team has verified your HPCSA registration number and your <strong>Mindfulnutrico Dietitians App</strong> account is now fully activated. You can log in and start using all features immediately.</p>

        <div class="success-box">
            ✅ <strong>HPCSA number <em>{{ $dietician->dietician_number }}</em> has been confirmed</strong> against the official HPCSA iRegister. Your account is active.
        </div>

        <hr class="divider">

        <p class="sec-label">📋 Account Details</p>
        <table class="info-table" cellpadding="0" cellspacing="0">
            <tr>
                <td>Full Name</td>
                <td>{{ $dietician->name }}</td>
            </tr>
            <tr>
                <td>Email Address</td>
                <td>{{ $dietician->email }}</td>
            </tr>
            <tr>
                <td>HPCSA Number</td>
                <td><strong>{{ $dietician->dietician_number }}</strong></td>
            </tr>
            <tr>
                <td>Verified At</td>
                <td>{{ $dietician->admin_verified_at->format('d M Y, H:i') }}</td>
            </tr>
        </table>

        <hr class="divider">

        <p class="sec-label">🚀 Get Started</p>
        <p>Click the button below to log in to your account:</p>

        <div class="cta-wrap">
            <a href="{{ route('login') }}" class="cta-btn">Log In to Mindfulnutrico →</a>
        </div>

        <hr class="divider">
        <p style="font-size:.84rem;color:#5a7a62;text-align:center;margin:0">
            If you did not register for this account or have any concerns, please contact
            <a href="mailto:support@mindfulnutrico.co.za" style="color:#1e5c3d;font-weight:600;">support@mindfulnutrico.co.za</a>.
        </p>
    </div>

    {{-- Footer --}}
    <div class="ft">
        <p style="margin:.4rem 0 .2rem">
            <strong>The Mindfulnutrico Team</strong>
        </p>
        <p style="margin:.2rem 0">
            Questions? <a href="mailto:support@mindfulnutrico.co.za">support@mindfulnutrico.co.za</a>
        </p>
        <p style="margin:.5rem 0 0;font-size:.7rem;color:#8aab92">
            This email was sent because your Mindfulnutrico account was verified by an administrator.
        </p>
    </div>

</div>
</td></tr>
</table>
</body>
</html>
