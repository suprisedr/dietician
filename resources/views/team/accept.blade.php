<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You've been invited — NutriCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #eaf4e8 0%, #d4ead0 100%);
            font-family: 'Inter', sans-serif;
            padding: 2rem 1rem;
        }

        .card {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 8px 40px rgba(0,0,0,.1);
            padding: 3rem 2.5rem;
            max-width: 460px;
            width: 100%;
            text-align: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 900;
            color: #429677;
            letter-spacing: -.04em;
            margin-bottom: 1.75rem;
        }

        .icon {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #679F5F, #429677);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 1.75rem;
        }

        h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1a2e1a;
            letter-spacing: -.025em;
            margin-bottom: .5rem;
        }

        .sub {
            font-size: .9rem;
            color: #5a7a5a;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .sub strong { color: #1a2e1a; }

        .btn {
            display: inline-block;
            width: 100%;
            padding: .85rem 1.5rem;
            background: linear-gradient(135deg, #679F5F, #429677);
            color: #fff;
            font-size: .95rem;
            font-weight: 700;
            border-radius: .65rem;
            text-decoration: none;
            margin-bottom: .75rem;
            transition: opacity .2s;
        }

        .btn:hover { opacity: .9; }

        .btn-secondary {
            display: inline-block;
            width: 100%;
            padding: .75rem 1.5rem;
            background: #f0faf0;
            color: #429677;
            font-size: .9rem;
            font-weight: 600;
            border-radius: .65rem;
            text-decoration: none;
            border: 1.5px solid #c3e6be;
            transition: background .2s;
        }

        .btn-secondary:hover { background: #e2f5e0; }

        .divider {
            font-size: .8rem;
            color: #9aae9a;
            margin: .5rem 0;
        }

        .note {
            font-size: .78rem;
            color: #aabcaa;
            margin-top: 1.5rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">NutriCare</div>

        <div class="icon">🎉</div>

        <h1>You've been invited!</h1>

        <p class="sub">
            <strong>{{ $owner->name }}</strong> has invited you to join their team on
            <strong>NutriCare</strong>. Create your account to get started — your subscription
            will be covered by them.
        </p>

        {{-- Register link carries the token so RegisteredUserController can handle it --}}
        <a href="{{ route('register', ['token' => $invitation->token]) }}" class="btn">
            Create Account &amp; Accept Invite
        </a>

        <div class="divider">or</div>

        <a href="{{ route('login') }}" class="btn-secondary">
            I already have an account — Log in
        </a>

        <p class="note">
            This invitation was sent to <strong>{{ $invitation->email }}</strong>.
            If you weren't expecting this, you can safely ignore this page.
        </p>
    </div>
</body>
</html>
