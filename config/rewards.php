<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Points awarded to the referrer when a new user completes registration
    | via a valid referral session (Phase 7–8).
    |--------------------------------------------------------------------------
    */
    'points_per_referral' => (int) env('REWARD_POINTS_PER_REFERRAL', 10),

    /*
    |--------------------------------------------------------------------------
    | Minimum reward points to show “coupon eligible” messaging (Phase 8).
    |--------------------------------------------------------------------------
    */
    'coupon_eligibility_points' => (int) env('REWARD_COUPON_ELIGIBILITY_POINTS', 50),

    /*
    |--------------------------------------------------------------------------
    | Prefix for generated display coupon codes (optional gamification).
    |--------------------------------------------------------------------------
    */
    'coupon_code_prefix' => env('REWARD_COUPON_PREFIX', 'COH'),

];
