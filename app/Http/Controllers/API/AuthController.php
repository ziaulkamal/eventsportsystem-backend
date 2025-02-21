<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username_or_email' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($fieldType, $request->username_or_email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses tidak dikenal !',
            ], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;
        $cookie = cookie('auth_token', $token, 60 * 24, null, null, true, true);
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token'   => $token,
            'data'    => $user,
        ], 200)->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();  // Hapus semua token

            // Hapus cookie
            $cookie = Cookie::forget('auth_token');

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
            ])->withCookie($cookie);
        }

        return response()->json([
            'success' => false,
            'message' => 'No user logged in',
        ], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => $user,
            'token' => $token,
        ], 201);
    }

    public function getUser()
    {
        $user = Auth::user();

        if ($user) {
            return response()->json([
                'success' => true,
                'data' => $user,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No user logged in',
        ], 401);
    }
}
