<?php

namespace App\Http\Controllers\Auth;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function __construct(protected Google2FA $google2fa) {}

    // ── SETUP ─────────────────────────────────────────────────────────────────

    public function showSetup(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Only redirect to challenge when already set up AND not intentionally adding a new method
        if ($user->hasTwoFactorEnabled() && ! $request->boolean('add')) {
            return redirect()->route('two-factor.challenge');
        }

        // Clear only the pending method so user returns to method picker without losing confirmed methods
        if ($request->boolean('reset')) {
            $user->forceFill(['two_factor_method' => null])->save();
        }

        // No pending method — show the method picker
        if (! $user->two_factor_method) {
            return view('auth.two-factor-method', [
                'gracePeriodEndsAt' => $user->twoFactorGracePeriodEndsAt(),
                'canSkip'           => $user->canSkipTwoFactorSetup(),
                'confirmedMethods'  => $user->confirmedMethods(),
            ]);
        }

        if ($user->two_factor_method === 'email') {
            return view('auth.two-factor-setup-email', [
                'gracePeriodEndsAt' => $user->twoFactorGracePeriodEndsAt(),
                'canSkip'           => $user->canSkipTwoFactorSetup(),
                'email'             => $user->email,
            ]);
        }

        if ($user->two_factor_method === 'passkey') {
            return view('auth.two-factor-setup-passkey', [
                'gracePeriodEndsAt' => $user->twoFactorGracePeriodEndsAt(),
                'canSkip'           => $user->canSkipTwoFactorSetup(),
            ]);
        }

        // TOTP (default)
        $secret = $this->ensurePendingSecret($user);

        return view('auth.two-factor-setup', [
            'secret'            => $secret,
            'qrCodeSvg'         => $this->qrCodeSvg($this->otpAuthUrl($user->email, $secret)),
            'gracePeriodEndsAt' => $user->twoFactorGracePeriodEndsAt(),
            'canSkip'           => $user->canSkipTwoFactorSetup(),
        ]);
    }

    public function selectMethod(Request $request): RedirectResponse
    {
        $request->validate(['method' => ['required', 'in:totp,email,passkey']]);

        $user   = $request->user();
        $method = $request->input('method');

        $user->forceFill([
            'two_factor_method'      => $method,
            'two_factor_prompted_at' => $user->two_factor_prompted_at ?? now(),
            // Regenerate TOTP secret whenever TOTP is (re)selected
            'two_factor_secret'      => $method === 'totp' ? null : $user->two_factor_secret,
        ])->save();

        return redirect()->route('two-factor.setup');
    }

    // ── TOTP confirm ──────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $user   = $request->user();
        $secret = $this->ensurePendingSecret($user);

        if (! $this->google2fa->verifyKey($secret, $request->string('code')->toString(), 1)) {
            throw ValidationException::withMessages(['code' => 'The authentication code is invalid.']);
        }

        $user->enableMethod('totp');
        $request->session()->put('auth.two_factor_passed', true);
        $request->session()->forget('auth.two_factor_skip_granted');

        return redirect()->route('two-factor.setup', ['add' => 1])
            ->with('status', 'Authenticator app enabled. Add more methods below or go to the app.');
    }

    // ── Email OTP setup ───────────────────────────────────────────────────────

    public function sendEmailCode(Request $request): RedirectResponse
    {
        $user = $request->user();
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put("2fa_email_{$user->id}", $code, now()->addMinutes(10));

        Mail::to($user->email)->send(new \App\Mail\TwoFactorCodeMail($user, $code));

        return back()->with('code_sent', true);
    }

    public function verifyEmailSetup(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $user   = $request->user();
        $stored = Cache::get("2fa_email_{$user->id}");

        if (! $stored || ! hash_equals($stored, $request->input('code'))) {
            throw ValidationException::withMessages(['code' => 'The code is incorrect or has expired.']);
        }

        Cache::forget("2fa_email_{$user->id}");
        $user->enableMethod('email');
        $request->session()->put('auth.two_factor_passed', true);
        $request->session()->forget('auth.two_factor_skip_granted');

        return redirect()->route('two-factor.setup', ['add' => 1])
            ->with('status', 'Email verification enabled. Add more methods below or go to the app.');
    }

    // ── CHALLENGE ─────────────────────────────────────────────────────────────

    public function showChallenge(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.setup');
        }

        if ($request->session()->has('auth.two_factor_passed')) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return view('auth.two-factor-challenge', [
            'methods'   => $user->confirmedMethods(),
            'codeSent'  => session('code_sent', false),
            'userEmail' => $user->email,
        ]);
    }

    // ── TOTP verify ───────────────────────────────────────────────────────────

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.setup');
        }

        $secret = Crypt::decryptString($user->two_factor_secret);

        if (! $this->google2fa->verifyKey($secret, $request->string('code')->toString(), 1)) {
            throw ValidationException::withMessages(['code' => 'The authentication code is invalid.']);
        }

        $request->session()->put('auth.two_factor_passed', true);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    // ── Email challenge verify ─────────────────────────────────────────────────

    public function verifyEmail(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $user   = $request->user();
        $stored = Cache::get("2fa_email_{$user->id}");

        if (! $stored || ! hash_equals($stored, $request->input('code'))) {
            throw ValidationException::withMessages(['code' => 'The code is incorrect or has expired.']);
        }

        Cache::forget("2fa_email_{$user->id}");
        $request->session()->put('auth.two_factor_passed', true);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    // ── Send email code during challenge ──────────────────────────────────────

    public function sendChallengeEmailCode(Request $request): RedirectResponse
    {
        $user = $request->user();
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put("2fa_email_{$user->id}", $code, now()->addMinutes(10));

        Mail::to($user->email)->send(new \App\Mail\TwoFactorCodeMail($user, $code));

        return back()->with('code_sent', true);
    }

    // ── Skip ─────────────────────────────────────────────────────────────────

    public function skip(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->canSkipTwoFactorSetup()) {
            return redirect()->route('two-factor.setup')
                ->withErrors(['code' => 'The 15-day grace period has ended. Set up two-factor authentication to continue.']);
        }

        $request->session()->put('auth.two_factor_skip_granted', true);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    protected function ensurePendingSecret($user): string
    {
        if ($user->two_factor_secret) {
            return Crypt::decryptString($user->two_factor_secret);
        }

        $secret = $this->google2fa->generateSecretKey();

        $user->forceFill([
            'two_factor_secret'      => Crypt::encryptString($secret),
            'two_factor_prompted_at' => $user->two_factor_prompted_at ?? now(),
        ])->save();

        return $secret;
    }

    protected function otpAuthUrl(string $email, string $secret): string
    {
        $issuer = config('app.name', 'Laravel');
        $label  = rawurlencode($issuer . ':' . $email);

        return sprintf('otpauth://totp/%s?secret=%s&issuer=%s', $label, $secret, rawurlencode($issuer));
    }

    protected function qrCodeSvg(string $contents): string
    {
        $writer = new Writer(new ImageRenderer(new RendererStyle(220), new SvgImageBackEnd()));
        return $writer->writeString($contents);
    }
}
