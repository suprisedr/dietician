<x-app-layout :minimal="true">

    <div class="dash-hero" style="padding-bottom:5rem">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div style="display:flex;align-items:center;gap:1rem">
                <div style="width:3rem;height:3rem;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.4rem;height:1.4rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:.2rem">Security — Step 2</p>
                    <h1 style="font-size:1.5rem;font-weight:800;letter-spacing:-.025em;line-height:1.15;color:#fff;margin:0">Email verification setup</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top:-3.5rem;position:relative;z-index:10;padding-bottom:3rem">
        <div style="max-width:500px;margin:0 auto">

            <div style="background:#fff;border:1px solid var(--border);border-radius:1rem;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.07)">

                <div style="padding:1.5rem 1.75rem;border-bottom:1px solid var(--border)">
                    <h2 style="font-size:1rem;font-weight:800;color:var(--text-primary);margin:0 0 .35rem">Verify your email address</h2>
                    <p style="font-size:.875rem;color:var(--text-muted);margin:0">
                        Each time you sign in we'll send a 6-digit code to <strong>{{ $email }}</strong>.
                        Click the button below to send a test code and confirm the setup.
                    </p>
                </div>

                @if ($errors->any())
                    <div style="margin:1rem 1.5rem 0;border:1px solid #fca5a5;background:#fef2f2;padding:.7rem 1rem;font-size:.85rem;color:#b91c1c;border-radius:.5rem">{{ $errors->first() }}</div>
                @endif

                @if (session('code_sent'))
                    <div style="margin:1rem 1.5rem 0;border:1px solid #bbf7d0;background:#f0fdf4;padding:.7rem 1rem;font-size:.85rem;color:#15803d;border-radius:.5rem">
                        ✓ Code sent to {{ $email }}. Check your inbox (and spam folder).
                    </div>
                @endif

                <div style="padding:1.5rem 1.75rem">

                    {{-- Send code --}}
                    <form method="POST" action="{{ route('two-factor.email.send') }}" style="margin-bottom:1.25rem">
                        @csrf
                        <button type="submit" style="width:100%;padding:.8rem;background:#f8fafc;border:1.5px solid var(--border);border-radius:.75rem;font-size:.875rem;font-weight:600;color:var(--text-primary);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;transition:border-color .15s">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:var(--primary)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Send verification code
                        </button>
                    </form>

                    {{-- Enter code --}}
                    <form method="POST" action="{{ route('two-factor.email.verify-setup') }}">
                        @csrf
                        <label style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:.5rem">
                            Enter the 6-digit code
                        </label>
                        <input name="code" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6"
                            style="display:block;width:100%;padding:.85rem 1rem;border:1.5px solid var(--border);border-radius:.75rem;font-size:1.6rem;font-weight:800;letter-spacing:.4em;text-align:center;color:var(--text-primary);outline:none;transition:border-color .15s;box-sizing:border-box;margin-bottom:1rem"
                            onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"
                            placeholder="000000" required autofocus>

                        <button type="submit" style="width:100%;padding:.85rem;background:linear-gradient(135deg,var(--primary-dark),var(--teal));color:#fff;font-size:.9rem;font-weight:700;border:none;border-radius:.75rem;cursor:pointer">
                            Enable Email Verification →
                        </button>
                    </form>

                </div>

                <div style="padding:0 1.75rem 1.25rem;display:flex;align-items:center;justify-content:space-between">
                    <a href="{{ route('two-factor.setup') }}" onclick="event.preventDefault();document.getElementById('change-method-form').submit()" style="font-size:.8rem;color:var(--text-muted);text-decoration:none">← Change method</a>
                    @if ($canSkip)
                    <form method="POST" action="{{ route('two-factor.skip') }}" style="display:inline">
                        @csrf
                        <button type="submit" style="font-size:.8rem;color:var(--text-muted);background:none;border:none;cursor:pointer">Skip for now</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form to go back to method selection --}}
    <form id="change-method-form" method="POST" action="{{ route('two-factor.select-method') }}" style="display:none">
        @csrf
        <input type="hidden" name="method" value="">
    </form>

    <script>
    document.querySelector('[onclick*="change-method-form"]').addEventListener('click', function(e) {
        e.preventDefault();
        // Clear method so setup shows the selection screen again
        const form = document.getElementById('change-method-form');
        // We send an invalid method so selectMethod redirects back — actually let's just clear it
        // Better: POST to a "reset method" endpoint. For now, navigate directly.
        window.location.href = '{{ route("two-factor.setup") }}?reset=1';
    });
    </script>
</x-app-layout>
