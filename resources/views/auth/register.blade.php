<x-guest-layout>
    {{-- Step progress bar --}}
    <div class="step-progress" id="step-progress-bar">
        <div class="step-pip active" id="pip-1"></div>
        <div class="step-pip" id="pip-2"></div>
        <div class="step-pip" id="pip-3"></div>
        <div class="step-pip" id="pip-4"></div>
        <div class="step-pip" id="pip-5"></div>
    </div>

    <form method="POST" action="{{ route('register') }}" id="register-form" style="width:100%">
        @csrf

        {{-- ── STEP 1: Name ── --}}
        <div class="step" id="step-1-content">
            <div style="margin-bottom:1.75rem">
                <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--primary);margin-bottom:.35rem">Step 1 of 5</p>
                <h1 class="auth-card-title">Welcome! Let's get started 🎉</h1>
                <p class="auth-card-sub">First, what's your full name?</p>
            </div>
            <div class="auth-field">
                <label class="auth-label" for="name">Full Name</label>
                <div class="auth-input-wrap">
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="auth-input" required autofocus autocomplete="name" placeholder="Dr. Jane Smith"/>
                    <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0 1 12 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                </div>
                @error('name')<p class="auth-field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── STEP 2: Email ── --}}
        <div class="step hidden" id="step-2-content">
            <div style="margin-bottom:1.75rem">
                <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--primary);margin-bottom:.35rem">Step 2 of 5</p>
                <h1 class="auth-card-title">Your email address 📧</h1>
                <p class="auth-card-sub">Used for notifications and account recovery.</p>
            </div>
            <div class="auth-field">
                <label class="auth-label" for="email">Email</label>
                <div class="auth-input-wrap">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="auth-input" required autocomplete="email" placeholder="jane@clinic.com"/>
                    <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/></svg>
                </div>
                @error('email')<p class="auth-field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── STEP 3: Dietician Number ── --}}
        <div class="step hidden" id="step-3-content">
            <div style="margin-bottom:1.75rem">
                <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--primary);margin-bottom:.35rem">Step 3 of 5</p>
                <h1 class="auth-card-title">Professional ID 🪪</h1>
                <p class="auth-card-sub">Enter your registered dietitian number.</p>
            </div>
            <div class="auth-field">
                <label class="auth-label" for="dietician_number">Dietitian Number</label>
                <div class="auth-input-wrap">
                    <input id="dietician_number" type="text" name="dietician_number" value="{{ old('dietician_number') }}" class="auth-input" required autocomplete="off" placeholder="e.g. DC-00123"/>
                    <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-5m-4 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1m-4 0h4"/></svg>
                </div>
                @error('dietician_number')<p class="auth-field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── STEP 4: Password ── --}}
        <div class="step hidden" id="step-4-content">
            <div style="margin-bottom:1.75rem">
                <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--primary);margin-bottom:.35rem">Step 4 of 5</p>
                <h1 class="auth-card-title">Create a password 🔐</h1>
                <p class="auth-card-sub">Choose a strong password for your account.</p>
            </div>
            <div class="auth-field">
                <label class="auth-label" for="password">Password</label>
                <div class="auth-input-wrap">
                    <input id="password" type="password" name="password" class="auth-input auth-input-pw" required autocomplete="new-password" placeholder="••••••••" oninput="updateStrength(this.value)"/>
                    <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4h8z"/></svg>
                    <button type="button" class="pw-toggle" onclick="togglePw('password',this)" tabindex="-1" aria-label="Show password">
                        <svg class="eye-show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="eye-hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 0 1 2.71-4.29M9.88 9.88a3 3 0 1 0 4.243 4.243M6.1 6.1 3 3m18 18-3.1-3.1M9.88 9.88 3 3m10.122 10.122L21 21"/></svg>
                    </button>
                </div>
                {{-- Strength meter --}}
                <div class="pw-strength-wrap" id="pw-strength-wrap" style="display:none">
                    <div class="pw-strength-bar"><div class="pw-strength-fill" id="pw-strength-fill"></div></div>
                    <div class="pw-reqs" id="pw-reqs">
                        <span class="pw-req" id="req-len">✗ 8+ characters</span>
                        <span class="pw-req" id="req-upper">✗ Uppercase</span>
                        <span class="pw-req" id="req-lower">✗ Lowercase</span>
                        <span class="pw-req" id="req-num">✗ Number</span>
                        <span class="pw-req" id="req-sym">✗ Symbol</span>
                    </div>
                </div>
                @error('password')<p class="auth-field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── STEP 5: Confirm Password ── --}}
        <div class="step hidden" id="step-5-content">
            <div style="margin-bottom:1.75rem">
                <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--primary);margin-bottom:.35rem">Step 5 of 5</p>
                <h1 class="auth-card-title">Confirm password ✅</h1>
                <p class="auth-card-sub">Just confirming — almost there!</p>
            </div>
            <div class="auth-field">
                <label class="auth-label" for="password_confirmation">Confirm Password</label>
                <div class="auth-input-wrap">
                    <input id="password_confirmation" type="password" name="password_confirmation" class="auth-input auth-input-pw" required autocomplete="new-password" placeholder="••••••••"/>
                    <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation',this)" tabindex="-1" aria-label="Show password">
                        <svg class="eye-show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg class="eye-hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 0 1 2.71-4.29M9.88 9.88a3 3 0 1 0 4.243 4.243M6.1 6.1 3 3m18 18-3.1-3.1M9.88 9.88 3 3m10.122 10.122L21 21"/></svg>
                    </button>
                </div>
                @error('password_confirmation')<p class="auth-field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Navigation --}}
        <div style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-top:.5rem">
            <button type="button" id="prev-btn" class="auth-btn-ghost" style="width:auto;padding:.75rem 1.25rem" hidden>
                ← Back
            </button>
            <button type="button" id="action-btn" class="auth-btn" style="flex:1">
                <span id="button-text">Continue →</span>
            </button>
        </div>

        <p style="text-align:center;margin-top:1.5rem;font-size:.83rem;color:var(--text-muted)">
            Already have an account?
            <a href="{{ route('login') }}" style="font-weight:700;color:var(--primary);text-decoration:none">Sign in →</a>
        </p>
    </form>

    <script>
    (function () {
        let step = 1;
        const total = 5;

        const pips    = Array.from({ length: total }, (_, i) => document.getElementById(`pip-${i + 1}`));
        const steps   = Array.from({ length: total }, (_, i) => document.getElementById(`step-${i + 1}-content`));
        const prevBtn = document.getElementById('prev-btn');
        const actBtn  = document.getElementById('action-btn');
        const btnText = document.getElementById('button-text');

        function show(n) {
            steps.forEach((el, i) => el.classList.toggle('hidden', i !== n - 1));
            pips.forEach((pip, i) => {
                pip.classList.remove('done', 'active');
                if (i < n - 1)      pip.classList.add('done');
                else if (i === n - 1) pip.classList.add('active');
            });
            prevBtn.hidden = n === 1;
            if (n === total) {
                btnText.textContent = 'Create Account ✓';
                actBtn.type = 'submit';
            } else {
                btnText.textContent = 'Continue →';
                actBtn.type = 'button';
            }

            // focus first input of newly visible step
            const input = steps[n - 1].querySelector('input');
            if (input) setTimeout(() => input.focus(), 50);
        }

        actBtn.addEventListener('click', () => {
            if (step < total) { step++; show(step); }
        });
        prevBtn.addEventListener('click', () => {
            if (step > 1) { step--; show(step); }
        });

        show(1);
    })();
    </script>

    <script>
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.querySelector('.eye-show').style.display = show ? 'none' : '';
        btn.querySelector('.eye-hide').style.display = show ? '' : 'none';
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

        const fill = document.getElementById('pw-strength-fill');
        const pct  = (passed / 5) * 100;
        fill.style.width = pct + '%';
        fill.style.background = pct <= 40 ? '#ef4444' : pct <= 60 ? '#f97316' : pct <= 80 ? '#eab308' : '#22c55e';
    }
    </script>
</x-guest-layout>
