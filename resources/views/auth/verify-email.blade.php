<x-guest-layout>
    <div style="text-align:center;margin-bottom:1.75rem">
        {{-- Icon --}}
        <div style="width:4rem;height:4rem;border-radius:50%;background:linear-gradient(135deg,#e8f5ec,#d1fae5);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;border:2px solid #b7dfc9">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1e5c3d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/>
            </svg>
        </div>

        <h1 class="auth-card-title">Verify your email</h1>
        <p class="auth-card-sub">
            We've sent a verification link to <strong style="color:var(--primary)">{{ auth()->user()->email }}</strong>.
            Please check your inbox and click the link to activate your account.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.75rem;padding:.75rem 1rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;font-size:.85rem;color:#166534">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            A new verification link has been sent to your email.
        </div>
    @endif

    {{-- Steps --}}
    <div style="background:#f8faf9;border:1px solid #e6f2ec;border-radius:.75rem;padding:1.25rem;margin-bottom:1.5rem">
        <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#3a8c5f;margin:0 0 .85rem">What to do</p>
        <div style="display:flex;flex-direction:column;gap:.75rem">
            <div style="display:flex;align-items:flex-start;gap:.65rem">
                <span style="width:1.5rem;height:1.5rem;border-radius:50%;background:linear-gradient(135deg,#3a8c5f,#429677);color:#fff;font-size:.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0">1</span>
                <span style="font-size:.85rem;color:#2e3d30;line-height:1.5">Open the email from <strong>Mindfulnutrico</strong></span>
            </div>
            <div style="display:flex;align-items:flex-start;gap:.65rem">
                <span style="width:1.5rem;height:1.5rem;border-radius:50%;background:linear-gradient(135deg,#3a8c5f,#429677);color:#fff;font-size:.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0">2</span>
                <span style="font-size:.85rem;color:#2e3d30;line-height:1.5">Click the <strong>"Verify Email Address"</strong> button</span>
            </div>
            <div style="display:flex;align-items:flex-start;gap:.65rem">
                <span style="width:1.5rem;height:1.5rem;border-radius:50%;background:linear-gradient(135deg,#3a8c5f,#429677);color:#fff;font-size:.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0">3</span>
                <span style="font-size:.85rem;color:#2e3d30;line-height:1.5">You'll be redirected back and can continue setup</span>
            </div>
        </div>
    </div>

    {{-- Resend --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="auth-btn" style="width:100%">
            Resend Verification Email
        </button>
    </form>

    {{-- Didn't get it hint --}}
    <div style="background:#fffbeb;border:1px solid #fde68a;border-left:3px solid #f59e0b;border-radius:0 .5rem .5rem 0;padding:.7rem 1rem;margin-top:1.25rem;font-size:.8rem;color:#92400e;line-height:1.55">
        <strong>Didn't get the email?</strong> Check your spam or junk folder. If you still can't find it, click the button above to send a new one.
    </div>

    {{-- Logout --}}
    <div style="text-align:center;margin-top:1.25rem">
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:var(--text-muted);font-size:.82rem;cursor:pointer;text-decoration:underline">
                Sign out
            </button>
        </form>
    </div>
</x-guest-layout>
