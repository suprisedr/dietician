<?php

namespace App\Http\Controllers;

use App\Mail\AdminNewDieticianMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OnboardingController extends Controller
{
    public const STEPS = [
        1 => ['title' => 'Personal details',  'fields' => ['name']],
        2 => ['title' => 'HPCSA DT Number',    'fields' => ['dietician_number']],
    ];

    public function show(Request $request, ?int $step = null)
    {
        $user = $request->user();

        if ($user->hasCompletedOnboarding()) {
            return redirect()->route('dashboard');
        }

        $step = $step ?? $user->onboarding_step ?? 1;
        $step = max(1, min(count(self::STEPS), $step));

        return view('auth.onboarding', [
            'step'      => $step,
            'totalSteps' => count(self::STEPS),
            'stepMeta'  => self::STEPS[$step],
            'steps'     => self::STEPS,
        ]);
    }

    public function store(Request $request, int $step)
    {
        $user = $request->user();
        $meta = self::STEPS[$step] ?? null;

        if (! $meta) {
            return redirect()->route('onboarding.show');
        }

        $rules = $this->rulesForStep($step, $user);
        $validated = $request->validate($rules);

        if ($step === 2) {
            $validated['dietician_number'] = strtoupper($validated['dietician_number']);
        }

        $user->forceFill($validated)->save();

        $nextStep = $step + 1;

        if ($nextStep > count(self::STEPS)) {
            $user->update(['onboarding_step' => null]);

            Mail::to(env('ADMIN_EMAIL', 'support@mindfulnutrico.co.za'))
                ->send(new AdminNewDieticianMail($user->fresh()));

            Mail::send('emails.welcome', [
                'userName'     => $user->name,
                'dashboardUrl' => route('dashboard'),
                'logoUrl'      => asset('images/mindful-nutrico.png'),
            ], fn ($m) => $m->to($user->email, $user->name)
                ->subject('Welcome to Mindfulnutrico Dietitians App'));

            return redirect()->route('dashboard')->with('success', 'Onboarding complete — welcome!');
        }

        $user->update(['onboarding_step' => $nextStep]);

        return redirect()->route('onboarding.show', ['step' => $nextStep]);
    }

    public function skip(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function rulesForStep(int $step, $user): array
    {
        return match ($step) {
            1 => ['name' => ['required', 'string', 'max:255']],
            2 => ['dietician_number' => ['required', 'string', 'regex:/^DT\d{7}$/i', 'unique:users,dietician_number,' . $user->id]],
            default => [],
        };
    }
}
