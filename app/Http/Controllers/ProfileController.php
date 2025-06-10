<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Models\Profile;

class ProfileController extends Controller
{
    function profile()
    {
        $user = auth()->user();
        $profile = $user->profile;
        // dd($profile);
        $profile = new ProfileResource($profile);
        return apiSuccessResponse($profile, 'Profile retrieved successfully');
    }
    function profile_edit( Request $request)
    {
        $user = auth()->user();
        $profile = Profile::find($user->id);
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        if(request('age') >= 18) {
            $profile->update([
                'name' => request('name'),
                'age' => request('age'),
                'is_kid' => false
            ]);
            if(request('email') ==null)
            {
                $user->update([
                'name' => request('name'),
                'email'=>$user->email,
                'password' =>$user->password
            ]);
            }
            else{
                $user->update([
                    'name' => request('name'),
                    'email'=>request('email'),
                    'password' => bcrypt($request->password)
                ]);
            }
            }
            else {
                $profile->update([
                    'name' => request('name'),
                    'age' => request('age'),
                    'is_kid' => true
                ]);
                $user->update([
                'name' => request('name'),
                'email'=>request('email'),
                'password' => bcrypt($request->password)
            ]);
            }
        return apiSuccessResponse($profile, 'Profile updated successfully');
    }
    function profile_delete(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        $profile->delete();
        $user->delete();
        $user->tokens()->delete();
        return apiSuccessResponse(null, 'Profile deleted successfully');
    }
}
