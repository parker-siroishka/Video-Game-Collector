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
        function countSessionsPerDay(data) {
            const sessionCounts = {};
            Object.keys(data).forEach((date) => {
                sessionCounts[date] = data[date].length;
            });
            return sessionCounts;
        }

        const chartSeries = computed(() => {
            const sessionCounts = countSessionsPerDay(props.series);
            const months = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];
            const today = new Date();
            const currentMonth = today.getMonth();

            // Create array of 12 months starting from current month and going backwards
            const orderedMonths = [];
            for (let i = 0; i < 12; i++) {
                const monthIndex = (currentMonth - i + 12) % 12;
                orderedMonths.push(months[monthIndex]);
            }

            return orderedMonths
                .map((month) => {
                    const monthIndex = months.indexOf(month);
                    const isBeforeCurrentMonth = monthIndex > currentMonth;

                    return {
                        name: month,
                        data: Array(31)
                            .fill(0)
                            .map((_, day) => {
                                const date = new Date(
                                    today.getFullYear() -
                                        (isBeforeCurrentMonth ? 1 : 0),
                                    monthIndex,
                                    day + 1
                                );

                                // Check if date is within the last 365 days
                                const oneYearAgo = new Date(today);
                                oneYearAgo.setFullYear(today.getFullYear() - 1);

                                if (date >= oneYearAgo && date <= today) {
                                    const dateString = date
                                        .toISOString()
                                        .split("T")[0];
                                    return {
                                        x: day + 1,
                                        y: sessionCounts[dateString] || 0,
                                    };
                                }
                                return {
                                    x: day + 1,
                                    y: null,
                                };
                            }),
                    };
                })
                .reverse(); // Reverse to make current month appear at the top
        });

        const chartOptions = computed(() => ({
            stroke: {
                show: true,
                width: 0.5,
                colors: ["#f3f3f3"],
            },
            responsive: [
                {
                    breakpoint: 700,
                    options: {
                        chart: {
                            height: 700,
                        },
                    },
                },
            ],
            legend: {
                labels: {
                    colors: "#ffffff", // This sets the legend text color to white
                },
                horizontalAlign: "left",
            },
            chart: {
                height: 350,
                type: "heatmap",
                background: "transparent",
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false,
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
            xaxis: {
                type: "category",
                categories: Array.from({ length: 31 }, (_, i) => i + 1),
                labels: {
                    show: true,
                    rotate: 0,
                    style: {
                        colors: Array(31).fill("#fff"),
                        fontSize: "12px",
                        fontWeight: 900,
                        fontFamily: "Helvetica, Arial, sans-serif",
                    },
                },
                axisTicks: {
                    show: true,
                    color: "#78909c",
                },
                axisBorder: {
                    show: true,
                    color: "#78909c",
                },
            },
            yaxis: {
                type: "category",
                labels: {
                    style: {
                        colors: Array(12).fill("#fff"),
                        fontSize: "12px",
                        fontWeight: 900,
                        fontFamily: "Helvetica, Arial, sans-serif",
                    },
                    formatter: function (value) {
                        return value;
                    },
                },
            },
            plotOptions: {
                heatmap: {
                    radius: 2,
                    enableShades: false,
                    colorScale: {
                        ranges: [
                            {
                                from: null,
                                to: null,
                                name: "No Sessions",
                                color: "#111827",
                            },
                            {
                                from: 1,
                                to: 1,
                                name: "1",
                                color: "#5e2e66",
                            },
                            {
                                from: 2,
                                to: 2,
                                name: "2",
                                color: "#ca2f63",
                            },
                            {
                                from: 3,
                                to: 100,
                                name: "3+",
                                color: "#ff7700",
                            },
                        ],
                    },
                },
            },
        }));
        return {
            chartSeries,
            chartOptions,
        };
    },
});
</script>

<style scoped>
.chart {
    margin-top: 60px;
    padding-left: 0.1rem;
    flex: 1;
}
</style>
