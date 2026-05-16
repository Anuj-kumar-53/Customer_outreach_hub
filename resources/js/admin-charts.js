import Chart from 'chart.js/auto';

const payload = window.__ADMIN_CHARTS__;
if (!payload) {
    // Not on admin dashboard
} else {
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = '#334155';

    const growthCtx = document.getElementById('adminChartGrowth');
    if (growthCtx) {
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: payload.growth.labels,
                datasets: [
                    {
                        label: 'Signups',
                        data: payload.growth.signups,
                        borderColor: 'rgb(129, 140, 248)',
                        backgroundColor: 'rgba(129, 140, 248, 0.15)',
                        tension: 0.25,
                        fill: true,
                    },
                    {
                        label: 'New campaigns',
                        data: payload.growth.campaigns,
                        borderColor: 'rgb(45, 212, 191)',
                        backgroundColor: 'rgba(45, 212, 191, 0.12)',
                        tension: 0.25,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: { y: { beginAtZero: true } },
            },
        });
    }

    const repCtx = document.getElementById('adminChartReports');
    if (repCtx && payload.reports.values.some((v) => v > 0)) {
        new Chart(repCtx, {
            type: 'doughnut',
            data: {
                labels: payload.reports.labels,
                datasets: [
                    {
                        data: payload.reports.values,
                        backgroundColor: [
                            'rgba(248, 113, 113, 0.8)',
                            'rgba(251, 191, 36, 0.8)',
                            'rgba(52, 211, 153, 0.8)',
                            'rgba(148, 163, 184, 0.8)',
                        ],
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    }

    const campCtx = document.getElementById('adminChartCampaigns');
    if (campCtx && payload.campaignPerformance.labels.length) {
        new Chart(campCtx, {
            type: 'bar',
            data: {
                labels: payload.campaignPerformance.labels,
                datasets: [
                    {
                        label: 'Likes',
                        data: payload.campaignPerformance.likes,
                        backgroundColor: 'rgba(56, 189, 248, 0.65)',
                    },
                    {
                        label: 'Referrals',
                        data: payload.campaignPerformance.referrals,
                        backgroundColor: 'rgba(167, 139, 250, 0.65)',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: false, ticks: { maxRotation: 45, minRotation: 0 } },
                    y: { beginAtZero: true },
                },
            },
        });
    }

    const engCtx = document.getElementById('adminChartEngagement');
    if (engCtx && payload.engagement.values.some((v) => v > 0)) {
        new Chart(engCtx, {
            type: 'polarArea',
            data: {
                labels: payload.engagement.labels,
                datasets: [
                    {
                        data: payload.engagement.values,
                        backgroundColor: [
                            'rgba(56, 189, 248, 0.55)',
                            'rgba(99, 102, 241, 0.55)',
                            'rgba(251, 191, 36, 0.55)',
                            'rgba(167, 139, 250, 0.55)',
                        ],
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    }
}
