<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consent to Capture and Process Personal Health Information</title>
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
        .badge-strip { background:#fff; border-bottom:2px solid #e6f2ec; padding:.6rem 2rem; text-align:center; font-size:.72rem; font-weight:700; color:#429677; letter-spacing:.09em; text-transform:uppercase; }
        .bd { background:#fff; padding:2rem 2.5rem 1.5rem; }
        .bd p { font-size:.93rem; color:#2e3d30; line-height:1.78; margin:0 0 1rem; }
        .bd strong { color:#0d3320; }
        .divider { border:none; border-top:1px solid #e6f2ec; margin:1.4rem 0; }
        .sec-label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#3a8c5f; margin:0 0 .9rem; }
        .info-box { background:#f0f9f4; border:1px solid #b7dfc9; border-left:4px solid #3a8c5f; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.9rem; color:#1e4030; margin:1.2rem 0; line-height:1.65; }
        .consent-list { padding-left:1.25rem; margin:.5rem 0 1rem; }
        .consent-list li { font-size:.9rem; color:#2e3d30; line-height:1.75; margin-bottom:.35rem; }
        .rights-table { width:100%; border-collapse:collapse; margin-bottom:1rem; }
        .rights-table td { padding:.55rem .9rem; font-size:.86rem; border-bottom:1px solid #e6f2ec; vertical-align:top; color:#2e3d30; line-height:1.6; }
        .rights-table td:first-child { font-weight:700; color:#1e4030; width:36%; background:#f9f9f9; }
        .rights-table tr:last-child td { border-bottom:none; }
        .ft { background:#e8f5ec; padding:1.2rem 2.5rem 1.5rem; border-radius:0 0 14px 14px; text-align:center; font-size:.76rem; color:#5a7a62; line-height:1.7; }
        .ft a { color:#1e5c3d; text-decoration:none; font-weight:600; }
        .ft .support { display:inline-block; margin-top:.5rem; background:#fff; border:1px solid #b7dfc9; border-radius:6px; padding:.3rem .9rem; font-weight:700; color:#1e5c3d; }
    </style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f7f4">
<tr><td align="center" style="padding:2rem 1rem">
<div class="wrap">

    {{-- Header --}}
    <div class="hd">
        <div class="hd-inner">
            <h1>Consent to Capture &amp;<br>Process Personal Health Information</h1>
            <p>In accordance with the Protection of Personal Information Act (POPIA)</p>
        </div>
    </div>

    <div class="badge-strip">✦ POPIA Compliance Notice ✦</div>

    {{-- Body --}}
    <div class="bd">
        <p>Dear <strong>{{ $patient->name }}{{ $patient->surname ? ' ' . $patient->surname : '' }}</strong>,</p>

        <p>
            Your dietician, <strong>{{ $dietician->name }}</strong>, has registered your personal and health
            information on the <strong>Mindfulnutrico Dietitians App</strong> as part of your dietetic
            assessment and nutritional care programme.
        </p>

        <p>
            Under the <strong>Protection of Personal Information Act (POPIA)</strong>, we are required to
            obtain your explicit consent before your information can be used to provide dietetic services.
            Please review the information below and click <strong>Grant Consent</strong> to confirm you
            agree to your information being managed on this platform.
        </p>

        <div class="info-box" style="background:#fff8e1;border-color:#fbc02d;border-left-color:#f57f17;color:#5d4037">
            &#x23F0; <strong>Action required:</strong> This consent link expires <strong>72 hours</strong> after it was sent.
            If it has expired, ask your dietician to resend it.
        </div>

        <div style="text-align:center;margin:1.8rem 0 .8rem">
            <a href="{{ $consentLink }}" style="display:inline-block;background:#1e5c3d;color:#fff;font-family:'Segoe UI',Arial,sans-serif;font-size:1rem;font-weight:700;text-decoration:none;padding:.9rem 2.4rem;border-radius:8px;letter-spacing:.03em">&#x2714; Grant Consent</a>
        </div>
        <p style="text-align:center;font-size:.77rem;color:#64748b">Or copy this link: <a href="{{ $consentLink }}" style="color:#1e5c3d;word-break:break-all">{{ $consentLink }}</a></p>

        <hr class="divider">

        <div class="info-box">
            &#x1F4CB; <strong>Dietician:</strong> {{ $dietician->name }}<br>
            &#x1F4C5; <strong>Date of capture:</strong> {{ now()->format('d F Y') }}
        </div>
            📋 <strong>Dietician:</strong> {{ $dietician->name }}<br>
            📅 <strong>Date of capture:</strong> {{ now()->format('d F Y') }}
        </div>

        <hr class="divider">

        {{-- What is collected --}}
        <p class="sec-label">📂 Information Collected</p>
        <p>The following categories of personal and health information may be collected and processed:</p>
        <ul class="consent-list">
            <li>Personal identifiers (name, date of birth, ID/passport number, contact details)</li>
            <li>Physical measurements (weight, height, BMI, and related body composition data)</li>
            <li>Dietary and nutritional assessment data</li>
            <li>Medical history relevant to your dietetic care (reason for assessment, clinical notes)</li>
            <li>Progress records and follow-up visit data</li>
        </ul>

        <hr class="divider">

        {{-- Purpose --}}
        <p class="sec-label">🎯 Purpose of Processing</p>
        <p>Your information is collected and used for the following purposes:</p>
        <ul class="consent-list">
            <li>To calculate and provide personalised nutritional recommendations</li>
            <li>To generate nutrition prescriptions and meal plans tailored to your needs</li>
            <li>To monitor your health and dietary progress over time</li>
            <li>To produce clinical reports for your records or healthcare team (with your consent)</li>
        </ul>

        <hr class="divider">

        {{-- Your rights --}}
        <p class="sec-label">🛡 Your Rights Under POPIA</p>
        <p>As a data subject, you have the following rights regarding your personal information:</p>
        <table class="rights-table" cellpadding="0" cellspacing="0">
            <tr>
                <td>Right of Access</td>
                <td>You may request a copy of the personal information held about you.</td>
            </tr>
            <tr>
                <td>Right to Correct</td>
                <td>You may request correction of inaccurate or incomplete information.</td>
            </tr>
            <tr>
                <td>Right to Delete</td>
                <td>You may request deletion of your information, subject to legal retention requirements.</td>
            </tr>
            <tr>
                <td>Right to Object</td>
                <td>You may object to the processing of your information in certain circumstances.</td>
            </tr>
            <tr>
                <td>Right to Complain</td>
                <td>You may lodge a complaint with the Information Regulator of South Africa if you believe your rights have been violated.</td>
            </tr>
        </table>

        <hr class="divider">

        {{-- Confidentiality --}}
        <p class="sec-label">🔒 Confidentiality &amp; Data Security</p>
        <p>
            Your information is treated with strict confidentiality in accordance with POPIA and professional
            dietetic ethical standards. Data is stored securely and will not be shared with any third party
            without your explicit consent, except where required by law.
        </p>

        <hr class="divider">
        <p style="font-size:.84rem;color:#5a7a62;text-align:center;margin:0">
            If you have any questions about how your information is used, or wish to exercise any of your
            rights above, please contact your dietician, <strong>{{ $dietician->name }}</strong>, directly.
        </p>
    </div>

    {{-- Footer --}}
    <div class="ft">
        <p style="margin:.4rem 0 .2rem">
            <strong>The Mindfulnutrico Team</strong>
        </p>
        <p style="margin:.6rem 0 .2rem">Platform support:</p>
        <a href="mailto:support@mindfulnutrico.co.za" class="support">support@mindfulnutrico.co.za</a>
        <p style="margin:.9rem 0 0;font-size:.72rem;color:#8aaa90">
            You received this because your dietician registered your records on the Mindfulnutrico platform.<br>
            If you did not expect this email, please ignore it or contact your healthcare provider.<br>
            © {{ date('Y') }} Mindfulnutrico · All rights reserved.
        </p>
    </div>

</div>
</td></tr>
</table>
</body>
</html>
