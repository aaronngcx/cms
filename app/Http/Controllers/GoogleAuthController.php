<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback()
    {
        // try {
            $user = Socialite::driver('google')->user();

            $authUser = User::where('email', $user->getEmail())->first();

            if (!$authUser) {
                return redirect()->route('login')->withErrors(['email' => 'No account associated with this email address.']);
            }

            Auth::login($authUser, true);

            return redirect()->route('dashboard');
        // } catch (\Exception $e) {
        //     // Handle any errors that may occur during the authentication process
        //     return redirect()->route('login')->withErrors(['email' => 'Could not authenticate with Google.']);
        // }
    }
}
