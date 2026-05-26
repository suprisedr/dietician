<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasskeyController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('two-factor/setup', [TwoFactorController::class, 'showSetup'])
        ->name('two-factor.setup');

    Route::post('two-factor/setup', [TwoFactorController::class, 'store'])
        ->name('two-factor.store');

    Route::get('two-factor/challenge', [TwoFactorController::class, 'showChallenge'])
        ->name('two-factor.challenge');

    Route::post('two-factor/challenge', [TwoFactorController::class, 'verify'])
        ->middleware('throttle:6,1')
        ->name('two-factor.verify');

    Route::post('two-factor/skip', [TwoFactorController::class, 'skip'])
        ->name('two-factor.skip');

    // Method selection
    Route::post('two-factor/method', [TwoFactorController::class, 'selectMethod'])
        ->name('two-factor.select-method');

    // Email 2FA setup & challenge
    Route::post('two-factor/email/send', [TwoFactorController::class, 'sendEmailCode'])
        ->name('two-factor.email.send');
    Route::post('two-factor/email/verify-setup', [TwoFactorController::class, 'verifyEmailSetup'])
        ->name('two-factor.email.verify-setup');
    Route::post('two-factor/email/challenge-send', [TwoFactorController::class, 'sendChallengeEmailCode'])
        ->name('two-factor.email.challenge-send');
    Route::post('two-factor/email/verify', [TwoFactorController::class, 'verifyEmail'])
        ->middleware('throttle:6,1')
        ->name('two-factor.email.verify');

    // Passkey (WebAuthn) setup & challenge
    Route::post('two-factor/passkey/register-options', [PasskeyController::class, 'registerOptions'])
        ->name('two-factor.passkey.register-options');
    Route::post('two-factor/passkey/register', [PasskeyController::class, 'register'])
        ->name('two-factor.passkey.register');
    Route::post('two-factor/passkey/auth-options', [PasskeyController::class, 'authOptions'])
        ->name('two-factor.passkey.auth-options');
    Route::post('two-factor/passkey/authenticate', [PasskeyController::class, 'authenticate'])
        ->middleware('throttle:6,1')
        ->name('two-factor.passkey.authenticate');
});

Route::middleware(['auth', 'two-factor'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
