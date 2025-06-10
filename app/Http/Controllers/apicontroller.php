<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\GenreCollection;
use App\Http\Resources\MovieResource;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Profile;
use App\Models\Favorite;
use App\Models\MovieGenres;
use App\Models\Rating;
class ApiController extends Controller
{
    function register(Request $request)
    {
      $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    \DB::transaction(function () use($request) {
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
    function movies()
    {
        $movies = Movie::paginate(10);
        $movies = new MovieCollection($movies);
        return apiSuccessResponse($movies, 'Movies retrieved successfully');
    }
    function movieDetail($id)
    {
        $movie = Movie::with('genres')->find($id);
        // $movieGenres = MovieGenres::where('movie_id', $id)->get();
        // $genres = [];
        // foreach ($movieGenres as $movieGenre) {
        //     $genre = Genres::find($movieGenre->genre_id);
        //     if ($genre) {
        //         $genres[] = $genre->name;
        //     }
        // }
        // if (!$movie)
        // {
        //     return response()->json(['message' => 'Movie not found'], 404);
        // }
        $movie = new MovieResource($movie);
        return apiSuccessResponse($movie, 'Movie retrieved successfully');
    }
    function profile()
    {
        $user = auth()->user();
        $profile = $user->profile;
        // dd($profile);
        $profile = new ProfileResource($profile);
        return apiSuccessResponse($profile, 'Profile retrieved successfully');
    }
    function profile_edit($id, Request $request)
    {
        $user = auth()->user();
        $profile = Profile::find($id);
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        if(request('age') >= 18) {
            $profile->update([
                'name' => request('name'),
                'age' => request('age'),
                'is_kid' => false
            ]);
            $user->update([
                'name' => request('name'),
                'email'=>request('email'),
                'password' => bcrypt($request->password)
            ]);
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
    function view_favorite(Request $request)
    {
        $user = auth()->user();
        $favorites = Favorite::where('Profile_id', $user->id)->with('movie')->get();
        if ($favorites->isEmpty()) {
            return response()->json(['message' => 'No favorites found'], 404);
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
    function search_movies(Request $request)
    {
        $movieName = $request->input('name');
        $movieGenre = $request->input('genre');
        $query = Movie::query();
        $query = $query->where('title','like', "%".$movieName."%", 'or', 'genres', 'like', "%".$movieGenre."%");
        $movies = $query->paginate(10);
        if ($movies->isEmpty()) {
            return response()->json(['message' => 'No movies found'], 404);
        }
        $movies = new MovieCollection($movies);
        return apiSuccessResponse($movies, 'Movies retrieved successfully');
    }
    function movies_genre()
    {
        $genres = Genre::all();
        if ($genres->isEmpty()) {
            return response()->json(['message' => 'No genres found'], 404);
        }
        $genres = new GenreCollection($genres);
        return apiSuccessResponse($genres, 'Genres retrieved successfully');
    }
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
    function movies_rating()
    {
        $ratings = Rating::with('movie')->paginate(10);
        if ($ratings->isEmpty()) {
            return response()->json(['message' => 'No ratings found'], 404);
        }
        return apiSuccessResponse($ratings, 'Ratings retrieved successfully');
    }
}

