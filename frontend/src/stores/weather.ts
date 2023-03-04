import { ref } from "vue";
import { defineStore } from "pinia";
import axios from "axios";
import type { Ref } from "vue";

export enum Status {
    ERROR,
    LOADING,
    COMPLETE,
}

export interface Forecast {
    dt: number,
    main: any,
    weather: Array<any>,
    clouds: any,
    wind: any,
    visibility: number,
    pop: number,
    sys: any,
    dt_txt: string,
};

export interface User {
    id: number;
    name: string,
    current_weather: any,
    email: string,
    forecast_weather?: Forecast[],
}

export const useWeather = defineStore("currentWeather", () => {
    const status = ref(Status.LOADING);
    const data: Ref<User[]> = ref([]);
    const error = ref();

    const fetchCurrentWeatherData = async () => {
        try {
            status.value = Status.LOADING;
            const res = await axios.get("/current-weather");
            status.value = Status.COMPLETE;
            data.value = res.data;
        } catch (error) {
            console.log(error)
        }
    }

    const fetchUserCurrentWeatherData = async (user_id: number) => {
        try {
            const res = await axios.get(`/current-weather/${user_id}`);
            const user = data.value.find(({ id }) => id == user_id);
            if (user) user.current_weather = res.data;
        } catch (error) {
            console.log(error)
        }
    };

    const fetchUserForecastWeatherData = async (user_id: number) => {
        try {
            const res = await axios.get(`/forecast-weather/${user_id}`);
            const user = data.value.find(({ id }) => id == user_id);
            if (user) user.forecast_weather = res.data;
            return res.data;
        } catch (error) {
            console.log(error)
        }
    };

    return { status, data, error, fetchCurrentWeatherData, fetchUserCurrentWeatherData, fetchUserForecastWeatherData };
});
