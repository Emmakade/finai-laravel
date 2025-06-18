<?php

namespace App\Http\Controllers;

use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleAuthController extends Controller
{
    private $googleClient;

    public function __construct()
    {
        $this->googleClient = new Client();
        $this->googleClient->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->googleClient->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->googleClient->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->googleClient->createAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        if (!$request->has('code')) {
            return response()->json(['error' => 'Authorization code not found'], 400);
        }

        $token = $this->googleClient->fetchAccessTokenWithAuthCode($request->code);

        if (isset($token['error'])) {
            return response()->json(['error' => $token['error_description']], 400);
        }

        $this->googleClient->setAccessToken($token['access_token']);
        $googleUser = $this->googleClient->verifyIdToken();

        if (!$googleUser) {
            return response()->json(['error' => 'Invalid Google user data'], 400);
        }

        // Save user or log in
        $user = User::updateOrCreate(
            ['email' => $googleUser['email']],
            [
                'first_name' => $googleUser['given_name'],
                'last_name' => $googleUser['family_name'],
                'google_id' => $googleUser['sub'],
                //'password' => bcrypt(str_random(16)), // Random password for Google users
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
}