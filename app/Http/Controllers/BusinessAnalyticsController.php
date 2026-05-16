<?php

namespace App\Http\Controllers;

use App\Services\BusinessAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessAnalyticsController extends Controller
{
    public function __construct(
        private readonly BusinessAnalyticsService $analytics
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $business = $user->business;

        if (! $business) {
            return view('business.analytics', [
                'business' => null,
            ]);
        }

        $period = $request->query('period', BusinessAnalyticsService::PERIOD_ALL);
        $payload = $this->analytics->build($business, is_string($period) ? $period : null);

        return view('business.analytics', [
            'business' => $business,
            'payload' => $payload,
        ]);
    }
}
