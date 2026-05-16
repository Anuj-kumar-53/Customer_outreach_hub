@extends('layouts.business')

@section('title', __('Analytics'))

@section('content')
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Campaign analytics') }}</h1>
            <p class="mt-1 text-gray-500">{{ __('Performance across your campaigns.') }}</p>
        </div>
        @if ($business)
            <form method="GET" action="{{ route('business.analytics') }}" class="flex flex-wrap items-end gap-2">
                <label class="sr-only" for="period">{{ __('Period') }}</label>
                <select id="period" name="period"
                    class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="week" @selected(($payload['period'] ?? '') === 'week')>{{ __('This week') }}</option>
                    <option value="month" @selected(($payload['period'] ?? '') === 'month')>{{ __('This month') }}</option>
                    <option value="all" @selected(($payload['period'] ?? 'all') === 'all')>{{ __('All time') }}</option>
                </select>
                <button type="submit"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-700">
                    {{ __('Apply') }}
                </button>
            </form>
        @endif
    </div>

    @if (! $business)
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-600">
            {{ __('Complete your business profile to see analytics.') }}
        </div>
    @else
        <p class="mb-4 text-sm text-gray-500">{{ __('Showing:') }} <span class="font-semibold text-gray-800">{{ $payload['period_label'] }}</span></p>

        <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-gray-500">{{ __('Campaigns') }}</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $payload['totals']['campaigns'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-gray-500">{{ __('Likes') }}</p>
                <p class="mt-2 text-3xl font-bold text-sky-600">{{ $payload['totals']['likes'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-gray-500">{{ __('Comments') }}</p>
                <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $payload['totals']['comments'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-gray-500">{{ __('Saves') }}</p>
                <p class="mt-2 text-3xl font-bold text-amber-600">{{ $payload['totals']['saves'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-gray-500">{{ __('Referrals') }}</p>
                <p class="mt-2 text-3xl font-bold text-violet-600">{{ $payload['totals']['referrals'] }}</p>
            </div>
        </div>

        @if ($payload['top_campaign'])
            <div class="mb-8 rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-white p-6 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-800">{{ __('Top performing campaign') }}</p>
                <p class="mt-2 text-xl font-bold text-gray-900">{{ $payload['top_campaign']['title'] }}</p>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Engagement score: :s (likes + comments + saves + referrals×2)', ['s' => $payload['top_campaign']['engagement_score']]) }}
                </p>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <h2 class="mb-2 text-sm font-semibold text-gray-900">{{ __('Referrals per campaign') }}</h2>
                <div class="h-64">
                    <canvas id="chartReferralsPerCampaign"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <h2 class="mb-2 text-sm font-semibold text-gray-900">{{ __('Likes per campaign') }}</h2>
                <div class="h-64">
                    <canvas id="chartLikesPerCampaign"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <h2 class="mb-2 text-sm font-semibold text-gray-900">{{ __('Engagement overview') }}</h2>
                <div class="mx-auto h-64 max-w-sm">
                    <canvas id="chartEngagementPie"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <h2 class="mb-2 text-sm font-semibold text-gray-900">{{ __('Engagement timeline') }}</h2>
                <div class="h-64">
                    <canvas id="chartEngagementLine"></canvas>
                </div>
            </div>
        </div>

        <div class="mt-10 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow">
            <h2 class="border-b border-gray-100 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-900">
                {{ __('Per-campaign breakdown') }}</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-white text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3">{{ __('Campaign') }}</th>
                            <th class="px-4 py-3">{{ __('Likes') }}</th>
                            <th class="px-4 py-3">{{ __('Comments') }}</th>
                            <th class="px-4 py-3">{{ __('Saves') }}</th>
                            <th class="px-4 py-3">{{ __('Referrals') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($payload['per_campaign'] as $row)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $row['title'] }}</td>
                                <td class="px-4 py-3">{{ $row['likes'] }}</td>
                                <td class="px-4 py-3">{{ $row['comments'] }}</td>
                                <td class="px-4 py-3">{{ $row['saves'] }}</td>
                                <td class="px-4 py-3">{{ $row['referrals'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">{{ __('No campaigns yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            window.__BUSINESS_ANALYTICS__ = @json($payload['charts']);
        </script>
        @vite(['resources/js/business-analytics.js'])
    @endif
@endsection
