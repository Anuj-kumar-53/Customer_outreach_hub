@extends('layouts.customer')

@section('title', __('Dashboard'))

@section('content')
    <div class="mb-8">
        <span
            class="mb-2 inline-block rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-sky-800">{{ __('Customer') }}</span>
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Welcome, :name!', ['name' => $user->name]) }}</h1>
        <p class="mt-1 text-gray-500">{{ __('Here\'s your account summary.') }}</p>
        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('customer.campaign-feed') }}"
                class="inline-flex rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                {{ __('Campaign Feed') }}
            </a>
            <a href="{{ route('customer.referrals') }}"
                class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                {{ __('My Referrals') }}
            </a>
            <a href="{{ route('customer.leaderboard') }}"
                class="inline-flex rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                {{ __('Leaderboard') }}
            </a>
        </div>
    </div>

    @if ($stats['coupon_eligible'])
        <div class="mb-6 rounded-2xl border border-violet-200 bg-violet-50 px-4 py-4 text-violet-900 shadow-sm">
            <p class="font-semibold">{{ __('Eligible for Reward Coupon') }}</p>
            <p class="mt-1 text-sm">{{ __('You have :pts reward points.', ['pts' => $stats['reward_points']]) }}</p>
            @if ($stats['coupon_code'])
                <p class="mt-2 font-mono text-sm font-bold tracking-wide">{{ $stats['coupon_code'] }}</p>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow">
            <p class="mb-1 text-sm text-gray-500">{{ __('Total referrals') }}</p>
            <p class="text-4xl font-bold text-sky-600">{{ $stats['total_referrals'] }}</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow">
            <p class="mb-1 text-sm text-gray-500">{{ __('Reward points') }}</p>
            <p class="text-4xl font-bold text-amber-600">{{ $stats['reward_points'] }}</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow">
            <p class="mb-1 text-sm text-gray-500">{{ __('Email') }}</p>
            <p class="text-lg font-semibold text-gray-700 break-all">{{ $user->email }}</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow">
            <p class="mb-1 text-sm text-gray-500">{{ __('Member since') }}</p>
            <p class="text-lg font-semibold text-gray-700">{{ $user->created_at->format('M d, Y') }}</p>
        </div>

    </div>

    <div class="mt-10 rounded-2xl border border-gray-100 bg-white p-6 shadow">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('Recent referral activity') }}</h2>
        <ul class="mt-4 divide-y divide-gray-100">
            @forelse ($recentReferrals as $ref)
                <li class="flex flex-wrap items-center justify-between gap-2 py-3 text-sm">
                    <span class="font-medium text-gray-900">{{ $ref->referredUser?->name ?? __('Unknown') }}</span>
                    <span class="text-gray-600">{{ $ref->campaign?->title ?? __('Campaign') }}</span>
                    <span class="font-semibold text-sky-700">+{{ $ref->points_earned ?? 0 }} {{ __('pts') }}</span>
                    <span class="text-xs text-gray-500">{{ $ref->created_at->diffForHumans() }}</span>
                </li>
            @empty
                <li class="py-6 text-center text-gray-500">{{ __('No referrals yet. Share your links from the campaign feed.') }}</li>
            @endforelse
        </ul>
    </div>
@endsection
