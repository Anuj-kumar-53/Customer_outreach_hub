@extends('layouts.customer')

@section('title', __('My referrals'))

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('My referrals') }}</h1>
        <p class="mt-1 text-gray-500">{{ __('People who joined using your referral links.') }}</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('Referred user') }}</th>
                        <th class="px-4 py-3">{{ __('Campaign') }}</th>
                        <th class="px-4 py-3">{{ __('Points earned') }}</th>
                        <th class="px-4 py-3">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($referrals as $referral)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $referral->referredUser?->name ?? __('Unknown') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $referral->campaign?->title ?? __('Unknown') }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-sky-700">+{{ $referral->points_earned ?? 0 }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $referral->created_at->format('M j, Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                                {{ __('No referrals yet. Share your campaign links from the feed.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $referrals->links() }}
    </div>
@endsection
