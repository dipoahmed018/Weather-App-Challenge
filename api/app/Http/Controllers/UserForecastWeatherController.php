<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserForecastWeatherController extends Controller
{
    public function __invoke(User $user)
    {
        $forecastWeather = Cache::remember("FORECAST_WEATHER_USER_$user->id", now()->addHour(), function () use ($user) {

            $response = Http::openWeather()
                ->retry(3)
                ->get('/forecast', ['lat' => $user->latitude, 'lon' => $user->longitude, 'units' => 'metric']);

            if ($response->ok()) {
                return $response['list'];
            } else {
                throw new NotFoundHttpException("Weather For current user is currently not availabel please try again after some times");
            }
        });

        return response($forecastWeather, 200);
    }
}
