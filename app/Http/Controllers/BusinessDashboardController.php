<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Services\BusinessAnalyticsService;
use Illuminate\Support\Facades\Auth;

class BusinessDashboardController extends Controller
{
    public function __construct(
        private readonly BusinessAnalyticsService $analytics
    ) {}

    public function index()
    {
        $user = Auth::user();
        $business = $user->business;

        $campaignIds = $business
            ? $business->campaigns()->pluck('id')
            : collect();

        $stats = [
            'total_campaigns' => $business ? $business->campaigns()->count() : 0,
            'total_referrals' => $campaignIds->isNotEmpty()
                ? Referral::query()->whereIn('campaign_id', $campaignIds)->count()
                : 0,
        ];

        $analyticsTotals = null;
        if ($business) {
            $payload = $this->analytics->build($business, BusinessAnalyticsService::PERIOD_ALL);
            $analyticsTotals = $payload['totals'];
        }

        return view('business.dashboard', compact('user', 'stats', 'analyticsTotals'));
    }
}
