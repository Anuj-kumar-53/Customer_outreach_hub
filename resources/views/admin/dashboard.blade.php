@extends('layouts.admin')

@section('title', __('Dashboard'))

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">{{ __('Platform overview') }}</h1>
        <p class="mt-1 text-sm text-slate-400">{{ __('Live metrics across users, campaigns, engagement, and moderation.') }}</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => __('Total users'), 'value' => $stats['users_total'], 'tone' => 'text-indigo-300'],
            ['label' => __('Businesses'), 'value' => $stats['users_business'], 'tone' => 'text-emerald-300'],
            ['label' => __('Customers'), 'value' => $stats['users_customer'], 'tone' => 'text-sky-300'],
            ['label' => __('Suspended / banned'), 'value' => $stats['users_suspended'] + $stats['users_banned'], 'tone' => 'text-amber-300'],
            ['label' => __('Campaigns'), 'value' => $stats['campaigns_total'], 'tone' => 'text-white'],
            ['label' => __('Public campaigns'), 'value' => $stats['campaigns_active_moderation'], 'tone' => 'text-teal-300'],
            ['label' => __('Open reports'), 'value' => $stats['reports_open'], 'tone' => 'text-rose-300'],
            ['label' => __('Comments pending review'), 'value' => $stats['comments_pending'], 'tone' => 'text-violet-300'],
        ] as $card)
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-5 shadow-inner">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $card['label'] }}</p>
                <p class="mt-2 text-3xl font-bold {{ $card['tone'] }}">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4">
            <h2 class="text-sm font-semibold text-slate-200">{{ __('User & campaign growth (30 days)') }}</h2>
            <div class="mt-3 h-72">
                <canvas id="adminChartGrowth"></canvas>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4">
            <h2 class="text-sm font-semibold text-slate-200">{{ __('Reports by status') }}</h2>
            <div class="mt-3 h-72">
                <canvas id="adminChartReports"></canvas>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4">
            <h2 class="text-sm font-semibold text-slate-200">{{ __('Top campaigns (likes vs referrals)') }}</h2>
            <div class="mt-3 h-72">
                <canvas id="adminChartCampaigns"></canvas>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4">
            <h2 class="text-sm font-semibold text-slate-200">{{ __('Engagement totals') }}</h2>
            <div class="mt-3 h-72">
                <canvas id="adminChartEngagement"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-5">
            <h2 class="text-sm font-semibold text-slate-200">{{ __('Top businesses (engagement score)') }}</h2>
            <ul class="mt-4 divide-y divide-slate-800 text-sm">
                @forelse ($topBusinesses as $row)
                    <li class="flex justify-between py-2">
                        <span class="text-slate-300">{{ $row['name'] }}</span>
                        <span class="font-mono text-indigo-300">{{ $row['engagement'] }}</span>
                    </li>
                @empty
                    <li class="py-4 text-slate-500">{{ __('No data yet.') }}</li>
                @endforelse
            </ul>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-5">
            <h2 class="text-sm font-semibold text-slate-200">{{ __('Highest referral earners') }}</h2>
            <ul class="mt-4 divide-y divide-slate-800 text-sm">
                @forelse ($topEarners as $u)
                    <li class="flex justify-between py-2">
                        <span class="text-slate-300">{{ $u->name }}</span>
                        <span class="font-mono text-amber-300">{{ $u->reward_points }} {{ __('pts') }}</span>
                    </li>
                @empty
                    <li class="py-4 text-slate-500">{{ __('No data yet.') }}</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-8 rounded-2xl border border-slate-800 bg-slate-900/40 p-5">
        <h2 class="text-sm font-semibold text-slate-200">{{ __('Most active users (engagement score)') }}</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase text-slate-500">
                    <tr>
                        <th class="pb-2">{{ __('User') }}</th>
                        <th class="pb-2">{{ __('Email') }}</th>
                        <th class="pb-2">{{ __('Score') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($mostActive as $row)
                        <tr>
                            <td class="py-2 font-medium text-white">{{ $row['name'] }}</td>
                            <td class="py-2">{{ $row['email'] }}</td>
                            <td class="py-2 font-mono text-sky-300">{{ $row['score'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-slate-500">{{ __('No data yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @php
        $adminCharts = [
            'growth'              => $growth,
            'reports'             => $reportsByStatus,
            'campaignPerformance' => $campaignPerformance,
            'engagement'          => [
                'labels' => [__('Likes'), __('Comments'), __('Saves'), __('Referrals')],
                'values' => [
                    $stats['likes_total'],
                    $stats['comments_total'],
                    $stats['saves_total'],
                    $stats['referrals_total'],
                ],
            ],
        ];
    @endphp

    <script>
        window.__ADMIN_CHARTS__ = @json($adminCharts);
    </script>
    @vite(['resources/js/admin-charts.js'])
@endsection