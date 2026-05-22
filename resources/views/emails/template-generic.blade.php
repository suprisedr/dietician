<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $heading ?? 'Message' }}</title>
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
        .hd h1 { color:#fff; font-size:1.35rem; font-weight:800; margin:0; letter-spacing:-.01em; line-height:1.3; }
        /* Badge strip */
        .badge-strip { background:#fff; border-bottom:2px solid #e6f2ec; padding:.6rem 2rem; text-align:center; font-size:.72rem; font-weight:700; color:#429677; letter-spacing:.09em; text-transform:uppercase; }
        /* Body */
        .bd { background:#fff; padding:2rem 2.5rem 1.5rem; }
        .bd p { font-size:.92rem; color:#2e3d30; line-height:1.78; margin:0 0 1rem; }
        .bd strong { color:#0d3320; }
        .bd ul, .bd ol { margin:1rem 0; padding-left:1.5rem; }
        .bd li { font-size:.9rem; color:#2e3d30; line-height:1.7; margin-bottom:.5rem; }
        .bd h2, .bd h3 { color:#0d3320; font-size:.95rem; margin:1.2rem 0 .6rem; }
        .bd h2 { font-weight:800; font-size:1rem; }
        .bd h3 { font-weight:700; }
        /* Content styles */
        .content { padding:0; }
        /* CTA button */
        .cta-wrap { text-align:center; margin:1.6rem 0 1rem; }
        .cta-btn { display:inline-block; padding:.9rem 2.4rem; background:linear-gradient(135deg,#1e5c3d,#3a8c5f); color:#fff !important; text-decoration:none; border-radius:9px; font-weight:800; font-size:.97rem; letter-spacing:.01em; }
        /* Footer */
        .ft { background:#e8f5ec; padding:1.2rem 2.5rem 1.5rem; border-radius:0 0 14px 14px; text-align:center; font-size:.76rem; color:#5a7a62; line-height:1.7; }
        .ft a { color:#1e5c3d; text-decoration:none; font-weight:600; }
    </style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f7f4">
<tr><td align="center" style="padding:2rem 1rem">
<div class="wrap">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="hd">
        <div class="hd-inner">
            <h1>{{ $heading ?? 'Message' }}</h1>
        </div>
    </div>

    <div class="badge-strip">✦ Message from your Dietitian ✦</div>

    {{-- ── Body ───────────────────────────────────────────────── --}}
    <div class="bd">
        <div class="content">
            {!! $body_html !!}
        </div>

        @if($cta_text && $cta_url)
        <div class="cta-wrap">
            <a href="{{ $cta_url }}" class="cta-btn">{{ $cta_text }}</a>
        </div>
        @endif
    </div>

    {{-- ── Footer ─────────────────────────────────────────────── --}}
    <div class="ft">
        <p>Best regards</p>
    </div>

</div>
</td></tr>
</table>
</body>
</html>
