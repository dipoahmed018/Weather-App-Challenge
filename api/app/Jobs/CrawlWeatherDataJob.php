<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\Pool;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CrawlWeatherDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::take(3)->get();

        $currentWeatherResponses = Http::retry(3)->pool(function (Pool $pool) use ($users) {
            return  $users->map(fn ($user) => $pool->as($user->id)->get('https://api.openweathermap.org/data/2.5/weather', ['lat' => $user->latitude, 'lon' => $user->longitude, 'units' => 'metric', 'appid' => env("OPEN_WEATHER_API_KEY")]));
        });

        $forecastWeatherResponses = Http::retry(3)->pool(function (Pool $pool) use ($users) {
            return  $users->map(fn ($user) => $pool->as($user->id)->get('https://api.openweathermap.org/data/2.5/forecast', ['lat' => $user->latitude, 'lon' => $user->longitude, 'units' => 'metric', 'appid' => env("OPEN_WEATHER_API_KEY")]));
        });

        $users->each(function ($user) use ($currentWeatherResponses, $forecastWeatherResponses) {
            if ($currentWeatherResponses[$user->id]->ok()) {
                Cache::put("CURRENT_WEATHER_USER_$user->id", $currentWeatherResponses[$user->id]->json(), now()->addHour());
            }

            if ($forecastWeatherResponses[$user->id]->ok()) {
                Cache::put("FORECAST_WEATHER_USER_$user->id", $forecastWeatherResponses[$user->id]->json(), now()->addHour());
            }
        });
    }
}
