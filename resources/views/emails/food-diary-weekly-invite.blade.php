<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Weekly Food Diary</title>
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

    .week-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#2d5a43; margin:1.4rem 0 .65rem; }

    .day-row { display:block; margin-bottom:.5rem; }
    .day-btn {
        display:block;
        background:#f0f9f4;
        border:1px solid #b7dfc9;
        border-radius:8px;
        padding:.65rem 1rem;
        text-decoration:none;
        color:#1a3d2b;
        font-family:'Segoe UI',Arial,sans-serif;
        font-size:.88rem;
    }
    .day-name { font-weight:700; }
    .day-date { color:#64748b; font-size:.78rem; }
    .day-arrow { float:right; color:#2d5a43; font-weight:700; }

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
            <span class="hd-icon">&#x1F4C5;</span>
            <h1>Your Weekly Food Diary</h1>
            <p>{{ $weekStart->format('d M') }} &ndash; {{ $weekEnd->format('d M Y') }} &mdash; Sent by {{ $dietician->name }}</p>
        </div>
    </div>

    <div class="badge-strip">&#x2665; Personalised Nutrition Tracking &bull; 7-Day Record</div>

    <div class="bd">
        <p>Hi <strong>{{ $patient->name ?? 'there' }}</strong>,</p>

        <p>
            Your dietician, <strong>{{ $dietician->name }}</strong>, has sent you a weekly food diary to complete.
            There is a separate link for each day of the week — click the day you'd like to fill in and record your meals for that day.
        </p>

        <div class="info-box">
            &#x1F4CB; <strong>How it works:</strong><br>
            Click any day below to open that day's diary. Fill in your meals, rate your day, and add any notes. You can complete each day as you go — there's no need to do them all at once.
        </div>

        <hr class="divider">

        <div class="week-label">&#x1F4C5; Week of {{ $weekStart->format('d M Y') }}</div>

        @foreach($entries as $entry)
        <span class="day-row">
            <a href="{{ $entry['link'] }}" class="day-btn">
                <span class="day-name">{{ $entry['day_name'] }}</span>
                &nbsp;<span class="day-date">{{ $entry['date_str'] }}</span>
                <span class="day-arrow">&#x2192;</span>
            </a>
        </span>
        @endforeach

        <hr class="divider">

        <p style="font-size:.82rem;color:#64748b;margin:0">
            Each link is personal to you and can only be submitted once per day. If you have any questions, please contact your dietician directly.
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
