<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="{{ asset('css/soft-ui-overrides.css') }}" rel="stylesheet">
    </head>
    <body class="font-sans antialiased" style="background:var(--bg-page,#f8fafc);min-height:100vh;display:flex">

        {{-- ── Split-screen wrapper ── --}}
        <div style="display:flex;min-height:100vh;width:100%">

            {{-- LEFT PANEL – decorative brand side (hidden on mobile) --}}
            <div class="auth-brand-panel hidden lg:flex" style="
                flex:0 0 42%;
                background:linear-gradient(145deg,#1e1b4b 0%,#312e81 40%,#f97316 100%);
                position:relative;
                overflow:hidden;
                flex-direction:column;
                align-items:center;
                justify-content:center;
                padding:3rem 3.5rem;
                color:#fff;
            ">
                {{-- Background orbs --}}
                <div style="position:absolute;top:-4rem;right:-4rem;width:20rem;height:20rem;background:rgba(249,115,22,.18);border-radius:50%;filter:blur(60px)"></div>
                <div style="position:absolute;bottom:-5rem;left:-3rem;width:22rem;height:22rem;background:rgba(99,102,241,.22);border-radius:50%;filter:blur(70px)"></div>
                <div style="position:absolute;top:35%;left:20%;width:10rem;height:10rem;background:rgba(255,255,255,.04);border-radius:50%"></div>

                <div style="position:relative;z-index:1;max-width:22rem">
                    {{-- Logo mark --}}
                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:3rem">
                        <div style="width:3rem;height:3rem;background:rgba(255,255,255,.15);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.25);border-radius:.9rem;display:flex;align-items:center;justify-content:center">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>
                        <span style="font-size:1.25rem;font-weight:800;letter-spacing:-.02em">Dietician</span>
                    </div>

                    {{-- Headline --}}
                    <h2 style="font-size:2rem;font-weight:800;letter-spacing:-.03em;line-height:1.15;margin-bottom:1rem">
                        Your practice,<br>beautifully organised.
                    </h2>
                    <p style="opacity:.7;font-size:.95rem;line-height:1.7">
                        Track patients, calculate macronutrients, manage energy targets — all in one place built for registered dieticians.
                    </p>

                    {{-- Feature pills --}}
                    <div style="margin-top:2.5rem;display:flex;flex-direction:column;gap:.75rem">
                        @foreach([
                            ['icon'=>'M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2', 'label'=>'Patient records & history'],
                            ['icon'=>'M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z', 'label'=>'BMI · BMR · TEE calculations'],
                            ['icon'=>'M4 6h16M4 12h16M4 18h16', 'label'=>'Exchange template management'],
                        ] as $feat)
                        <div style="display:flex;align-items:center;gap:.75rem;padding:.7rem 1rem;background:rgba(255,255,255,.08);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.12);border-radius:.875rem">
                            <div style="width:2rem;height:2rem;background:rgba(249,115,22,.25);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:#fdba74" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feat['icon'] }}"/>
                                </svg>
                            </div>
                            <span style="font-size:.83rem;font-weight:600;opacity:.9">{{ $feat['label'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL – form side --}}
            <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:2rem 1.5rem;overflow-y:auto">
                <div style="width:100%;max-width:420px">
                    {{-- Mobile logo --}}
                    <div class="lg:hidden" style="text-align:center;margin-bottom:2rem">
                        <div style="display:inline-flex;align-items:center;gap:.6rem">
                            <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#f97316,#ea580c);border-radius:.65rem;display:flex;align-items:center;justify-content:center">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            <span style="font-size:1.15rem;font-weight:800;color:#0f172a;letter-spacing:-.02em">Dietician</span>
                        </div>
                    </div>

                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
