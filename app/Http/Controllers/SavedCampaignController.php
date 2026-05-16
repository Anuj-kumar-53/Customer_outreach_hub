<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\SavedCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedCampaignController extends Controller
{
    public function store(Campaign $campaign)
    {
        if ($campaign->moderation_status !== 'active') {
            abort(404);
        }

        $userId = Auth::id();

        SavedCampaign::firstOrCreate([
            'user_id' => $userId,
            'campaign_id' => $campaign->id,
        ]);

        return back()->with('success', __('Campaign saved.'));
    }

    public function destroy(Campaign $campaign)
    {
        $userId = Auth::id();

        SavedCampaign::where('user_id', $userId)
            ->where('campaign_id', $campaign->id)
            ->delete();

        return back()->with('success', __('Saved campaign removed.'));
    }
}
