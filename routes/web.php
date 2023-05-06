<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\TracksController;
use App\Http\Middleware\SpotifyAuthenticated;
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

Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('/', SiteController::class)->name('home');

    Route::middleware(SpotifyAuthenticated::class)->group(function () {
        Route::get('/song', SongController::class)->name('song');
        Route::get('/tracks', TracksController::class)->name('tracks');
    });

    Route::controller(SpotifyController::class)->prefix('spotify')->name('spotify.')->group(function () {
        Route::get('authorize', 'handleAuthorization')->name('authorize');
        Route::get('callback', 'handleCallback')->name('callback');
    });
});

require __DIR__.'/auth.php';
