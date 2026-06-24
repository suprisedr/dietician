<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'credential' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credential = $this->input('credential');
        $password   = $this->input('password');
        $isEmail    = filter_var($credential, FILTER_VALIDATE_EMAIL) !== false;
        $field      = $isEmail ? 'email' : 'dietician_number';

        $user = User::where($field, $credential)->first();

        if (! $user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'credential' => trans('auth.failed'),
            ]);
        }

        if ($isEmail && $user->hasCompletedOnboarding()) {
            throw ValidationException::withMessages([
                'credential' => 'Your onboarding is complete — please log in with your DT number.',
            ]);
        }

        if (! $isEmail && ! $user->hasCompletedOnboarding()) {
            throw ValidationException::withMessages([
                'credential' => 'Your onboarding is not yet complete — please log in with your email address.',
            ]);
        }

        if (! Auth::attempt([$field => $credential, 'password' => $password], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'credential' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'credential' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('credential')) . '|' . $this->ip());
    }
}
