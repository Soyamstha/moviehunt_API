<?php

namespace App\Http\Controllers;
use App\Http\Resources\MovieCollection;
use App\Http\Resources\GenreCollection;
use App\Http\Resources\MovieResource;
use App\Http\Resources\PaginationCollection;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\MovieGenres;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    function movies()
    {
        $pagination =Movie::paginate(8);
        return apiSuccessResponse($pagination, 'Movies retrieved successfully');

        // $movie_pagination = Movie::paginate(6);
        // $items = new MovieCollection($movie_pagination->items());
        // $total = $movie_pagination->currentPage();
        // $data = compact('items','total');
        // return apiSuccessResponse($data, 'Movies retrieved successfully');
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
        $genres = $request->input('gen');

        // dd($request->all());{}
        if ($movieName == null && $genres == null){
            return response()->json(['message' => 'there is no movie name and genre'], 404);

        }

        $query = Movie::query();
        if($movieName!=null)
        {
            $query = $query->where('title','like', "%".$movieName."%");
        }
        if($genres!=null)
        {
                $query = $query->whereHas('genres',function ($q) use ($genres) {
                    $q->whereIn('genres.id', $genres);
                });
        }

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
