<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_redirects_unconfigured_users_to_two_factor_setup(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'dietician_number' => $user->dietician_number,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('two-factor.setup', absolute: false));
    }

    public function test_login_redirects_configured_users_to_two_factor_challenge(): void
    {
        $secret = app(Google2FA::class)->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_confirmed_at' => now(),
            'two_factor_prompted_at' => now()->subDay(),
        ]);

        $response = $this->post('/login', [
            'dietician_number' => $user->dietician_number,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('two-factor.challenge', absolute: false));
    }

    public function test_users_with_active_grace_period_can_access_authenticated_pages_after_skipping(): void
    {
        $user = User::factory()->create([
            'two_factor_prompted_at' => now()->subDays(5),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['auth.two_factor_skip_granted' => true])
            ->get('/profile');

        $response->assertOk();
    }

    public function test_users_cannot_skip_two_factor_after_the_grace_period(): void
    {
        $user = User::factory()->create([
            'two_factor_prompted_at' => now()->subDays(16),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['auth.two_factor_skip_granted' => true])
            ->get('/profile');

        $response->assertRedirect(route('two-factor.setup', absolute: false));
    }

    public function test_users_can_complete_two_factor_challenge(): void
    {
        $google2fa = app(Google2FA::class);
        $secret = $google2fa->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_confirmed_at' => now(),
            'two_factor_prompted_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($user)->post('/two-factor/challenge', [
            'code' => $google2fa->getCurrentOtp($secret),
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        $response->assertSessionHas('auth.two_factor_passed', true);
    }
}
