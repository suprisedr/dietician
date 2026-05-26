<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\WebAuthnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRequestOptions;

class PasskeyController extends Controller
{
    public function __construct(private WebAuthnService $webAuthn) {}

    // ── Setup ─────────────────────────────────────────────────────────────────

    public function registerOptions(Request $request): JsonResponse
    {
        $user    = $request->user();
        $options = $this->webAuthn->registrationOptions($user);

        // Store serialised options in session for later verification
        $request->session()->put('webauthn.register_options', serialize($options));

        return response()->json($options);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'credential' => ['required', 'string'],
            'name'       => ['nullable', 'string', 'max:100'],
        ]);

        $serialised = $request->session()->get('webauthn.register_options');
        if (! $serialised) {
            return response()->json(['error' => 'Registration session expired. Please try again.'], 422);
        }

        $options = unserialize($serialised);
        if (! $options instanceof PublicKeyCredentialCreationOptions) {
            return response()->json(['error' => 'Invalid registration session.'], 422);
        }

        $user = $request->user();
        $name = $request->input('name') ?: ('Passkey ' . now()->format('d M Y'));

        $this->webAuthn->processRegistration($user, $request->input('credential'), $options, $name);

        $request->session()->forget('webauthn.register_options');

        $user->enableMethod('passkey');
        $request->session()->put('auth.two_factor_passed', true);
        $request->session()->forget('auth.two_factor_skip_granted');
        $request->session()->flash('status', 'Passkey registered. Add more methods below or go to the app.');

        return response()->json(['redirect' => route('two-factor.setup', ['add' => 1])]);
    }

    // ── Challenge ─────────────────────────────────────────────────────────────

    public function authOptions(Request $request): JsonResponse
    {
        $user    = $request->user();
        $options = $this->webAuthn->authenticationOptions($user);

        $request->session()->put('webauthn.auth_options', serialize($options));

        return response()->json($options);
    }

    public function authenticate(Request $request): JsonResponse
    {
        $request->validate(['credential' => ['required', 'string']]);

        $serialised = $request->session()->get('webauthn.auth_options');
        if (! $serialised) {
            return response()->json(['error' => 'Authentication session expired. Please try again.'], 422);
        }

        $options = unserialize($serialised);
        if (! $options instanceof PublicKeyCredentialRequestOptions) {
            return response()->json(['error' => 'Invalid authentication session.'], 422);
        }

        $user = $request->user();
        $ok   = $this->webAuthn->processAuthentication($user, $request->input('credential'), $options);

        $request->session()->forget('webauthn.auth_options');

        if (! $ok) {
            return response()->json(['error' => 'Passkey verification failed. Please try again.'], 422);
        }

        $request->session()->put('auth.two_factor_passed', true);

        return response()->json(['redirect' => redirect()->intended(route('dashboard'))->getTargetUrl()]);
    }
}
