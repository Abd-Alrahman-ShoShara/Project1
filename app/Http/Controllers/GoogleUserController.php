<?php

namespace App\Http\Controllers;

use App\Models\GoogleUser;
use App\Models\User;
use Illuminate\Http\Request;

class GoogleUserController extends Controller
{
    public function googleRegister(Request $request)
{
    $attr = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email',
        'google_id' => 'required',
    ]);

    $googleUser = GoogleUser::where('email', $attr['email'])->first();

    if ($googleUser) {
        $token = $googleUser->user->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $googleUser->load('user'),
            'token' => $token,
        ], 200);
    } else {
        $user = User::create([
            'name' => $attr['name'],
            'type' => 'google',
        ]);

        $googleUser = GoogleUser::create([
            'user_id' => $user->id,
            'email' => $attr['email'],
            'google_id' => $attr['google_id'],
        ]);

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $googleUser->load('user'),
            'token' => $token,
        ], 200);
    }
}
}
