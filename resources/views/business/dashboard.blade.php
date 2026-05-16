@extends('layouts.business')

@section('title', __('Dashboard'))

@section('content')
    <div class="mb-8">
        <span
            class="mb-2 inline-block rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-800">{{ __('Business') }}</span>
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Welcome, :name!', ['name' => $user->name]) }}</h1>
        <p class="text-gray-500 mt-1">{{ __('Manage your campaigns and referrals from here.') }}</p>
        <a href="{{ route('business.analytics') }}"
            class="mt-4 inline-flex rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            {{ __('Open analytics dashboard') }}
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow">
            <p class="mb-1 text-sm text-gray-500">{{ __('Total campaigns') }}</p>
            <p class="text-4xl font-bold text-emerald-600">{{ $stats['total_campaigns'] }}</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow">
            <p class="mb-1 text-sm text-gray-500">{{ __('Total referrals (all time)') }}</p>
            <p class="text-4xl font-bold text-indigo-600">{{ $stats['total_referrals'] }}</p>
        </div>

        @if ($analyticsTotals)
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow sm:col-span-2 xl:col-span-1">
                <p class="mb-2 text-sm font-semibold text-gray-700">{{ __('Engagement snapshot (all time)') }}</p>
                <ul class="grid grid-cols-2 gap-3 text-sm text-gray-600">
                    <li><span class="font-semibold text-sky-700">{{ $analyticsTotals['likes'] }}</span> {{ __('likes') }}</li>
                    <li><span class="font-semibold text-indigo-700">{{ $analyticsTotals['comments'] }}</span> {{ __('comments') }}</li>
                    <li><span class="font-semibold text-amber-700">{{ $analyticsTotals['saves'] }}</span> {{ __('saves') }}</li>
                    <li><span class="font-semibold text-violet-700">{{ $analyticsTotals['referrals'] }}</span> {{ __('referrals') }}</li>
                </ul>
            </div>
        @endif

    </div>
@endsection
