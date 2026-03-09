<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    /**
     * Display the registration view.
     */
    public function create(\Illuminate\Http\Request $request): View
    {
        if ($request->filled('plan')) {
            $slug = $request->input('plan');
            $allowed = ['package_1', 'package_2', 'package_3'];
            if (in_array($slug, $allowed)) {
                session(['pending_plan' => $slug]);
                // Also set intended so login redirects correctly if user logs in instead
                session()->put('url.intended', route('subscription.checkout', $slug));
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'dietician_number' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'dietician_number' => $request->dietician_number,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($pendingPlan = session()->pull('pending_plan')) {
            return redirect()->route('subscription.checkout', $pendingPlan);
        }

        return redirect(route('dashboard', absolute: false));
    }
}
