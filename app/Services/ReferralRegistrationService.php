<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Creates referral rows and credits referrer points after a new user registers.
 */
class ReferralRegistrationService
{
    /**
     * Consume referral session (if any) and create a referral + reward once.
     */
    public function processForNewUser(User $newUser): ?Referral
    {
        $referrerId = (int) session()->pull('referral_referrer_id', 0);
        $campaignId = (int) session()->pull('referral_campaign_id', 0);

        if ($referrerId <= 0 || $campaignId <= 0) {
            return null;
        }

        // Never allow self-referrals.
        if ($referrerId === (int) $newUser->id) {
            return null;
        }

        if (! User::query()->whereKey($referrerId)->exists()) {
            return null;
        }

        $campaign = Campaign::query()->find($campaignId);
        if (! $campaign) {
            return null;
        }

        // Campaign must still be public + not expired at signup (same rules as customer feed).
        if ($campaign->moderation_status !== 'active') {
            return null;
        }

        if ($campaign->expiry_date === null || $campaign->expiry_date->lt(now()->startOfDay())) {
            return null;
        }

        if (Referral::query()
            ->where('referrer_id', $referrerId)
            ->where('referred_user_id', $newUser->id)
            ->where('campaign_id', $campaignId)
            ->exists()) {
            return null;
        }

        $points = RewardService::pointsPerReferral();
        if ($points <= 0) {
            return null;
        }

        return DB::transaction(function () use ($referrerId, $newUser, $campaignId, $points) {
            $referral = Referral::create([
                'referrer_id' => $referrerId,
                'referred_user_id' => $newUser->id,
                'campaign_id' => $campaignId,
                'status' => 'completed',
                'points_earned' => $points,
            ]);

            User::query()->whereKey($referrerId)->increment('reward_points', $points);

            return $referral;
        });
    }
}
