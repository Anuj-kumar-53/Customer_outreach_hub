<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Campaign;
use App\Models\CampaignComment;
use App\Models\CampaignLike;
use App\Models\Referral;
use App\Models\SavedCampaign;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Aggregated analytics for a business (Phase 9) — optimized counts, no N+1 in views.
 */
class BusinessAnalyticsService
{
    public const PERIOD_WEEK = 'week';

    public const PERIOD_MONTH = 'month';

    public const PERIOD_ALL = 'all';

    /**
     * @return array{start: ?Carbon, end: Carbon, label: string}
     */
    public function resolvePeriod(?string $period): array
    {
        $end = now();

        return match ($period) {
            self::PERIOD_WEEK => [
                'start' => $end->copy()->subDays(7)->startOfDay(),
                'end' => $end,
                'label' => __('This week'),
            ],
            self::PERIOD_MONTH => [
                'start' => $end->copy()->subDays(30)->startOfDay(),
                'end' => $end,
                'label' => __('This month'),
            ],
            default => [
                'start' => null,
                'end' => $end,
                'label' => __('All time'),
            ],
        };
    }

    /**
     * @return array<string, mixed>
     */
    public function build(Business $business, ?string $period): array
    {
        $periodKey = in_array($period, [self::PERIOD_WEEK, self::PERIOD_MONTH, self::PERIOD_ALL], true)
            ? $period
            : self::PERIOD_ALL;

        $resolved = $this->resolvePeriod($periodKey);
        $start = $resolved['start'];

        $campaignIds = $business->campaigns()->pluck('id');
        $totalCampaigns = $campaignIds->count();

        if ($totalCampaigns === 0) {
            return [
                'period' => $periodKey,
                'period_label' => $resolved['label'],
                'totals' => [
                    'campaigns' => 0,
                    'likes' => 0,
                    'comments' => 0,
                    'saves' => 0,
                    'referrals' => 0,
                ],
                'top_campaign' => null,
                'per_campaign' => collect(),
                'charts' => [
                    'referrals_per_campaign' => ['labels' => [], 'values' => []],
                    'likes_per_campaign' => ['labels' => [], 'values' => []],
                    'engagement_totals' => ['labels' => [], 'values' => []],
                    'engagement_timeline' => ['labels' => [], 'likes' => [], 'comments' => [], 'referrals' => []],
                ],
            ];
        }

        $likesQuery = CampaignLike::query()->whereIn('campaign_id', $campaignIds);
        $commentsQuery = CampaignComment::query()->whereIn('campaign_id', $campaignIds);
        $savesQuery = SavedCampaign::query()->whereIn('campaign_id', $campaignIds);
        $referralsQuery = Referral::query()->whereIn('campaign_id', $campaignIds);

        if ($start) {
            $likesQuery->where('created_at', '>=', $start);
            $commentsQuery->where('created_at', '>=', $start);
            $savesQuery->where('created_at', '>=', $start);
            $referralsQuery->where('created_at', '>=', $start);
        }

        $totals = [
            'campaigns' => $totalCampaigns,
            'likes' => (clone $likesQuery)->count(),
            'comments' => (clone $commentsQuery)->count(),
            'saves' => (clone $savesQuery)->count(),
            'referrals' => (clone $referralsQuery)->count(),
        ];

        $perCampaign = $this->perCampaignMetrics($business, $campaignIds, $start);

        $topCampaign = $perCampaign->sortByDesc(fn ($row) => $row['engagement_score'])->first();

        $charts = [
            'referrals_per_campaign' => [
                'labels' => $perCampaign->pluck('short_title')->all(),
                'values' => $perCampaign->pluck('referrals')->all(),
            ],
            'likes_per_campaign' => [
                'labels' => $perCampaign->pluck('short_title')->all(),
                'values' => $perCampaign->pluck('likes')->all(),
            ],
            'engagement_totals' => [
                'labels' => [__('Likes'), __('Comments'), __('Saves'), __('Referrals')],
                'values' => [
                    $totals['likes'],
                    $totals['comments'],
                    $totals['saves'],
                    $totals['referrals'],
                ],
            ],
            'engagement_timeline' => $this->engagementTimeline($campaignIds, $start),
        ];

        return [
            'period' => $periodKey,
            'period_label' => $resolved['label'],
            'totals' => $totals,
            'top_campaign' => $topCampaign,
            'per_campaign' => $perCampaign,
            'charts' => $charts,
        ];
    }

    /**
     * @param  Collection<int, int>  $campaignIds
     */
    private function perCampaignMetrics(Business $business, Collection $campaignIds, ?Carbon $start): Collection
    {
        return Campaign::query()
            ->where('business_id', $business->id)
            ->orderByDesc('created_at')
            ->get(['id', 'title'])
            ->map(function (Campaign $campaign) use ($start) {
                $likes = CampaignLike::query()->where('campaign_id', $campaign->id)
                    ->when($start, fn ($q) => $q->where('created_at', '>=', $start))
                    ->count();
                $comments = CampaignComment::query()->where('campaign_id', $campaign->id)
                    ->when($start, fn ($q) => $q->where('created_at', '>=', $start))
                    ->count();
                $saves = SavedCampaign::query()->where('campaign_id', $campaign->id)
                    ->when($start, fn ($q) => $q->where('created_at', '>=', $start))
                    ->count();
                $referrals = Referral::query()->where('campaign_id', $campaign->id)
                    ->when($start, fn ($q) => $q->where('created_at', '>=', $start))
                    ->count();

                $engagementScore = ($likes + $comments + $saves) + ($referrals * 2);

                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'short_title' => Str::limit($campaign->title, 28),
                    'likes' => $likes,
                    'comments' => $comments,
                    'saves' => $saves,
                    'referrals' => $referrals,
                    'engagement_score' => $engagementScore,
                ];
            });
    }

    /**
     * Daily buckets for likes/comments/referrals (saves omitted for readability).
     *
     * @param  Collection<int, int>  $campaignIds
     * @return array{labels: array<int, string>, likes: array<int, int>, comments: array<int, int>, referrals: array<int, int>}
     */
    private function engagementTimeline(Collection $campaignIds, ?Carbon $start): array
    {
        $from = $start?->copy()->startOfDay() ?? now()->subDays(13)->startOfDay();
        $to = now()->startOfDay();

        $labels = [];
        $likes = [];
        $comments = [];
        $referrals = [];

        $day = $from->copy();
        $guard = 0;
        while ($day->lte($to) && $guard < 60) {
            $labels[] = $day->format('M j');

            $likes[] = CampaignLike::query()
                ->whereIn('campaign_id', $campaignIds)
                ->whereDate('created_at', $day->toDateString())
                ->count();

            $comments[] = CampaignComment::query()
                ->whereIn('campaign_id', $campaignIds)
                ->whereDate('created_at', $day->toDateString())
                ->count();

            $referrals[] = Referral::query()
                ->whereIn('campaign_id', $campaignIds)
                ->whereDate('created_at', $day->toDateString())
                ->count();

            $day->addDay();
            $guard++;
        }

        return [
            'labels' => $labels,
            'likes' => $likes,
            'comments' => $comments,
            'referrals' => $referrals,
        ];
    }
}
