<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WebSiteController;
use Illuminate\Support\Facades\Route;

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

Route::post('/register-subscribe', [UserController::class, 'registerAndSubscribe']);
Route::post('/login', [LoginController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Websites
|--------------------------------------------------------------------------
*/
Route::prefix('websites')->group(function () {
    Route::get('/', [WebSiteController::class, 'index']);
    Route::post('/', [WebSiteController::class, 'store']);
    Route::get('{website}/get-posts', [PostController::class, 'show']);
    Route::post('{website}/posts', [PostController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Subscribers
|--------------------------------------------------------------------------
*/
Route::prefix('subscriber')->group(function () {
    Route::post('/', [SubscriberController::class, 'store']);
    Route::get('{subscriber_id}/get-websites', [SubscriberController::class, 'show']);
});
