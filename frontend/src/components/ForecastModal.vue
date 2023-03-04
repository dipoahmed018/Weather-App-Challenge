<template>
    <div class="fixed top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] z-10 bg-gradient-to-t to-foreground2 from-foregorund1 px-4 py-6 text-white min-w-[700px] drop-shadow-xl"
        v-click-away="() =>$emit('update:modelValue', false)">
        <h1 class="text-xl text-center">{{ user.name }}</h1>
        <h3 class="text-lg text-center text-white/50">{{ dayjs().format('dddd, MMM YY') }}</h3>

        <div class="flex mt-4 justify-between gap-2">
            <div class="flex bg-keppel/50 rounded-sm drop-shadow-lg gap-2 p-2 text-white/70">
                <WeatherIcon :icon_id="current_weather.weather_icon" :large="true" />
                <div class="grid grid-cols-[35px,_80px]">
                    <TemperatureIcon :temp="current_weather.temperature" />
                    <p>{{ current_weather.temperature }} °C</p>

                    <WindIcon />
                    <p>{{ current_weather.wind_speed }} m/s</p>

                    <HumidityIcon />
                    <p>{{ current_weather.humidity }} %</p>
                </div>
            </div>
            <div class="flex flex-col bg-keppel/50 rounded-sm drop-shadow-lg gap-2 p-2 text-white/70 w-full text-sm">
                <div class="flex">
                    <p class="w-[140px]">Min:</p>
                    <p>{{ current_weather.min_temp }} °C</p>
                </div>
                <div class="flex">
                    <p class="w-[140px]">Max:</p>
                    <p>{{ current_weather.max_temp }} °C</p>
                </div>

                <div class="flex">
                    <p class="w-[140px]">Pressure:</p>
                    <p>{{ current_weather.pressure }}</p>
                </div>

                <div class="flex">
                    <p class="w-[140px]">Sea Level:</p>
                    <p>{{ current_weather.sea_level }}</p>
                </div>

                <template v-for="(value, key) in current_weather.sys" :key="key">
                    <div class="flex">
                        <p class="w-[140px] capitalize">{{ key }}:</p>
                        <p>{{ typeof value == 'number' ? dayjs(value).format("HH:mm") : value }}</p>
                    </div>
                </template>
            </div>
        </div>
        <div class="flex gap-2">
            <div class="flex flex-col w-[20%] bg-keppel/50 rounded-sm drop-shadow-lg gap-2 p-2 text-white/70 mt-5"
                v-for="(daily_forecast, index) in forecast" :key="index">

                <div class="self-center">
                    <WeatherIcon :icon_id="daily_forecast.weather[0].icon" />
                </div>

                <p class="text-center">{{ dayjs(daily_forecast.dt_txt).format('dddd') }}</p>
                <div class="flex">
                    <TemperatureIcon class="w-[30px]" :temp="daily_forecast.main.temp" />
                    <p>{{ daily_forecast.main.temp }} °C</p>
                </div>
                <div class="flex">
                    <WindIcon class="w-[30px]" />
                    <p>{{ daily_forecast.wind.speed }} m/s</p>
                </div>
                <div class="flex">
                    <HumidityIcon class="w-[30px]" />
                    <p>{{ daily_forecast.main.humidity }} %</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import dayjs from 'dayjs'
import type { User } from '@/stores/weather';
import WeatherIcon from './Icons/WeatherIcon.vue';
import TemperatureIcon from './Icons/TemperatureIcon.vue';
import WindIcon from './Icons/WindIcon.vue';
import HumidityIcon from './Icons/HumidityIcon.vue';

const props = defineProps<{
    user: User,
    current_weather: any,
}>();

const forecast = computed(() => {
    const temp_days: string[] = [];
    return props.user.forecast_weather?.filter((weather) => {
        const does_not_exist = !temp_days.some((date) => dayjs(date).isSame(dayjs(weather.dt_txt), "day"));
        if (does_not_exist) temp_days.push(weather.dt_txt);
        return does_not_exist;
    }).slice(1);
});

</script>