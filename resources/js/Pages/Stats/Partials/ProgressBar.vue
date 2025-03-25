<template>
    <div class="chart">
        <apexchart :options="chartOptions" :series="chartSeries"></apexchart>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import { computed, defineComponent } from "vue";

export default defineComponent({
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        selectedGame: {
            type: Object,
            required: true,
        },
        games: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const chartOptions = computed(() => ({
            chart: {
                height: 500,
                type: "radialBar",
            },
            colors: ["#F97316"],
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 15,
                        size: "58%",
                    },
                    dataLabels: {
                        value: {
                            offsetY: -8,
                            color: "#fff",
                            fontSize: "20px",
                            show: true,
                        },
                    },
                    track: {
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 0,
                            blur: 4,
                            opacity: 0.15,
                        },
                    },
                },
            },
            stroke: {
                lineCap: "round",
            },
            labels: [""],
        }));

        const chartSeries = computed(() => {
            // calculate individual game's completion
            if (props.selectedGame) {
                let percentage = Math.round(
                    (parseFloat(props.selectedGame.playtime) /
                        parseFloat(props.selectedGame.estimated_playtime)) *
                        100
                );
                return percentage < 100 ? [percentage] : [100];
            } else {
                // calculate total completion % given every game that has an estimated completion value
            }
        });

        return {
            chartOptions,
            chartSeries,
        };
    },
});
</script>

<style scoped>
.chart {
    padding: 0.5rem;
    flex: 1;
}
</style>
