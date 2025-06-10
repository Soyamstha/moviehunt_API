<?php

namespace App\Http\Controllers;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\GenreCollection;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\MovieGenres;
use Illuminate\Http\Request;

class MovieController extends Controller
{
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
    function search_movies(Request $request)
    {
        $movieName = $request->input('name');
        $movieGenre = $request->input('genre');
        $query = Movie::query();
        $query = $query->where('title','like', "%".$movieName."%");
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
}
