<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            [
                'first_name' => $googleUser->user['given_name'],
                'last_name' => $googleUser->user['family_name'],
                'password' => bcrypt(str_random(16)), // Random password for Google users
            ]
        );

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }
}
