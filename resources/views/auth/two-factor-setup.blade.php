<x-app-layout>

    {{-- ── Hero banner ─────────────────────────────────────────────────────── --}}
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
                    <h1 style="font-size:1.5rem;font-weight:800;letter-spacing:-.025em;line-height:1.15;color:#fff;margin:0">Two-Factor Authentication Setup</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main content ─────────────────────────────────────────────────────── --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top:-3.5rem;position:relative;z-index:10;padding-bottom:3rem">

        @if (session('status'))
            <div style="margin-bottom:1.25rem;border:1px solid #bbf7d0;background:#f0fdf4;padding:.75rem 1rem;font-size:.875rem;color:#15803d">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="margin-bottom:1.25rem;border:1px solid #fecaca;background:#fef2f2;padding:.75rem 1rem;font-size:.875rem;color:#b91c1c">
                {{ $errors->first() }}
            </div>
        @endif

        <div style="display:grid;grid-template-columns:1fr;gap:1.5rem" class="lg:two-col-2fa">

            {{-- ── Left column: intro + steps ─────────────────────────────────── --}}
            <div>

                {{-- Grace-period / deadline banner --}}
                @if ($canSkip && $gracePeriodEndsAt)
                <div style="background:#fffbeb;border:1px solid #fcd34d;padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:flex-start;gap:.875rem">
                    <div style="flex-shrink:0;width:2.25rem;height:2.25rem;background:#fef3c7;border:1px solid #fcd34d;display:flex;align-items:center;justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;color:#d97706" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:.8rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.25rem">Grace period active</p>
                        <p style="font-size:.875rem;color:#78350f;line-height:1.55">
                            You can skip setup until <strong>{{ $gracePeriodEndsAt->format('j M Y \a\t g:i A') }}</strong>. After that, two-factor authentication becomes mandatory.
                        </p>
                    </div>
                </div>
                @else
                <div style="background:#fef2f2;border:1px solid #fca5a5;padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:flex-start;gap:.875rem">
                    <div style="flex-shrink:0;width:2.25rem;height:2.25rem;background:#fee2e2;border:1px solid #fca5a5;display:flex;align-items:center;justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;color:#dc2626" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:.8rem;font-weight:700;color:#991b1b;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.25rem">Grace period ended</p>
                        <p style="font-size:.875rem;color:#7f1d1d;line-height:1.55">
                            Your 15-day grace period has expired. You must complete two-factor authentication setup to continue using your account.
                        </p>
                    </div>
                </div>
                @endif

                {{-- Title block --}}
                <div style="background:var(--surface);border:1px solid var(--border);box-shadow:var(--shadow-card);padding:1.75rem">
                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.25rem">
                        <div style="width:2.5rem;height:2.5rem;background:linear-gradient(135deg,var(--primary-dark),var(--teal));display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1.2rem;height:1.2rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted)">Authenticator App</p>
                            <h2 style="font-size:1.1rem;font-weight:800;color:var(--text-primary);margin:0">Protect your account</h2>
                        </div>
                    </div>
                    <p style="font-size:.9rem;color:var(--text-muted);line-height:1.65">
                        Use Google Authenticator, Microsoft Authenticator, 1Password, or any TOTP-compatible app to scan the QR code on the right. Then enter the 6-digit code to activate two-factor authentication.
                    </p>
                </div>

                {{-- Steps --}}
                <div style="margin-top:1.25rem;display:flex;flex-direction:column;gap:.625rem">

                    @foreach([
                        ['num'=>'1','title'=>'Open your authenticator app', 'body'=>'Create a new entry and choose the QR code scan option.'],
                        ['num'=>'2','title'=>'Scan the QR code or enter the key', 'body'=>'Point your camera at the code on the right. If scanning fails use the manual key below the QR code.'],
                        ['num'=>'3','title'=>'Enter the 6-digit code', 'body'=>'Type the current rotating code shown in your app into the field on the right, then click Enable.'],
                    ] as $step)
                    <div style="background:var(--surface);border:1px solid var(--border);padding:1.1rem 1.25rem;display:flex;align-items:flex-start;gap:1rem">
                        <div style="flex-shrink:0;width:2rem;height:2rem;background:linear-gradient(135deg,var(--primary),var(--primary-dark));display:flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:800">
                            {{ $step['num'] }}
                        </div>
                        <div>
                            <p style="font-size:.875rem;font-weight:700;color:var(--text-primary);margin-bottom:.2rem">{{ $step['title'] }}</p>
                            <p style="font-size:.825rem;color:var(--text-muted);line-height:1.55">{{ $step['body'] }}</p>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            {{-- ── Right column: QR + form ─────────────────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:1.25rem">

                {{-- QR code card --}}
                <div style="background:var(--surface);border:1px solid var(--border);box-shadow:var(--shadow-card)">
                    <div style="padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.5rem">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.95rem;height:.95rem;color:var(--primary-dark)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        <span style="font-size:.8rem;font-weight:700;color:var(--text-primary);text-transform:uppercase;letter-spacing:.07em">Scan QR Code</span>
                    </div>
                    <div style="padding:1.5rem;display:flex;justify-content:center">
                        <div style="background:#fff;border:1px solid var(--border);padding:1rem;display:inline-block">
                            <div style="width:200px;height:200px;color:#0d1f0c">
                                {!! $qrCodeSvg !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Manual key card --}}
                <div style="background:var(--surface);border:1px solid var(--border)">
                    <div style="padding:.9rem 1.25rem;border-bottom:1px solid var(--border)">
                        <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin:0">Manual Setup Key</p>
                    </div>
                    <div style="padding:1rem 1.25rem">
                        <p style="font-size:.78rem;color:var(--text-muted);margin-bottom:.5rem">Can't scan? Enter this key in your app manually.</p>
                        <div style="background:#f8fafc;border:1px dashed var(--border);padding:.75rem 1rem;font-family:monospace;font-size:.85rem;color:var(--text-primary);word-break:break-all;letter-spacing:.08em">{{ $secret }}</div>
                    </div>
                </div>

                {{-- Confirmation form card --}}
                <div style="background:var(--surface);border:1px solid var(--border);box-shadow:var(--shadow-card)">
                    <div style="padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.5rem">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.95rem;height:.95rem;color:var(--primary-dark)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span style="font-size:.8rem;font-weight:700;color:var(--text-primary);text-transform:uppercase;letter-spacing:.07em">Confirm & Enable</span>
                    </div>

                    <form method="POST" action="{{ route('two-factor.store') }}" style="padding:1.25rem">
                        @csrf

                        <div style="margin-bottom:1rem">
                            <label for="code" style="display:block;font-size:.78rem;font-weight:700;color:var(--text-primary);text-transform:uppercase;letter-spacing:.07em;margin-bottom:.5rem">
                                6-digit authenticator code
                            </label>
                            <input
                                id="code"
                                name="code"
                                type="text"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                maxlength="6"
                                style="display:block;width:100%;padding:.7rem 1rem;border:1.5px solid var(--border);background:#fff;font-size:1.2rem;font-weight:700;letter-spacing:.25em;text-align:center;color:var(--text-primary);outline:none;transition:border-color .15s;box-sizing:border-box"
                                onfocus="this.style.borderColor='var(--primary)'"
                                onblur="this.style.borderColor='var(--border)'"
                                placeholder="0 0 0 0 0 0"
                                required
                                autofocus
                            >
                            @error('code')
                                <p style="margin-top:.4rem;font-size:.8rem;color:#dc2626">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            style="display:flex;width:100%;align-items:center;justify-content:center;gap:.5rem;padding:.8rem 1.25rem;background:linear-gradient(135deg,var(--primary-dark),var(--teal));color:#fff;font-size:.875rem;font-weight:700;border:none;cursor:pointer;transition:opacity .15s"
                            onmouseover="this.style.opacity='.88'"
                            onmouseout="this.style.opacity='1'"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Enable Two-Factor Authentication
                        </button>
                    </form>

                    @if ($canSkip)
                        <div style="padding:0 1.25rem 1.25rem">
                            <form method="POST" action="{{ route('two-factor.skip') }}">
                                @csrf
                                <button
                                    type="submit"
                                    style="display:flex;width:100%;align-items:center;justify-content:center;gap:.4rem;padding:.7rem 1.25rem;background:#fff;border:1.5px solid var(--border);color:var(--text-muted);font-size:.825rem;font-weight:600;cursor:pointer;transition:border-color .15s,color .15s"
                                    onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary-dark)'"
                                    onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Skip for now
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <style>
        @media (min-width: 1024px) {
            .lg\:two-col-2fa { grid-template-columns: 1fr 380px !important; }
        }
    </style>

</x-app-layout>
