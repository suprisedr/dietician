<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consent Granted</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0f7f4; font-family: 'Segoe UI', Arial, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
    .card { background: #fff; max-width: 500px; width: 100%; border-radius: 14px; box-shadow: 0 10px 40px rgba(0,0,0,.1); overflow: hidden; text-align: center; }
    .hd { background: linear-gradient(135deg, #0d3320 0%, #1e5c3d 55%, #3a8c5f 100%); padding: 2.5rem 2rem 2rem; }
    .hd-icon { font-size: 3rem; display: block; margin-bottom: .7rem; }
    .hd h1 { color: #fff; font-size: 1.4rem; font-weight: 800; margin: 0; }
    .bd { padding: 2rem 2.5rem 2.5rem; }
    .bd p { font-size: .92rem; color: #2e3d30; line-height: 1.78; margin: 0 0 1rem; }
    strong { color: #0d3320; }
    .ft { background: #e8f5ec; padding: .9rem 2rem; font-size: .74rem; color: #5a7a62; }
</style>
</head>
<body>
<div class="card">
    <div class="hd">
        <span class="hd-icon">&#x2705;</span>
        <h1>{{ $already ?? false ? 'Consent Already Granted' : 'Thank You!' }}</h1>
    </div>
    <div class="bd">
        <p>
            Hi <strong>{{ $patient->full_name }}</strong>, {{ $already ?? false ? 'you have already granted consent.' : 'your consent has been recorded successfully.' }}
        </p>
        <p>
            Your dietician <strong>{{ $patient->user->name }}</strong> can now manage your nutritional care
            on the <strong>{{ config('app.name') }}</strong> platform.
        </p>
        <p style="font-size:.82rem;color:#64748b">
            If you did not intend to grant consent or have any concerns, please contact your dietician directly.
        </p>
    </div>
    <div class="ft">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
</div>
</body>
</html>
