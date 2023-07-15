<?php
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




Route::apiResource('blogs', BlogController::class);
Route::post('blogs', [BlogController::class, 'store']);       // Add/Create a new blog
Route::patch('/blogs/{id}', 'BlogController@update')->name('blogs.update');

Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
