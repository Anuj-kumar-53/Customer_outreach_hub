<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    /** Fixed dropdown options for campaign category (Phase 4). */
    public const CATEGORIES = [
        'Food',
        'Electronics',
        'Fashion',
        'Education',
        'Healthcare',
        'Services',
        'Others',
    ];

    /**
     * Ensure the authenticated business user has a Business row (lazy setup).
     * Campaigns always belong to businesses, not directly to users.
     */
    private function businessForUser(): Business
    {
        $user = Auth::user();

        return Business::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => $user->name."'s Business",
                'description' => null,
                'address' => null,
                'status' => 'active',
            ]
        );
    }

    /**
     * Abort 403 if this campaign does not belong to the current user's business.
     */
    private function authorizeCampaign(Campaign $campaign): void
    {
        $business = Auth::user()->business;

        if (! $business || $campaign->business_id !== $business->id) {
            abort(403);
        }
    }

    public function index(): View
    {
        $business = $this->businessForUser();
        $campaigns = Campaign::query()
            ->where('business_id', $business->id)
            ->latest()
            ->get();

        return view('campaigns.index', [
            'campaigns' => $campaigns,
            'categories' => self::CATEGORIES,
        ]);
    }

    public function create(): View
    {
        $this->businessForUser();

        return view('campaigns.create', [
            'categories' => self::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $business = $this->businessForUser();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100', Rule::in(self::CATEGORIES)],
            'expiry_date' => ['required', 'date'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $validated['image'] = $request->file('image')->store('campaigns', 'public');
        $validated['business_id'] = $business->id;

        Campaign::create($validated);

        return redirect()
            ->route('business.campaigns.index')
            ->with('success', __('Campaign created successfully.'));
    }

    public function edit(Campaign $campaign): View
    {
        $this->authorizeCampaign($campaign);

        return view('campaigns.edit', [
            'campaign' => $campaign,
            'categories' => self::CATEGORIES,
        ]);
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $this->authorizeCampaign($campaign);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100', Rule::in(self::CATEGORIES)],
            'expiry_date' => ['required', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        // Never let the client change business_id via mass assignment.
        unset($validated['business_id']);

        $campaign->update($validated);

        return redirect()
            ->route('business.campaigns.index')
            ->with('success', __('Campaign updated successfully.'));
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $this->authorizeCampaign($campaign);

        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return redirect()
            ->route('business.campaigns.index')
            ->with('success', __('Campaign deleted successfully.'));
    }
}
