<?php

namespace App\Http\Controllers;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Movie;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    function view_favorite(Request $request)
    {
        $user = auth()->user();
        $favorites = Favorite::where('Profile_id', $user->id)->with('movie')->get();
        if ($favorites->isEmpty()) {
            return response()->json(['message' => 'No favorites found','status'=>'true','data'=>null], 404);
        }
        return apiSuccessResponse($favorites, 'Favorites retrieved successfully');
    }
    function add_favorite(Request $request,$id)
    {
        $user = auth()->user();
        $movie = Movie::find($id);
        $movieId = $id;
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }
        else{
            $favorite = Favorite::where('profile_id', $user->id)->where('movie_id', $movieId)->first();
            if ($favorite) {
                return response()->json(['message' => 'Movie already in favorites'], 400);
            }
            $favorite = new Favorite();
            $favorite->profile_id = $user->id;
            $favorite->movie_id = $movieId;
            $favorite->save();
        }
        return apiSuccessResponse(null, 'Movie added to favorites successfully');
    }
    function remove_favorite(Request $request,$id)
    {
        $user = auth()->user();
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }
        $favorite = Favorite::where('profile_id', $user->id)->where('movie_id', $id)->first();
        if (!$favorite) {
            return response()->json(['message' => 'Movie not in favorites'], 404);
        }
        $favorite->delete();
        return apiSuccessResponse(null, 'Movie removed from favorites successfully');

    }
}
