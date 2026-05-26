<x-app-layout>

    @php
        $activeMethod = $methods[0] ?? 'totp';
        $multiMethod  = count($methods) > 1;
        $tabLabels    = ['totp' => 'Authenticator App', 'email' => 'Email Code', 'passkey' => 'Passkey'];
    @endphp

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
                    <h1 style="font-size:1.5rem;font-weight:800;letter-spacing:-.025em;line-height:1.15;color:#fff;margin:0">Verify your identity</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top:-3.5rem;position:relative;z-index:10;padding-bottom:3rem">
        <div style="max-width:500px;margin:0 auto">

            <div style="background:#fff;border:1px solid var(--border);border-radius:1rem;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.07)">

                @if ($errors->any())
                    <div style="margin:1rem 1.5rem 0;border:1px solid #fca5a5;background:#fef2f2;padding:.7rem 1rem;font-size:.85rem;color:#b91c1c;border-radius:.5rem">{{ $errors->first() }}</div>
                @endif

                {{-- ── Method tabs (only shown when multiple methods available) ──── --}}
                @if ($multiMethod)
                <div style="display:flex;border-bottom:2px solid var(--border);background:#f8fafc;overflow-x:auto">
                    @foreach($methods as $m)
                    <button type="button" class="ch-tab" data-method="{{ $m }}"
                        style="flex:1;min-width:0;padding:.75rem .5rem;border:none;background:transparent;font-size:.8rem;font-weight:700;color:var(--text-muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;white-space:nowrap;transition:color .15s,border-color .15s;display:flex;align-items:center;justify-content:center;gap:.35rem">
                        @if($m === 'totp')
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        @elseif($m === 'email')
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        @elseif($m === 'passkey')
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        @endif
                        {{ $tabLabels[$m] ?? ucfirst($m) }}
                    </button>
                    @endforeach
                </div>
                @endif

                {{-- ── TOTP pane ──────────────────────────────────────────────────── --}}
                @if(in_array('totp', $methods))
                <div class="ch-pane" data-pane="totp" style="{{ $activeMethod !== 'totp' ? 'display:none' : '' }}">
                    <div style="padding:1.5rem 1.75rem;border-bottom:1px solid var(--border);text-align:center">
                        <div style="width:3.5rem;height:3.5rem;background:linear-gradient(135deg,#eff6ff,#bfdbfe);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 style="font-size:1.1rem;font-weight:800;color:var(--text-primary);margin:0 0 .35rem">Authenticator code</h2>
                        <p style="font-size:.875rem;color:var(--text-muted);margin:0">Open your authenticator app and enter the current 6-digit code.</p>
                    </div>
                    <form method="POST" action="{{ route('two-factor.verify') }}" style="padding:1.5rem 1.75rem">
                        @csrf
                        <input name="code" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6"
                            style="display:block;width:100%;padding:.85rem 1rem;border:1.5px solid var(--border);border-radius:.75rem;font-size:1.6rem;font-weight:800;letter-spacing:.4em;text-align:center;color:var(--text-primary);outline:none;transition:border-color .15s;box-sizing:border-box;margin-bottom:1rem"
                            onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"
                            placeholder="000000" required {{ $activeMethod === 'totp' ? 'autofocus' : '' }}>
                        <button type="submit" style="width:100%;padding:.85rem;background:linear-gradient(135deg,var(--primary-dark),var(--teal));color:#fff;font-size:.9rem;font-weight:700;border:none;border-radius:.75rem;cursor:pointer">
                            Verify and continue →
                        </button>
                    </form>
                </div>
                @endif

                {{-- ── Email pane ─────────────────────────────────────────────────── --}}
                @if(in_array('email', $methods))
                <div class="ch-pane" data-pane="email" style="{{ $activeMethod !== 'email' ? 'display:none' : '' }}">
                    <div style="padding:1.5rem 1.75rem;border-bottom:1px solid var(--border);text-align:center">
                        <div style="width:3.5rem;height:3.5rem;background:linear-gradient(135deg,#f0fdf4,#bbf7d0);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;color:#22c55e" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 style="font-size:1.1rem;font-weight:800;color:var(--text-primary);margin:0 0 .35rem">Email verification code</h2>
                        <p style="font-size:.875rem;color:var(--text-muted);margin:0">We'll send a 6-digit code to <strong>{{ $userEmail }}</strong>.</p>
                    </div>

                    @if ($codeSent)
                    <div style="margin:1rem 1.5rem 0;border:1px solid #bbf7d0;background:#f0fdf4;padding:.7rem 1rem;font-size:.85rem;color:#15803d;border-radius:.5rem">
                        ✓ Code sent — check your inbox (and spam folder). Valid for 10 minutes.
                    </div>
                    @endif

                    <div style="padding:1.5rem 1.75rem">
                        <form method="POST" action="{{ route('two-factor.email.challenge-send') }}" style="margin-bottom:1rem">
                            @csrf
                            <button type="submit" style="width:100%;padding:.8rem;background:#f8fafc;border:1.5px solid var(--border);border-radius:.75rem;font-size:.875rem;font-weight:600;color:var(--text-primary);cursor:pointer">
                                {{ $codeSent ? 'Resend code' : 'Send code to my email' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('two-factor.email.verify') }}">
                            @csrf
                            <input name="code" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6"
                                style="display:block;width:100%;padding:.85rem 1rem;border:1.5px solid var(--border);border-radius:.75rem;font-size:1.6rem;font-weight:800;letter-spacing:.4em;text-align:center;color:var(--text-primary);outline:none;transition:border-color .15s;box-sizing:border-box;margin-bottom:1rem"
                                onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"
                                placeholder="000000" required>
                            <button type="submit" style="width:100%;padding:.85rem;background:linear-gradient(135deg,var(--primary-dark),var(--teal));color:#fff;font-size:.9rem;font-weight:700;border:none;border-radius:.75rem;cursor:pointer">
                                Verify and continue →
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- ── Passkey pane ───────────────────────────────────────────────── --}}
                @if(in_array('passkey', $methods))
                <div class="ch-pane" data-pane="passkey" style="{{ $activeMethod !== 'passkey' ? 'display:none' : '' }}">
                    <div style="padding:1.5rem 1.75rem;border-bottom:1px solid var(--border);text-align:center">
                        <div style="width:3.5rem;height:3.5rem;background:linear-gradient(135deg,#fdf4ff,#e9d5ff);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;color:#a855f7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <h2 style="font-size:1.1rem;font-weight:800;color:var(--text-primary);margin:0 0 .35rem">Use your passkey</h2>
                        <p style="font-size:.875rem;color:var(--text-muted);margin:0">Use your device's biometrics or security key to sign in.</p>
                    </div>

                    <div id="pk-error" style="display:none;margin:1rem 1.5rem 0;border:1px solid #fca5a5;background:#fef2f2;padding:.7rem 1rem;font-size:.85rem;color:#b91c1c;border-radius:.5rem"></div>

                    <div style="padding:1.5rem 1.75rem">
                        <button id="pk-btn" onclick="authenticatePasskey()"
                            style="width:100%;padding:.85rem;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;font-size:.9rem;font-weight:700;border:none;border-radius:.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Use passkey
                        </button>
                    </div>
                </div>
                @endif

                {{-- Sign out --}}
                <div style="padding:1rem 1.75rem;border-top:1px solid var(--border);text-align:center">
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

    @if(in_array('passkey', $methods))
    <script>
    async function authenticatePasskey() {
        const btn = document.getElementById('pk-btn');
        const err = document.getElementById('pk-error');
        err.style.display = 'none';
        btn.disabled = true;
        btn.innerHTML = '<svg style="width:1rem;height:1rem;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Waiting…';

        try {
            const optRes = await fetch('{{ route("two-factor.passkey.auth-options") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            });
            const options = await optRes.json();

            options.challenge = base64UrlDecode(options.challenge);
            if (options.allowCredentials) {
                options.allowCredentials = options.allowCredentials.map(c => ({ ...c, id: base64UrlDecode(c.id) }));
            }

            const assertion = await navigator.credentials.get({ publicKey: options });

            const payload = {
                id:    assertion.id,
                rawId: base64UrlEncode(assertion.rawId),
                type:  assertion.type,
                response: {
                    authenticatorData: base64UrlEncode(assertion.response.authenticatorData),
                    clientDataJSON:    base64UrlEncode(assertion.response.clientDataJSON),
                    signature:         base64UrlEncode(assertion.response.signature),
                    userHandle: assertion.response.userHandle ? base64UrlEncode(assertion.response.userHandle) : null,
                },
            };

            const authRes = await fetch('{{ route("two-factor.passkey.authenticate") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ credential: JSON.stringify(payload) }),
            });

            const result = await authRes.json();
            if (result.redirect) { window.location.href = result.redirect; return; }
            if (result.error) throw new Error(result.error);

        } catch (e) {
            err.textContent = e.message || 'Passkey authentication failed.';
            err.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = 'Use passkey';
        }
    }

    function base64UrlDecode(b64) {
        const pad = b64.length % 4 === 0 ? '' : '===='.slice(b64.length % 4);
        return Uint8Array.from(atob(b64.replace(/-/g,'+').replace(/_/g,'/') + pad), c => c.charCodeAt(0)).buffer;
    }
    function base64UrlEncode(buf) {
        return btoa(String.fromCharCode(...new Uint8Array(buf))).replace(/\+/g,'-').replace(/\//g,'_').replace(/=/g,'');
    }
    </script>
    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>
    @endif

    @if($multiMethod)
    <script>
    (function() {
        const tabs  = document.querySelectorAll('.ch-tab');
        const panes = document.querySelectorAll('.ch-pane');

        function activate(method) {
            tabs.forEach(function(t) {
                const active = t.dataset.method === method;
                t.style.color        = active ? 'var(--primary-dark)' : 'var(--text-muted)';
                t.style.borderColor  = active ? 'var(--primary-dark)' : 'transparent';
                t.style.background   = active ? '#fff' : 'transparent';
            });
            panes.forEach(function(p) {
                p.style.display = p.dataset.pane === method ? '' : 'none';
            });
            // Auto-trigger passkey when switching to that tab
            if (method === 'passkey' && typeof authenticatePasskey === 'function') {
                authenticatePasskey();
            }
        }

        tabs.forEach(function(t) {
            t.addEventListener('click', function() { activate(t.dataset.method); });
        });

        // Initialise the first tab
        activate('{{ $activeMethod }}');
    })();
    </script>
    @endif

</x-app-layout>
