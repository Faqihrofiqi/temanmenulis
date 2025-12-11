<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists with this Google ID
            $existingUser = User::where('provider', 'google')
                ->where('provider_id', $googleUser->getId())
                ->first();

            if ($existingUser) {
                // Login existing user
                Auth::login($existingUser);
                return redirect()->route('dashboard')->with('success', 'Login berhasil dengan Google!');
            }

            // Check if user exists with same email
            $existingEmailUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingEmailUser) {
                // Update existing user with Google provider info
                $existingEmailUser->update([
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                Auth::login($existingEmailUser);
                return redirect()->route('dashboard')->with('success', 'Akun berhasil dihubungkan dengan Google!');
            }

            // Create new user
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(uniqid()), // Random password for OAuth users
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(), // Google emails are verified
            ]);

            Auth::login($newUser);
            return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat dengan Google!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}
