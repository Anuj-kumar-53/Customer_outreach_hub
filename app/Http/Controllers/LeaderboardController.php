<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Rank users by reward points (Phase 8).
     */
    public function index(): View
    {
        $topThree = User::query()
            ->select(['id', 'name', 'reward_points', 'role'])
            ->withCount('referralsGiven')
            ->orderByDesc('reward_points')
            ->orderByDesc('referrals_given_count')
            ->orderBy('name')
            ->limit(3)
            ->get();

        $users = User::query()
            ->select(['id', 'name', 'reward_points', 'role'])
            ->withCount('referralsGiven')
            ->orderByDesc('reward_points')
            ->orderByDesc('referrals_given_count')
            ->orderBy('name')
            ->paginate(25);

        return view('customer.leaderboard', compact('users', 'topThree'));
    }
}
