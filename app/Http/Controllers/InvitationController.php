<?php

namespace App\Http\Controllers;

use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class InvitationController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    /**
     * Show the team management page.
     */
    public function index(Request $request): View
    {
        $owner       = $request->user();
        $invitations = $owner->teamInvitations()->with('acceptedBy')->latest()->get();
        $members     = $owner->teamMembers()->latest()->get();

        return view('team.index', compact('owner', 'invitations', 'members'));
    }

    // ── Send invite ───────────────────────────────────────────────────────────

    /**
     * Send a team invitation email.
     */
    public function store(Request $request): RedirectResponse
    {
        $owner = $request->user();

        // Only subscription owners can invite
        if ($owner->isTeamMember()) {
            return back()->with('error', 'Team members cannot send invitations.');
        }

        if ($owner->remainingInviteSlots() <= 0) {
            return back()->with('error', 'You have reached the maximum number of team members for your plan. Upgrade to invite more.');
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = strtolower(trim($request->input('email')));

        // Cannot invite yourself
        if ($email === strtolower($owner->email)) {
            return back()->with('error', 'You cannot invite yourself.');
        }

        // Check if the email already belongs to a registered user under this owner
        if ($owner->teamMembers()->where('email', $email)->exists()) {
            return back()->with('error', 'That user is already a member of your team.');
        }

        // Prevent duplicate pending invite
        $existing = TeamInvitation::where('owner_id', $owner->id)
            ->where('email', $email)
            ->whereNull('accepted_at')
            ->first();

        if ($existing) {
            return back()->with('error', 'An invitation has already been sent to that email address.');
        }

        $invitation = TeamInvitation::create([
            'owner_id' => $owner->id,
            'email'    => $email,
            'token'    => TeamInvitation::generateToken(),
        ]);

        // Send invitation email
        $this->sendInviteEmail($invitation, $owner);

        return back()->with('success', "Invitation sent to {$email}.");
    }

    // ── Accept (public) ───────────────────────────────────────────────────────

    /**
     * Show the invitation acceptance landing page (public, no auth required).
     */
    public function accept(string $token): View|RedirectResponse
    {
        $invitation = TeamInvitation::where('token', $token)->first();

        if (! $invitation || $invitation->isAccepted()) {
            return redirect()->route('login')
                ->with('error', 'This invitation is invalid or has already been used.');
        }

        $owner = $invitation->owner;

        return view('team.accept', compact('invitation', 'owner'));
    }

    // ── Revoke pending invite ─────────────────────────────────────────────────

    /**
     * Revoke a pending invitation.
     */
    public function destroy(Request $request, TeamInvitation $invitation): RedirectResponse
    {
        // Ensure the logged-in user owns this invitation
        if ((int) $invitation->owner_id !== (int) $request->user()->id) {
            abort(403, 'Unauthorised.');
        }

        if ($invitation->isAccepted()) {
            return back()->with('error', 'Cannot revoke an invitation that has already been accepted.');
        }

        $invitation->delete();

        return back()->with('success', 'Invitation revoked.');
    }

    // ── Remove member ─────────────────────────────────────────────────────────

    /**
     * Remove an accepted team member (resets their owner_id and downgrades to free).
     */
    public function removeMember(Request $request, User $member): RedirectResponse
    {
        $owner = $request->user();

        // Ensure the member actually belongs to this owner
        if ((int) $member->owner_id !== (int) $owner->id) {
            abort(403, 'Unauthorised.');
        }

        // Detach member — they become a standalone free user
        $member->update([
            'owner_id'             => null,
            'pricing_package_slug' => 'free',
        ]);

        return back()->with('success', "{$member->name} has been removed from your team.");
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function sendInviteEmail(TeamInvitation $invitation, User $owner): void
    {
        $acceptUrl = route('team.accept', ['token' => $invitation->token]);
        $ownerName = $owner->name;

        Mail::send('emails.team-invite', compact('acceptUrl', 'ownerName', 'invitation'), function ($message) use ($invitation) {
            $message->to($invitation->email)
                    ->subject("You've been invited to join a team on NutriCare");
        });
    }
}
