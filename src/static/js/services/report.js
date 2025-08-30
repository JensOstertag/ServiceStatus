export const initHttp = () => {
    // Nothing to do
}

export const initPing = (data, start, end) => {
    const ctx = document.getElementById("pingReportChart").getContext("2d");
    const pingChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: data.map(point => new Date(point.timestamp).toLocaleTimeString()),
            datasets: [{
                label: 'Ping Time (ms)',
                data: data.map(point => point.responseTime),
                borderColor: "rgba(75, 192, 192, 1)",
                backgroundColor: "rgba(75, 192, 192, 0.2)",
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: "Time"
                    },
                    type: "time",
                    time: {
                        unit: "day",
                        displayFormats: {
                            day: "dd.MM."
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: "Ping Time (ms)"
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
