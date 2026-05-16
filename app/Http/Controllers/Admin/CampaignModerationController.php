<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\AdminActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CampaignModerationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Campaign::query()->with('business');

        if ($search = trim((string) $request->query('q', ''))) {
            $term = '%'.$search.'%';
            $query->where('title', 'like', $term);
        }

        if ($status = $request->query('moderation')) {
            if (in_array($status, ['active', 'removed'], true)) {
                $query->where('moderation_status', $status);
            }
        }

        $campaigns = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function remove(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $campaign->update([
            'moderation_status' => 'removed',
            'moderation_reason' => $data['reason'],
            'moderated_at' => now(),
            'moderated_by' => Auth::id(),
        ]);

        AdminActivityLogger::log(Auth::user(), 'campaign.removed', $campaign, 'Campaign removed from public', ['reason' => $data['reason']]);

        return back()->with('success', __('Campaign removed from public view.'));
    }

    public function restore(Campaign $campaign): RedirectResponse
    {
        $campaign->update([
            'moderation_status' => 'active',
            'moderation_reason' => null,
            'moderated_at' => now(),
            'moderated_by' => Auth::id(),
        ]);

        AdminActivityLogger::log(Auth::user(), 'campaign.restored', $campaign, 'Campaign restored');

        return back()->with('success', __('Campaign restored.'));
    }
}
