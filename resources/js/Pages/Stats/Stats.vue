<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import getGames from "@/services/getGames";
import getWeeklyPlaytimeTotals from "@/services/getWeeklyPlaytimeTotals";
import LineChart from "./Partials/LineChart.vue";
import getGamePlaySessions from "@/services/getGamePlaySessions";

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
        <div class="max-w-4xl mx-auto mt-10">
            <div class="pl-4 pr-4">
                <h1 class="text-2xl font-extrabold text-white mb-5">
                    Game Playtime
                </h1>
                <form class="flex items-center space-x-4 mt-3 mb-10">
                    <div class="flex-1">
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
                    <div class="flex-1 flex justify-end">
                        <div
                            class="inline-block p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 ml-auto"
                        >
                            <p
                                class="text-xs sm:text-lg font-bold text-gray-200 mb-3"
                            >
                                Total Sessions
                            </p>
                            <h1 class="text-6xl font-extrabold text-orange-500">
                                {{ sessionCount }}
                            </h1>
                        </div>
                    </div>
                </form>
                <h2 class="text-xl font-extrabold text-white mb-5">
                    {{ selectedGame.title ? selectedGame.title : "All Games" }}
                </h2>
            </div>
            <div
                class="pt-3 pb-4 px-4 mb-20 rounded-lg bg-gradient-to-t bg-[#1e293b] border-2 border-gray-700"
            >
                <LineChart
                    :series="weeklyTotals"
                    :xAxis="xAxisValues"
                    title="Total Game Playtime (hrs)"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
