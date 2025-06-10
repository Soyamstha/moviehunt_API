<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Models\Profile;

class LoginController extends Controller
{
    function register(Request $request)
    {
      $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);
    $user = null;
    \DB::transaction(function () use($request, &$user) {
            $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $profile = $user->profile()->create([
            'user_id' => $user->id,
            'name' => $request->name,
            'is_kid' => 1
        ]);

    });
    $user = new UserResource($user);
    return apiSuccessResponse($user,'User created successfully');
    // return response()->json([
    //     // 'message' => 'User created successfully',
    //     // 'user' => new UserResource($user),
    // ]);

    }
    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();
        $user = new UserResource($user);
        $token = $user->createToken('API Token')->plainTextToken;

        // return response()->json([
        //     'message' => 'Login successful',
        //     'user' => new UserResource($user),
        //     'token' => $token,
        // ]);
        return apiSuccessResponse($user,'Login successful', $token);
    }
    function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return apiSuccessResponse(null, 'Logged out successfully');
    }
}
