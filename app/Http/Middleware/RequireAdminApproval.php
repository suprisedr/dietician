<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class RequireAdminApproval
{
    private const ALLOWED_WRITE_ROUTES = [
        'logout',
        'two-factor.*',
        'onboarding.*',
        'notifications.read',
        'notifications.read-all',
        'profile.update',
        'profile.destroy',
        'password.update',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $pending = $user && $user->hasVerifiedEmail() && ! $user->admin_verified_at;
        $onExemptPage = $request->routeIs('two-factor.*', 'onboarding.*');

        View::share('pendingApproval', $pending && ! $onExemptPage);

        if ($pending && $this->isMutatingRequest($request) && ! $this->isAllowedWriteRoute($request)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your account is pending admin approval. You can browse the app but cannot make changes yet.',
                ], 403);
            }

            return back()->with('error', 'Your account is pending admin approval. You can browse the app but cannot make changes yet.');
        }

        return $next($request);
    }

    private function isMutatingRequest(Request $request): bool
    {
        return in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);
    }

    private function isAllowedWriteRoute(Request $request): bool
    {
        foreach (self::ALLOWED_WRITE_ROUTES as $pattern) {
            if ($request->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    }
}
