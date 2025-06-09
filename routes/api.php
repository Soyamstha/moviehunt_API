<?php
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::Post('/logout',[ApiController::class, 'logout']);
    Route::get('/movie-detail/{id}', [ApiController::class, 'movieDetail']);
    Route::get('/profile',[ApiController::class, 'profile']);
    Route::Post('/profile-edit/{id}',[ApiController::class, 'profile_edit']);
    Route::delete('/profile-delete',[ApiController::class, 'profile_delete']);
    Route::get('/view-favorite',[ApiController::class, 'view_favorite']);
    Route::Post('/add-favorite/{id}',[ApiController::class, 'add_favorite']);
    Route::delete('/remove-favorite/{id}',[ApiController::class, 'remove_favorite']);
    Route::Post('add-user-rating/{id}',[ApiController::class,'add_user_rating']);
});
Route::get('/movies',[ApiController::class, 'movies']);
Route::post('/search-movies',[ApiController::class, 'search_movies']);
Route::get('/movies-genre', [ApiController::class, 'movies_genre']);
