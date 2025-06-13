<?php
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AdminController;
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
    Route::Post('/add-movie',[AdminController::class, 'add_movie']);
    Route::post('/add-genre',[AdminController::class, 'add_genre']);
    Route::Post('/update-movie/{id}', [AdminController::class, 'update_movie']);
    Route::delete('/delete-movie/{id}', [AdminController::class, 'delete_movie']);
    Route::Post('/user-admin-access/{id}', [AdminController::class, 'user_admin_access']);
});
Route::get('/movies',[MovieController::class, 'movies']);
Route::get('/movie-detail/{id}', [MovieController::class, 'movieDetail']);
Route::Post('/search-movies',[MovieController::class, 'search_movies']);
Route::get('/movies-genre', [MovieController::class, 'movies_genre']);
Route::get('/users-rating/{id}', [RatingController::class, 'movies_rating']);
