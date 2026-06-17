<x-app-layout :minimal="true">

    <div class="dash-hero" style="padding-bottom:5rem">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div style="display:flex;align-items:center;gap:1rem">
                <div style="width:3rem;height:3rem;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);border-radius:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:1.4rem;height:1.4rem;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:.2rem">Security — Step 2</p>
                    <h1 style="font-size:1.5rem;font-weight:800;letter-spacing:-.025em;line-height:1.15;color:#fff;margin:0">Register a passkey</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top:-3.5rem;position:relative;z-index:10;padding-bottom:3rem">
        <div style="max-width:500px;margin:0 auto">

            <div style="background:#fff;border:1px solid var(--border);border-radius:1rem;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.07)">

                <div style="padding:1.5rem 1.75rem;border-bottom:1px solid var(--border)">
                    <h2 style="font-size:1rem;font-weight:800;color:var(--text-primary);margin:0 0 .35rem">Create your passkey</h2>
                    <p style="font-size:.875rem;color:var(--text-muted);margin:0;line-height:1.6">
                        Passkeys use your device's biometrics (fingerprint, Face ID) or a hardware security key.
                        Your browser will prompt you after clicking the button below.
                    </p>
                </div>

                <div id="error-box" style="display:none;margin:1rem 1.5rem 0;border:1px solid #fca5a5;background:#fef2f2;padding:.7rem 1rem;font-size:.85rem;color:#b91c1c;border-radius:.5rem"></div>

                <div style="padding:1.5rem 1.75rem">

                    <div style="margin-bottom:1.25rem;display:flex;flex-direction:column;gap:.75rem">
                        @foreach([
                            ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','text'=>'No passwords to remember'],
                            ['icon'=>'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z','text'=>'Phishing resistant'],
                            ['icon'=>'M13 10V3L4 14h7v7l9-11h-7z','text'=>'Instant sign-in with biometrics'],
                        ] as $f)
                        <div style="display:flex;align-items:center;gap:.75rem">
                            <div style="width:2rem;height:2rem;background:linear-gradient(135deg,#fdf4ff,#e9d5ff);border-radius:.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem;color:#a855f7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/></svg>
                            </div>
                            <span style="font-size:.875rem;color:var(--text-primary);font-weight:500">{{ $f['text'] }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div style="margin-bottom:1rem">
                        <label style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-muted);margin-bottom:.5rem">
                            Name this passkey (optional)
                        </label>
                        <input id="passkey-name" type="text" placeholder="e.g. MacBook Touch ID"
                            style="width:100%;padding:.75rem 1rem;border:1.5px solid var(--border);border-radius:.75rem;font-size:.9rem;color:var(--text-primary);outline:none;box-sizing:border-box;transition:border-color .15s"
                            onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                    </div>

                    <button id="register-btn" onclick="startRegistration()"
                        style="width:100%;padding:.85rem;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;font-size:.9rem;font-weight:700;border:none;border-radius:.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Create passkey
                    </button>

                </div>

                <div style="padding:0 1.75rem 1.25rem;display:flex;align-items:center;justify-content:space-between">
                    <a href="{{ route('two-factor.setup') }}?reset=1" style="font-size:.8rem;color:var(--text-muted);text-decoration:none">← Change method</a>
                    @if ($canSkip)
                    <form method="POST" action="{{ route('two-factor.skip') }}" style="display:inline">@csrf
                        <button type="submit" style="font-size:.8rem;color:var(--text-muted);background:none;border:none;cursor:pointer">Skip for now</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    async function startRegistration() {
        const btn = document.getElementById('register-btn');
        const errBox = document.getElementById('error-box');
        errBox.style.display = 'none';
        btn.disabled = true;
        btn.innerHTML = '<svg style="width:1rem;height:1rem;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Waiting for device…';

        try {
            // 1. Get options from server
            const optRes = await fetch('{{ route("two-factor.passkey.register-options") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            });
            const options = await optRes.json();

            // 2. Decode base64url fields for WebAuthn API
            options.challenge = base64UrlDecode(options.challenge);
            options.user.id   = base64UrlDecode(options.user.id);
            if (options.excludeCredentials) {
                options.excludeCredentials = options.excludeCredentials.map(c => ({
                    ...c, id: base64UrlDecode(c.id)
                }));
            }

            // 3. Browser prompts the user
            const credential = await navigator.credentials.create({ publicKey: options });

            // 4. Encode and send back
            const payload = {
                id: credential.id,
                rawId: base64UrlEncode(credential.rawId),
                type: credential.type,
                response: {
                    attestationObject: base64UrlEncode(credential.response.attestationObject),
                    clientDataJSON:    base64UrlEncode(credential.response.clientDataJSON),
                    transports: credential.response.getTransports ? credential.response.getTransports() : [],
                },
            };

            const regRes = await fetch('{{ route("two-factor.passkey.register") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    credential: JSON.stringify(payload),
                    name: document.getElementById('passkey-name').value.trim(),
                }),
            });

            const result = await regRes.json();
            if (result.redirect) { window.location.href = result.redirect; return; }
            if (result.error) throw new Error(result.error);

        } catch (err) {
            errBox.textContent = err.message || 'Passkey registration failed. Please try again.';
            errBox.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = 'Create passkey';
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
</x-app-layout>
