<?php

namespace App\Http\Middleware;

use App\Services\DeviceManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceDeviceLimit
{
    public function __construct(protected DeviceManager $devices) {}

    /**
     * On every authenticated request:
     *  1. If the current session is new and exceeds the package limit → block.
     *  2. Otherwise → touch (upsert) the device record.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            if ($this->devices->isOverLimit($request)) {
                // Logout so the session does not persist
                auth()->guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors([
                        'dietician_number' => 'Device limit reached for your plan. '
                            . 'Please sign out of another device or upgrade your package.',
                    ]);
            }

            $this->devices->touchDevice($request);
        }

        return $next($request);
    }
}
