<x-guest-layout>
    <div style="margin-bottom:1.75rem">
        <h1 class="auth-card-title">Create your account</h1>
        <p class="auth-card-sub">Sign up to get started with Mindfulnutrico.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" style="width:100%">
        @csrf

        {{-- Email --}}
        <div class="auth-field">
            <label class="auth-label" for="email">Email</label>
            <div class="auth-input-wrap">
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="auth-input" required autofocus autocomplete="email" placeholder="jane@clinic.com"/>
                <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/></svg>
            </div>
            @error('email')<p class="auth-field-error">{{ $message }}</p>@enderror
        </div>

        {{-- Password --}}
        <div class="auth-field" style="margin-top:1rem">
            <label class="auth-label" for="password">Password</label>
            <div class="auth-input-wrap">
                <input id="password" type="password" name="password" class="auth-input auth-input-pw" required autocomplete="new-password" placeholder="••••••••" oninput="updateStrength(this.value)" onkeyup="checkCaps(event,'capslock-reg')" onkeydown="checkCaps(event,'capslock-reg')" onfocus="checkCaps(event,'capslock-reg')"/>
                <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4h8z"/></svg>
                <button type="button" class="pw-toggle" onclick="togglePw('password',this)" tabindex="-1" aria-label="Show password">
                    <svg class="eye-show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="eye-hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 0 1 2.71-4.29M9.88 9.88a3 3 0 1 0 4.243 4.243M6.1 6.1 3 3m18 18-3.1-3.1M9.88 9.88 3 3m10.122 10.122L21 21"/></svg>
                </button>
            </div>

            <div id="capslock-reg" style="display:none;margin-top:.4rem;align-items:center;gap:.35rem;font-size:.75rem;font-weight:600;color:#f97316">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="17 11 12 6 7 11"/><line x1="12" y1="6" x2="12" y2="18"/><line x1="8" y1="18" x2="16" y2="18"/>
                </svg>
                Caps Lock is on
            </div>

            <div class="pw-strength-wrap" id="pw-strength-wrap" style="display:none;margin-top:.75rem">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.4rem">
                    <span style="font-size:.72rem;font-weight:600;color:var(--text-muted)">Password strength</span>
                    <span id="pw-strength-label" style="font-size:.72rem;font-weight:700"></span>
                </div>
                <div class="pw-strength-bar"><div class="pw-strength-fill" id="pw-strength-fill"></div></div>
                <div class="pw-reqs" id="pw-reqs" style="margin-top:.6rem">
                    <span class="pw-req" id="req-len">&#x2717; At least 8 characters</span>
                    <span class="pw-req" id="req-upper">&#x2717; One uppercase letter (A-Z)</span>
                    <span class="pw-req" id="req-lower">&#x2717; One lowercase letter (a-z)</span>
                    <span class="pw-req" id="req-num">&#x2717; One number (0-9)</span>
                    <span class="pw-req" id="req-sym">&#x2717; One special character (!@#$...)</span>
                </div>
            </div>
            @error('password')<p class="auth-field-error">{{ $message }}</p>@enderror
        </div>

        {{-- Confirm Password --}}
        <div class="auth-field" style="margin-top:1rem">
            <label class="auth-label" for="password_confirmation">Confirm Password</label>
            <div class="auth-input-wrap">
                <input id="password_confirmation" type="password" name="password_confirmation" class="auth-input auth-input-pw" required autocomplete="new-password" placeholder="••••••••" onkeyup="checkCaps(event,'capslock-confirm')" onkeydown="checkCaps(event,'capslock-confirm')" onfocus="checkCaps(event,'capslock-confirm')"/>
                <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation',this)" tabindex="-1" aria-label="Show password">
                    <svg class="eye-show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="eye-hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 0 1 2.71-4.29M9.88 9.88a3 3 0 1 0 4.243 4.243M6.1 6.1 3 3m18 18-3.1-3.1M9.88 9.88 3 3m10.122 10.122L21 21"/></svg>
                </button>
            </div>
            <div id="capslock-confirm" style="display:none;margin-top:.4rem;align-items:center;gap:.35rem;font-size:.75rem;font-weight:600;color:#f97316">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="17 11 12 6 7 11"/><line x1="12" y1="6" x2="12" y2="18"/><line x1="8" y1="18" x2="16" y2="18"/>
                </svg>
                Caps Lock is on
            </div>
            @error('password_confirmation')<p class="auth-field-error">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="auth-btn" style="margin-top:1.5rem">
            Create Account
        </button>

        <p style="text-align:center;margin-top:1.5rem;font-size:.83rem;color:var(--text-muted)">
            Already have an account?
            <a href="{{ route('login') }}" style="font-weight:700;color:var(--primary);text-decoration:none">Sign in</a>
        </p>
    </form>

    <script>
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.querySelector('.eye-show').style.display = show ? 'none' : '';
        btn.querySelector('.eye-hide').style.display = show ? '' : 'none';
    }

    function checkCaps(e, alertId) {
        const on = e.getModifierState && e.getModifierState('CapsLock');
        const el = document.getElementById(alertId);
        if (el) el.style.display = on ? 'flex' : 'none';
    }

    function updateStrength(val) {
        const wrap = document.getElementById('pw-strength-wrap');
        wrap.style.display = val.length ? 'block' : 'none';

        const checks = {
            'req-len':   val.length >= 8,
            'req-upper': /[A-Z]/.test(val),
            'req-lower': /[a-z]/.test(val),
            'req-num':   /[0-9]/.test(val),
            'req-sym':   /[^A-Za-z0-9]/.test(val),
        };

        let passed = 0;
        for (const [id, ok] of Object.entries(checks)) {
            const el = document.getElementById(id);
            el.textContent = (ok ? '✓ ' : '✗ ') + el.textContent.slice(2);
            el.classList.toggle('pw-req-ok', ok);
            if (ok) passed++;
        }

        const fill  = document.getElementById('pw-strength-fill');
        const label = document.getElementById('pw-strength-label');
        const pct   = (passed / 5) * 100;

        fill.style.width = pct + '%';

        if (pct <= 20)      { fill.style.background = '#ef4444'; label.textContent = 'Very Weak'; label.style.color = '#ef4444'; }
        else if (pct <= 40) { fill.style.background = '#f97316'; label.textContent = 'Weak';      label.style.color = '#f97316'; }
        else if (pct <= 60) { fill.style.background = '#eab308'; label.textContent = 'Fair';      label.style.color = '#eab308'; }
        else if (pct <= 80) { fill.style.background = '#84cc16'; label.textContent = 'Good';      label.style.color = '#84cc16'; }
        else                { fill.style.background = '#22c55e'; label.textContent = 'Strong';    label.style.color = '#22c55e'; }
    }
    </script>
</x-guest-layout>
