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
        const chartSeries = computed(() => {
            return props.series ? [props.series] : [{ name: "", data: [] }];
        });

        const chartOptions = computed(() => {
            return {
                series: [{ data: [] }],
                chart: {
                    height: 350,
                    type: "line",
                    zoom: {
                        enabled: false,
                    },
                },
                colors: ["#f97316"],
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: "smooth",
                },
                title: {
                    text: props.title,
                    align: "left",
                    style: {
                        fontWeight: "bold",
                        fontFamily: undefined,
                        color: "white",
                    },
                },
                grid: {
                    row: {
                        colors: ["#f3f3f3", "#374151"],
                        opacity: 0.5,
                    },
                },
                xaxis: {
                    categories: props.xAxis,
                    labels: {
                        style: {
                            colors: "#ffffff",
                            fontSize: "12px",
                            fontFamily: "Helvetica, Arial, sans-serif",
                            fontWeight: 400,
                            cssClass: "apexcharts-xaxis-label",
                        },
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: "#ffffff",
                            fontSize: "12px",
                            fontFamily: "Helvetica, Arial, sans-serif",
                            fontWeight: 400,
                            cssClass: "apexcharts-xaxis-label",
                        },
                    },
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
