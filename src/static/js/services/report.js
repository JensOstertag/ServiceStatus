import { t } from "../Translator.js";

export const initIncidents = () => {
    document.querySelectorAll(".incident-tooltip").forEach((element, index) => {
        tippy(element, {
            allowHTML: true,
            content: element.querySelector(".tooltip").innerHTML,
            placement: "bottom",
            theme: "tailwind"
        });
    });
}

export const initUptime = async (uptime) => {
    const [ onlineTranslation, offlineTranslation, uptimeTranslation ] = await Promise.all([ t("Online"), t("Offline"), t("Uptime") ]);

    // Register a plugin that places a text in the center of the doughnut chart
    Chart.register({
        id: 'textInside',
        afterDatasetsDraw: function (chart, args, options) {
            let fontSizeSums = 0;
            for(let i = 0; i < options.texts().length; i++) {
                let text = options.texts()[i];
                fontSizeSums += text.fontSize;
            }

            const ctx = chart.ctx;
            const x = chart.getDatasetMeta(0).data[0].x;
            let y = chart.getDatasetMeta(0).data[0].y - fontSizeSums / 2;

            const previousFont = ctx.font;
            const previousFillStyle = ctx.fillStyle;
            const previousTextAlign = ctx.textAlign;
            const previousTextBaseline = ctx.textBaseline;

            for(let i = 0; i < options.texts().length; i++) {
                let text = options.texts()[i];

                y += text.fontSize / 2;

                ctx.font = text.fontSize + 'px ' + text.fontFamily;
                ctx.fillStyle = text.color;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                ctx.fillText(text.text, x, y);

                y += text.fontSize / 2;
            }

            ctx.font = previousFont;
            ctx.fillStyle = previousFillStyle;
            ctx.textAlign = previousTextAlign;
            ctx.textBaseline = previousTextBaseline;
        },
        defaults: {
            texts: () => []
        }
    });

    const ctx = document.getElementById("uptimeChart").getContext("2d");
    const uptimeChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: [ onlineTranslation, offlineTranslation ],
            datasets: [{
                label: uptimeTranslation,
                data: [ uptime, 1 - uptime ],
                backgroundColor: [
                    "hsl(130, 45%, 60%)",
                    "hsl(6, 89%, 60%)"
                ],
                borderWidth: 0,
                hoverOffset: 4,
                rotation: 180 * (1 - uptime)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: 80,
            plugins: {
                tooltip: {
                    enabled: false
                },
                legend: {
                    display: true,
                    position: "right",
                    labels: {
                        generateLabels: (chart) => {
                            const datasets = chart.data.datasets;
                            return datasets[0].data.map((data, i) => ({
                                text: `${chart.data.labels[i]}\r\n${Math.round(data * 1000) / 10}%`, // Place the percent value in the legend
                                fillStyle: datasets[0].backgroundColor[i],
                                strokeStyle: "transparent",
                                fontColor: "hsl(0, 0%, 97%)",
                                lineWidth: 0,
                                index: i
                            }));
                        },
                        font: {
                            family: "Outfit"
                        }
                    },
                    onClick: null
                },
                textInside: {
                    texts: () => [
                        {
                            text: Math.round(uptime * 1000) / 10 + "%",
                            fontSize: 22,
                            fontFamily: "Outfit",
                            color: "hsl(130, 45%, 60%)"
                        },
                        {
                            text: uptimeTranslation,
                            fontSize: 14,
                            fontFamily: "Outfit",
                            color: "hsl(0, 0%, 97%)"
                        }
                    ]
                }
            },
            hover: {
                mode: null
            }
        }
    });
}

export const initHttp = () => {
    document.querySelectorAll(".http-tooltip").forEach((element, index) => {
        tippy(element, {
            allowHTML: true,
            content: element.querySelector(".tooltip").innerHTML,
            placement: "bottom",
            theme: "tailwind"
        });
    });
}

export const initPing = (data, start, end) => {
    let chartLabels = [];
    let chartData = [];

    data.forEach((point, index) => {
        chartLabels.push(new Date(point.timestamp));
        chartData.push(point.responseTime);
    });

    const getGradient = (ctx, chartArea) => {
        const chartWidth = chartArea.right - chartArea.left;
        const chartHeight = chartArea.bottom - chartArea.top;

        let gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
        gradient.addColorStop(0, "hsl(130, 45%, 60%)");
        gradient.addColorStop(0.5, "hsl(44, 89%, 60%)");
        gradient.addColorStop(1, "hsl(6, 89%, 60%)");

        return gradient;
    }

    const ctx = document.getElementById("pingReportChart").getContext("2d");
    const pingChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: chartLabels,
            datasets: [{
                data: chartData,
                borderColor: (context) => {
                    const chart = context.chart;
                    const { ctx, chartArea } = chart;

                    if(!chartArea) {
                        // This case happens on initial chart load
                        return null;
                    }
                    return getGradient(ctx, chartArea);
                },
                tension: 0.1,
                pointRadius: 0
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: false
                    },
                    ticks: {
                        color: "hsl(0, 0%, 60%)"
                    },
                    grid: {
                        color: "hsl(0, 0%, 30%)"
                    },
                    type: "time",
                    time: {
                        unit: "day",
                        displayFormats: {
                            day: "yyyy-MM-dd"
                        }
                    },
                    min: new Date(start),
                    max: new Date(end)
                },
                y: {
                    title: {
                        display: false
                    },
                    ticks: {
                        callback: (value) => {
                            return value + " ms";
                        },
                        color: "hsl(0, 0%, 60%)"
                    },
                    grid: {
                        color: "hsl(0, 0%, 30%)"
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

export default { initHttp, initPing };
