<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username_or_email' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username_or_email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::with(['people','level'])->where($fieldType, $request->username_or_email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses tidak dikenal!',
            ], 401);
        }

        // Buat token
        $token = $user->createToken('API Token')->plainTextToken;
        $image = $user->people->documentId === null
            ? 'assets/img/default-bg/' . ($user->people->gender === "male" ? 'male.png' : 'female.png')
            : 'storage/' . $user->people->document->docsImageProfile;

        $userData = [
            'peopleId'  => $user->peopleId,
            'image'     => $image,
            'name'      => $user->people->fullName,
            'role'      => $user->level->role ?? null,
            'roleName'  => $user->level->name ?? null,
            '_psix'     => $token
            // 'hasParent' => $user->level->parentId,
        ];
        // dd($userData);
        // Buat cookie auth_token (simpan token di cookie)
        $authCookie = cookie('auth_token', $token, 60 * 24, '/', null, true, true, false, 'None');


        // return response()->json([
        //     'success' => true,
        //     'message' => 'Login successful',
        //     'token'   => $token,
        //     'data'    => $userData,
        // ])->withCookie($authCookie);

        // dd(response()->withCookie($authCookie)); // Debug
        // Kembalikan response dengan cookies dan data pengguna
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token'   => $token,
            'data'    => $userData,  // Data pengguna dikirim dalam response
        ], 200)->withCookie($authCookie);
    }



    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Hapus semua token
            $user->tokens()->delete();

            // Hapus cookies di server
            $authCookie = Cookie::forget('auth_token');
            $userCookie = Cookie::forget('user_data');

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
            ])->withCookie($authCookie)->withCookie($userCookie);
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

        // Auto login setelah registrasi
        $token = $user->createToken('API Token')->plainTextToken;

        $userData = json_encode([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => 'user',
        ]);

        $encryptedData = Crypt::encryptString($userData);

        // Buat cookie
        $authCookie = cookie('auth_token', $token, 60 * 24, '/', null, false, true, false, 'Lax');
        $userCookie = cookie('user_data', $encryptedData, 60 * 24, '/', null, false, false);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => $user,
            'token' => $token,
        ], 201)->withCookie($authCookie)->withCookie($userCookie);
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
