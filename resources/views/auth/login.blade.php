<x-guest-layout>

    {{-- Session Status --}}
    @if(session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    {{-- Heading --}}
    <div style="margin-bottom:2rem">
        <h1 class="auth-card-title">Welcome back 👋</h1>
        <p class="auth-card-sub">Sign in to your dietician account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" style="width:100%">
        @csrf

        {{-- Dietician Number --}}
        <div class="auth-field">
            <label class="auth-label" for="dietician_number">Dietician Number</label>
            <div class="auth-input-wrap">
                <input
                    id="dietician_number"
                    type="text"
                    name="dietician_number"
                    value="{{ old('dietician_number') }}"
                    class="auth-input"
                    required autofocus autocomplete="username"
                    placeholder="e.g. DC-00123"
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
                    class="auth-input"
                    required autocomplete="current-password"
                    placeholder="••••••••"
                />
                <svg xmlns="http://www.w3.org/2000/svg" class="field-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2zm10-10V7a4 4 0 0 0-8 0v4h8z"/>
                </svg>
            </div>
            @error('password')
                <p class="auth-field-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="auth-check-wrap">
            <input id="remember_me" type="checkbox" name="remember" class="auth-check">
            <label for="remember_me" class="auth-check-label">Keep me signed in</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="auth-btn">Sign In</button>

        {{-- Register link --}}
        <p style="text-align:center;margin-top:1.5rem;font-size:.83rem;color:var(--text-muted)">
            Don't have an account?
            <a href="{{ route('register') }}" style="font-weight:700;color:var(--primary);text-decoration:none">Create one →</a>
        </p>
    </form>

</x-guest-layout>
