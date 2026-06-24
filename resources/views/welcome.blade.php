<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MindfulNutrico — Professional Nutrition Practice Platform</title>
    <meta name="theme-color" content="#679F5F">
    <link rel="icon" type="image/png" href="{{ asset('images/mindful-nutrico.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link href="{{ asset('css/soft-ui-overrides.css') }}" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Figtree', 'Inter', sans-serif; background: var(--bg-page, #f5f8f5); color: var(--text-primary, #0d1f0c); line-height: 1.6; }

        /* NAV */
        .pub-nav { position: sticky; top: 0; z-index: 50; background: rgba(255,255,255,0.92); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border, #d4e6d1); box-shadow: 0 2px 12px rgba(13,31,12,.06); }
        .pub-nav-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; height: 64px; padding: 0 1.5rem; }
        .pub-logo { display: flex; align-items: center; gap: .6rem; text-decoration: none; }
        .pub-logo img { height: 2.2rem; width: auto; }
        .pub-logo-wm { font-size: 1.1rem; font-weight: 800; letter-spacing: -.02em; }
        .pub-logo-wm .mn { color: #4d7d47; }
        .pub-logo-wm .nu { color: #429677; }
        .pub-nav-links { display: flex; align-items: center; gap: .5rem; }
        .pub-nav-link { padding: .4rem .85rem; border-radius: .65rem; font-size: .85rem; font-weight: 600; text-decoration: none; color: var(--text-muted, #52705e); transition: all .15s; }
        .pub-nav-link:hover { background: #e8f5e6; color: #4d7d47; }
        .pub-nav-link.active { background: linear-gradient(135deg,#e8f5e6,#c8e6c4); color: #4d7d47; }
        .pub-btn-outline { padding: .42rem 1.1rem; border: 1.5px solid var(--border,#d4e6d1); border-radius: .5rem; color: var(--text-primary, #0d1f0c); font-size: .85rem; font-weight: 600; text-decoration: none; background: #fff; transition: all .15s; }
        .pub-btn-outline:hover { border-color: #679F5F; color: #679F5F; }
        .pub-btn-primary { padding: .42rem 1.1rem; background: linear-gradient(135deg, #679F5F, #429677); border: none; border-radius: .5rem; color: #fff; font-size: .85rem; font-weight: 700; text-decoration: none; box-shadow: 0 4px 14px rgba(103,159,95,.35); transition: all .15s; }
        .pub-btn-primary:hover { box-shadow: 0 6px 20px rgba(103,159,95,.50); color: #fff; }

        /* HERO */
        .hero-wrap { background: linear-gradient(135deg, #0d1f0c 0%, #2e6e56 45%, #679F5F 100%); position: relative; overflow: hidden; padding: 5rem 1.5rem 7rem; }
        .hero-wrap::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 20% 60%, rgba(103,159,95,.25) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(66,150,119,.35) 0%, transparent 50%); }
        .hero-orb { position: absolute; border-radius: 50%; background: rgba(255,255,255,.04); }
        .hero-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 3.5rem; align-items: center; position: relative; z-index: 1; }
        @media (max-width: 860px) { .hero-inner { grid-template-columns: 1fr; } .hero-visual-col { display: none; } .hero-wrap { padding: 3.5rem 1.25rem 6rem; } }
        .hero-badge { display: inline-flex; align-items: center; gap: .4rem; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.22); color: #a8d5a2; font-size: .78rem; font-weight: 700; padding: .3rem .85rem; border-radius: 999px; margin-bottom: 1.25rem; backdrop-filter: blur(8px); }
        .hero-h1 { font-size: clamp(2.2rem, 4.5vw, 3.4rem); font-weight: 800; line-height: 1.12; letter-spacing: -.04em; color: #fff; margin-bottom: 1.25rem; }
        .hero-h1 span { background: linear-gradient(90deg, #a8d5a2, #8dc485); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-sub { font-size: 1.05rem; color: rgba(255,255,255,.75); max-width: 460px; line-height: 1.75; margin-bottom: 2.25rem; }
        .hero-cta { display: flex; gap: .75rem; flex-wrap: wrap; }
        .btn-hero-primary { display: inline-block; padding: .85rem 2rem; background: linear-gradient(135deg, #679F5F, #429677); color: #fff; font-size: 1rem; font-weight: 700; text-decoration: none; border-radius: .6rem; box-shadow: 0 8px 24px rgba(103,159,95,.50); transition: all .2s; }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(103,159,95,.60); color: #fff; }
        .btn-hero-ghost { display: inline-block; padding: .85rem 2rem; border: 2px solid rgba(255,255,255,.35); color: #fff; font-size: 1rem; font-weight: 700; text-decoration: none; border-radius: .6rem; background: rgba(255,255,255,.08); backdrop-filter: blur(8px); transition: all .2s; }
        .btn-hero-ghost:hover { border-color: rgba(255,255,255,.7); background: rgba(255,255,255,.15); color: #fff; }

        /* HERO MOCK CARD */
        .hero-card { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.18); border-radius: .75rem; backdrop-filter: blur(16px); overflow: hidden; }
        .hero-card-header { background: rgba(255,255,255,.1); border-bottom: 1px solid rgba(255,255,255,.15); padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; }
        .hero-card-header-name { color: #fff; font-size: .9rem; font-weight: 700; }
        .hero-card-header-sub { color: rgba(255,255,255,.55); font-size: .72rem; margin-top: .1rem; }
        .hero-card-body { padding: 1.25rem; }
        .hc-label { font-size: .65rem; text-transform: uppercase; letter-spacing: .07em; color: rgba(255,255,255,.5); margin-bottom: .75rem; font-weight: 700; }
        .hc-macro-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: .5rem; margin-bottom: 1.1rem; }
        .hc-macro { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); border-radius: .5rem; padding: .65rem .5rem; text-align: center; }
        .hc-macro-val { font-size: 1rem; font-weight: 800; color: #a8d5a2; }
        .hc-macro-unit { font-size: .6rem; color: rgba(255,255,255,.45); }
        .hc-macro-name { font-size: .65rem; font-weight: 600; color: rgba(255,255,255,.6); margin-top: .15rem; }
        .hc-exchange-row { display: flex; align-items: center; justify-content: space-between; padding: .55rem 0; border-bottom: 1px solid rgba(255,255,255,.08); font-size: .78rem; }
        .hc-exchange-row:last-child { border-bottom: none; }
        .hc-ex-name { color: rgba(255,255,255,.8); font-weight: 600; }
        .hc-ex-val { color: #a8d5a2; font-weight: 700; }

        /* STATS BAND */
        .stats-band { max-width: 1200px; margin: -3.5rem auto 0; position: relative; z-index: 10; display: grid; grid-template-columns: repeat(4,1fr); background: #fff; border: 1px solid var(--border, #d4e6d1); border-radius: .75rem; box-shadow: 0 20px 40px rgba(13,31,12,.12); overflow: hidden; }
        @media (max-width: 860px) { .stats-band { grid-template-columns: repeat(2,1fr); margin: -2rem 1.25rem 0; } }
        @media (max-width: 460px) { .stats-band { grid-template-columns: 1fr; } }
        .stat-band-item { padding: 1.5rem 1.75rem; border-right: 1px solid var(--border, #d4e6d1); position: relative; }
        .stat-band-item:last-child { border-right: none; }
        .stat-band-item::before { content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%; background: linear-gradient(to bottom, #679F5F, #429677); }
        .stat-band-val { font-size: 1.65rem; font-weight: 800; color: var(--text-primary, #0d1f0c); }
        .stat-band-label { font-size: .75rem; font-weight: 600; color: var(--text-muted, #52705e); margin-top: .2rem; text-transform: uppercase; letter-spacing: .06em; }

        /* SECTIONS */
        .section { max-width: 1200px; margin: 0 auto; padding: 5rem 1.5rem; }
        @media (max-width: 640px) { .section { padding: 3rem 1.25rem; } }
        .section-eyebrow { display: inline-flex; align-items: center; gap: .4rem; background: #e8f5e6; color: #4d7d47; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; padding: .3rem .85rem; border-radius: 999px; margin-bottom: 1rem; }
        .section-h2 { font-size: clamp(1.75rem, 3.5vw, 2.6rem); font-weight: 800; letter-spacing: -.035em; line-height: 1.15; color: var(--text-primary, #0d1f0c); margin-bottom: 1rem; }
        .section-h2 span { color: #679F5F; }
        .section-lead { font-size: 1.05rem; color: var(--text-muted, #52705e); max-width: 560px; line-height: 1.75; }

        /* FEATURE CARDS */
        .features-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: var(--border, #d4e6d1); border: 1px solid var(--border, #d4e6d1); border-radius: .75rem; overflow: hidden; margin-top: 3rem; }
        @media (max-width: 860px) { .features-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 500px) { .features-grid { grid-template-columns: 1fr; } }
        .feature-card { background: #fff; padding: 2rem 1.75rem; transition: background .2s; }
        .feature-card:hover { background: #f4fbf3; }
        .feature-icon { width: 2.75rem; height: 2.75rem; border-radius: .6rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; }
        .feature-icon.green  { background: linear-gradient(135deg, #679F5F, #8dc485); }
        .feature-icon.teal   { background: linear-gradient(135deg, #2e6e56, #429677); }
        .feature-icon.lime   { background: linear-gradient(135deg, #4d7d47, #679F5F); }
        .feature-icon.rose   { background: linear-gradient(135deg, #D21E33, #f43f5e); }
        .feature-icon.moss   { background: linear-gradient(135deg, #429677, #5cb896); }
        .feature-icon.forest { background: linear-gradient(135deg, #0d1f0c, #2e6e56); }
        .feature-icon svg { width: 1.3rem; height: 1.3rem; color: #fff; }
        .feature-title { font-size: 1rem; font-weight: 700; color: var(--text-primary, #0d1f0c); margin-bottom: .5rem; }
        .feature-desc { font-size: .875rem; color: var(--text-muted, #52705e); line-height: 1.7; }

        /* HOW IT WORKS */
        .hiw-bg { background: #f0f7ef; border-top: 1px solid var(--border,#d4e6d1); border-bottom: 1px solid var(--border,#d4e6d1); }
        .hiw-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
        @media (max-width: 860px) { .hiw-grid { grid-template-columns: 1fr; gap: 2.5rem; } }
        .hiw-step { display: flex; gap: 1.25rem; margin-bottom: 2rem; }
        .hiw-step:last-child { margin-bottom: 0; }
        .hiw-num { width: 2.25rem; height: 2.25rem; flex-shrink: 0; background: linear-gradient(135deg, #679F5F, #429677); color: #fff; font-size: .8rem; font-weight: 800; display: flex; align-items: center; justify-content: center; border-radius: .4rem; }
        .hiw-step-title { font-size: .95rem; font-weight: 700; color: var(--text-primary, #0d1f0c); margin-bottom: .3rem; }
        .hiw-step-desc { font-size: .85rem; color: var(--text-muted, #52705e); line-height: 1.65; }
        .hiw-visual { background: #fff; border: 1px solid var(--border,#d4e6d1); border-radius: .75rem; box-shadow: 0 20px 40px rgba(13,31,12,.08); overflow: hidden; }
        .hiw-vis-header { background: linear-gradient(135deg, #0d1f0c, #2e6e56); padding: 1rem 1.25rem; color: #fff; display: flex; align-items: center; justify-content: space-between; }
        .hiw-vis-title { font-size: .85rem; font-weight: 700; }
        .hiw-vis-body { padding: 1.25rem; }
        .meal-row { display: flex; align-items: center; justify-content: space-between; padding: .7rem 0; border-bottom: 1px solid #edf5ec; }
        .meal-row:last-child { border-bottom: none; }
        .meal-name { font-size: .83rem; font-weight: 600; color: var(--text-primary,#0d1f0c); }
        .meal-chips { display: flex; gap: .35rem; }
        .chip { padding: .18rem .55rem; font-size: .65rem; font-weight: 700; border-radius: 999px; }
        .chip.green  { background: #e8f5e6; color: #4d7d47; }
        .chip.teal   { background: #e0f2ee; color: #2e6e56; }
        .chip.forest { background: #d8ede6; color: #1a4a3a; }

        /* CTA */
        .cta-strip { background: linear-gradient(135deg, #0d1f0c 0%, #2e6e56 55%, #679F5F 100%); position: relative; overflow: hidden; }
        .cta-strip::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 75% 50%, rgba(103,159,95,.30) 0%, transparent 55%); }
        .cta-inner { max-width: 900px; margin: 0 auto; padding: 4.5rem 1.5rem; text-align: center; position: relative; z-index: 1; }
        .cta-h2 { font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 800; color: #fff; letter-spacing: -.03em; margin-bottom: 1rem; }
        .cta-sub { color: rgba(255,255,255,.75); font-size: 1rem; line-height: 1.75; margin-bottom: 2rem; max-width: 520px; margin-left: auto; margin-right: auto; }
        .cta-btns { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }

        /* FOOTER */
        .pub-footer { background: #0d1f0c; color: rgba(255,255,255,.6); padding: 2.5rem 1.5rem; }
        .pub-footer-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
        .pub-footer-logo { display: flex; align-items: center; gap: .6rem; color: #fff; font-weight: 800; font-size: .95rem; text-decoration: none; }
        .pub-footer-logo img { height: 1.75rem; width: auto; }
        .pub-footer-links { display: flex; gap: 1.5rem; }
        .pub-footer-links a { color: rgba(255,255,255,.5); font-size: .82rem; text-decoration: none; transition: color .15s; }
        .pub-footer-links a:hover { color: #8dc485; }
    </style>
</head>
<body>

<nav class="pub-nav">
    <div class="pub-nav-inner">
        <a href="/" class="pub-logo">
            <img src="{{ asset('images/mindful-nutrico.png') }}" alt="MindfulNutrico">
            <span class="pub-logo-wm"><span class="mn">mindful</span><span class="nu">nutrico</span></span>
        </a>
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

<div class="hero-wrap">
    <div class="hero-orb" style="width:28rem;height:28rem;top:-8rem;right:-6rem"></div>
    <div class="hero-orb" style="width:18rem;height:18rem;bottom:-4rem;left:5%"></div>
    <div class="hero-inner">
        <div>
            <div class="hero-badge">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Built for Registered Dietitians
            </div>
            <h1 class="hero-h1">Simplify your<br><span>nutrition interventions.</span></h1>
            <p class="hero-sub">Track patients, calculate macronutrients, manage exchange templates and energy targets. All in one secure platform designed for nutrition professionals.</p>
            <div class="hero-cta">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-hero-primary">Go to Dashboard →</a>
                @else
                    <a href="{{ route('register') }}" class="btn-hero-primary">Start for Free</a>
                    <a href="{{ route('login') }}" class="btn-hero-ghost">Sign In</a>
                @endauth
            </div>
        </div>
        <div class="hero-visual-col">
            <div class="hero-card">
                <div class="hero-card-header">
                    <div>
                        <div class="hero-card-header-name">Sarah M. — Patient Overview</div>
                        <div class="hero-card-header-sub">Updated today · TEE 8 400 kJ</div>
                    </div>
                    <div style="width:2rem;height:2rem;background:linear-gradient(135deg,#679F5F,#429677);border-radius:.4rem;display:flex;align-items:center;justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.9rem;height:.9rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </div>
                </div>
                <div class="hero-card-body">
                    <div class="hc-label">Daily Targets</div>
                    <div class="hc-macro-grid">
                        <div class="hc-macro"><div class="hc-macro-val">8 400</div><div class="hc-macro-unit">kJ</div><div class="hc-macro-name">Energy</div></div>
                        <div class="hc-macro"><div class="hc-macro-val">250</div><div class="hc-macro-unit">g</div><div class="hc-macro-name">Carbs</div></div>
                        <div class="hc-macro"><div class="hc-macro-val">75</div><div class="hc-macro-unit">g</div><div class="hc-macro-name">Protein</div></div>
                        <div class="hc-macro"><div class="hc-macro-val">56</div><div class="hc-macro-unit">g</div><div class="hc-macro-name">Fat</div></div>
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

<div style="padding: 0 1.5rem">
    <div class="stats-band">
        <div class="stat-band-item">
            <div class="stat-band-val">&nbsp;</div>
            <div class="stat-band-label">Food exchange list items</div>
        </div>
        <div class="stat-band-item"><div class="stat-band-val">∞</div><div class="stat-band-label">Patient profiles</div></div>
        <div class="stat-band-item"><div class="stat-band-val">Live</div><div class="stat-band-label">Macro calculations</div></div>
        <div class="stat-band-item"><div class="stat-band-val">100%</div><div class="stat-band-label">Secure & private</div></div>
    </div>
</div>

<div class="section" style="padding-top: 6rem">
    <div class="section-eyebrow">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:.75rem;height:.75rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Everything you need
    </div>
    <h2 class="section-h2">Tools built for <span>clinical nutrition</span></h2>
    <p class="section-lead">From patient registration to live macro calculations — every feature is designed around how dietitians actually work.</p>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon green"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg></div>
            <div class="feature-title">Exchange Templates</div>
            <div class="feature-desc">Build and save custom food exchange lists for common clinical scenarios. Assign them to patients in seconds.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon teal"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/></svg></div>
            <div class="feature-title">Live Macro Totals</div>
            <div class="feature-desc">Energy (kJ), carbohydrate, protein and fat targets update instantly as you adjust exchange allocations.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon lime"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/></svg></div>
            <div class="feature-title">Meal Planner</div>
            <div class="feature-desc">Distribute exchanges across breakfast, lunch, dinner and snacks. Print or export ready-to-share meal plans.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon rose"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0"/></svg></div>
            <div class="feature-title">Patient Management</div>
            <div class="feature-desc">Store patient records, track BMI / BMR / TEE over time and monitor progress across multiple consultations.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon moss"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/></svg></div>
            <div class="feature-title">Reports & Export</div>
            <div class="feature-desc">Generate downloadable reports with macro breakdowns and exchange totals — ready for patient handouts.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon forest"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4h8z"/></svg></div>
            <div class="feature-title">Secure Access</div>
            <div class="feature-desc">Login with your dietitian number. Patient data is protected and only accessible to your account.</div>
        </div>
    </div>
</div>

<div class="cta-strip">
    <div class="cta-inner">
        <h2 class="cta-h2">Ready to streamline your practice?</h2>
        <p class="cta-sub">Join dietitians already saving hours of admin time each week. Free to get started, no credit card required.</p>
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

<footer class="pub-footer">
    <div class="pub-footer-inner">
        <a href="/" class="pub-footer-logo">
            <img src="{{ asset('images/mindful-nutrico.png') }}" alt="MindfulNutrico">
            <span><span style="color:#8dc485">mindful</span><span style="color:#5cb896">nutrico</span></span>
        </a>
        <span style="font-size:.82rem">© {{ date('Y') }} MindfulNutrico. All rights reserved.</span>
        <div class="pub-footer-links">
            <a href="{{ route('pricing') }}">Pricing</a>
            <a href="{{ route('login') }}">Log In</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
</footer>

</body>
</html>
