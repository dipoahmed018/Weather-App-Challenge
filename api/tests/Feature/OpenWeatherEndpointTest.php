<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class OpenWeatherEndpointTest extends TestCase
{
    use WithFaker;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_open_weather_current_api()
    {
        $response = Http::openWeather()->get('/weather', ['lat' => $this->faker->latitude, 'lon' => $this->faker->longitude, 'units' => 'metric']);
        $this->assertTrue($response->ok() == true);
    }

    public function test_open_weather_forcast_hourly()
    {
        $response = Http::openWeather()->get('/forecast', ['lat' => $this->faker->latitude, 'lon' => $this->faker->longitude, 'units' => 'metric']);
        $this->assertTrue($response->ok() == true);
    }
}
