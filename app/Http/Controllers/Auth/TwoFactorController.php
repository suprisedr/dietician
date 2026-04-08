<?php

namespace App\Http\Controllers\Auth;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function __construct(protected Google2FA $google2fa) {}

    public function showSetup(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.challenge');
        }

        $secret = $this->ensurePendingSecret($user);

        return view('auth.two-factor-setup', [
            'secret' => $secret,
            'qrCodeSvg' => $this->qrCodeSvg($this->otpAuthUrl($user->email, $secret)),
            'gracePeriodEndsAt' => $user->twoFactorGracePeriodEndsAt(),
            'canSkip' => $user->canSkipTwoFactorSetup(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            $request->session()->put('auth.two_factor_passed', true);

            return redirect()->intended(route('dashboard', absolute: false));
        }

        $secret = $this->ensurePendingSecret($user);

        if (! $this->google2fa->verifyKey($secret, $request->string('code')->toString(), 1)) {
            throw ValidationException::withMessages([
                'code' => 'The authentication code is invalid. Please try again.',
            ]);
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => [],
        ])->save();

        $request->session()->put('auth.two_factor_passed', true);
        $request->session()->forget('auth.two_factor_skip_granted');

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('status', 'Two-factor authentication has been enabled.');
    }

    public function showChallenge(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.setup');
        }

        if ($request->session()->has('auth.two_factor_passed')) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.setup');
        }

        $secret = Crypt::decryptString($user->two_factor_secret);

        if (! $this->google2fa->verifyKey($secret, $request->string('code')->toString(), 1)) {
            throw ValidationException::withMessages([
                'code' => 'The authentication code is invalid. Please try again.',
            ]);
        }

        $request->session()->put('auth.two_factor_passed', true);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function skip(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->canSkipTwoFactorSetup()) {
            return redirect()->route('two-factor.setup')
                ->withErrors([
                    'code' => 'The 15-day grace period has ended. Set up two-factor authentication to continue.',
                ]);
        }

        $request->session()->put('auth.two_factor_skip_granted', true);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    protected function ensurePendingSecret($user): string
    {
        if ($user->two_factor_secret) {
            return Crypt::decryptString($user->two_factor_secret);
        }

        $secret = $this->google2fa->generateSecretKey();

        $user->forceFill([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_prompted_at' => $user->two_factor_prompted_at ?? now(),
        ])->save();

        return $secret;
    }

    protected function otpAuthUrl(string $email, string $secret): string
    {
        $issuer = config('app.name', 'Laravel');
        $label = rawurlencode($issuer.':'.$email);

        return sprintf(
            'otpauth://totp/%s?secret=%s&issuer=%s',
            $label,
            $secret,
            rawurlencode($issuer),
        );
    }

    protected function qrCodeSvg(string $contents): string
    {
        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(220),
                new SvgImageBackEnd(),
            ),
        );

        return $writer->writeString($contents);
    }
}
