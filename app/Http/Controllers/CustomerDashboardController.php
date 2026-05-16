<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Services\RewardService;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->loadCount('referralsGiven');

        $stats = [
            'total_referrals' => (int) $user->referrals_given_count,
            'reward_points' => (int) $user->reward_points,
            'coupon_eligible' => RewardService::userIsCouponEligible($user),
            'coupon_code' => RewardService::userIsCouponEligible($user)
                ? RewardService::generateDisplayCouponCode($user)
                : null,
        ];

        $recentReferrals = Referral::query()
            ->where('referrer_id', $user->id)
            ->with(['referredUser', 'campaign'])
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('user', 'stats', 'recentReferrals'));
    }
}
