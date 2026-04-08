<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\DeviceManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(protected DeviceManager $devices) {}

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $request->session()->forget([
            'auth.two_factor_passed',
            'auth.two_factor_skip_granted',
        ]);

        // Register this session's device record immediately so the device-limit
        // middleware doesn't treat the new session as an "extra" device on the
        // very next request (e.g. the 2FA setup/challenge page).
        $this->devices->touchDevice($request);

        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.challenge');
        }

        if (is_null($user->two_factor_prompted_at)) {
            $user->forceFill([
                'two_factor_prompted_at' => now(),
            ])->save();
        }

        return redirect()->route('two-factor.setup');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->devices->removeDevice($request->session()->getId());

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
