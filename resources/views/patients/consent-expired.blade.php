<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consent Link Expired</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0f7f4; font-family: 'Segoe UI', Arial, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
    .card { background: #fff; max-width: 500px; width: 100%; border-radius: 14px; box-shadow: 0 10px 40px rgba(0,0,0,.1); overflow: hidden; text-align: center; }
    .hd { background: linear-gradient(135deg, #78350f 0%, #d97706 60%, #f59e0b 100%); padding: 2.5rem 2rem 2rem; }
    .hd-icon { font-size: 3rem; display: block; margin-bottom: .7rem; }
    .hd h1 { color: #fff; font-size: 1.4rem; font-weight: 800; margin: 0; }
    .bd { padding: 2rem 2.5rem 2.5rem; }
    .bd p { font-size: .92rem; color: #2e3d30; line-height: 1.78; margin: 0 0 1rem; }
    strong { color: #0d3320; }
    .ft { background: #fffbeb; padding: .9rem 2rem; font-size: .74rem; color: #92400e; }
</style>
</head>
<body>
<div class="card">
    <div class="hd">
        <span class="hd-icon">&#x23F0;</span>
        <h1>Consent Link Expired</h1>
    </div>
    <div class="bd">
        <p>
            Hi <strong>{{ $patient->full_name }}</strong>, this consent link has expired (links are valid for 72 hours).
        </p>
        <p>
            Please contact your dietician <strong>{{ $patient->user->name }}</strong> and ask them to
            resend the consent link from the <strong>{{ config('app.name') }}</strong> platform.
        </p>
    </div>
    <div class="ft">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
</div>
</body>
</html>
