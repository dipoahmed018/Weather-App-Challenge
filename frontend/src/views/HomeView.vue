<template>
  <div class="users-current-weather px-5 flex flex-col gap-4 mt-[40px]" v-if="status == Status.COMPLETE">
    <div class="titles grid grid-cols-6 justify-start items-center text-white px-2">
      <p class="col-span-2">Name</p>
      <WeaatherIcon icon_id="01d" />
      <div class="flex gap-1 items-center">
        <TemperatureIcon :temp="11" />
        <span class="text-white/50">°C</span>
      </div>
      <HumidityIcon />
      <div class="flex gap-1 items-center">
        <WindIcon />
        <span class="text-white/50">m/s</span>
      </div>
    </div>
    <WeatherRow v-for="(user, index) in data" :key="index" :user="user" />
  </div>
</template>
<script setup lang="ts">
import WeatherRow from "@/components/WeahterRow.vue";
import { onMounted } from "vue";
import { useWeather, Status } from "@/stores/weather";
import { storeToRefs } from "pinia";
import WeaatherIcon from "@/components/Icons/WeatherIcon.vue";
import TemperatureIcon from "@/components/Icons/TemperatureIcon.vue";
import WindIcon from "@/components/Icons/WindIcon.vue";
import HumidityIcon from "@/components/Icons/HumidityIcon.vue";

const currentWeatherStore = useWeather();
const { status, data, error } = storeToRefs(currentWeatherStore);

onMounted(() => {
  currentWeatherStore.fetchCurrentWeatherData()
});
</script>
