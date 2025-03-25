<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import getGames from "@/services/getGames";
import getWeeklyPlaytimeTotals from "@/services/getWeeklyPlaytimeTotals";
import LineChart from "./Partials/LineChart.vue";
import HeatMap from "./Partials/HeatMap.vue";
import getGamePlaySessions from "@/services/getGamePlaySessions";
import ProgressBar from "./Partials/ProgressBar.vue";

const weeklyTotals = ref({ data: [] });
const sessionCount = ref(0);
const sessions = ref([]);
const xAxisValues = ref([]);
const selectedGame = ref("");
const hasGames = ref(false);
const games = ref([]);

const fetchGames = async () => {
    games.value = await getGames();
    hasGames.value = games.value.length > 0;
};

const getWeeklyTotals = async (gameId) => {
    const { data } = await getWeeklyPlaytimeTotals(gameId);
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

const getSessions = async (gameid) => {
    let count = 0;
    sessions.value = await getGamePlaySessions(gameid);
    for (const date in sessions.value) {
        if (sessions.value.hasOwnProperty(date)) {
            count += sessions.value[date].length;
        }
    }
    sessionCount.value = count;
};

const onSelectChange = async () => {
    getWeeklyTotals(selectedGame.value.id);
    getSessions(selectedGame.value.id);
};

onMounted(() => {
    getWeeklyTotals(-1);
    getSessions(-1);
    fetchGames();
});
</script>

<template>
    <Head title="Statistics" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto mt-10 pb-20">
            <div class="pl-4 pr-4">
                <h1 class="text-2xl font-extrabold text-white mb-5">Stats</h1>
                <form class="flex flex-col space-y-4 mt-3 mb-10">
                    <div class="flex-1 sm:w-3/4 md:w-1/2">
                        <label
                            for="games"
                            class="block mb-2 text-sm font-medium text-white"
                            >Select a game</label
                        >
                        <select
                            v-model="selectedGame"
                            :disabled="!hasGames"
                            @change="onSelectChange()"
                            id="games"
                            class="disabled:bg-slate-200 border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white"
                        >
                            <option selected value="">All games</option>
                            <option v-for="game in games" :value="game">
                                {{ game.title }}
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-row justify-start md:justify-end">
                        <div class="flex mr-5">
                            <div
                                class="inline-block p-6 border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700"
                            >
                                <p
                                    class="text-lg font-bold text-gray-200 mb-3 items-center"
                                >
                                    Total Sessions
                                </p>
                                <h1
                                    class="mt-10 flex text-6xl font-extrabold text-orange-500 justify-center"
                                >
                                    {{ sessionCount }}
                                </h1>
                            </div>
                        </div>
                        <div class="flex-1 flex">
                            <div
                                v-if="selectedGame.estimated_playtime"
                                class="w-[165px] inline-block bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700"
                            >
                                <p
                                    class="text-center text-lg p-6 pb-0 sm:text-lg font-bold text-gray-200"
                                >
                                    Estimated Completion
                                </p>
                                <ProgressBar
                                    :selectedGame="selectedGame"
                                    :games="games"
                                />
                            </div>
                        </div>
                    </div>
                </form>
                <h2 class="text-4xl font-extrabold text-white mb-5">
                    {{ selectedGame.title ? selectedGame.title : "All Games" }}
                </h2>
            </div>
            <div
                class="pt-3 pb-4 rounded-lg bg-gradient-to-t bg-[#1e293b] border-2 border-gray-700 rounded-md"
            >
                <LineChart
                    :series="weeklyTotals"
                    :xAxis="xAxisValues"
                    title="Total Game Playtime (hrs)"
                />
                <HeatMap :series="sessions" title="Session Heatmap" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
