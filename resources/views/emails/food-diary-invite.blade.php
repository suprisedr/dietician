<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daily Food Diary</title>
<style>
    body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
    body { margin:0; padding:0; background:#f0f7f4; font-family:'Segoe UI',Arial,sans-serif; color:#1e293b; }
    .wrap { max-width:580px; margin:0 auto; }

    .hd { background:linear-gradient(135deg,#1a3d2b 0%,#2d5a43 55%,#4a7a60 100%); border-radius:14px 14px 0 0; }
    .hd-inner { padding:2.2rem 2rem 1.8rem; text-align:center; }
    .hd-icon { font-size:2.5rem; display:block; margin-bottom:.6rem; }
    .hd h1 { color:#fff; font-size:1.3rem; font-weight:800; margin:0 0 .3rem; font-family:'Segoe UI',Arial,sans-serif; }
    .hd p  { color:rgba(255,255,255,.8); font-size:.88rem; margin:0; }

    .badge-strip { background:#fff; border-bottom:2px solid #e6f2ec; padding:.55rem 2rem; text-align:center; font-size:.7rem; font-weight:700; color:#2d5a43; letter-spacing:.09em; text-transform:uppercase; }

    .bd { background:#fff; padding:2rem 2.5rem 1.5rem; }
    .bd p { font-size:.92rem; color:#2e3d30; line-height:1.8; margin:0 0 1rem; }
    .bd strong { color:#1a3d2b; }
    .divider { border:none; border-top:1px solid #e6f2ec; margin:1.4rem 0; }

    .info-box { background:#f0f9f4; border:1px solid #b7dfc9; border-left:4px solid #2d5a43; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.9rem; color:#1e4030; margin:1.2rem 0; line-height:1.65; }

    .cta-wrap { text-align:center; margin:1.8rem 0 1rem; }
    .cta-btn {
        display:inline-block;
        background:#2d5a43;
        color:#fff !important;
        font-family:'Segoe UI',Arial,sans-serif;
        font-size:1rem;
        font-weight:700;
        text-decoration:none;
        padding:.85rem 2.4rem;
        border-radius:8px;
        letter-spacing:.03em;
    }

    .link-fallback { font-size:.78rem; color:#64748b; margin-top:.75rem; word-break:break-all; }
    .link-fallback a { color:#2d5a43; }

    .ft { background:#e8f5ec; padding:1.2rem 2.5rem 1.5rem; border-radius:0 0 14px 14px; text-align:center; font-size:.76rem; color:#5a7a62; line-height:1.7; }
    .ft a { color:#1e5c3d; text-decoration:none; font-weight:600; }
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f7f4">
<tr><td align="center" style="padding:2rem 1rem">
<div class="wrap">

    <div class="hd">
        <div class="hd-inner">
            <span class="hd-icon">&#x1F4D3;</span>
            <h1>Your Daily Food Diary</h1>
            <p>Sent by {{ $dietician->name }} &mdash; {{ config('app.name') }}</p>
        </div>
    </div>

    <div class="badge-strip">&#x2665; Personalised Nutrition Tracking</div>

    <div class="bd">
        <p>Hi <strong>{{ $diary->patient?->name ?? 'there' }}</strong>,</p>

        <p>
            Your dietician, <strong>{{ $dietician->name }}</strong>, has sent you a food diary to complete.
            Recording what you eat helps track your progress and allows your dietician to provide the best personalised nutrition advice.
        </p>

        <div class="info-box">
            &#x1F4CB; <strong>What to do:</strong><br>
            Click the button below, fill in each meal section for the day, then rate your day and note anything you'd like to improve. It only takes a few minutes!
        </div>

        <hr class="divider">

        <div class="cta-wrap">
            <a href="{{ $link }}" class="cta-btn">&#x270D; Fill In My Food Diary</a>
            <p class="link-fallback">
                If the button doesn't work, copy and paste this link into your browser:<br>
                <a href="{{ $link }}">{{ $link }}</a>
            </p>
        </div>

        <hr class="divider">

        <p style="font-size:.82rem;color:#64748b;margin:0">
            This link is personal to you and can only be used once. If you have any questions, please contact your dietician directly.
        </p>
    </div>

    <div class="ft">
        {{ config('app.name') }} &bull; {{ now()->format('d M Y') }}<br>
        <a href="{{ url('/') }}">{{ url('/') }}</a>
    </div>

</div>
</td></tr>
</table>
</body>
</html>
