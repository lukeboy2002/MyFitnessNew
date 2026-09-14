import {BarController, BarElement, CategoryScale, Chart, LinearScale, Tooltip,} from 'chart.js';

Chart.register(
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip
);

window.exerciseCharts = {};

window.createExerciseChart = function (
    canvas,
    labels,
    values
) {
    if (!canvas) {
        console.error('Exercise chart canvas not found');

        return null;
    }

    const chartId = canvas.dataset.chartId;

    if (
        chartId &&
        window.exerciseCharts[chartId]
    ) {
        window.exerciseCharts[chartId].destroy();
    }

    const chart = new Chart(canvas, {
        type: 'bar',

        data: {
            labels,

            datasets: [
                {
                    data: values,
                    backgroundColor: 'rgba(234,88,12,0.5)',
                    borderRadius: 6,
                    borderWidth: 0,
                },
            ],
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            animation: {
                duration: 300,
            },

            plugins: {
                legend: {
                    display: false,
                },

                tooltip: {
                    enabled: true,
                },
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        color: '#9ca3af',
                    },

                    grid: {
                        color: 'rgba(128,128,128,0.1)',
                    },
                },

                x: {
                    ticks: {
                        color: '#9ca3af',
                    },

                    grid: {
                        display: false,
                    },
                },
            },
        },
    });

    if (chartId) {
        window.exerciseCharts[chartId] = chart;
    }

    return chart;
};
