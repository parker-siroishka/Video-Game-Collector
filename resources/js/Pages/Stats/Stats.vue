<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import LineChart from "./Partials/LineChart.vue";

const weeklyTotals = ref([]);
const xAxisValues = ref([]);

const getWeeklyTotals = async () => {
    const { data } = await axios.get(route("playSessionsWeeklyTotals.get"));
    weeklyTotals.value = {
        name: "Total Playtime (hrs)",
        data: Object.values(data),
    };
    console.log(weeklyTotals.value);
    xAxisValues.value = Object.keys(data);
};

onMounted(() => getWeeklyTotals());
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <LineChart
            :series="weeklyTotals"
            :xAxis="xAxisValues"
            title="Total Game Playtime (hrs)"
        />
    </AuthenticatedLayout>
</template>
