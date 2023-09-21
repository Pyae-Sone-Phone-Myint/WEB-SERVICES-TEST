<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "email" => "email|required",
            "password" => "required|min:6",
        ]);

        if (!Auth::attempt($request->only('email', "password"))) {
            return response()->json([
                "message" => "User Name or Password Wrong",
            ]);
        };

        $token = $request->user()->createToken($request->has("device") ? $request->device : 'unknown');

        return response()->json([
            "message" => Auth::user()->name . " has signed in successfully",
            "device_name" => $token->accessToken->name,
            "token" => $token->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => "logout successful",
        ]);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            "message" => "Logout All Successfully",
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', "current_password"],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => "Password Updated",
        ]);
    }
}
