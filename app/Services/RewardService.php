<?php

namespace App\Services;

use App\Models\User;

/**
 * Central place for reward / gamification rules (Phase 8).
 */
class RewardService
{
    public static function pointsPerReferral(): int
    {
        return max(0, (int) config('rewards.points_per_referral', 10));
    }

    public static function couponEligibilityPoints(): int
    {
        return max(0, (int) config('rewards.coupon_eligibility_points', 50));
    }

    public static function userIsCouponEligible(User $user): bool
    {
        return (int) $user->reward_points >= self::couponEligibilityPoints();
    }

    /**
     * Deterministic “coupon code” for UI display (not a payment integration).
     */
    public static function generateDisplayCouponCode(User $user): string
    {
        $prefix = (string) config('rewards.coupon_code_prefix', 'COH');
        $hash = strtoupper(substr(sha1($user->id.'|'.$user->email.'|'.config('app.key')), 0, 8));

        return sprintf('%s-%d-%s', $prefix, $user->id, $hash);
    }
}
