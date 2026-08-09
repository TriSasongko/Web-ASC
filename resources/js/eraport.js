import Chart from 'chart.js/auto';

Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.color = '#424654';

document.addEventListener('DOMContentLoaded', () => {
    const trendEl = document.getElementById('progressTrendChart');
    const radarEl = document.getElementById('skillRadarChart');

    if (trendEl) {
        const ctx = trendEl.getContext('2d');
        const labels = JSON.parse(trendEl.dataset.labels || '[]');
        const values = JSON.parse(trendEl.dataset.values || '[]');
        const gradient = ctx.createLinearGradient(0, 0, 0, 128);
        gradient.addColorStop(0, 'rgba(87, 223, 254, 0.25)');
        gradient.addColorStop(1, 'rgba(87, 223, 254, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Skor Keseluruhan',
                    data: values,
                    borderColor: '#4cd7f6',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#0047a9',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#273044',
                        titleFont: { size: 12, weight: '600' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: (context) => `Skor: ${context.parsed.y}`,
                        },
                    },
                },
                scales: {
                    y: { display: false, min: 0, max: 100 },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { size: 10 }, color: '#737785' },
                    },
                },
            },
        });
    }

    if (radarEl) {
        const ctx = radarEl.getContext('2d');
        const labels = JSON.parse(radarEl.dataset.labels || '[]');
        const values = JSON.parse(radarEl.dataset.values || '[]');
        const colors = JSON.parse(radarEl.dataset.colors || '[]');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#273044',
                        titleFont: { size: 12, weight: '600' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: (context) => `Skor: ${context.parsed}`,
                        },
                    },
                },
            },
        });
    }
});
