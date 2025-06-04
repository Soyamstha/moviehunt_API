<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
class apicontroller extends Controller
{
    function register(Request $request)
    {
      $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);
    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);
    // return response()->json([
    //     // 'message' => 'User created successfully',
    //     // 'user' => new UserResource($user),
    // ]);
    $user = new UserResource($user);
    return apiSuccessResponse($user,'User created successfully');
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
        return apiSuccessResponse($user,'Login successful');
    }
}
