<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class LoginController extends Controller
{
     public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if (!auth()->attempt($loginData)) {
            return $this->jsonError(201, "Invalid credentials");
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => new UserResource(auth()->user()), 'token' => $accessToken, 'success' => true, 'message' => 'Logged in successfully']);
    }

    public function refresh(Request $request)
    {
        //TODO will refresh token here
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
