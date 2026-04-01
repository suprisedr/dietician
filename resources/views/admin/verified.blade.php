<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dietician Account {{ $alreadyVerified ? 'Already Verified' : 'Verified' }} — Mindfulnutrico</title>
    <style>
        body { margin:0; padding:0; background:#f0f7f4; font-family:'Segoe UI',Arial,sans-serif; display:flex; align-items:center; justify-content:center; min-height:100vh; }
        .card { background:#fff; border-radius:16px; max-width:520px; width:90%; padding:3rem 2.5rem; text-align:center; box-shadow:0 4px 24px rgba(0,0,0,.08); }
        .icon { width:72px; height:72px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; }
        .icon-ok { background:linear-gradient(135deg,#e8f5ec,#c3e6cb); }
        .icon-warn { background:linear-gradient(135deg,#fff3cd,#ffeaa7); }
        h1 { font-size:1.5rem; font-weight:800; color:#0d3320; margin:0 0 .75rem; }
        p { font-size:.95rem; color:#4a5e4f; line-height:1.75; margin:0 0 1.1rem; }
        .info-box { background:#f0f9f4; border:1px solid #b7dfc9; border-radius:10px; padding:1rem 1.2rem; text-align:left; margin:1.2rem 0; }
        .info-box dt { font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#3a8c5f; margin-bottom:.2rem; }
        .info-box dd { font-size:.93rem; color:#1e3a2a; font-weight:600; margin:0 0 .7rem; }
        .info-box dd:last-child { margin-bottom:0; }
        .badge-ok { display:inline-block; background:#d4edda; color:#155724; border-radius:20px; padding:.25rem .9rem; font-size:.8rem; font-weight:700; }
        .badge-warn { display:inline-block; background:#fff3cd; color:#856404; border-radius:20px; padding:.25rem .9rem; font-size:.8rem; font-weight:700; }
        .footer { margin-top:2rem; font-size:.78rem; color:#8aaa90; }
    </style>
</head>
<body>
    <div class="card">

        @if($alreadyVerified)
            <div class="icon icon-warn">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                     fill="none" stroke="#e6a817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <h1>Already Verified</h1>
            <p>This dietician's account was already activated previously. No changes have been made.</p>
            <span class="badge-warn">Previously Activated</span>
        @else
            <div class="icon icon-ok">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                     fill="none" stroke="#1e5c3d" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <h1>Account Activated</h1>
            <p>The dietician's account has been successfully verified and is now active. They may log in immediately.</p>
            <span class="badge-ok">✓ Activated Successfully</span>
        @endif

        <div class="info-box" style="margin-top:1.5rem">
            <dt>Dietician Name</dt>
            <dd>{{ $dietician->name }}</dd>
            <dt>Email Address</dt>
            <dd>{{ $dietician->email }}</dd>
            <dt>HPCSA Number</dt>
            <dd>{{ $dietician->dietician_number }}</dd>
            @if(! $alreadyVerified)
            <dt>Activated At</dt>
            <dd>{{ $dietician->admin_verified_at->format('d M Y, H:i') }}</dd>
            @endif
        </div>

        <div class="footer">
            © {{ date('Y') }} Mindfulnutrico · All rights reserved.
        </div>
    </div>
</body>
</html>
