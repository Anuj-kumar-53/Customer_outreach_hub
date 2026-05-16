import Chart from 'chart.js/auto';

const data = window.__BUSINESS_ANALYTICS__;
if (!data) {
    // Analytics page not loaded
} else {
    const commonBar = (canvasId, labels, values, label, color) => {
        const ctx = document.getElementById(canvasId);
        if (!ctx) {
            return;
        }
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label,
                        data: values,
                        backgroundColor: color,
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { maxRotation: 45, minRotation: 0 } },
                    y: { beginAtZero: true },
                },
            },
        });
    };

    if (data.referrals_per_campaign.labels.length) {
        commonBar(
            'chartReferralsPerCampaign',
            data.referrals_per_campaign.labels,
            data.referrals_per_campaign.values,
            'Referrals',
            'rgba(124, 58, 237, 0.7)'
        );
    }

    if (data.likes_per_campaign.labels.length) {
        commonBar(
            'chartLikesPerCampaign',
            data.likes_per_campaign.labels,
            data.likes_per_campaign.values,
            'Likes',
            'rgba(14, 165, 233, 0.7)'
        );
    }

    const pieCtx = document.getElementById('chartEngagementPie');
    const pieTotal = (data.engagement_totals.values || []).reduce((a, b) => a + b, 0);
    if (pieCtx && pieTotal > 0) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: data.engagement_totals.labels,
                datasets: [
                    {
                        data: data.engagement_totals.values,
                        backgroundColor: [
                            'rgba(14, 165, 233, 0.75)',
                            'rgba(99, 102, 241, 0.75)',
                            'rgba(245, 158, 11, 0.75)',
                            'rgba(124, 58, 237, 0.75)',
                        ],
                        borderWidth: 1,
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

    const lineCtx = document.getElementById('chartEngagementLine');
    if (lineCtx && data.engagement_timeline.labels.length) {
        const tl = data.engagement_timeline;
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: tl.labels,
                datasets: [
                    {
                        label: 'Likes',
                        data: tl.likes,
                        borderColor: 'rgb(14, 165, 233)',
                        backgroundColor: 'rgba(14, 165, 233, 0.15)',
                        tension: 0.25,
                        fill: true,
                    },
                    {
                        label: 'Comments',
                        data: tl.comments,
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.25,
                        fill: true,
                    },
                    {
                        label: 'Referrals',
                        data: tl.referrals,
                        borderColor: 'rgb(124, 58, 237)',
                        backgroundColor: 'rgba(124, 58, 237, 0.1)',
                        tension: 0.25,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: { beginAtZero: true },
                },
            },
        });
    }
}
