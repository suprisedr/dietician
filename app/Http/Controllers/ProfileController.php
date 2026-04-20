<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Upload or replace the letterhead image.
     */
    public function updateLetterhead(Request $request): RedirectResponse
    {
        $request->validate([
            'letterhead' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:3072'],
        ]);

        $user = $request->user();

        if ($user->letterhead_path) {
            Storage::disk('local')->delete($user->letterhead_path);
        }

        $ext  = $request->file('letterhead')->getClientOriginalExtension();
        $path = $request->file('letterhead')->storeAs(
            'letterheads/' . $user->id,
            'letterhead.' . strtolower($ext),
            'local'
        );

        $user->update(['letterhead_path' => $path]);

        return Redirect::route('profile.edit')->with('status', 'letterhead-updated');
    }

    /**
     * Remove the letterhead image.
     */
    public function removeLetterhead(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->letterhead_path) {
            Storage::disk('local')->delete($user->letterhead_path);
            $user->update(['letterhead_path' => null]);
        }

        return Redirect::route('profile.edit')->with('status', 'letterhead-removed');
    }

    /**
     * Serve the letterhead image for in-browser preview.
     */
    public function previewLetterhead(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $user = $request->user();

        abort_if(! $user->letterhead_path, 404);

        $fullPath = Storage::disk('local')->path($user->letterhead_path);

        abort_if(! file_exists($fullPath), 404);

        $mime = mime_content_type($fullPath);

        return response()->file($fullPath, ['Content-Type' => $mime]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
