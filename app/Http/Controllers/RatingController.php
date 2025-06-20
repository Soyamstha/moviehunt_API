<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserRatingCollection;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    function add_user_rating(Request $request,$id)
    {
        $user = auth()->user();
        $rating = new Rating();
        $rating->profile_id = $user->id;
        $rating->movie_id = $id;
        if($request->input('rating') < 1 || $request->input('rating') > 5)
        {
            return response()->json(['message' => 'Rating must be between 1 and 5'], 400);
        }
        $rating->rating = $request->input('rating');
        $rating->review = $request->input('review');
        $rating->save();
        return apiSuccessResponse(null, 'Rating added successfully');
    }
    function movies_rating($id)
    {
        $user = auth()->user();
        $ratings = Rating::with('profile')->where('movie_id', $id)->get();
        if ($ratings->isEmpty())
        {
            return response()->json(['message' => 'No ratings found for this movie'], 404);
        }
        $ratings= new UserRatingCollection($ratings);
        return apiSuccessResponse($ratings, 'Ratings retrieved successfully');
    }
}
