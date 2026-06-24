<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(Request $request): View
    {
        if ($request->filled('plan')) {
            $slug    = $request->input('plan');
            $allowed = ['package_1', 'package_2', 'package_3'];
            if (in_array($slug, $allowed)) {
                session(['pending_plan' => $slug]);
                session()->put('url.intended', route('subscription.checkout', $slug));
            }
        }

        if ($request->filled('token')) {
            $token      = $request->input('token');
            $invitation = TeamInvitation::where('token', $token)->whereNull('accepted_at')->first();
            if ($invitation) {
                session(['pending_invite_token' => $token]);
            }
        }

        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::create([
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'onboarding_step' => 1,
        ]);

        event(new Registered($user));

        $user->markEmailAsVerified();

        Auth::login($user);

        if ($inviteToken = session()->pull('pending_invite_token')) {
            $invitation = TeamInvitation::where('token', $inviteToken)
                ->whereNull('accepted_at')
                ->first();

            if ($invitation) {
                $owner = $invitation->owner;
                $user->update([
                    'owner_id'             => $owner->id,
                    'pricing_package_slug' => $owner->pricing_package_slug,
                ]);
                $invitation->update([
                    'accepted_by' => $user->id,
                    'accepted_at' => now(),
                ]);
                session(['invite_accepted_owner' => $owner->name]);
            }
        }

        return redirect()->route('onboarding.show', ['step' => 1]);
    }
}
