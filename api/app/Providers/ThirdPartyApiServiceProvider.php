<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class ThirdPartyApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        Http::macro('openWeather', function () {
            return  Http::baseUrl('https://api.openweathermap.org/data/2.5')->withOptions(['query' => ['appid' => env('OPEN_WEATHER_API_KEY')]])->acceptJson();
        });
    }
}
