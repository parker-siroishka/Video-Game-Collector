<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import getWeeklyPlaytimeTotals from "@/services/getWeeklyPlaytimeTotals";
import LineChart from "./Partials/LineChart.vue";

const weeklyTotals = ref([]);
const xAxisValues = ref([]);
const selectedGame = ref("");

const getWeeklyTotals = async (gameId) => {
    const { data } = getWeeklyPlaytimeTotals(gameId);
    const sortedArrayOfWeeklyTotals = Object.entries(data).sort(
        (a, b) => new Date(a[0]) - new Date(b[0])
    );
    const sortedObj = Object.fromEntries(sortedArrayOfWeeklyTotals);
    weeklyTotals.value = {
        name: "Total Playtime (hrs)",
        data: Object.values(sortedObj),
    };
    xAxisValues.value = Object.keys(sortedObj);
};

onMounted(() => getWeeklyTotals(-1));
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto mt-10">
            <div class="pl-4 pr-4">
                <h1 class="text-2xl font-extrabold text-gray-900">
                    Total Game Playtime
                </h1>
                <form class="max-w-sm mt-3">
                    <label
                        for="games"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >Select a game</label
                    >
                    <select
                        v-model="selectedGame"
                        :disabled="!hasGames || showingAddNewGameForm"
                        id="games"
                        class="disabled:bg-slate-200 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected value="">Select a game</option>
                        <option v-for="game in games" :value="game">
                            {{ game.title }}
                        </option>
                    </select>
                </form>
            </div>

            <LineChart
                :series="weeklyTotals"
                :xAxis="xAxisValues"
                title="Total Game Playtime (hrs)"
            />
        </div>
    </AuthenticatedLayout>
</template>
