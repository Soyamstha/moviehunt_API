<?php
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RatingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::Post('/logout',[LoginController::class, 'logout']);
    Route::get('/profile',[ProfileController::class, 'profile']);
    Route::Post('/profile-edit',[ProfileController::class, 'profile_edit']);
    Route::delete('/profile-delete',[ProfileController::class, 'profile_delete']);
    Route::get('/view-favorite',[FavoriteController::class, 'view_favorite']);
    Route::Post('/add-favorite/{id}',[FavoriteController::class, 'add_favorite']);
    Route::delete('/remove-favorite/{id}',[FavoriteController::class, 'remove_favorite']);
    Route::Post('add-user-rating/{id}',[RatingController::class,'add_user_rating']);
});
Route::get('/movies',[MovieController::class, 'movies']);
Route::get('/movie-detail/{id}', [MovieController::class, 'movieDetail']);
Route::post('/search-movies',[MovieController::class, 'search_movies']);
Route::get('/movies-genre', [MovieController::class, 'movies_genre']);
Route::get('/movies-rating', [RatingController::class, 'movies_rating']);
