<x-app-layout>

    @php
        $isAdding = ! empty($confirmedMethods);
        $labels   = ['totp' => 'Authenticator App', 'email' => 'Email Code', 'passkey' => 'Passkey'];
    @endphp

    <div class="dash-hero" style="padding-bottom:5rem">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div style="display:flex;align-items:center;gap:1rem">
                <div style="width:3rem;height:3rem;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.4rem;height:1.4rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:.2rem">Security</p>
                    <h1 style="font-size:1.5rem;font-weight:800;letter-spacing:-.025em;line-height:1.15;color:#fff;margin:0">
                        {{ $isAdding ? 'Add another verification method' : 'Choose your verification method' }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top:-3.5rem;position:relative;z-index:10;padding-bottom:3rem">
        <div style="max-width:680px;margin:0 auto">

            @if (session('status'))
                <div style="margin-bottom:1rem;border:1px solid #bbf7d0;background:#f0fdf4;padding:.75rem 1rem;font-size:.875rem;color:#15803d;border-radius:.75rem;display:flex;align-items:center;gap:.5rem">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="margin-bottom:1rem;border:1px solid #fca5a5;background:#fef2f2;padding:.7rem 1rem;font-size:.85rem;color:#b91c1c;border-radius:.5rem">{{ $errors->first() }}</div>
            @endif

            {{-- Already-confirmed methods banner --}}
            @if ($isAdding)
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:1rem 1.25rem;margin-bottom:1.5rem;border-radius:.875rem">
                <p style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#15803d;margin:0 0 .6rem">Active verification methods</p>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem">
                    @foreach($confirmedMethods as $cm)
                    <span style="display:inline-flex;align-items:center;gap:.35rem;background:#dcfce7;border:1px solid #86efac;border-radius:999px;padding:.3rem .75rem;font-size:.8rem;font-weight:700;color:#15803d">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ $labels[$cm] ?? ucfirst($cm) }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            @if ($canSkip && $gracePeriodEndsAt && ! $isAdding)
            <div style="background:#fffbeb;border:1px solid #fcd34d;padding:1rem 1.25rem;margin-bottom:1.5rem;border-radius:.75rem;display:flex;gap:.875rem;align-items:flex-start">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;color:#d97706;flex-shrink:0;margin-top:.15rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p style="font-size:.875rem;color:#78350f;margin:0">Grace period active — set up before <strong>{{ $gracePeriodEndsAt->format('j M Y') }}</strong>.</p>
            </div>
            @endif

            <div style="background:#fff;border:1px solid var(--border);border-radius:1rem;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.07)">
                <div style="padding:1.5rem 1.75rem;border-bottom:1px solid var(--border)">
                    <h2 style="font-size:1rem;font-weight:800;color:var(--text-primary);margin:0 0 .35rem">
                        {{ $isAdding ? 'Add a backup method' : 'Two-factor authentication' }}
                    </h2>
                    <p style="font-size:.875rem;color:var(--text-muted);margin:0">
                        {{ $isAdding
                            ? 'Having multiple methods means you can always verify your identity even if one method is unavailable.'
                            : 'Pick how you want to verify your identity each time you sign in.' }}
                    </p>
                </div>

                <form method="POST" action="{{ route('two-factor.select-method') }}" style="padding:1.5rem 1.75rem">
                    @csrf

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1.5rem" class="method-grid">

                        {{-- Authenticator App --}}
                        @php $totpDone = in_array('totp', $confirmedMethods) @endphp
                        <label style="cursor:{{ $totpDone ? 'default' : 'pointer' }}" {{ $totpDone ? '' : '' }}>
                            <input type="radio" name="method" value="totp" style="display:none" class="method-radio" {{ $totpDone ? 'disabled' : '' }}>
                            <div class="method-card {{ $totpDone ? 'method-card-done' : '' }}" data-method="totp">
                                @if($totpDone)
                                <div style="position:absolute;top:.5rem;right:.5rem;width:1.25rem;height:1.25rem;background:#22c55e;border-radius:50%;display:flex;align-items:center;justify-content:center">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:.7rem;height:.7rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                @endif
                                <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#eff6ff,#bfdbfe);border-radius:.875rem;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div style="font-size:.875rem;font-weight:700;color:var(--text-primary);margin-bottom:.3rem;text-align:center">Authenticator App</div>
                                <div style="font-size:.775rem;color:var(--text-muted);text-align:center;line-height:1.5">
                                    {{ $totpDone ? 'Already enabled' : 'Google Authenticator, Microsoft Authenticator, 1Password, etc.' }}
                                </div>
                            </div>
                        </label>

                        {{-- Email --}}
                        @php $emailDone = in_array('email', $confirmedMethods) @endphp
                        <label style="cursor:{{ $emailDone ? 'default' : 'pointer' }}">
                            <input type="radio" name="method" value="email" style="display:none" class="method-radio" {{ $emailDone ? 'disabled' : '' }}>
                            <div class="method-card {{ $emailDone ? 'method-card-done' : '' }}" data-method="email">
                                @if($emailDone)
                                <div style="position:absolute;top:.5rem;right:.5rem;width:1.25rem;height:1.25rem;background:#22c55e;border-radius:50%;display:flex;align-items:center;justify-content:center">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:.7rem;height:.7rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                @endif
                                <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#f0fdf4,#bbf7d0);border-radius:.875rem;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;color:#22c55e" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div style="font-size:.875rem;font-weight:700;color:var(--text-primary);margin-bottom:.3rem;text-align:center">Email Code</div>
                                <div style="font-size:.775rem;color:var(--text-muted);text-align:center;line-height:1.5">
                                    {{ $emailDone ? 'Already enabled' : 'A 6-digit code sent to your registered email address.' }}
                                </div>
                            </div>
                        </label>

                        {{-- Passkey --}}
                        <label style="cursor:pointer" id="passkey-option">
                            <input type="radio" name="method" value="passkey" style="display:none" class="method-radio">
                            <div class="method-card" data-method="passkey">
                                @if(in_array('passkey', $confirmedMethods))
                                <div style="position:absolute;top:.5rem;right:.5rem;background:#7c3aed;border-radius:999px;padding:.15rem .5rem;font-size:.6rem;font-weight:700;color:#fff">+Add</div>
                                @endif
                                <div style="width:3rem;height:3rem;background:linear-gradient(135deg,#fdf4ff,#e9d5ff);border-radius:.875rem;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;color:#a855f7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </div>
                                <div style="font-size:.875rem;font-weight:700;color:var(--text-primary);margin-bottom:.3rem;text-align:center">Passkey</div>
                                <div style="font-size:.775rem;color:var(--text-muted);text-align:center;line-height:1.5">Biometric or hardware key — fastest and most secure.</div>
                            </div>
                        </label>

                    </div>

                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
                        <button type="submit" id="continue-btn" disabled
                            style="flex:1;min-width:140px;padding:.85rem 1.5rem;background:linear-gradient(135deg,var(--primary-dark),var(--teal));color:#fff;font-size:.9rem;font-weight:700;border:none;border-radius:.875rem;cursor:pointer;opacity:.5;transition:opacity .2s">
                            Continue →
                        </button>

                        {{-- When user already has at least one method: let them proceed --}}
                        @if ($isAdding)
                        <a href="{{ route('dashboard') }}" style="flex:1;min-width:140px;padding:.85rem 1.5rem;background:#f1f5f9;border:1.5px solid var(--border);border-radius:.875rem;font-size:.875rem;font-weight:700;color:var(--text-primary);text-align:center;text-decoration:none">
                            Done — go to app →
                        </a>
                        @elseif ($canSkip)
                        <form method="POST" action="{{ route('two-factor.skip') }}" style="flex:0 0 auto">
                            @csrf
                            <button type="submit" style="padding:.82rem 1.1rem;background:#f1f5f9;color:var(--text-muted);font-size:.85rem;font-weight:600;border:1.5px solid var(--border);border-radius:.875rem;cursor:pointer">
                                Skip for now
                            </button>
                        </form>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
    .method-card {
        position: relative;
        padding:1.25rem 1rem;
        border:2px solid var(--border);
        border-radius:.875rem;
        transition:border-color .15s,box-shadow .15s;
        background:#fff;
    }
    .method-card:hover { border-color:#94a3b8; }
    .method-card-done {
        background:#f8fafc;
        opacity:.65;
    }
    .method-card-done:hover { border-color:var(--border); }
    .method-radio:not(:disabled):checked + .method-card {
        border-color:var(--primary);
        box-shadow:0 0 0 3px rgba(103,159,95,.15);
    }
    @media(max-width:600px) { .method-grid { grid-template-columns:1fr !important; } }
    </style>

    <script>
    document.querySelectorAll('.method-radio:not(:disabled)').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const btn = document.getElementById('continue-btn');
            btn.disabled = false;
            btn.style.opacity = '1';
        });
    });

    if (!window.PublicKeyCredential) {
        document.getElementById('passkey-option').style.display = 'none';
    }
    </script>
</x-app-layout>
