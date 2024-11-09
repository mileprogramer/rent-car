<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAdministratorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginAdministratorRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validated($request->only(['email', 'password']));

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                "message" => "Wrong credentials"
            ], 401);
        }

        $user = User::where('username', $request->email)->first();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function logout()
    {

    }

    public function register()
    {

    }
}
