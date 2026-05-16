<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\View\View;

class CustomerReferralController extends Controller
{
    /**
     * Referrals the current customer has generated (as referrer).
     */
    public function index(): View
    {
        $referrals = Referral::query()
            ->where('referrer_id', auth()->id())
            ->with(['referredUser', 'campaign'])
            ->latest()
            ->paginate(12);

        return view('customer.referrals', compact('referrals'));
    }
}
