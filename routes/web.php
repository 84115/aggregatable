<?php

use App\Http\Controllers\ArticlesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ArticlesController::class, 'index']);

Route::get('learn', [ArticlesController::class, 'index']);
Route::get('learn/{article}', [ArticlesController::class, 'show']);

Route::get('animals', [ArticlesController::class, 'index']);
Route::get('animals/{article}', [ArticlesController::class, 'show']);

Route::get('zodiacs', [ArticlesController::class, 'index']);
Route::get('zodiacs/{article}', [ArticlesController::class, 'show']);

Route::get('ping', function() {
    return 'pong';
});
