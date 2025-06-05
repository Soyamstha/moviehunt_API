<?php
use App\Http\Controllers\apicontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [apicontroller::class, 'register']);
Route::post('/login', [apicontroller::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::Post('/logout',[apicontroller::class, 'logout']);
    Route::get('/movies',[apicontroller::class, 'movies']);
    Route::get('/movie-detail/{id}', [apicontroller::class, 'movieDetail']);
});
