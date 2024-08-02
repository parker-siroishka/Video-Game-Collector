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
        series: {
            type: Object,
            required: true,
        },
        title: {
            type: String,
        },
        xAxis: {
            type: Array,
        },
    },
    setup(props) {
        // TODO: fix console error here complaining about 'data' property not being passed
        const chartSeries = computed(() => {
            return props.series ? [props.series] : [{ name: "", data: [] }];
        });

        const chartOptions = computed(() => {
            return {
                chart: {
                    height: 350,
                    type: "line",
                    zoom: {
                        enabled: false,
                    },
                },
                colors: ["#1f2937"],
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: "smooth",
                },
                title: {
                    text: props.title,
                    align: "left",
                },
                grid: {
                    row: {
                        colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                        opacity: 0.5,
                    },
                },
                xaxis: {
                    categories: props.xAxis,
                },
            };
        });

        return {
            chartSeries,
            chartOptions,
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
