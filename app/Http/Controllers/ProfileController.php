<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
        $validated = $request->validated();
        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $sensitiveFields = ['phone', 'institution', 'study_program', 'city', 'student_id', 'linkedin_url'];
        if ($request->user()->isDirty($sensitiveFields) && $request->user()->profile_verification_status === User::PROFILE_STATUS_VERIFIED) {
            $request->user()->profile_verification_status = User::PROFILE_STATUS_DRAFT;
            $request->user()->profile_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

    public function requestVerification(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->profile_verification_status === User::PROFILE_STATUS_VERIFIED) {
            return Redirect::route('profile.edit')->with('profile_verification_status', 'already-verified');
        }

        $requiredFields = ['phone', 'institution', 'study_program'];
        $missing = collect($requiredFields)->filter(fn (string $field) => blank($user->{$field} ?? null))->values();

        if ($missing->isNotEmpty()) {
            return Redirect::route('profile.edit')->with('profile_verification_error', 'Lengkapi data kontak dan institusi sebelum mengajukan verifikasi.');
        }

        $user->forceFill([
            'profile_verification_status' => User::PROFILE_STATUS_PENDING,
            'profile_verified_at' => null,
        ])->save();

        return Redirect::route('profile.edit')->with('profile_verification_status', 'request-submitted');
    }
}
