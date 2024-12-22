<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Validation\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|string|confirmed'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 403);
        }



        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 403);
        }
    }

    public function login(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 403);
        }

        $crediantials = ['email' => $request->email, 'password' => $request->password];
        try {
            if (!Auth::attempt($crediantials)) {


                return response()->json([
                    'error' => 'Invalid Credentials.'
                ], 403);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 403);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfuly logged out.'
        ]);
    }
}
