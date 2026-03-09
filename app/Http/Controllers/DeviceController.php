<?php

namespace App\Http\Controllers;

use App\Services\DeviceManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeviceController extends Controller
{
    public function __construct(protected DeviceManager $devices) {}

    /**
     * List all devices for the authenticated user.
     */
    public function index(Request $request): View
    {
        $user      = $request->user();
        $sessionId = $request->session()->getId();
        $devices   = $this->devices->getDevicesForUser($user, $sessionId);
        $limit     = $this->devices->deviceLimitForUser($user);

        return view('devices.index', compact('devices', 'limit'));
    }

    /**
     * Revoke a specific device session.
     */
    public function destroy(Request $request, int $device): RedirectResponse
    {
        $revoked = $this->devices->revokeDevice($device, $request->user());

        return redirect()->route('devices.index')
            ->with($revoked ? 'success' : 'error',
                   $revoked ? 'Device session revoked.' : 'Device not found.');
    }

    /**
     * Revoke all other sessions except the current one.
     */
    public function revokeOthers(Request $request): RedirectResponse
    {
        $count = $this->devices->revokeOthers(
            $request->user(),
            $request->session()->getId()
        );

        return redirect()->route('devices.index')
            ->with('success', $count . ' other device session(s) removed.');
    }
}
