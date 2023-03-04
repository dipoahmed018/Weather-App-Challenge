<template>
    <div class="grid grid-cols-6 justify-start bg-gradient-to-r from-foregorund1 to-foreground2 rounded-md p-2 shadow-md h-[50px] items-center cursor-pointer"
        @click="openDetailsModal">
        <div class="text-white col-span-2">{{ user.name }}</div>
        <div class="">
            <WeaatherIcon :icon_id="current_weather.weather_icon" />
        </div>
        <div class="text-white flex items-center">
            <p class="w-[40%]">{{ current_weather.temperature }}</p>
        </div>
        <div class="text-white flex">
            <p class="w-[40%]">{{ current_weather.humidity }} %</p>
        </div>
        <div class="text-white flex items-center">
            <p class="w-[40%]">{{ current_weather.wind_speed }}</p>
        </div>
    </div>
    <Teleport to="#app">
        <ForecastModal v-if="showForecastModal" :user="user" :current_weather="current_weather"
            v-model="showForecastModal" />
    </Teleport>
</template>

<script setup lang="ts">
import { useWeather, type User } from "@/stores/weather";
import { onMounted, computed, ref } from 'vue'
import WeaatherIcon from "@/components/Icons/WeatherIcon.vue";
import ForecastModal from "./ForecastModal.vue";

const showForecastModal = ref(false)
const weatherStore = useWeather();
const props = defineProps<{
    user: User;
}>();

const current_weather = computed(() => {
    return {
        weather_icon: props.user.current_weather?.weather_status[0]?.icon,
        temperature: props.user.current_weather?.temp_status.temp,
        max_temp: props.user.current_weather?.temp_status.temp_max,
        min_temp: props.user.current_weather?.temp_status.temp_min,
        pressure: props.user.current_weather?.temp_status.pressure,
        sea_level: props.user.current_weather?.temp_status.sea_level,
        humidity: props.user.current_weather?.temp_status.humidity,
        wind_speed: props.user.current_weather?.wind_status.speed,
        sys: props.user.current_weather?.sys,
    }
})

const openDetailsModal = () => {
    if (!props.user.forecast_weather) {
        weatherStore
            .fetchUserForecastWeatherData(props.user.id)
            .then(() => (showForecastModal.value = true));
    } else {
        showForecastModal.value = true;
    }
}


onMounted(() => {
    if (!props.user.current_weather)
        weatherStore.fetchUserCurrentWeatherData(props.user.id);
});

</script>