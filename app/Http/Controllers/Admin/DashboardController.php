<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAnalyticsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminAnalyticsService $analytics
    ) {}

    public function index(): View
    {
        $stats = $this->analytics->summaryCounts();
        $growth = $this->analytics->growthSeries(30);
        $topBusinesses = $this->analytics->topBusinesses(8);
        $topEarners = $this->analytics->topReferralEarners(8);
        $mostActive = $this->analytics->mostActiveUsers(8);
        $reportsByStatus = $this->analytics->reportsByStatus();
        $campaignPerformance = $this->analytics->campaignPerformanceTop(8);

        return view('admin.dashboard', compact(
            'stats',
            'growth',
            'topBusinesses',
            'topEarners',
            'mostActive',
            'reportsByStatus',
            'campaignPerformance'
        ));
    }
}
