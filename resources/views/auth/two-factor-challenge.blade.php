<x-app-layout>

    {{-- ── Hero banner ─────────────────────────────────────────────────────── --}}
    <div class="dash-hero" style="padding-bottom:5rem">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div style="display:flex;align-items:center;gap:1rem">
                <div style="width:3rem;height:3rem;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.4rem;height:1.4rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:.2rem">Security</p>
                    <h1 style="font-size:1.5rem;font-weight:800;letter-spacing:-.025em;line-height:1.15;color:#fff;margin:0">Two-Factor Authentication</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Centered card ────────────────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top:-3.5rem;position:relative;z-index:10;padding-bottom:3rem">
        <div style="max-width:480px;margin:0 auto">

            <div style="background:var(--surface);border:1px solid var(--border);box-shadow:var(--shadow-card)">

                {{-- Card header --}}
                <div style="padding:1.5rem 1.5rem 1.25rem;border-bottom:1px solid var(--border);text-align:center">
                    <div style="width:3.5rem;height:3.5rem;background:linear-gradient(135deg,var(--primary-dark),var(--teal));display:flex;align-items:center;justify-content:center;margin:0 auto .875rem">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 style="font-size:1.15rem;font-weight:800;color:var(--text-primary);margin:0 0 .4rem">Enter your authentication code</h2>
                    <p style="font-size:.875rem;color:var(--text-muted);line-height:1.55;margin:0">
                        Open your authenticator app and enter the current 6-digit code below.
                    </p>
                </div>

                {{-- Error --}}
                @if ($errors->any())
                    <div style="margin:1rem 1.5rem 0;border:1px solid #fca5a5;background:#fef2f2;padding:.7rem 1rem;font-size:.85rem;color:#b91c1c">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('two-factor.verify') }}" style="padding:1.25rem 1.5rem 1.5rem">
                    @csrf

                    <div style="margin-bottom:1.1rem">
                        <label for="code" style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:.5rem">
                            Authenticator Code
                        </label>
                        <input
                            id="code"
                            name="code"
                            type="text"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            maxlength="6"
                            style="display:block;width:100%;padding:.85rem 1rem;border:1.5px solid var(--border);background:#fff;font-size:1.6rem;font-weight:800;letter-spacing:.4em;text-align:center;color:var(--text-primary);outline:none;transition:border-color .15s;box-sizing:border-box"
                            onfocus="this.style.borderColor='var(--primary)'"
                            onblur="this.style.borderColor='var(--border)'"
                            placeholder="000000"
                            required
                            autofocus
                        >
                    </div>

                    <button
                        type="submit"
                        style="display:flex;width:100%;align-items:center;justify-content:center;gap:.5rem;padding:.85rem 1.25rem;background:linear-gradient(135deg,var(--primary-dark),var(--teal));color:#fff;font-size:.9rem;font-weight:700;border:none;cursor:pointer;transition:opacity .15s;letter-spacing:.01em"
                        onmouseover="this.style.opacity='.88'"
                        onmouseout="this.style.opacity='1'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        Verify and continue
                    </button>
                </form>

                {{-- Sign out --}}
                <div style="padding:0 1.5rem 1.25rem;text-align:center">
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" style="font-size:.825rem;color:var(--text-muted);background:none;border:none;cursor:pointer;text-decoration:underline">
                            Sign out instead
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
