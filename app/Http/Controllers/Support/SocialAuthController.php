<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google Callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login via Google gagal.');
        }

        // 1. Check if user exists by google_id
        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            Auth::login($user);
            return redirect()->intended('/dashboard');
        }

        // 2. Check if user exists by email (link accounts)
        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar
            ]);
            Auth::login($user);
            return redirect()->intended('/dashboard');
        }

        // 3. New User -> Store info in Session and redirect to Gender Selection
        session([
            'social_user' => [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'token' => $googleUser->token,
            ]
        ]);

        return redirect()->route('auth.social.complete');
    }

    /**
     * Show form to complete registration (Select Gender).
     */
    public function showCompleteForm()
    {
        if (!session()->has('social_user')) {
            return redirect()->route('login');
        }

        return view('auth.social-register', [
            'socialUser' => session('social_user')
        ]);
    }

    /**
     * Store final user data.
     */
    public function storeComplete(Request $request)
    {
        if (!session()->has('social_user')) {
            return redirect()->route('login');
        }

        $request->validate([
            'gender' => ['required', 'in:male,female'],
        ]);

        $socialUser = session('social_user');

        $user = User::create([
            'name' => $socialUser['name'],
            'email' => $socialUser['email'],
            'google_id' => $socialUser['google_id'],
            'avatar' => $socialUser['avatar'],
            'gender' => $request->gender,
            'password' => Hash::make(Str::random(16)), // Random password
            'role' => 'user',
        ]);

        session()->forget('social_user');

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
