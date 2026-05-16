<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignFeedController extends Controller
{
    /**
     * Category options for the filter dropdown (Phase 5).
     * Matches common campaign categories; use "All" in the UI for no filter.
     */
    public const FILTER_CATEGORIES = [
        'Food',
        'Fashion',
        'Electronics',
        'Education',
        'Healthcare',
        'Others',
    ];

    /**
     * Customer campaign feed: active campaigns only, search, category filter, pagination.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $categoryParam = $request->query('category');
        $userId = (int) $request->user()->id;

        $query = Campaign::query()
            ->publiclyVisible()
            ->with([
                'business.user',
                'comments' => fn ($q) => $q->where('moderation_status', 'approved')->with('user'),
                'likes' => fn ($q) => $q->where('user_id', $userId),
                'saves' => fn ($q) => $q->where('user_id', $userId),
            ])
            ->withCount(['likes'])
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now()->startOfDay());

        // Case-insensitive search on title and category (query parameter: ?search=...)
        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(title) LIKE LOWER(?)', [$term])
                    ->orWhereRaw('LOWER(category) LIKE LOWER(?)', [$term]);
            });
        }

        // Category filter (?category=Food) — must match whitelist so query strings stay safe
        if ($categoryParam !== null && $categoryParam !== '' && $categoryParam !== 'all') {
            if (in_array($categoryParam, self::FILTER_CATEGORIES, true)) {
                $query->where('category', $categoryParam);
            }
        }

        // A) Latest: always newest first
        $query->orderByDesc('created_at');

        $campaigns = $query->paginate(8)->withQueryString();

        return view('customer.feed', [
            'campaigns' => $campaigns,
            'categories' => self::FILTER_CATEGORIES,
            'search' => $search,
            'selectedCategory' => $categoryParam === 'all' || $categoryParam === null || $categoryParam === ''
                ? 'all'
                : (string) $categoryParam,
        ]);
    }
}
