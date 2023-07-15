<?php
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::post('blogs', [BlogController::class, 'store']);  
Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
Route::patch('/blogs/{id}', 'BlogController@update')->name('blogs.update');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/sample', [App\Http\Controllers\SampleController::class, 'index'])->name('sample');