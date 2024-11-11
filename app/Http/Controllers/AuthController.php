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
        $request->validated($request->only(['username', 'password']));

        if(!Auth::attempt($request->only(['username', 'password']))) {
            return response()->json([
                "message" => "Wrong credentials"
            ], 401);
        }

        $user = User::where('username', $request->username)->first();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function register()
    {

    }
}
