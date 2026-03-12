<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to mindfulnutrico</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f9f3; margin: 0; padding: 0; }
        .wrap { max-width: 580px; margin: 2rem auto; background: #fff;
                border-radius: 12px; overflow: hidden;
                box-shadow: 0 4px 20px rgba(0,0,0,.07); }
        .header { background: linear-gradient(135deg, #0d1f0c, #2e6e56, #679F5F);
                  padding: 2.5rem 2rem; text-align: center; }
        .header h1 { color: #fff; font-size: 1.5rem; margin: 0 0 .4rem; font-weight: 800; letter-spacing: -.02em; }
        .header p  { color: rgba(255,255,255,.75); font-size: .9rem; margin: 0; }
        .body { padding: 2rem 2.5rem; }
        .body p { font-size: .95rem; color: #3a4a3a; line-height: 1.75; margin-bottom: 1rem; }
        .body strong { color: #1a2e1a; }
        .divider { border: none; border-top: 1px solid #e8f0e6; margin: 1.5rem 0; }
        .feature-list { list-style: none; margin: 0 0 1.5rem; padding: 0; }
        .feature-list li { display: flex; align-items: flex-start; gap: .65rem;
                           font-size: .92rem; color: #3a4a3a; margin-bottom: .75rem; }
        .feature-list li .icon { flex-shrink: 0; width: 1.6rem; height: 1.6rem; border-radius: 50%;
                                  background: #eef7ec; display: flex; align-items: center;
                                  justify-content: center; font-size: .85rem; }
        .btn { display: inline-block; padding: .85rem 2rem;
               background: linear-gradient(135deg, #679F5F, #429677);
               color: #fff !important; text-decoration: none;
               border-radius: 8px; font-weight: 700; font-size: .95rem; margin-top: .25rem; }
        .tip { background: #f4f9f3; border-left: 3px solid #679F5F; border-radius: 0 8px 8px 0;
               padding: .9rem 1.1rem; font-size: .875rem; color: #3a4a3a; margin: 1.25rem 0; }
        .footer { padding: 1.25rem 2.5rem; background: #f4f9f3;
                  font-size: .78rem; color: #8a9e8a; text-align: center; line-height: 1.6; }
        .footer a { color: #429677; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="header">
            <h1>🌿 Welcome to mindfulnutrico</h1>
            <p>Your clinical nutrition platform is ready</p>
        </div>
        <div class="body">
            <p>Hi <strong>{{ $userName }}</strong>,</p>
            <p>
                We're thrilled to have you on board! Your <strong>mindfulnutrico</strong> account
                has been created and you can start managing your patients right away.
            </p>

            <hr class="divider">
            <p style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#8a9e8a;margin-bottom:.85rem">
                What you can do now
            </p>

            <ul class="feature-list">
                <li>
                    <span class="icon">👤</span>
                    <span><strong>Add patients</strong> — capture anthropometric data, assign titles and assessment reasons.</span>
                </li>
                <li>
                    <span class="icon">⚖️</span>
                    <span><strong>Calculate TEE & IBW</strong> — Mifflin-St Jeor formula with activity factor, BMI-based ideal body weight.</span>
                </li>
                <li>
                    <span class="icon">🥗</span>
                    <span><strong>Build exchange templates</strong> — customise food exchange lists and track macronutrient targets.</span>
                </li>
                <li>
                    <span class="icon">📊</span>
                    <span><strong>Monitor progress</strong> — record visit-by-visit changes in weight, height and body composition.</span>
                </li>
                <li>
                    <span class="icon">📄</span>
                    <span><strong>Generate PDF reports</strong> — professional patient reports ready to print or share.</span>
                </li>
            </ul>

            <div class="tip">
                💡 <strong>Tip:</strong> Start by adding your first patient under the
                <em>Patients</em> tab, then assign an exchange template to unlock the
                full nutrition analysis panel.
            </div>

            <p style="text-align:center;margin-top:1.5rem">
                <a href="{{ $dashboardUrl }}" class="btn">Go to Dashboard →</a>
            </p>

            <hr class="divider">
            <p style="font-size:.85rem;color:#8a9e8a">
                If you have any questions or need help getting started, reply to this
                email and our team will be happy to assist.
            </p>
        </div>
        <div class="footer">
            You received this email because you registered at
            <a href="{{ $dashboardUrl }}">mindfulnutrico</a>.<br>
            © {{ date('Y') }} mindfulnutrico · All rights reserved.
        </div>
    </div>
</body>
</html>
