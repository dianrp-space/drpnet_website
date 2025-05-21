<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        Log::info('SocialiteController: Redirecting to Google.');
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        Log::info('SocialiteController: handleGoogleCallback reached.');
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            Log::info('Google User Retrieved:', [
                'id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            // Find user by google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                Log::info('User found by google_id. Logging in.', ['user_id' => $user->id]);
                Auth::login($user, true);
                
                // Cek apakah username dan phone kosong
                if (empty($user->username) || empty($user->phone)) {
                    Log::info('User profile incomplete. Redirecting to complete profile page.', ['user_id' => $user->id]);
                    return redirect()->route('complete-profile');
                }
                
                return redirect()->intended('/dashboard');
            }

            // Check if user exists by email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                Log::info('User found by email. Linking Google account.', ['user_id' => $user->id]);
                $user->google_id = $googleUser->getId();
                $user->avatar = $googleUser->getAvatar();
                $user->save();

                Auth::login($user, true);
                
                // Cek apakah username dan phone kosong
                if (empty($user->username) || empty($user->phone)) {
                    Log::info('User profile incomplete. Redirecting to complete profile page.', ['user_id' => $user->id]);
                    return redirect()->route('complete-profile');
                }
                
                return redirect()->intended('/dashboard');
            }

            Log::info('User not found. Creating new user.', ['email' => $googleUser->getEmail()]);
            // Buat username sementara dari email
            $baseUsername = Str::slug(Str::before($googleUser->getEmail(), '@'));
            $username = $baseUsername;
            $counter = 1;
            
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            // User does not exist, create a new user
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'username' => $username, // Gunakan username sementara
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
            ]);
            Log::info('New user created.', ['user_id' => $newUser->id]);

            Auth::login($newUser, true);
            
            // Arahkan ke halaman lengkapi profil untuk mengisi nomor HP
            Log::info('New user profile incomplete. Redirecting to complete profile page.', ['user_id' => $newUser->id]);
            return redirect()->route('complete-profile');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Google Socialite InvalidStateException: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Login attempt failed due to an invalid state. Please try again.');
        } catch (\Exception $e) {
            Log::error('Google Socialite Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect('/login')->with('error', 'Unable to login using Google. Please try again. Check logs for details.');
        }
    }
}
