<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dietician — Professional Nutrition Practice Platform</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link href="{{ asset('css/soft-ui-overrides.css') }}" rel="stylesheet">

    <style>
        /* ── Reset & base ─────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Figtree', 'Inter', sans-serif; background: var(--bg-page, #f8fafc); color: var(--text-primary, #0f172a); line-height: 1.6; }

        /* ── Public Nav ───────────────────────────── */
        .pub-nav {
            position: sticky; top: 0; z-index: 50;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border, #e2e8f0);
        }
        .pub-nav-inner {
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 64px; padding: 0 1.5rem;
        }
        .pub-logo {
            display: flex; align-items: center; gap: .6rem;
            text-decoration: none; color: var(--text-primary, #0f172a);
            font-size: 1.1rem; font-weight: 800; letter-spacing: -.02em;
        }
        .pub-logo-icon {
            width: 2rem; height: 2rem;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-radius: .6rem;
            display: flex; align-items: center; justify-content: center;
        }
        .pub-nav-links { display: flex; align-items: center; gap: .5rem; }
        .pub-nav-link {
            padding: .4rem .85rem; border-radius: .65rem;
            font-size: .85rem; font-weight: 600; text-decoration: none;
            color: var(--text-muted, #64748b); transition: all .15s;
        }
        .pub-nav-link:hover { background: #fff7ed; color: #c2410c; }
        .pub-nav-link.active { background: linear-gradient(135deg,#fff7ed,#fed7aa); color: #c2410c; }
        .pub-btn-outline {
            padding: .42rem 1.1rem; border: 1.5px solid #e2e8f0; border-radius: 0;
            color: var(--text-primary, #0f172a); font-size: .85rem; font-weight: 600;
            text-decoration: none; background: #fff; transition: all .15s;
        }
        .pub-btn-outline:hover { border-color: #f97316; color: #f97316; }
        .pub-btn-primary {
            padding: .42rem 1.1rem;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border: none; border-radius: 0; color: #fff;
            font-size: .85rem; font-weight: 700; text-decoration: none;
            box-shadow: 0 4px 14px rgba(249,115,22,.35); transition: all .15s;
        }
        .pub-btn-primary:hover { box-shadow: 0 6px 20px rgba(249,115,22,.50); color: #fff; }

        /* ── Hero ────────────────────────────────── */
        .hero-wrap {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #f97316 100%);
            position: relative; overflow: hidden;
            padding: 5rem 1.5rem 7rem;
        }
        .hero-wrap::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(circle at 20% 60%, rgba(249,115,22,.30) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(99,102,241,.40) 0%, transparent 50%);
        }
        .hero-orb {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,.05);
        }
        .hero-inner {
            max-width: 1200px; margin: 0 auto;
            display: grid; grid-template-columns: 1fr 1fr; gap: 3.5rem;
            align-items: center; position: relative; z-index: 1;
        }
        @media (max-width: 860px) {
            .hero-inner { grid-template-columns: 1fr; }
            .hero-visual-col { display: none; }
            .hero-wrap { padding: 3.5rem 1.25rem 6rem; }
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: .4rem;
            background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.22);
            color: #fdba74; font-size: .78rem; font-weight: 700;
            padding: .3rem .85rem; border-radius: 999px; margin-bottom: 1.25rem;
            backdrop-filter: blur(8px);
        }
        .hero-h1 {
            font-size: clamp(2.2rem, 4.5vw, 3.4rem);
            font-weight: 800; line-height: 1.12;
            letter-spacing: -.04em; color: #fff;
            margin-bottom: 1.25rem;
        }
        .hero-h1 span {
            background: linear-gradient(90deg, #fdba74, #f97316);
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-sub {
            font-size: 1.05rem; color: rgba(255,255,255,.7);
            max-width: 460px; line-height: 1.75; margin-bottom: 2.25rem;
        }
        .hero-cta { display: flex; gap: .75rem; flex-wrap: wrap; }
        .btn-hero-primary {
            display: inline-block; padding: .85rem 2rem;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff; font-size: 1rem; font-weight: 700;
            text-decoration: none; border-radius: 0;
            box-shadow: 0 8px 24px rgba(249,115,22,.50); transition: all .2s;
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(249,115,22,.60); color: #fff; }
        .btn-hero-ghost {
            display: inline-block; padding: .85rem 2rem;
            border: 2px solid rgba(255,255,255,.35); color: #fff;
            font-size: 1rem; font-weight: 700; text-decoration: none;
            border-radius: 0; background: rgba(255,255,255,.08);
            backdrop-filter: blur(8px); transition: all .2s;
        }
        .btn-hero-ghost:hover { border-color: rgba(255,255,255,.7); background: rgba(255,255,255,.15); color: #fff; }

        /* ── Hero mock card ──────────────────────── */
        .hero-card {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 0; backdrop-filter: blur(16px);
            overflow: hidden;
        }
        .hero-card-header {
            background: rgba(255,255,255,.1);
            border-bottom: 1px solid rgba(255,255,255,.15);
            padding: 1rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .hero-card-header-name { color: #fff; font-size: .9rem; font-weight: 700; }
        .hero-card-header-sub { color: rgba(255,255,255,.55); font-size: .72rem; margin-top: .1rem; }
        .hero-card-body { padding: 1.25rem; }
        .hc-label { font-size: .65rem; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.5); margin-bottom: .75rem; font-weight: 700; }
        .hc-macro-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: .5rem; margin-bottom: 1.1rem; }
        .hc-macro {
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
            border-radius: 0; padding: .65rem .5rem; text-align: center;
        }
        .hc-macro-val { font-size: 1rem; font-weight: 800; color: #fdba74; }
        .hc-macro-unit { font-size: .6rem; color: rgba(255,255,255,.45); }
        .hc-macro-name { font-size: .65rem; font-weight: 600; color: rgba(255,255,255,.6); margin-top: .15rem; }
        .hc-exchange-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: .55rem 0; border-bottom: 1px solid rgba(255,255,255,.08);
            font-size: .78rem;
        }
        .hc-exchange-row:last-child { border-bottom: none; }
        .hc-ex-name { color: rgba(255,255,255,.8); font-weight: 600; }
        .hc-ex-val { color: #fdba74; font-weight: 700; }

        /* ── Stats band (overlaps hero) ──────────── */
        .stats-band {
            max-width: 1200px; margin: -3.5rem auto 0;
            position: relative; z-index: 10;
            display: grid; grid-template-columns: repeat(4,1fr);
            gap: 0; background: #fff;
            border: 1px solid var(--border, #e2e8f0);
            box-shadow: 0 20px 40px rgba(0,0,0,.10);
        }
        @media (max-width: 860px) { .stats-band { grid-template-columns: repeat(2,1fr); margin: -2rem 1.25rem 0; } }
        @media (max-width: 460px) { .stats-band { grid-template-columns: 1fr; } }
        .stat-band-item {
            padding: 1.5rem 1.75rem;
            border-right: 1px solid var(--border, #e2e8f0);
            position: relative;
        }
        .stat-band-item:last-child { border-right: none; }
        .stat-band-item::before {
            content: ''; position: absolute; top: 0; left: 0;
            width: 3px; height: 100%;
            background: linear-gradient(to bottom, #f97316, #ea580c);
        }
        .stat-band-val { font-size: 1.65rem; font-weight: 800; color: var(--text-primary, #0f172a); }
        .stat-band-label { font-size: .75rem; font-weight: 600; color: var(--text-muted, #64748b); margin-top: .2rem; text-transform: uppercase; letter-spacing: .06em; }

        /* ── Section layout ───────────────────────── */
        .section { max-width: 1200px; margin: 0 auto; padding: 5rem 1.5rem; }
        .section-sm { max-width: 1200px; margin: 0 auto; padding: 4rem 1.5rem; }
        @media (max-width: 640px) { .section, .section-sm { padding: 3rem 1.25rem; } }
        .section-eyebrow {
            display: inline-flex; align-items: center; gap: .4rem;
            background: #fff7ed; color: #c2410c;
            font-size: .75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .08em; padding: .3rem .85rem; border-radius: 999px;
            margin-bottom: 1rem;
        }
        .section-h2 {
            font-size: clamp(1.75rem, 3.5vw, 2.6rem); font-weight: 800;
            letter-spacing: -.035em; line-height: 1.15;
            color: var(--text-primary, #0f172a); margin-bottom: 1rem;
        }
        .section-h2 span { color: #f97316; }
        .section-lead {
            font-size: 1.05rem; color: var(--text-muted, #64748b);
            max-width: 560px; line-height: 1.75;
        }

        /* ── Feature cards ────────────────────────── */
        .features-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: var(--border, #e2e8f0); border: 1px solid var(--border, #e2e8f0); margin-top: 3rem; }
        @media (max-width: 860px) { .features-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 500px) { .features-grid { grid-template-columns: 1fr; } }
        .feature-card {
            background: #fff; padding: 2rem 1.75rem;
            transition: background .2s;
        }
        .feature-card:hover { background: #fff7ed; }
        .feature-icon {
            width: 2.75rem; height: 2.75rem; border-radius: 0;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
        }
        .feature-icon.orange { background: linear-gradient(135deg, #f97316, #fb923c); }
        .feature-icon.indigo { background: linear-gradient(135deg, #4f46e5, #818cf8); }
        .feature-icon.teal   { background: linear-gradient(135deg, #0d9488, #14b8a6); }
        .feature-icon.rose   { background: linear-gradient(135deg, #e11d48, #f43f5e); }
        .feature-icon.amber  { background: linear-gradient(135deg, #d97706, #eab308); }
        .feature-icon.sky    { background: linear-gradient(135deg, #0284c7, #0ea5e9); }
        .feature-icon svg { width: 1.3rem; height: 1.3rem; color: #fff; }
        .feature-title { font-size: 1rem; font-weight: 700; color: var(--text-primary, #0f172a); margin-bottom: .5rem; }
        .feature-desc { font-size: .875rem; color: var(--text-muted, #64748b); line-height: 1.7; }

        /* ── How it works ─────────────────────────── */
        .hiw-bg { background: #f8fafc; border-top: 1px solid var(--border,#e2e8f0); border-bottom: 1px solid var(--border,#e2e8f0); }
        .hiw-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
        @media (max-width: 860px) { .hiw-grid { grid-template-columns: 1fr; gap: 2.5rem; } }
        .hiw-step { display: flex; gap: 1.25rem; margin-bottom: 2rem; }
        .hiw-step:last-child { margin-bottom: 0; }
        .hiw-num {
            width: 2.25rem; height: 2.25rem; flex-shrink: 0;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff; font-size: .8rem; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
        }
        .hiw-step-title { font-size: .95rem; font-weight: 700; color: var(--text-primary, #0f172a); margin-bottom: .3rem; }
        .hiw-step-desc { font-size: .85rem; color: var(--text-muted, #64748b); line-height: 1.65; }
        .hiw-visual {
            background: #fff; border: 1px solid var(--border,#e2e8f0);
            box-shadow: 0 20px 40px rgba(0,0,0,.08);
        }
        .hiw-vis-header {
            background: linear-gradient(135deg, #1e1b4b, #312e81);
            padding: 1rem 1.25rem; color: #fff;
            display: flex; align-items: center; justify-content: space-between;
        }
        .hiw-vis-title { font-size: .85rem; font-weight: 700; }
        .hiw-vis-body { padding: 1.25rem; }
        .meal-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: .7rem 0; border-bottom: 1px solid #f1f5f9;
        }
        .meal-row:last-child { border-bottom: none; }
        .meal-name { font-size: .83rem; font-weight: 600; color: var(--text-primary,#0f172a); }
        .meal-chips { display: flex; gap: .35rem; }
        .chip {
            padding: .18rem .55rem; font-size: .65rem; font-weight: 700;
            border-radius: 999px;
        }
        .chip.orange { background: #fff7ed; color: #c2410c; }
        .chip.indigo { background: #eef2ff; color: #4338ca; }
        .chip.teal   { background: #f0fdfa; color: #0f766e; }

        /* ── CTA strip ────────────────────────────── */
        .cta-strip {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 55%, #f97316 100%);
            position: relative; overflow: hidden;
        }
        .cta-strip::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(circle at 75% 50%, rgba(249,115,22,.35) 0%, transparent 55%);
        }
        .cta-inner {
            max-width: 900px; margin: 0 auto; padding: 4.5rem 1.5rem;
            text-align: center; position: relative; z-index: 1;
        }
        .cta-h2 { font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 800; color: #fff; letter-spacing: -.03em; margin-bottom: 1rem; }
        .cta-sub { color: rgba(255,255,255,.7); font-size: 1rem; line-height: 1.75; margin-bottom: 2rem; max-width: 520px; margin-left: auto; margin-right: auto; }
        .cta-btns { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }

        /* ── Footer ───────────────────────────────── */
        .pub-footer {
            background: #0f172a; color: rgba(255,255,255,.6);
            padding: 2.5rem 1.5rem;
        }
        .pub-footer-inner {
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
        }
        .pub-footer-logo { display: flex; align-items: center; gap: .6rem; color: #fff; font-weight: 800; font-size: .95rem; text-decoration: none; }
        .pub-footer-links { display: flex; gap: 1.5rem; }
        .pub-footer-links a { color: rgba(255,255,255,.5); font-size: .82rem; text-decoration: none; transition: color .15s; }
        .pub-footer-links a:hover { color: #f97316; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════
     NAV
═══════════════════════════════════════════════════════ --}}
<nav class="pub-nav">
    <div class="pub-nav-inner">
        {{-- Logo --}}
        <a href="/" class="pub-logo">
            <div class="pub-logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            Dietician
        </a>

        {{-- Links --}}
        <div class="pub-nav-links">
            <a href="/" class="pub-nav-link active">Home</a>
            <a href="{{ route('pricing') }}" class="pub-nav-link">Pricing</a>

            @auth
                <a href="{{ route('dashboard') }}" class="pub-btn-primary" style="margin-left:.5rem">Dashboard →</a>
            @else
                <a href="{{ route('login') }}" class="pub-btn-outline" style="margin-left:.5rem">Log In</a>
                <a href="{{ route('register') }}" class="pub-btn-primary">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════ --}}
<div class="hero-wrap">
    {{-- decorative orbs --}}
    <div class="hero-orb" style="width:28rem;height:28rem;top:-8rem;right:-6rem"></div>
    <div class="hero-orb" style="width:18rem;height:18rem;bottom:-4rem;left:5%"></div>

    <div class="hero-inner">
        {{-- Copy --}}
        <div>
            <div class="hero-badge">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                Built for Registered Dieticians
            </div>
            <h1 class="hero-h1">Your practice,<br><span>beautifully organised.</span></h1>
            <p class="hero-sub">
                Track patients, calculate macronutrients, manage exchange templates and energy targets — all in one secure platform designed for nutrition professionals.
            </p>
            <div class="hero-cta">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-hero-primary">Go to Dashboard →</a>
                @else
                    <a href="{{ route('register') }}" class="btn-hero-primary">Start for Free</a>
                    <a href="{{ route('login') }}" class="btn-hero-ghost">Sign In</a>
                @endauth
            </div>
        </div>

        {{-- Mock patient card --}}
        <div class="hero-visual-col">
            <div class="hero-card">
                <div class="hero-card-header">
                    <div>
                        <div class="hero-card-header-name">Sarah M. — Patient Overview</div>
                        <div class="hero-card-header-sub">Updated today · TEE 8 400 kJ</div>
                    </div>
                    <div style="width:2rem;height:2rem;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    </div>
                </div>
                <div class="hero-card-body">
                    <div class="hc-label">Daily Targets</div>
                    <div class="hc-macro-grid">
                        <div class="hc-macro">
                            <div class="hc-macro-val">8 400</div>
                            <div class="hc-macro-unit">kJ</div>
                            <div class="hc-macro-name">Energy</div>
                        </div>
                        <div class="hc-macro">
                            <div class="hc-macro-val">250</div>
                            <div class="hc-macro-unit">g</div>
                            <div class="hc-macro-name">Carbs</div>
                        </div>
                        <div class="hc-macro">
                            <div class="hc-macro-val">75</div>
                            <div class="hc-macro-unit">g</div>
                            <div class="hc-macro-name">Protein</div>
                        </div>
                        <div class="hc-macro">
                            <div class="hc-macro-val">56</div>
                            <div class="hc-macro-unit">g</div>
                            <div class="hc-macro-name">Fat</div>
                        </div>
                    </div>
                    <div class="hc-label">Exchange Allocation</div>
                    <div class="hc-exchange-row"><span class="hc-ex-name">🥛 Milk / Dairy</span><span class="hc-ex-val">2 exchanges</span></div>
                    <div class="hc-exchange-row"><span class="hc-ex-name">🍎 Fruit</span><span class="hc-ex-val">3 exchanges</span></div>
                    <div class="hc-exchange-row"><span class="hc-ex-name">🍞 Starch</span><span class="hc-ex-val">6 exchanges</span></div>
                    <div class="hc-exchange-row"><span class="hc-ex-name">🥩 Protein</span><span class="hc-ex-val">4 exchanges</span></div>
                    <div class="hc-exchange-row"><span class="hc-ex-name">🥦 Vegetables</span><span class="hc-ex-val">5 exchanges</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     STATS BAND
═══════════════════════════════════════════════════════ --}}
<div style="padding: 0 1.5rem">
    <div class="stats-band">
        <div class="stat-band-item">
            <div class="stat-band-val">500+</div>
            <div class="stat-band-label">Exchange food items</div>
        </div>
        <div class="stat-band-item">
            <div class="stat-band-val">∞</div>
            <div class="stat-band-label">Patient profiles</div>
        </div>
        <div class="stat-band-item">
            <div class="stat-band-val">Live</div>
            <div class="stat-band-label">Macro calculations</div>
        </div>
        <div class="stat-band-item">
            <div class="stat-band-val">100%</div>
            <div class="stat-band-label">Secure & private</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     FEATURES
═══════════════════════════════════════════════════════ --}}
<div class="section" style="padding-top: 6rem">
    <div class="section-eyebrow">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:.75rem;height:.75rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Everything you need
    </div>
    <h2 class="section-h2">Tools built for <span>clinical nutrition</span></h2>
    <p class="section-lead">From patient registration to live macro calculations — every feature is designed around how dieticians actually work.</p>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon orange">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
            </div>
            <div class="feature-title">Exchange Templates</div>
            <div class="feature-desc">Build and save custom food exchange lists for common clinical scenarios. Assign them to patients in seconds.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon indigo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/>
                </svg>
            </div>
            <div class="feature-title">Live Macro Totals</div>
            <div class="feature-desc">Energy (kJ), carbohydrate, protein and fat targets update instantly as you adjust exchange allocations.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon teal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/>
                </svg>
            </div>
            <div class="feature-title">Meal Planner</div>
            <div class="feature-desc">Distribute exchanges across breakfast, lunch, dinner and snacks. Print or export ready-to-share meal plans.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon rose">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0"/>
                </svg>
            </div>
            <div class="feature-title">Patient Management</div>
            <div class="feature-desc">Store patient records, track BMI / BMR / TEE over time and monitor progress across multiple consultations.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon amber">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                </svg>
            </div>
            <div class="feature-title">Reports & Export</div>
            <div class="feature-desc">Generate downloadable reports with macro breakdowns and exchange totals — ready for patient handouts.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon sky">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4h8z"/>
                </svg>
            </div>
            <div class="feature-title">Secure Access</div>
            <div class="feature-desc">Login with your dietician number. Patient data is protected and only accessible to your account.</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     HOW IT WORKS
═══════════════════════════════════════════════════════ --}}
<div class="hiw-bg">
    <div class="section">
        <div class="hiw-grid">
            <div>
                <div class="section-eyebrow">How it works</div>
                <h2 class="section-h2">From registration to <span>meal plan</span> in minutes</h2>
                <p class="section-lead" style="margin-bottom:2.5rem">A streamlined workflow so you spend more time with patients, less time on admin.</p>

                <div class="hiw-step">
                    <div class="hiw-num">1</div>
                    <div>
                        <div class="hiw-step-title">Register your patient</div>
                        <div class="hiw-step-desc">Enter anthropometrics (height, weight, age, activity factor) to automatically calculate BMI, IBW, ABW and TEE.</div>
                    </div>
                </div>
                <div class="hiw-step">
                    <div class="hiw-num">2</div>
                    <div>
                        <div class="hiw-step-title">Set macro targets</div>
                        <div class="hiw-step-desc">Adjust carbohydrate, protein and fat distribution percentages. Energy in kJ or kcal is recalculated live.</div>
                    </div>
                </div>
                <div class="hiw-step">
                    <div class="hiw-num">3</div>
                    <div>
                        <div class="hiw-step-title">Assign exchange list</div>
                        <div class="hiw-step-desc">Pick an exchange template or build a custom one. Allocate portions of milk, fruit, starch, protein, fat and vegetables.</div>
                    </div>
                </div>
                <div class="hiw-step">
                    <div class="hiw-num">4</div>
                    <div>
                        <div class="hiw-step-title">Export & share</div>
                        <div class="hiw-step-desc">Download a formatted meal plan PDF or share it directly with the patient from the platform.</div>
                    </div>
                </div>
            </div>

            <div class="hiw-visual">
                <div class="hiw-vis-header">
                    <div class="hiw-vis-title">Sample Meal Plan — Sarah M.</div>
                    <span style="font-size:.72rem;color:rgba(255,255,255,.55)">8 400 kJ / day</span>
                </div>
                <div class="hiw-vis-body">
                    <div class="meal-row">
                        <div>
                            <div class="meal-name">🌅 Breakfast</div>
                            <div style="font-size:.72rem;color:var(--text-muted,#64748b);margin-top:.15rem">Starch × 2 · Fruit × 1 · Milk × 1</div>
                        </div>
                        <div class="meal-chips">
                            <span class="chip orange">2 100 kJ</span>
                            <span class="chip indigo">62g C</span>
                            <span class="chip teal">18g P</span>
                        </div>
                    </div>
                    <div class="meal-row">
                        <div>
                            <div class="meal-name">☀️ Lunch</div>
                            <div style="font-size:.72rem;color:var(--text-muted,#64748b);margin-top:.15rem">Starch × 2 · Protein × 2 · Veg × 2</div>
                        </div>
                        <div class="meal-chips">
                            <span class="chip orange">2 500 kJ</span>
                            <span class="chip indigo">60g C</span>
                            <span class="chip teal">28g P</span>
                        </div>
                    </div>
                    <div class="meal-row">
                        <div>
                            <div class="meal-name">🌙 Dinner</div>
                            <div style="font-size:.72rem;color:var(--text-muted,#64748b);margin-top:.15rem">Starch × 1 · Protein × 2 · Veg × 2 · Fat × 1</div>
                        </div>
                        <div class="meal-chips">
                            <span class="chip orange">2 400 kJ</span>
                            <span class="chip indigo">48g C</span>
                            <span class="chip teal">28g P</span>
                        </div>
                    </div>
                    <div class="meal-row">
                        <div>
                            <div class="meal-name">🍎 Snacks</div>
                            <div style="font-size:.72rem;color:var(--text-muted,#64748b);margin-top:.15rem">Fruit × 1 · Milk × 1</div>
                        </div>
                        <div class="meal-chips">
                            <span class="chip orange">1 400 kJ</span>
                            <span class="chip indigo">38g C</span>
                            <span class="chip teal">8g P</span>
                        </div>
                    </div>
                    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f1f5f9;display:flex;gap:.5rem;flex-wrap:wrap">
                        <span class="chip orange" style="font-size:.72rem">Total 8 400 kJ</span>
                        <span class="chip indigo" style="font-size:.72rem">208g Carbs</span>
                        <span class="chip teal" style="font-size:.72rem">82g Protein</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     CTA
═══════════════════════════════════════════════════════ --}}
<div class="cta-strip">
    <div class="cta-inner">
        <h2 class="cta-h2">Ready to streamline your practice?</h2>
        <p class="cta-sub">Join dieticians already saving hours of admin time each week. Free to get started, no credit card required.</p>
        <div class="cta-btns">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-hero-primary">Go to Dashboard →</a>
            @else
                <a href="{{ route('register') }}" class="btn-hero-primary">Create Free Account</a>
                <a href="{{ route('pricing') }}" class="btn-hero-ghost">View Pricing</a>
            @endauth
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════ --}}
<footer class="pub-footer">
    <div class="pub-footer-inner">
        <a href="/" class="pub-footer-logo">
            <div style="width:1.75rem;height:1.75rem;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            Dietician
        </a>
        <span style="font-size:.82rem">© {{ date('Y') }} Dietician Platform. All rights reserved.</span>
        <div class="pub-footer-links">
            <a href="{{ route('pricing') }}">Pricing</a>
            <a href="{{ route('login') }}">Log In</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
</footer>

</body>
</html>
