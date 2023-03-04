<?php

use App\Http\Controllers\UserCurrentWeatherController;
use App\Http\Controllers\UserForecastWeatherController;
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

Route::get('/', fn() => "Welcom to the jungle");

Route::get('/current-weather', [UserCurrentWeatherController::class, 'index']);
Route::get('/current-weather/{user}', [UserCurrentWeatherController::class, 'show']);
Route::get('/forecast-weather/{user}', UserForecastWeatherController::class);
