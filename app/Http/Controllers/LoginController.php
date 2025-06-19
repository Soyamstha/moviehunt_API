<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

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
        DB::transaction(function () use ($request, &$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => 0,
            ]);
            $profile = $user->profile()->create([
                'user_id' => $user->id,
                'name' => $request->name,
                'is_kid' => 1,
            ]);
        });
        event(new Registered($user));
        return response()->json(['message' => 'Account have been Registered. Please verify your email.'], 201);
        // $user = new UserResource($user);
        // return apiSuccessResponse($user,'User created successfully');
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
            return apiErrorResponse('Invalid credentials', 401);
        }

        $user = auth()->user();
        $user = new UserResource($user);
        $token = $user->createToken('API Token')->plainTextToken;

        // return response()->json([
        //     'message' => 'Login successful',
        //     'user' => new UserResource($user),
        //     'token' => $token,
        // ]);
        return apiSuccessResponse($user, 'Login successful', $token);
    }
    function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return apiSuccessResponse(null, 'Logged out successfully');
    }
    function forget_password(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|string|email',
        // ]);
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);
        $user = DB::table('users')->where('email', $request->email)->first();
        $token = strtoupper(str()->random(8));

        DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $request->email
        ],[
            'created_at' => now(), 'token' => bcrypt($token)
        ]);

        Mail::send('auth.password-reset', ['token' => $token, 'user' => $user], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });
            return response()->json(['message' => 'Forget password Link has been send to your email']);

        // return $this->apiSuccess('a mail has been sent to your email');
        // $status = Password::sendResetLink($request->only('email'));
        // if ($status === Password::RESET_LINK_SENT) {
        //     return response()->json(['message' => 'Forget password Link has been send to your email']);
        // }
        // else
        // {
        //     return response()->json(['message' => 'Fail  to send link to your email']);
        // }
    }
    function reset_password(Request $request)
    {
        $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
        ]);
        // return $request->only('email', 'password', 'password_confirmation', 'token');
        $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) use($request) {
            $user->forceFill([
                'password' => $request->password,
                'remember_token' => str()->random(60),
            ])->save();

            // event(new PasswordReset($user));
        }
        );
        if ($status == Password::PASSWORD_RESET) {
             return response()->json(['message' => 'your password has been reset successfully']);
        }
        else
        {
            return response()->json(['message' => 'Fail  to reset your password']);
        }
    }
}
