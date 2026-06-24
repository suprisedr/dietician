<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pricing — Panamarex Outpatient Clinical Nutrition Toolkit</title>
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

        /* PRICING HERO */
        .pricing-hero { background: linear-gradient(135deg, #0d1f0c 0%, #2e6e56 40%, #679F5F 100%); position: relative; overflow: hidden; padding: 4.5rem 1.5rem 6.5rem; text-align: center; }
        .pricing-hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 20% 60%, rgba(103,159,95,.25) 0%, transparent 50%), radial-gradient(circle at 80% 30%, rgba(66,150,119,.35) 0%, transparent 50%); }
        .pricing-hero-inner { position: relative; z-index: 1; max-width: 750px; margin: 0 auto; }
        .pricing-badge { display: inline-flex; align-items: center; gap: .4rem; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.22); color: #a8d5a2; font-size: .78rem; font-weight: 700; padding: .3rem .85rem; border-radius: 999px; margin-bottom: 1.25rem; backdrop-filter: blur(8px); }
        .pricing-toolkit-name { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.5); margin-bottom: .5rem; }
        .pricing-h1 { font-size: clamp(2rem, 4.5vw, 3rem); font-weight: 800; line-height: 1.12; letter-spacing: -.04em; color: #fff; margin-bottom: 1rem; }
        .pricing-h1 span { background: linear-gradient(90deg, #a8d5a2, #8dc485); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .pricing-sub { font-size: 1.05rem; color: rgba(255,255,255,.75); line-height: 1.75; }

        /* PRICING GRID */
        .pricing-section { max-width: 1100px; margin: -3.5rem auto 0; padding: 0 1.5rem 5rem; position: relative; z-index: 10; }
        .pricing-grid { display: grid; grid-template-columns: repeat(4,1fr); background: var(--border, #d4e6d1); border: 1px solid var(--border, #d4e6d1); border-radius: .75rem; overflow: hidden; box-shadow: 0 24px 48px rgba(13,31,12,.15); }
        @media (max-width: 900px) { .pricing-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 520px)  { .pricing-grid { grid-template-columns: 1fr; } }
        .pricing-card { background: #fff; padding: 2rem 1.75rem 2.25rem; position: relative; display: flex; flex-direction: column; transition: background .2s; }
        .pricing-card:hover { background: #f4fbf3; }
        .pricing-card.featured { background: linear-gradient(160deg, #0d1f0c 0%, #2e6e56 60%, #429677 100%); color: #fff; }
        .pricing-card.featured:hover { background: linear-gradient(160deg, #0d1f0c 0%, #1a4a3a 55%, #429677 100%); }
        .pc-badge { display: inline-block; padding: .2rem .7rem; font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; border-radius: 999px; margin-bottom: 1.25rem; width: fit-content; }
        .pc-badge.free    { background: #e8f5e6; color: #4d7d47; }
        .pc-badge.popular { background: #679F5F; color: #fff; }
        .pc-badge.pro     { background: #e0f2ee; color: #2e6e56; }
        .pc-badge.team    { background: #d8ede6; color: #1a4a3a; }
        .pc-name { font-size: 1.1rem; font-weight: 800; margin-bottom: .35rem; }
        .pricing-card:not(.featured) .pc-name { color: var(--text-primary, #0d1f0c); }
        .pricing-card.featured .pc-name { color: #fff; }
        .pc-price { display: flex; align-items: baseline; gap: .2rem; margin-bottom: .25rem; }
        .pc-currency { font-size: .9rem; font-weight: 700; }
        .pc-amount   { font-size: 2.4rem; font-weight: 800; line-height: 1; letter-spacing: -.04em; }
        .pc-period   { font-size: .8rem; }
        .pricing-card:not(.featured) .pc-currency,
        .pricing-card:not(.featured) .pc-amount { color: var(--text-primary, #0d1f0c); }
        .pricing-card:not(.featured) .pc-period { color: var(--text-muted, #52705e); }
        .pricing-card.featured .pc-currency,
        .pricing-card.featured .pc-amount { color: #a8d5a2; }
        .pricing-card.featured .pc-period { color: rgba(255,255,255,.55); }
        .pc-tagline { font-size: .82rem; margin-bottom: 1.5rem; }
        .pricing-card:not(.featured) .pc-tagline { color: var(--text-muted, #52705e); }
        .pricing-card.featured .pc-tagline { color: rgba(255,255,255,.65); }
        .pc-divider { height: 1px; margin-bottom: 1.5rem; }
        .pricing-card:not(.featured) .pc-divider { background: var(--border, #d4e6d1); }
        .pricing-card.featured .pc-divider { background: rgba(255,255,255,.15); }
        .pc-features { list-style: none; flex: 1; margin-bottom: 2rem; display: flex; flex-direction: column; gap: .7rem; }
        .pc-features li { display: flex; align-items: flex-start; gap: .6rem; font-size: .85rem; line-height: 1.5; }
        .pricing-card:not(.featured) .pc-features li { color: var(--text-primary, #0d1f0c); }
        .pricing-card.featured .pc-features li { color: rgba(255,255,255,.85); }
        .pc-check { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: .1rem; }
        .pricing-card:not(.featured) .pc-check { color: #679F5F; }
        .pricing-card.featured .pc-check { color: #a8d5a2; }
        .pc-cta { display: block; width: 100%; text-align: center; padding: .7rem 1rem; font-size: .9rem; font-weight: 700; text-decoration: none; transition: all .2s; border: none; cursor: pointer; border-radius: .5rem; }
        .pc-cta.outline { border: 1.5px solid var(--border, #d4e6d1); color: var(--text-primary, #0d1f0c); background: #fff; }
        .pc-cta.outline:hover { border-color: #679F5F; color: #679F5F; }
        .pc-cta.solid-green { background: linear-gradient(135deg, #679F5F, #429677); color: #fff; box-shadow: 0 4px 14px rgba(103,159,95,.40); }
        .pc-cta.solid-green:hover { box-shadow: 0 8px 20px rgba(103,159,95,.55); }
        .pc-cta.solid-white { background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.30); color: #fff; }
        .pc-cta.solid-white:hover { background: rgba(255,255,255,.25); }

        /* COMPARISON */
        .compare-section { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 5rem; }
        .compare-heading { font-size: 1.5rem; font-weight: 800; letter-spacing: -.025em; color: var(--text-primary,#0d1f0c); margin-bottom: 1.5rem; }
        .compare-table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid var(--border,#d4e6d1); border-radius: .75rem; overflow: hidden; box-shadow: 0 4px 24px rgba(13,31,12,.06); }
        .compare-table thead th { padding: 1rem 1.25rem; text-align: left; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--text-muted,#52705e); background: #f0f7ef; border-bottom: 1px solid var(--border,#d4e6d1); }
        .compare-table thead th:first-child { width: 40%; }
        .compare-table thead th:not(:first-child) { text-align: center; }
        .compare-table tbody td { padding: .85rem 1.25rem; font-size: .875rem; border-bottom: 1px solid #edf5ec; color: var(--text-primary,#0d1f0c); }
        .compare-table tbody tr:last-child td { border-bottom: none; }
        .compare-table tbody td:not(:first-child) { text-align: center; }
        .compare-table tbody tr:hover td { background: #f4fbf3; }
        .tick { color: #679F5F; font-size: 1rem; }
        .dash { color: #cbd5e1; font-size: 1rem; }
        .feature-label { font-weight: 600; }

        /* FAQ */
        .faq-section { background: #f0f7ef; border-top: 1px solid var(--border,#d4e6d1); padding: 4rem 1.5rem; }
        .faq-inner { max-width: 700px; margin: 0 auto; }
        .faq-h2 { font-size: 1.75rem; font-weight: 800; letter-spacing: -.03em; margin-bottom: 2rem; text-align: center; }
        .faq-item { border-bottom: 1px solid var(--border,#d4e6d1); padding: 1.25rem 0; }
        .faq-item:last-child { border-bottom: none; }
        .faq-q { font-size: .95rem; font-weight: 700; color: var(--text-primary,#0d1f0c); margin-bottom: .5rem; }
        .faq-a { font-size: .875rem; color: var(--text-muted,#52705e); line-height: 1.75; }

        /* CTA */
        .cta-strip { background: linear-gradient(135deg, #0d1f0c 0%, #2e6e56 55%, #679F5F 100%); position: relative; overflow: hidden; }
        .cta-strip::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 75% 50%, rgba(103,159,95,.30) 0%, transparent 55%); }
        .cta-inner { max-width: 900px; margin: 0 auto; padding: 4.5rem 1.5rem; text-align: center; position: relative; z-index: 1; }
        .cta-h2 { font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 800; color: #fff; letter-spacing: -.03em; margin-bottom: 1rem; }
        .cta-sub { color: rgba(255,255,255,.75); font-size: 1rem; line-height: 1.75; margin-bottom: 2rem; max-width: 520px; margin-left: auto; margin-right: auto; }
        .cta-btns { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }
        .btn-hero-primary { display: inline-block; padding: .85rem 2rem; background: linear-gradient(135deg, #679F5F, #429677); color: #fff; font-size: 1rem; font-weight: 700; text-decoration: none; border-radius: .6rem; box-shadow: 0 8px 24px rgba(103,159,95,.50); transition: all .2s; }
        .btn-hero-primary:hover { transform: translateY(-2px); color: #fff; }
        .btn-hero-ghost { display: inline-block; padding: .85rem 2rem; border: 2px solid rgba(255,255,255,.35); color: #fff; font-size: 1rem; font-weight: 700; text-decoration: none; border-radius: .6rem; background: rgba(255,255,255,.08); backdrop-filter: blur(8px); transition: all .2s; }
        .btn-hero-ghost:hover { border-color: rgba(255,255,255,.7); color: #fff; }

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
            <a href="/" class="pub-nav-link">Home</a>
            <a href="{{ route('pricing') }}" class="pub-nav-link active">Pricing</a>
            @auth
                <a href="{{ route('dashboard') }}" class="pub-btn-primary" style="margin-left:.5rem">Dashboard &rarr;</a>
            @else
                <a href="{{ route('login') }}" class="pub-btn-outline" style="margin-left:.5rem">Log In</a>
                <a href="{{ route('register') }}" class="pub-btn-primary">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

<div class="pricing-hero">
    <div class="pricing-hero-inner">
        <div class="pricing-toolkit-name">Panamarex Outpatient Clinical Nutrition Toolkit</div>
        <div class="pricing-badge">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
            Simple, transparent pricing
        </div>
        <h1 class="pricing-h1">Plans for every <span>practice size</span></h1>
        <p class="pricing-sub">Start free with essential calculators. Upgrade to the full Outpatient Clinical Nutrition Toolkit — no hidden fees.</p>
    </div>
</div>

<div class="pricing-section">
    <div class="pricing-grid">

        {{-- FREE --}}
        <div class="pricing-card">
            <span class="pc-badge free">Free Trial</span>
            <div class="pc-name">Free</div>
            <div class="pc-price"><span class="pc-currency">R</span><span class="pc-amount">0</span><span class="pc-period">/ month</span></div>
            <div class="pc-tagline">Limited access &mdash; essential calculators only</div>
            <div class="pc-divider"></div>
            <ul class="pc-features">
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>BMI calculator</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>ABW &middot; IBW &middot; AF calculations</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>RMR &middot; BMR estimations</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>BMI distribution &amp; patient statistics</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>1 device / 1 user</li>
            </ul>
            <a href="{{ route('register') }}" class="pc-cta outline">Get started free</a>
        </div>

        {{-- PACKAGE 1 (featured) --}}
        <div class="pricing-card featured">
            <span class="pc-badge popular">Most Popular</span>
            <div class="pc-name">Package 1</div>
            <div class="pc-price"><span class="pc-currency">R</span><span class="pc-amount">499</span><span class="pc-period">/ month</span></div>
            <div class="pc-tagline">Outpatient Clinical Nutrition Toolkit</div>
            <div class="pc-divider"></div>
            <ul class="pc-features">
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Everything in Free</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Macronutrient distribution (C &middot; P &middot; F) &amp; fibre/fluid targets</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Food exchange list items</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Meal plan template (downloadable)</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Grocery list generator</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Generate downloadable reports for your client statistics</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>1 device / 1 user</li>
            </ul>
            @auth
                <a href="{{ route('subscription.checkout', 'package_1') }}" class="pc-cta solid-green">Subscribe &mdash; R 499/mo</a>
            @else
                <a href="{{ route('register') }}?plan=package_1" class="pc-cta solid-green">Get Package 1</a>
            @endauth
        </div>

        {{-- PACKAGE 2 --}}
        <div class="pricing-card">
            <span class="pc-badge pro">Package 2</span>
            <div class="pc-name">Package 2</div>
            <div class="pc-price"><span class="pc-currency">R</span><span class="pc-amount">699</span><span class="pc-period">/ month</span></div>
            <div class="pc-tagline">Outpatient Clinical Nutrition Toolkit + extras</div>
            <div class="pc-divider"></div>
            <ul class="pc-features">
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Everything in Package 1</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Patient food diary</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Weekly email reminders</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>2 devices / 2 users</li>
            </ul>
            @auth
                <a href="{{ route('subscription.checkout', 'package_2') }}" class="pc-cta outline">Subscribe &mdash; R 699/mo</a>
            @else
                <a href="{{ route('register') }}?plan=package_2" class="pc-cta outline">Get Package 2</a>
            @endauth
        </div>

        {{-- PACKAGE 3 --}}
        <div class="pricing-card">
            <span class="pc-badge team">Package 3</span>
            <div class="pc-name">Package 3</div>
            <div class="pc-price"><span class="pc-currency">R</span><span class="pc-amount">899</span><span class="pc-period">/ month</span></div>
            <div class="pc-tagline">In and Outpatient Clinical Nutrition Toolkit + extras</div>
            <div class="pc-divider"></div>
            <ul class="pc-features">
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Everything in Package 2</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Enteral feed calculations &amp; selection</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Daily monitoring (rate / volume)</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Up to 3 users</li>
                <li><svg class="pc-check" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>5-user group: <strong>R 1 800 / month</strong></li>
            </ul>
            @auth
                <a href="{{ route('subscription.checkout', 'package_3') }}" class="pc-cta outline">Subscribe &mdash; R 899/mo</a>
            @else
                <a href="{{ route('register') }}?plan=package_3" class="pc-cta outline">Get Package 3</a>
            @endauth
        </div>

    </div>
</div>

<div class="compare-section">
    <h2 class="compare-heading">Feature comparison</h2>
    <div style="overflow-x:auto">
        <table class="compare-table">
            <thead>
                <tr>
                    <th>Feature</th><th>Free</th><th>Package 1</th><th>Package 2</th><th>Package 3</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="feature-label">BMI &middot; ABW &middot; IBW &middot; AF</td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">RMR &middot; BMR calculations</td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">BMI distribution &amp; patient statistics</td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Macronutrient distribution (C &middot; P &middot; F)</td><td><span class="dash">&mdash;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Food exchange list items</td><td><span class="dash">&mdash;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Meal plan template (downloadable)</td><td><span class="dash">&mdash;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Downloadable client statistics report</td><td><span class="dash">&mdash;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Grocery list generator</td><td><span class="dash">&mdash;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Patient food diary</td><td><span class="dash">&mdash;</span></td><td><span class="dash">&mdash;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Weekly email reminders</td><td><span class="dash">&mdash;</span></td><td><span class="dash">&mdash;</span></td><td><span class="tick">&#10003;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Enteral feed calculations</td><td><span class="dash">&mdash;</span></td><td><span class="dash">&mdash;</span></td><td><span class="dash">&mdash;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Daily monitoring (rate/volume)</td><td><span class="dash">&mdash;</span></td><td><span class="dash">&mdash;</span></td><td><span class="dash">&mdash;</span></td><td><span class="tick">&#10003;</span></td></tr>
                <tr><td class="feature-label">Users / devices</td><td>1</td><td>1</td><td>2</td><td>3 (up to 5)</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="faq-section">
    <div class="faq-inner">
        <h2 class="faq-h2">Frequently asked questions</h2>
        <div class="faq-item">
            <div class="faq-q">What is the Panamarex Outpatient Clinical Nutrition Toolkit?</div>
            <div class="faq-a">It is the all-in-one platform for registered dietitians to manage outpatient consultations — covering patient records, macronutrient calculations, food exchange list items, meal planning and downloadable reports. Package 1, 2 and 3 all belong to this toolkit.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">What does the Free plan include?</div>
            <div class="faq-a">The Free plan is a limited trial that gives you access to essential clinical calculators: BMI, ABW, IBW, Activity Factor, RMR and BMR. It also includes BMI distribution and basic patient statistics. Full toolkit features (food exchange list items, meal planning, reports) require Package 1 or higher.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">Can I start on the Free plan and upgrade later?</div>
            <div class="faq-a">Yes &mdash; your account and all patient data are preserved when you upgrade. Upgrading takes effect immediately.</div>
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
            <div class="faq-q">What is the 5-user group pricing?</div>
            <div class="faq-a">A group licence for 5 users is available at R 1 800/month &mdash; ideal for larger practices or clinics and a saving compared to 5 individual licences.</div>
        </div>
        <div class="faq-item">
            <div class="faq-q">Is patient data secure?</div>
            <div class="faq-a">All patient data is stored securely and is only accessible via your dietitian account. We follow industry-standard encryption and security practices.</div>
        </div>
    </div>
</div>

<div class="cta-strip">
    <div class="cta-inner">
        <h2 class="cta-h2">Start your free account today</h2>
        <p class="cta-sub">No credit card required. Get access to essential calculators immediately &mdash; upgrade whenever you&rsquo;re ready.</p>
        <div class="cta-btns">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-hero-primary">Go to Dashboard &rarr;</a>
            @else
                <a href="{{ route('register') }}" class="btn-hero-primary">Create Free Account</a>
                <a href="{{ route('login') }}" class="btn-hero-ghost">Sign In</a>
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
        <span style="font-size:.82rem">&copy; {{ date('Y') }} MindfulNutrico. All rights reserved.</span>
        <div class="pub-footer-links">
            <a href="/">Home</a>
            <a href="{{ route('login') }}">Log In</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
</footer>

</body>
</html>
