<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Public campaign landing used for referral links: /campaign/{campaign}?ref={user}
 */
class PublicCampaignController extends Controller
{
    public function show(Request $request, Campaign $campaign): View
    {
        if ($campaign->moderation_status !== 'active') {
            abort(404);
        }

        $isActive = $campaign->expiry_date !== null
            && $campaign->expiry_date->copy()->startOfDay()->gte(now()->startOfDay());

        // Only guests should carry referral intent in session (registered users don't need it).
        if (! Auth::check() && $request->filled('ref')) {
            $refId = (int) $request->query('ref');

            if ($refId > 0 && User::query()->whereKey($refId)->exists() && $isActive) {
                session([
                    'referral_referrer_id' => $refId,
                    'referral_campaign_id' => $campaign->id,
                ]);
            } elseif ($request->has('ref')) {
                // Invalid ?ref= clears stale referral session to avoid accidental wrong attribution.
                session()->forget(['referral_referrer_id', 'referral_campaign_id']);
            }
        }

        $campaign->loadMissing('business');

        return view('public.campaign-show', [
            'campaign' => $campaign,
            'isActive' => $isActive,
        ]);
    }
}
