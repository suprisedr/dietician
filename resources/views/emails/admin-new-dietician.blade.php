<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Dietician Registration — HPCSA Verification Required</title>
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
        .badge-strip { background:#fff3cd; border-bottom:2px solid #ffc107; padding:.6rem 2rem; text-align:center; font-size:.72rem; font-weight:700; color:#856404; letter-spacing:.09em; text-transform:uppercase; }
        .bd { background:#fff; padding:2rem 2.5rem 1.5rem; }
        .bd p { font-size:.93rem; color:#2e3d30; line-height:1.78; margin:0 0 1rem; }
        .bd strong { color:#0d3320; }
        .divider { border:none; border-top:1px solid #e6f2ec; margin:1.4rem 0; }
        .sec-label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#3a8c5f; margin:0 0 .9rem; }
        .info-table { width:100%; border-collapse:collapse; margin-bottom:1rem; border:1px solid #e6f2ec; border-radius:8px; overflow:hidden; }
        .info-table td { padding:.65rem 1rem; font-size:.9rem; border-bottom:1px solid #e6f2ec; vertical-align:top; }
        .info-table td:first-child { font-weight:700; color:#1e4030; background:#f0f9f4; width:36%; white-space:nowrap; }
        .info-table td:last-child { color:#2e3d30; word-break:break-all; }
        .info-table tr:last-child td { border-bottom:none; }
        .alert-box { background:#fff3cd; border:1px solid #ffc107; border-left:4px solid #e6a817; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.875rem; color:#533f03; margin:1.2rem 0; line-height:1.65; }
        .cta-wrap { text-align:center; margin:1.6rem 0 .8rem; }
        .cta-btn { display:inline-block; padding:.9rem 2rem; background:linear-gradient(135deg,#1e5c3d,#3a8c5f); color:#fff !important; text-decoration:none; border-radius:9px; font-weight:800; font-size:.95rem; }
        .cta-secondary { display:inline-block; margin-top:.8rem; padding:.7rem 1.6rem; background:#fff; border:2px solid #3a8c5f; color:#1e5c3d !important; text-decoration:none; border-radius:9px; font-weight:700; font-size:.88rem; }
        .ft { background:#e8f5ec; padding:1.2rem 2.5rem 1.5rem; border-radius:0 0 14px 14px; text-align:center; font-size:.76rem; color:#5a7a62; line-height:1.7; }
        .ft a { color:#1e5c3d; text-decoration:none; font-weight:600; }
        .ft .support { display:inline-block; margin-top:.5rem; background:#fff; border:1px solid #b7dfc9; border-radius:6px; padding:.3rem .9rem; font-weight:700; color:#1e5c3d; }
        .url-wrap { word-break:break-all; font-size:.78rem; color:#5a7a62; background:#f8f8f8; border:1px solid #e0e0e0; border-radius:6px; padding:.5rem .8rem; margin-top:.5rem; }
    </style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f7f4">
<tr><td align="center" style="padding:2rem 1rem">
<div class="wrap">

    {{-- Header --}}
    <div class="hd">
        <div class="hd-inner">
            <h1>New Dietician Registration<br>HPCSA Verification Required</h1>
            <p>Action required — please verify this dietician's registration</p>
        </div>
    </div>

    <div class="badge-strip">⚠ Pending Admin Verification ⚠</div>

    {{-- Body --}}
    <div class="bd">
        <p>A new dietician has registered on the <strong>Mindfulnutrico Dietitians App</strong> and requires HPCSA verification before their account is activated.</p>

        <hr class="divider">

        {{-- Dietician details --}}
        <p class="sec-label">📋 Dietician Details</p>
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
                <td>Registered At</td>
                <td>{{ $dietician->created_at->format('d M Y, H:i') }}</td>
            </tr>
        </table>

        <hr class="divider">

        {{-- HPCSA verification step --}}
        <p class="sec-label">🔍 Step 1 — Verify on HPCSA iRegister</p>
        <p>Search for the HPCSA number <strong>{{ $dietician->dietician_number }}</strong> on the official HPCSA iRegister to confirm this dietician holds a valid, active registration:</p>

        <div class="cta-wrap" style="margin-bottom:.5rem">
            <a href="{{ $hpcsaLookupUrl }}" class="cta-secondary" target="_blank">Open HPCSA iRegister →</a>
        </div>
        <p style="font-size:.78rem;color:#5a7a62;text-align:center;margin:.3rem 0 1rem">
            {{ $hpcsaLookupUrl }}
        </p>

        <hr class="divider">

        {{-- Approve step --}}
        <p class="sec-label">✅ Step 2 — Approve the Account</p>
        <p>Once you have confirmed the HPCSA registration is valid, click the button below to activate this dietician's account:</p>

        <div class="cta-wrap">
            <a href="{{ $verifyUrl }}" class="cta-btn">Approve &amp; Activate Account →</a>
        </div>

        <div class="alert-box">
            ⚠ <strong>Important:</strong> This verification link is unique to this dietician. Do <em>not</em> activate the account if the HPCSA registration is invalid, expired, or cannot be found. The link will remain valid — you can return to this email at any time.
        </div>

        <p style="font-size:.82rem;color:#5a7a62">If the button above does not work, copy and paste this URL into your browser:</p>
        <div class="url-wrap">{{ $verifyUrl }}</div>

        <hr class="divider">
        <p style="font-size:.84rem;color:#5a7a62;text-align:center;margin:0">
            This notification was sent automatically when a new user registered on the Mindfulnutrico platform.
        </p>
    </div>

    {{-- Footer --}}
    <div class="ft">
        <p style="margin:.4rem 0 .2rem">
            <strong>The Mindfulnutrico Team</strong>
        </p>
        <p style="margin:.6rem 0 .2rem">Questions? Contact us:</p>
        <a href="mailto:support@mindfulnutrico.co.za" class="support">support@mindfulnutrico.co.za</a>
        <p style="margin:.9rem 0 0;font-size:.72rem;color:#8aaa90">
            © {{ date('Y') }} Mindfulnutrico · All rights reserved.
        </p>
    </div>

</div>
</td></tr>
</table>
</body>
</html>
