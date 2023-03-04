<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCurrentWeatherResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserCurrentWeatherController extends Controller
{
    /**
     * Get current weather of all users
     *
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email')->get();
        return response()->json(UserCurrentWeatherResource::collection($users));
    }

    /**
     * Get current weather of a specific user
     *
     */
    public function show(User $user)
    {
        $currentWeather = Cache::remember("CURRENT_WEATHER_USER_$user->id", now()->addHour(), function () use ($user) {

            $response = Http::openWeather()
                ->retry(3)
                ->get('/weather', ['lat' => $user->latitude, 'lon' => $user->longitude, 'units' => 'metric']);

            if ($response->ok()) {
                return [
                    'weather_status'     => $response['weather'],
                    'temp_status'        => $response['main'],
                    'wind_status'        => $response['wind'],
                    'sys'                => $response['sys']
                ];
            } else {
                throw new NotFoundHttpException("Weather For current user is currently not availabel please try again after some times");
            }
        });

        return response($currentWeather, 200);
    }
}
