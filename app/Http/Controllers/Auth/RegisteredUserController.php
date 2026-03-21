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
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(\Illuminate\Http\Request $request): View
    {
        // Preserve plan intent (pricing CTA → register)
        if ($request->filled('plan')) {
            $slug    = $request->input('plan');
            $allowed = ['package_1', 'package_2', 'package_3'];
            if (in_array($slug, $allowed)) {
                session(['pending_plan' => $slug]);
                session()->put('url.intended', route('subscription.checkout', $slug));
            }
        }

        // Preserve invite token (invite email → register)
        if ($request->filled('token')) {
            $token      = $request->input('token');
            $invitation = TeamInvitation::where('token', $token)->whereNull('accepted_at')->first();
            if ($invitation) {
                session(['pending_invite_token' => $token]);
            }
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'dietician_number' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'password'         => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'dietician_number' => $request->dietician_number,
            'password'         => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Send welcome email (after email is verified the user lands on dashboard)
        Mail::send('emails.welcome', [
            'userName'     => $user->name,
            'dashboardUrl' => route('dashboard'),
            'logoUrl'      => asset('images/mindful-nutrico.png'),
        ], function ($m) use ($user) {
            $m->to($user->email, $user->name)
              ->subject('Welcome to Mindfulnutrico Dietitians App 🌿');
        });

        Auth::login($user);

        // If email not yet verified, redirect to verification notice
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // Handle pending team invite
        if ($inviteToken = session()->pull('pending_invite_token')) {
            $invitation = TeamInvitation::where('token', $inviteToken)
                ->whereNull('accepted_at')
                ->first();

            if ($invitation) {
                $owner = $invitation->owner;

                // Link this new user to the inviting owner and inherit their package
                $user->update([
                    'owner_id'             => $owner->id,
                    'pricing_package_slug' => $owner->pricing_package_slug,
                ]);

                // Mark invitation as accepted
                $invitation->update([
                    'accepted_by' => $user->id,
                    'accepted_at' => now(),
                ]);

                return redirect()->route('dashboard')
                    ->with('success', "Welcome! You've joined {$owner->name}'s team.");
            }
        }

        // Handle pending plan checkout
        if ($pendingPlan = session()->pull('pending_plan')) {
            return redirect()->route('subscription.checkout', $pendingPlan);
        }

        return redirect(route('dashboard', absolute: false));
    }
}
