<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Health Journey Starts Here</title>
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
        .hd h1 { color:#fff; font-size:1.35rem; font-weight:800; margin:0 0 .4rem; letter-spacing:-.01em; line-height:1.3; }
        .hd p  { color:rgba(255,255,255,.78); font-size:.88rem; margin:0; }
        .hd-icon { font-size:2.2rem; display:block; margin-bottom:.6rem; }
        /* Badge strip */
        .badge-strip { background:#fff; border-bottom:2px solid #e6f2ec; padding:.6rem 2rem; text-align:center; font-size:.72rem; font-weight:700; color:#429677; letter-spacing:.09em; text-transform:uppercase; }
        /* Body */
        .bd { background:#fff; padding:2rem 2.5rem 1.5rem; }
        .bd p { font-size:.92rem; color:#2e3d30; line-height:1.78; margin:0 0 1rem; }
        .bd strong { color:#0d3320; }
        .bd ul { margin:1rem 0; padding-left:1.5rem; }
        .bd ul li { font-size:.9rem; color:#2e3d30; line-height:1.7; margin-bottom:.5rem; }
        .divider { border:none; border-top:1px solid #e6f2ec; margin:1.4rem 0; }
        /* Section label */
        .sec-label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#3a8c5f; margin:1.2rem 0 .8rem; }
        /* Highlight box */
        .highlight { background:#f0f9f4; border:1px solid #b7dfc9; border-left:4px solid #3a8c5f; border-radius:0 8px 8px 0; padding:.9rem 1.1rem; font-size:.87rem; color:#1e4030; margin:1rem 0; line-height:1.65; }
        /* CTA button */
        .cta-wrap { text-align:center; margin:1.6rem 0 1rem; }
        .cta-btn { display:inline-block; padding:.9rem 2.4rem; background:linear-gradient(135deg,#1e5c3d,#3a8c5f); color:#fff !important; text-decoration:none; border-radius:9px; font-weight:800; font-size:.97rem; letter-spacing:.01em; }
        /* Footer */
        .ft { background:#e8f5ec; padding:1.2rem 2.5rem 1.5rem; border-radius:0 0 14px 14px; text-align:center; font-size:.76rem; color:#5a7a62; line-height:1.7; }
        .ft a { color:#1e5c3d; text-decoration:none; font-weight:600; }
        .ft p { margin:0.4rem 0; }
    </style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f0f7f4">
<tr><td align="center" style="padding:2rem 1rem">
<div class="wrap">

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="hd">
        <div class="hd-inner">
            <div class="hd-icon">🌟</div>
            <h1>Your Health Journey Starts Here</h1>
            <p>You've Got This!</p>
        </div>
    </div>

    <div class="badge-strip">✦ Welcome, {{ $patient->name }} ✦</div>

    {{-- ── Body ───────────────────────────────────────────────── --}}
    <div class="bd">
        <p>Congratulations on taking this important first step toward prioritizing your health through structured meal planning. Making the decision to commit to nourishing your body is a powerful one and I want to commend you for choosing to invest in yourself.</p>

        <p>Starting a new eating plan can feel overwhelming at first and that is completely normal.</p>

        <p>Please remember: <strong>this journey is not about perfection, but about progress</strong>. Each balanced meal, each mindful choice and each day you follow your plan is a step toward more energy, improved wellbeing, and long-term health.</p>

        <p class="sec-label">🎯 What You Can Look Forward To</p>
        <ul>
            <li>Increased energy levels to get through your day with ease</li>
            <li>Improved focus and mood as your body receives consistent, quality nutrition</li>
            <li>A sense of control and confidence that comes from fueling your body intentionally</li>
            <li>Celebrating non-scale victories like better sleep, clearer skin, or looser clothing</li>
        </ul>

        <p>There will be days that are easier than others. If you have a meal that is not on the plan, view it as one moment, not a failure. Simply resume with your next planned meal. <strong>Consistency over time is what creates lasting results</strong> not rigid perfection.</p>

        <p class="sec-label">💡 My Tips for Success</p>
        <ul>
            <li><strong>Plan and prep:</strong> Set aside time each week to prepare. Future you will be grateful.</li>
            <li><strong>Stay curious:</strong> Notice how different foods make you feel. You're learning what works best for your body.</li>
            <li><strong>Reach out:</strong> If you have questions or feel stuck, I am here to support and adjust the plan with you. You do not have to do this alone.</li>
        </ul>

        <div class="highlight">
            <strong>You have already done the hardest part by starting.</strong> I am confident in your ability to succeed and I am here to guide you every step of the way.
        </div>

        <p style="text-align:center; font-style:italic; color:#3a8c5f; margin-top:1.4rem">Let's create a healthier you together! 🌱</p>

    </div>

    {{-- ── Footer ─────────────────────────────────────────────── --}}
    <div class="ft">
        <p><strong>{{ $dietician->name }}</strong></p>
        <p style="color:#3a8c5f; margin:0.6rem 0">Registered Dietitian</p>
        <p style="margin-top:.8rem;opacity:.7">This is the start of your transformation. I'm with you every step of the way.</p>
    </div>

</div>
</td></tr>
</table>
</body>
</html>
