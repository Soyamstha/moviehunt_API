<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use App\Models\Genre;
use App\Models\MovieGenres;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function add_movie(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'release_date' => 'required|date',
            'duration' => 'required|integer|min:100',
            'rating' => 'required|String|min:0|max:10',
            'language' => 'required|string|max:255',
            'thumbnail_url' => 'required',
            'trailer_url' => 'required|url',
            'video_url' => 'required|url',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id'
        ]);
        $thumbnail_url = $request->thumbnail_url;

        $trailer_url = $request->trailer_url;
        $video_url = $request->video_url;
        unset($validated['genres']);
        $movie = Movie::create($validated);
        $movie->addMediaFromUrl($thumbnail_url)->toMediaCollection('preview');

        // $movie->addMediaFromUrl($trailer_url)->toMediaCollection();
        // $movie->addMediaFromUrl($video_url)->toMediaCollection();
        $movie->genres()->attach($request->input('genres'));
        // $moviegenres = [];
        // foreach ($request->input('genres') as $genreId) {
        //     $moviegenres[] = [
        //         'movie_id' => $movie->id,
        //         'genre_id' => $genreId
        //     ];
        //     MovieGenres::insert($moviegenres);
        // }
        return response()->json(['message' => 'Movie added successfully', 'movie' => $movie], 201);
    }
    function add_genre(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name'
        ]);
        $genre = Genre::create($validated);
        return response()->json(['message' => 'Genre added successfully', 'genre' => $genre], 201);
    }
    function update_movie(Request $request, $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'release_date' => 'required|date',
            'duration' => 'required|integer|min:100',
            'rating' => 'required|String|min:0|max:10',
            'language' => 'required|string|max:255',
            'thumbnail_url' => 'required|url',
            'trailer_url' => 'required|url',
            'video_url' => 'required|url',
            'genres' => 'required|array',
            'genres.*' => 'exists:genres,id'
        ]);
        unset($validated['genres']);
        $movie->update($validated);
        $movie->genres()->sync($request->input('genres'));
        return response()->json(['message' => 'Movie updated successfully', 'movie' => $movie], 200);
    }
    function delete_movie($id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }
        $movie->genres()->detach();
        $movie->delete();
        return response()->json(['message' => 'Movie deleted successfully'], 200);
    }
    function user_admin_access($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->is_admin = true;
        $user->save();
    }
}
