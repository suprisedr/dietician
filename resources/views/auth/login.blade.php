<x-guest-layout>

    {{-- Session Status --}}
    @if(session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    {{-- Heading --}}
    <div style="margin-bottom:2rem">
        <h1 class="auth-card-title">Welcome back 👋</h1>
        <p class="auth-card-sub">Sign in to your dietitian account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" style="width:100%">
        @csrf

        {{-- HPCSA Number --}}
        <div class="auth-field">
            <label class="auth-label" for="dietician_number">HPCSA Registration Number</label>
            <div class="auth-input-wrap">
                <input
                    id="dietician_number"
                    type="text"
                    name="dietician_number"
                    value="{{ old('dietician_number') }}"
                    class="auth-input"
                    required autofocus autocomplete="username"
                    placeholder="DT0012345"
                />
                <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-5m-4 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1m-4 0h4"/>
                </svg>
            </div>
            @error('dietician_number')
                <p class="auth-field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="auth-field">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.45rem">
                <label class="auth-label" for="password" style="margin-bottom:0">Password</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:.75rem;font-weight:600;color:var(--primary);text-decoration:none">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="auth-input-wrap">
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="auth-input auth-input-pw"
                    required autocomplete="current-password"
                    placeholder="••••••••"
                />
                <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4h8z"/>
                </svg>
                <button type="button" class="pw-toggle" onclick="togglePw('password',this)" tabindex="-1" aria-label="Show password">
                    <svg class="eye-show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="eye-hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 0 1 2.71-4.29M9.88 9.88a3 3 0 1 0 4.243 4.243M6.1 6.1 3 3m18 18-3.1-3.1M9.88 9.88 3 3m10.122 10.122L21 21"/></svg>
                </button>
            </div>
            <div id="capslock-login" style="display:none;margin-top:.4rem;align-items:center;gap:.35rem;font-size:.75rem;font-weight:600;color:#f97316">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:.85rem;height:.85rem;flex-shrink:0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="17 11 12 6 7 11"/><line x1="12" y1="6" x2="12" y2="18"/><line x1="8" y1="18" x2="16" y2="18"/>
                </svg>
                Caps Lock is on
            </div>
            @error('password')
                <p class="auth-field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="auth-btn">Sign In</button>

        {{-- Register link --}}
        <p style="text-align:center;margin-top:1.5rem;font-size:.83rem;color:var(--text-muted)">
            Don't have an account?
            <a href="{{ route('register') }}" style="font-weight:700;color:var(--primary);text-decoration:none">Create one →</a>
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

(function () {
    const pwInput   = document.getElementById('password');
    const capsAlert = document.getElementById('capslock-login');

    function checkCaps(e) {
        const on = e.getModifierState && e.getModifierState('CapsLock');
        capsAlert.style.display = on ? 'flex' : 'none';
    }

    pwInput.addEventListener('keyup',    checkCaps);
    pwInput.addEventListener('keydown',  checkCaps);
    pwInput.addEventListener('focus',    checkCaps);
})();
</script>
</x-guest-layout>
