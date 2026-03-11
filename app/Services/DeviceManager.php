<?php

namespace App\Services;

use App\Models\PricingPackage;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;

class DeviceManager
{
    /**
     * Register or refresh the current device record for an authenticated user.
     * Call this on every authenticated request (via middleware).
     */
    public function touchDevice(Request $request): void
    {
        $user      = $request->user();
        $sessionId = $request->session()->getId();

        if (! $user || ! $sessionId) {
            return;
        }

        [$browser, $platform] = $this->parseUserAgent($request->userAgent() ?? '');
        $deviceName = $browser . ' on ' . $platform;

        UserDevice::updateOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id'        => $user->id,
                'device_name'    => $deviceName,
                'browser'        => $browser,
                'platform'       => $platform,
                'ip_address'     => $request->ip(),
                'last_active_at' => now(),
            ]
        );
    }

    /**
     * Check whether the user has exceeded their package device limit.
     * Returns true if they are over limit (and the current session is NOT already registered).
     */
    public function isOverLimit(Request $request): bool
    {
        $user      = $request->user();
        $sessionId = $request->session()->getId();

        if (! $user || ! $sessionId) {
            return false;
        }

        // If this session is already registered, it's fine.
        if (UserDevice::where('session_id', $sessionId)->exists()) {
            return false;
        }

        $limit       = $this->deviceLimitForUser($user);
        $activeCount = $this->teamDeviceCount($user);

        return $activeCount >= $limit;
    }

    /**
     * Count all devices belonging to the user and their team (owner + all members).
     */
    public function teamDeviceCount(User $user): int
    {
        $owner   = $user->subscriptionOwner();
        $userIds = $owner->teamMembers()->pluck('id')->prepend($owner->id)->all();
        return UserDevice::whereIn('user_id', $userIds)->count();
    }

    /**
     * Remove the device record for the current session (on logout).
     */
    public function removeDevice(string $sessionId): void
    {
        UserDevice::where('session_id', $sessionId)->delete();
    }

    /**
     * Remove a specific device by its ID, ensuring it belongs to the given user.
     */
    public function revokeDevice(int $deviceId, User $user): bool
    {
        $owner   = $user->subscriptionOwner();
        $userIds = $owner->teamMembers()->pluck('id')->prepend($owner->id)->all();

        $device = UserDevice::where('id', $deviceId)
            ->whereIn('user_id', $userIds)
            ->first();

        if (! $device) {
            return false;
        }

        $device->delete();
        return true;
    }

    /**
     * Remove all devices for a user except the current session.
     */
    public function revokeOthers(User $user, string $currentSessionId): int
    {
        return UserDevice::where('user_id', $user->id)
            ->where('session_id', '!=', $currentSessionId)
            ->delete();
    }

    /**
     * Get all devices for a user, marking which one is current.
     */
    public function getDevicesForUser(User $user, string $currentSessionId): \Illuminate\Database\Eloquent\Collection
    {
        return UserDevice::where('user_id', $user->id)
            ->orderByDesc('last_active_at')
            ->get()
            ->map(function (UserDevice $device) use ($currentSessionId) {
                $device->is_current = ($device->session_id === $currentSessionId);
                return $device;
            });
    }

    /**
     * Resolve the max-device limit from the user's pricing package.
     * Falls back to 1 if no package is linked.
     */
    public function deviceLimitForUser(User $user): int
    {
        $owner = $user->subscriptionOwner();

        if (isset($owner->pricing_package_slug)) {
            $pkg = PricingPackage::where('slug', $owner->pricing_package_slug)->first();
            if ($pkg) {
                return max(1, $pkg->max_users);
            }
        }

        // Default: 1 device
        return 1;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────

    private function parseUserAgent(string $ua): array
    {
        $browser  = $this->detectBrowser($ua);
        $platform = $this->detectPlatform($ua);

        return [$browser, $platform];
    }

    private function detectBrowser(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Edg')             => 'Edge',
            str_contains($ua, 'OPR')             => 'Opera',
            str_contains($ua, 'Chrome')          => 'Chrome',
            str_contains($ua, 'Firefox')         => 'Firefox',
            str_contains($ua, 'Safari')          => 'Safari',
            str_contains($ua, 'MSIE')
                || str_contains($ua, 'Trident') => 'IE',
            str_contains($ua, 'curl')            => 'curl',
            default                              => 'Unknown Browser',
        };
    }

    private function detectPlatform(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'iPhone')   => 'iPhone',
            str_contains($ua, 'iPad')     => 'iPad',
            str_contains($ua, 'Android')  => 'Android',
            str_contains($ua, 'Windows')  => 'Windows',
            str_contains($ua, 'Macintosh')
                || str_contains($ua, 'Mac OS') => 'macOS',
            str_contains($ua, 'Linux')    => 'Linux',
            default                       => 'Unknown OS',
        };
    }
}
