<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pricing — Dietician Platform</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link href="{{ asset('css/soft-ui-overrides.css') }}" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Figtree', 'Inter', sans-serif; background: var(--bg-page, #f8fafc); color: var(--text-primary, #0f172a); line-height: 1.6; }

        /* ── Shared nav (same as welcome) ─────── */
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

        /* ── Page hero ───────────────────────── */
        .pricing-hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #f97316 100%);
            position: relative; overflow: hidden;
            padding: 4.5rem 1.5rem 6.5rem;
            text-align: center;
        }
        .pricing-hero::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(circle at 20% 60%, rgba(249,115,22,.30) 0%, transparent 50%),
                radial-gradient(circle at 80% 30%, rgba(99,102,241,.40) 0%, transparent 50%);
        }
        .pricing-hero-inner { position: relative; z-index: 1; max-width: 700px; margin: 0 auto; }
        .pricing-badge {
            display: inline-flex; align-items: center; gap: .4rem;
            background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.22);
            color: #fdba74; font-size: .78rem; font-weight: 700;
            padding: .3rem .85rem; border-radius: 999px; margin-bottom: 1.25rem;
            backdrop-filter: blur(8px);
        }
        .pricing-h1 {
            font-size: clamp(2rem, 4.5vw, 3rem);
            font-weight: 800; line-height: 1.12;
            letter-spacing: -.04em; color: #fff; margin-bottom: 1rem;
        }
        .pricing-h1 span {
            background: linear-gradient(90deg, #fdba74, #f97316);
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .pricing-sub { font-size: 1.05rem; color: rgba(255,255,255,.7); line-height: 1.75; }

        /* ── Pricing cards grid ──────────────── */
        .pricing-section {
            max-width: 1100px; margin: 0 auto;
            padding: 0 1.5rem 5rem;
            margin-top: -3.5rem;
            position: relative; z-index: 10;
        }
        .pricing-grid {
            display: grid; grid-template-columns: repeat(4,1fr); gap: 0;
            background: var(--border, #e2e8f0);
            border: 1px solid var(--border, #e2e8f0);
            box-shadow: 0 24px 48px rgba(0,0,0,.12);
        }
        @media (max-width: 900px) { .pricing-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 520px)  { .pricing-grid { grid-template-columns: 1fr; } }

        .pricing-card {
            background: #fff;
            padding: 2rem 1.75rem 2.25rem;
            position: relative;
            display: flex; flex-direction: column;
            transition: background .2s;
        }
        .pricing-card:hover { background: #fafafa; }
        .pricing-card.featured {
            background: linear-gradient(160deg, #1e1b4b 0%, #312e81 60%, #4f46e5 100%);
            color: #fff;
        }
        .pricing-card.featured:hover { background: linear-gradient(160deg, #1e1b4b 0%, #3730a3 55%, #4f46e5 100%); }

        .pc-badge {
            display: inline-block; padding: .2rem .7rem;
            font-size: .65rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: .08em; border-radius: 999px; margin-bottom: 1.25rem;
            width: fit-content;
        }
        .pc-badge.free    { background: #f1f5f9; color: #475569; }
        .pc-badge.popular { background: #f97316; color: #fff; }
        .pc-badge.pro     { background: #eef2ff; color: #4338ca; }
        .pc-badge.team    { background: #f0fdfa; color: #0f766e; }

        .pc-name { font-size: 1.1rem; font-weight: 800; margin-bottom: .35rem; }
        .pricing-card:not(.featured) .pc-name { color: var(--text-primary, #0f172a); }
        .pricing-card.featured .pc-name { color: #fff; }

        .pc-price {
            display: flex; align-items: baseline; gap: .2rem;
            margin-bottom: .25rem;
        }
        .pc-currency { font-size: .9rem; font-weight: 700; }
        .pc-amount   { font-size: 2.4rem; font-weight: 800; line-height: 1; letter-spacing: -.04em; }
        .pc-period   { font-size: .8rem; }
        .pricing-card:not(.featured) .pc-currency,
        .pricing-card:not(.featured) .pc-amount   { color: var(--text-primary, #0f172a); }
        .pricing-card:not(.featured) .pc-period   { color: var(--text-muted, #64748b); }
        .pricing-card.featured .pc-currency,
        .pricing-card.featured .pc-amount { color: #fdba74; }
        .pricing-card.featured .pc-period { color: rgba(255,255,255,.55); }

        .pc-tagline { font-size: .82rem; margin-bottom: 1.5rem; }
        .pricing-card:not(.featured) .pc-tagline { color: var(--text-muted, #64748b); }
        .pricing-card.featured .pc-tagline { color: rgba(255,255,255,.65); }

        .pc-divider {
            height: 1px; margin-bottom: 1.5rem;
        }
        .pricing-card:not(.featured) .pc-divider { background: var(--border, #e2e8f0); }
        .pricing-card.featured .pc-divider { background: rgba(255,255,255,.15); }

        .pc-features { list-style: none; flex: 1; margin-bottom: 2rem; display: flex; flex-direction: column; gap: .7rem; }
        .pc-features li {
            display: flex; align-items: flex-start; gap: .6rem;
            font-size: .85rem; line-height: 1.5;
        }
        .pricing-card:not(.featured) .pc-features li { color: var(--text-primary, #0f172a); }
        .pricing-card.featured .pc-features li { color: rgba(255,255,255,.85); }
        .pc-check {
            width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: .1rem;
        }
        .pricing-card:not(.featured) .pc-check { color: #f97316; }
        .pricing-card.featured .pc-check { color: #fdba74; }

        .pc-cta {
            display: block; width: 100%; text-align: center;
            padding: .7rem 1rem; font-size: .9rem; font-weight: 700;
            text-decoration: none; transition: all .2s; border: none; cursor: pointer;
        }
        .pc-cta.outline {
            border: 1.5px solid var(--border, #e2e8f0); color: var(--text-primary, #0f172a);
            background: #fff;
        }
        .pc-cta.outline:hover { border-color: #f97316; color: #f97316; }
        .pc-cta.solid-orange {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff; box-shadow: 0 4px 14px rgba(249,115,22,.40);
        }
        .pc-cta.solid-orange:hover { box-shadow: 0 8px 20px rgba(249,115,22,.55); }
        .pc-cta.solid-white {
            background: rgba(255,255,255,.15);
            border: 1.5px solid rgba(255,255,255,.30); color: #fff;
        }
        .pc-cta.solid-white:hover { background: rgba(255,255,255,.25); }

        /* ── Comparison note ─────────────────── */
        .compare-section {
            max-width: 1100px; margin: 0 auto;
            padding: 0 1.5rem 5rem;
        }
        .compare-heading { font-size: 1.5rem; font-weight: 800; letter-spacing: -.025em; color: var(--text-primary,#0f172a); margin-bottom: 1.5rem; }
        .compare-table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid var(--border,#e2e8f0); box-shadow: 0 4px 24px rgba(0,0,0,.06); }
        .compare-table thead th {
            padding: 1rem 1.25rem; text-align: left;
            font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
            color: var(--text-muted,#64748b); background: #f8fafc;
            border-bottom: 1px solid var(--border,#e2e8f0);
        }
        .compare-table thead th:first-child { width: 40%; }
        .compare-table thead th:not(:first-child) { text-align: center; }
        .compare-table tbody td {
            padding: .85rem 1.25rem; font-size: .875rem;
            border-bottom: 1px solid #f1f5f9; color: var(--text-primary,#0f172a);
        }
        .compare-table tbody tr:last-child td { border-bottom: none; }
        .compare-table tbody td:not(:first-child) { text-align: center; }
        .compare-table tbody tr:hover td { background: #fef9f5; }
        .tick { color: #f97316; font-size: 1rem; }
        .dash { color: #cbd5e1; font-size: 1rem; }
        .feature-label { font-weight: 600; }

        /* ── FAQ strip ───────────────────────── */
        .faq-section {
            background: #f8fafc;
            border-top: 1px solid var(--border,#e2e8f0);
            padding: 4rem 1.5rem;
        }
        .faq-inner { max-width: 700px; margin: 0 auto; }
        .faq-h2 { font-size: 1.75rem; font-weight: 800; letter-spacing: -.03em; margin-bottom: 2rem; text-align: center; }
        .faq-item { border-bottom: 1px solid var(--border,#e2e8f0); padding: 1.25rem 0; }
        .faq-item:last-child { border-bottom: none; }
        .faq-q { font-size: .95rem; font-weight: 700; color: var(--text-primary,#0f172a); margin-bottom: .5rem; }
        .faq-a { font-size: .875rem; color: var(--text-muted,#64748b); line-height: 1.75; }

        /* ── CTA strip ───────────────────────── */
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
        .btn-hero-primary {
            display: inline-block; padding: .85rem 2rem;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff; font-size: 1rem; font-weight: 700;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(249,115,22,.50); transition: all .2s;
        }
        .btn-hero-primary:hover { transform: translateY(-2px); color: #fff; }
        .btn-hero-ghost {
            display: inline-block; padding: .85rem 2rem;
            border: 2px solid rgba(255,255,255,.35); color: #fff;
            font-size: 1rem; font-weight: 700; text-decoration: none;
            background: rgba(255,255,255,.08); backdrop-filter: blur(8px); transition: all .2s;
        }
        .btn-hero-ghost:hover { border-color: rgba(255,255,255,.7); color: #fff; }

        /* ── Footer ─────────────────────────── */
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

{{-- ═══ NAV ═══ --}}
<nav class="pub-nav">
    <div class="pub-nav-inner">
        <a href="/" class="pub-logo">
            <div class="pub-logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            Dietician
        </a>
        <div class="pub-nav-links">
            <a href="/" class="pub-nav-link">Home</a>
            <a href="{{ route('pricing') }}" class="pub-nav-link active">Pricing</a>
            @auth
                <a href="{{ route('dashboard') }}" class="pub-btn-primary" style="margin-left:.5rem">Dashboard →</a>
            @else
                <a href="{{ route('login') }}" class="pub-btn-outline" style="margin-left:.5rem">Log In</a>
                <a href="{{ route('register') }}" class="pub-btn-primary">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

{{-- ═══ HERO ═══ --}}
<div class="pricing-hero">
    <div class="pricing-hero-inner">
        <div class="pricing-badge">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
            </svg>
            Simple, transparent pricing
        </div>
        <h1 class="pricing-h1">Plans for every <span>practice size</span></h1>
        <p class="pricing-sub">Start free with essential calculators. Upgrade when you need the full clinical toolkit — no hidden fees.</p>
    </div>
</div>

{{-- ═══ CARDS ═══ --}}
<div class="pricing-section">
    <div class="pricing-grid">

        {{-- Free --}}
        <div class="pricing-card">
            <span class="pc-badge free">Free</span>
            <div class="pc-name">Free</div>
            <div class="pc-price">
                <span class="pc-currency">R</span>
                <span class="pc-amount">0</span>
                <span class="pc-period">/ month</span>
            </div>
            <div class="pc-tagline">Essential calculators to get started</div>
            <div class="pc-divider"></div>
            <ul class="pc-features">
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    BMI calculator
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    ABW · IBW · AF calculations
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    RMR · BMR estimations
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    1 device / 1 user
                </li>
            </ul>
            <a href="{{ route('register') }}" class="pc-cta outline">Get started free</a>
        </div>

        {{-- Package 1 --}}
        <div class="pricing-card featured">
            <span class="pc-badge popular">Most Popular</span>
            <div class="pc-name">Package 1</div>
            <div class="pc-price">
                <span class="pc-currency">R</span>
                <span class="pc-amount">499</span>
                <span class="pc-period">/ month</span>
            </div>
            <div class="pc-tagline">Full clinical nutrition toolkit</div>
            <div class="pc-divider"></div>
            <ul class="pc-features">
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Everything in Free
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Macro distribution (C · P · F) & fibre/fluid targets
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Food exchange list (500+ items)
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Meal plan template (downloadable)
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Grocery list generator
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    1 device / 1 user
                </li>
            </ul>
            <a href="{{ route('register') }}" class="pc-cta solid-orange">Get Package 1</a>
        </div>

        {{-- Package 2 --}}
        <div class="pricing-card">
            <span class="pc-badge pro">Package 2</span>
            <div class="pc-name">Package 2</div>
            <div class="pc-price">
                <span class="pc-currency">R</span>
                <span class="pc-amount">699</span>
                <span class="pc-period">/ month</span>
            </div>
            <div class="pc-tagline">For dieticians with assistants</div>
            <div class="pc-divider"></div>
            <ul class="pc-features">
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Everything in Package 1
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Recipe library
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Patient food diary
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Weekly email reminders
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    2 devices / 2 users
                </li>
            </ul>
            <a href="{{ route('register') }}" class="pc-cta outline">Get Package 2</a>
        </div>

        {{-- Package 3 --}}
        <div class="pricing-card">
            <span class="pc-badge team">Team</span>
            <div class="pc-name">Package 3</div>
            <div class="pc-price">
                <span class="pc-currency">R</span>
                <span class="pc-amount">899</span>
                <span class="pc-period">/ month</span>
            </div>
            <div class="pc-tagline">Multi-dietician practice or clinic</div>
            <div class="pc-divider"></div>
            <ul class="pc-features">
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Everything in Package 2
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Enteral feed calculations & selection
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Daily monitoring (rate / volume)
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Group package — up to 5 users
                </li>
                <li>
                    <svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    5-user group: <strong>R 1 800 / month</strong>
                </li>
            </ul>
            <a href="{{ route('register') }}" class="pc-cta outline">Get Package 3</a>
        </div>

    </div>
</div>

{{-- ═══ COMPARISON TABLE ═══ --}}
<div class="compare-section">
    <h2 class="compare-heading">Feature comparison</h2>
    <div style="overflow-x:auto">
        <table class="compare-table">
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>Free</th>
                    <th>Package 1</th>
                    <th>Package 2</th>
                    <th>Package 3</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="feature-label">BMI · ABW · IBW · AF</td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">RMR · BMR calculations</td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Macro distribution (C · P · F)</td>
                    <td><span class="dash">—</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Food exchange list (500+ items)</td>
                    <td><span class="dash">—</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Meal plan template (downloadable)</td>
                    <td><span class="dash">—</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Grocery list generator</td>
                    <td><span class="dash">—</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Recipe library</td>
                    <td><span class="dash">—</span></td>
                    <td><span class="dash">—</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Food diary</td>
                    <td><span class="dash">—</span></td>
                    <td><span class="dash">—</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Weekly email reminders</td>
                    <td><span class="dash">—</span></td>
                    <td><span class="dash">—</span></td>
                    <td><span class="tick">✓</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Enteral feed calculations</td>
                    <td><span class="dash">—</span></td>
                    <td><span class="dash">—</span></td>
                    <td><span class="dash">—</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Daily monitoring (rate/volume)</td>
                    <td><span class="dash">—</span></td>
                    <td><span class="dash">—</span></td>
                    <td><span class="dash">—</span></td>
                    <td><span class="tick">✓</span></td>
                </tr>
                <tr>
                    <td class="feature-label">Users / devices</td>
                    <td>1</td>
                    <td>1</td>
                    <td>2</td>
                    <td>2–5</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ═══ FAQ ═══ --}}
<div class="faq-section">
    <div class="faq-inner">
        <h2 class="faq-h2">Frequently asked questions</h2>
        <div class="faq-item">
            <div class="faq-q">Can I start on the Free plan and upgrade later?</div>
            <div class="faq-a">Yes — your account and all patient data are preserved when you upgrade. Upgrading takes effect immediately.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">What currency are prices listed in?</div>
            <div class="faq-a">All prices are in South African Rand (ZAR / R) and include VAT.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">Is there a long-term contract?</div>
            <div class="faq-a">No. Plans are billed monthly and can be cancelled at any time. No lock-in periods.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">What is the 5-user group pricing for Package 3?</div>
            <div class="faq-a">Package 3 supports 2 users at R 899/month. A group licence for 5 users is available at R 1 800/month — a saving compared to 5 individual licences.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">Is patient data secure?</div>
            <div class="faq-a">All patient data is stored securely and is only accessible via your dietician account. We follow industry-standard encryption and security practices.</div>
        </div>
    </div>
</div>

{{-- ═══ CTA ═══ --}}
<div class="cta-strip">
    <div class="cta-inner">
        <h2 class="cta-h2">Start your free account today</h2>
        <p class="cta-sub">No credit card required. Get access to essential calculators immediately — upgrade whenever you're ready.</p>
        <div class="cta-btns">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-hero-primary">Go to Dashboard →</a>
            @else
                <a href="{{ route('register') }}" class="btn-hero-primary">Create Free Account</a>
                <a href="{{ route('login') }}" class="btn-hero-ghost">Sign In</a>
            @endauth
        </div>
    </div>
</div>

{{-- ═══ FOOTER ═══ --}}
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
            <a href="/">Home</a>
            <a href="{{ route('login') }}">Log In</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
</footer>

</body>
</html>
