<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Verify a dietician's account (admin action via signed URL).
     */
    public function verifyDietician(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'This verification link is invalid or has been tampered with.');
        }

        $alreadyVerified = $user->admin_verified_at !== null;

        if (! $alreadyVerified) {
            $user->update(['admin_verified_at' => now()]);
        }

        return view('admin.verified', [
            'dietician'       => $user,
            'alreadyVerified' => $alreadyVerified,
        ]);
    }
}
