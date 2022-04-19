<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AlertController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::GET('/tweets', [TweetController::class, 'index']);
Route::POST('/tweets', [TweetController::class, 'store']);

Route::GET('/instagram', [InstagramController::class, 'index']);
Route::POST('/instagram', [InstagramController::class, 'store']);

Route::GET('/news', [NewsController::class, 'index']);
Route::POST('/news', [NewsController::class, 'store']);

Route::POST('/alerts', [AlertController::class, 'store']);
