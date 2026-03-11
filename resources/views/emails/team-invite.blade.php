<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You've been invited to mindfulnutrico</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f9f3; margin: 0; padding: 0; }
        .wrap { max-width: 560px; margin: 2rem auto; background: #fff;
                border-radius: 12px; overflow: hidden;
                box-shadow: 0 4px 20px rgba(0,0,0,.07); }
        .header { background: linear-gradient(135deg, #679F5F, #429677);
                  padding: 2rem; text-align: center; }
        .header h1 { color: #fff; font-size: 1.4rem; margin: 0; font-weight: 800; }
        .body { padding: 2rem 2.5rem; }
        .body p { font-size: .95rem; color: #3a4a3a; line-height: 1.7; margin-bottom: 1rem; }
        .btn { display: inline-block; padding: .85rem 2rem;
               background: linear-gradient(135deg, #679F5F, #429677);
               color: #fff !important; text-decoration: none;
               border-radius: 8px; font-weight: 700; font-size: .95rem; margin-top: .5rem; }
        .footer { padding: 1.25rem 2.5rem; background: #f4f9f3;
                  font-size: .78rem; color: #8a9e8a; text-align: center; }
        .url { word-break: break-all; color: #429677; font-size: .82rem; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="header">
            <h1>🌿 mindfulnutrico — Team Invitation</h1>
        </div>
        <div class="body">
            <p>Hi there,</p>
            <p>
                <strong>{{ $ownerName }}</strong> has invited you to join their team on
                <strong>mindfulnutrico</strong>. Your subscription will be covered by them — all
                you need to do is create your free account.
            </p>
            <p>Click the button below to accept the invitation:</p>
            <a href="{{ $acceptUrl }}" class="btn">Accept Invitation</a>
            <p class="url">Or copy this link: {{ $acceptUrl }}</p>
            <p style="margin-top:1.5rem;font-size:.85rem;color:#8a9e8a">
                This invitation was sent to <strong>{{ $invitation->email }}</strong>.
                If you weren't expecting this, you can safely ignore this email.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} mindfulnutrico. All rights reserved.
        </div>
    </div>
</body>
</html>
