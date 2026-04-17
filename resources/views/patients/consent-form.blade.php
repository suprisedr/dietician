<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consent to Manage Your Health Information</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0f7f4; font-family: 'Segoe UI', Arial, sans-serif; min-height: 100vh; display: flex; align-items: flex-start; justify-content: center; padding: 2rem 1rem 4rem; color: #1e293b; }
    .card { background: #fff; max-width: 640px; width: 100%; border-radius: 14px; box-shadow: 0 10px 40px rgba(0,0,0,.1); overflow: hidden; }
    .hd { background: linear-gradient(135deg, #0d3320 0%, #1e5c3d 55%, #3a8c5f 100%); padding: 2.2rem 2rem 1.8rem; text-align: center; }
    .hd h1 { color: #fff; font-size: 1.3rem; font-weight: 800; margin: 0 0 .3rem; line-height: 1.3; }
    .hd p { color: rgba(255,255,255,.78); font-size: .88rem; margin: 0; }
    .badge { background: #fff; border-bottom: 2px solid #e6f2ec; padding: .55rem 2rem; text-align: center; font-size: .7rem; font-weight: 700; color: #3a8c5f; letter-spacing: .09em; text-transform: uppercase; }
    .bd { padding: 2rem 2.5rem 1.5rem; }
    .bd p { font-size: .92rem; color: #2e3d30; line-height: 1.78; margin: 0 0 1rem; }
    strong { color: #0d3320; }
    .divider { border: none; border-top: 1px solid #e6f2ec; margin: 1.4rem 0; }
    .sec { font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #3a8c5f; margin: 0 0 .75rem; }
    .info-box { background: #f0f9f4; border: 1px solid #b7dfc9; border-left: 4px solid #3a8c5f; border-radius: 0 8px 8px 0; padding: .9rem 1.1rem; font-size: .9rem; color: #1e4030; margin: 1.2rem 0; line-height: 1.65; }
    ul.cl { padding-left: 1.25rem; margin: .4rem 0 1rem; }
    ul.cl li { font-size: .88rem; color: #2e3d30; line-height: 1.75; margin-bottom: .3rem; }
    .btn-row { display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap; }
    .btn-accept {
        flex: 1; min-width: 160px;
        background: #1e5c3d; color: #fff;
        font-size: 1rem; font-weight: 700; font-family: inherit;
        padding: .9rem 1.5rem; border: none; border-radius: 8px;
        cursor: pointer; letter-spacing: .03em;
        transition: background .15s;
    }
    .btn-accept:hover { background: #0d3320; }
    .btn-decline {
        flex: 1; min-width: 160px;
        background: #fff; color: #dc2626;
        font-size: .9rem; font-weight: 600; font-family: inherit;
        padding: .9rem 1.5rem; border: 1.5px solid #fca5a5; border-radius: 8px;
        cursor: pointer; letter-spacing: .01em;
        transition: background .15s;
    }
    .btn-decline:hover { background: #fef2f2; }
    .ft { background: #e8f5ec; padding: 1.1rem 2.5rem 1.4rem; text-align: center; font-size: .75rem; color: #5a7a62; line-height: 1.7; }
</style>
</head>
<body>

<div class="card">
    <div class="hd">
        <h1>Consent to Manage Your<br>Personal Health Information</h1>
        <p>In accordance with the Protection of Personal Information Act (POPIA)</p>
    </div>
    <div class="badge">&#x2665; POPIA Compliance Notice</div>

    <div class="bd">
        <p>
            Dear <strong>{{ $patient->full_name }}</strong>,
        </p>
        <p>
            Your dietician, <strong>{{ $patient->user->name }}</strong>, has registered your personal and
            health information on the <strong>{{ config('app.name') }}</strong> platform as part of your
            dietetic assessment and nutritional care programme.
        </p>
        <p>
            Please review the information below, then click <strong>Grant Consent</strong> or
            <strong>Decline</strong> at the bottom of this page.
        </p>

        <div class="info-box">
            &#x1F4CB; <strong>Dietician:</strong> {{ $patient->user->name }}<br>
            &#x1F4C5; <strong>Date registered:</strong> {{ $patient->created_at->format('d F Y') }}
        </div>

        <hr class="divider">

        <p class="sec">&#x1F4C2; Information That May Be Collected</p>
        <ul class="cl">
            <li>Personal identifiers (name, date of birth, ID/passport number, contact details)</li>
            <li>Physical measurements (weight, height, BMI and related body composition data)</li>
            <li>Dietary and nutritional assessment data</li>
            <li>Medical history relevant to your dietetic care</li>
            <li>Progress records and follow-up visit data</li>
        </ul>

        <hr class="divider">

        <p class="sec">&#x1F3AF; Purpose of Processing</p>
        <ul class="cl">
            <li>To calculate personalised nutritional recommendations</li>
            <li>To generate nutrition prescriptions and meal plans</li>
            <li>To monitor your health and dietary progress over time</li>
            <li>To produce clinical reports for your records</li>
        </ul>

        <hr class="divider">

        <p class="sec">&#x1F6E1; Your Rights Under POPIA</p>
        <ul class="cl">
            <li><strong>Right of Access</strong> &mdash; request a copy of your personal information</li>
            <li><strong>Right to Correct</strong> &mdash; request correction of inaccurate information</li>
            <li><strong>Right to Delete</strong> &mdash; request deletion subject to legal retention requirements</li>
            <li><strong>Right to Object</strong> &mdash; object to processing in certain circumstances</li>
            <li><strong>Right to Complain</strong> &mdash; lodge a complaint with the SA Information Regulator</li>
        </ul>

        <hr class="divider">

        <p class="sec">&#x1F512; Confidentiality &amp; Security</p>
        <p>
            Your information is treated with strict confidentiality in accordance with POPIA and professional
            dietetic ethical standards. It will not be shared with any third party without your explicit consent,
            except where required by law.
        </p>

        <hr class="divider">

        <p style="font-size:.82rem;color:#5a7a62;text-align:center">
            If you have questions about how your information is used, contact your dietician
            <strong>{{ $patient->user->name }}</strong> directly.
        </p>

        <div class="btn-row">
            <form method="POST" action="{{ route('patient-consent.accept', $token) }}" style="flex:1;min-width:160px">
                @csrf
                <button type="submit" class="btn-accept" style="width:100%">&#x2714; Grant Consent</button>
            </form>
            <form method="POST" action="{{ route('patient-consent.decline', $token) }}" style="flex:1;min-width:160px"
                  onsubmit="return confirm('Are you sure you want to decline? Your dietician will be unable to manage your nutritional care on this platform.')">
                @csrf
                <button type="submit" class="btn-decline" style="width:100%">&#x2717; Decline</button>
            </form>
        </div>
    </div>

    <div class="ft">
        &copy; {{ date('Y') }} {{ config('app.name') }} &middot; All rights reserved.
    </div>
</div>

</body>
</html>
