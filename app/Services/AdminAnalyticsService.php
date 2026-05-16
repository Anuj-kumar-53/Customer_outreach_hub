<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Campaign;
use App\Models\CampaignComment;
use App\Models\CampaignLike;
use App\Models\Referral;
use App\Models\Report;
use App\Models\SavedCampaign;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminAnalyticsService
{
    /**
     * @return array<string, mixed>
     */
    public function summaryCounts(): array
    {
        return [
            'users_total' => User::query()->count(),
            'users_business' => User::query()->where('role', 'business')->count(),
            'users_customer' => User::query()->where('role', 'customer')->count(),
            'users_suspended' => User::query()->where('account_status', 'suspended')->count(),
            'users_banned' => User::query()->where('account_status', 'banned')->count(),
            'campaigns_total' => Campaign::query()->count(),
            'campaigns_active_moderation' => Campaign::query()->where('moderation_status', 'active')->count(),
            'reports_open' => Report::query()->where('status', Report::STATUS_OPEN)->count(),
            'comments_pending' => CampaignComment::query()->where('moderation_status', 'pending_review')->count(),
            'likes_total' => CampaignLike::query()->count(),
            'comments_total' => CampaignComment::query()->count(),
            'saves_total' => SavedCampaign::query()->count(),
            'referrals_total' => Referral::query()->count(),
        ];
    }

    /**
     * @return array{labels: array<int, string>, signups: array<int, int>, campaigns: array<int, int>}
     */
    public function growthSeries(int $days = 30): array
    {
        $start = now()->subDays($days - 1)->startOfDay();
        $labels = [];
        $signups = [];
        $campaigns = [];

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            $labels[] = $day->format('M j');
            $signups[] = User::query()->whereDate('created_at', $day->toDateString())->count();
            $campaigns[] = Campaign::query()->whereDate('created_at', $day->toDateString())->count();
        }

        return compact('labels', 'signups', 'campaigns');
    }

    /**
     * @return array<int, array{id: int, name: string, referrals: int, campaigns: int, engagement: int}>
     */
    public function topBusinesses(int $limit = 8): array
    {
        $referralSub = Referral::query()
            ->selectRaw('count(*)')
            ->join('campaigns', 'campaigns.id', '=', 'referrals.campaign_id')
            ->whereColumn('campaigns.business_id', 'businesses.id');

        $likeSub = CampaignLike::query()
            ->selectRaw('count(*)')
            ->join('campaigns', 'campaigns.id', '=', 'campaign_likes.campaign_id')
            ->whereColumn('campaigns.business_id', 'businesses.id');

        $commentSub = CampaignComment::query()
            ->selectRaw('count(*)')
            ->join('campaigns', 'campaigns.id', '=', 'campaign_comments.campaign_id')
            ->whereColumn('campaigns.business_id', 'businesses.id');

        $saveSub = SavedCampaign::query()
            ->selectRaw('count(*)')
            ->join('campaigns', 'campaigns.id', '=', 'saved_campaigns.campaign_id')
            ->whereColumn('campaigns.business_id', 'businesses.id');

        return Business::query()
            ->select('businesses.*')
            ->selectSub($referralSub, 'referrals_total')
            ->selectSub($likeSub, 'likes_total')
            ->selectSub($commentSub, 'comments_total')
            ->selectSub($saveSub, 'saves_total')
            ->withCount('campaigns as campaigns_total')
            ->orderByDesc(DB::raw('(likes_total + comments_total + saves_total + referrals_total * 2)'))
            ->limit($limit)
            ->get()
            ->map(fn (Business $b) => [
                'id' => $b->id,
                'name' => Str::limit($b->business_name, 36),
                'referrals' => (int) $b->referrals_total,
                'campaigns' => (int) $b->campaigns_total,
                'engagement' => (int) $b->likes_total + (int) $b->comments_total + (int) $b->saves_total + (int) $b->referrals_total * 2,
            ])
            ->all();
    }

    /**
     * Top referral point earners (uses reward_points).
     *
     * @return \Illuminate\Support\Collection<int, User>
     */
    public function topReferralEarners(int $limit = 8)
    {
        return User::query()
            ->whereIn('role', ['customer', 'business'])
            ->orderByDesc('reward_points')
            ->orderByDesc('id')
            ->limit($limit)
            ->get(['id', 'name', 'email', 'role', 'reward_points']);
    }

    /**
     * Most “active” users by engagement footprint (Phase 6 tables).
     *
     * @return array<int, array{id:int,name:string,email:string,score:int}>
     */
    public function mostActiveUsers(int $limit = 8): array
    {
        $rows = User::query()
            ->select('users.*')
            ->selectRaw('(
                (select count(*) from campaign_likes where campaign_likes.user_id = users.id)
              + (select count(*) from campaign_comments where campaign_comments.user_id = users.id)
              + (select count(*) from saved_campaigns where saved_campaigns.user_id = users.id)
              + (select count(*) from referrals where referrals.referrer_id = users.id) * 2
            ) as activity_score')
            ->whereIn('role', ['customer', 'business'])
            ->orderByDesc('activity_score')
            ->limit($limit)
            ->get();

        return $rows->map(fn (User $u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'score' => (int) ($u->activity_score ?? 0),
        ])->all();
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    public function reportsByStatus(): array
    {
        $statuses = [
            Report::STATUS_OPEN,
            Report::STATUS_REVIEWING,
            Report::STATUS_RESOLVED,
            Report::STATUS_DISMISSED,
        ];
        $labels = [];
        $values = [];
        foreach ($statuses as $s) {
            $labels[] = ucfirst(str_replace('_', ' ', $s));
            $values[] = Report::query()->where('status', $s)->count();
        }

        return compact('labels', 'values');
    }

    /**
     * Campaign performance snapshot (active moderation only).
     *
     * @return array{labels: array<int, string>, likes: array<int, int>, referrals: array<int, int>}
     */
    public function campaignPerformanceTop(int $limit = 8): array
    {
        $campaigns = Campaign::query()
            ->where('moderation_status', 'active')
            ->withCount(['likes', 'comments', 'referrals'])
            ->orderByDesc('referrals_count')
            ->orderByDesc('likes_count')
            ->limit($limit)
            ->get();

        return [
            'labels' => $campaigns->map(fn ($c) => Str::limit($c->title, 20))->all(),
            'likes' => $campaigns->pluck('likes_count')->all(),
            'referrals' => $campaigns->pluck('referrals_count')->all(),
        ];
    }
}
