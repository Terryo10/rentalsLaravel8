<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
     public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3',
                'email' => 'required|email',
                'password' => 'required|alpha_num|min:5',
                'confirm_password' => 'required|same:password',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $input = array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        );

        // check if email already registered
        $user = User::where('email', $request->email)->first();
        if (!is_null($user)) {
            return response()->json(['success' => false, 'message' => 'Sorry! this email is already registered'],401);
        }

        // create and return data
        $user = User::create($input);
        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['user' => new UserResource($user), 'token' => $accessToken, 'success' => true, 'message' => 'Registered successfully']);

    }
}
