@extends('layouts.customer')

@section('title', __('Leaderboard'))

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Leaderboard') }}</h1>
        <p class="mt-1 text-gray-500">{{ __('Ranked by reward points. Keep referring to climb the board.') }}</p>
    </div>

    @if (\App\Services\RewardService::userIsCouponEligible(auth()->user()))
        <div class="mb-6 rounded-2xl border border-violet-200 bg-violet-50 px-4 py-3 text-violet-900">
            <p class="font-semibold">{{ __('Eligible for Reward Coupon') }}</p>
            <p class="mt-1 text-sm">
                {{ __('Your display code:') }}
                <span class="font-mono font-bold">{{ \App\Services\RewardService::generateDisplayCouponCode(auth()->user()) }}</span>
            </p>
        </div>
    @endif

    <div class="grid gap-4 lg:grid-cols-3">
        @foreach ($topThree as $index => $top)
            @php $rank = $index + 1; @endphp
            <div
                class="relative overflow-hidden rounded-2xl border border-gray-100 bg-gradient-to-br from-white to-sky-50 p-6 shadow @if ($rank === 1) ring-2 ring-amber-300 @elseif($rank === 2) ring-2 ring-gray-300 @elseif($rank === 3) ring-2 ring-amber-700/30 @endif">
                <span
                    class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-900 text-sm font-bold text-white">
                    #{{ $rank }}
                </span>
                <p class="text-sm text-gray-500">{{ __('Points') }}</p>
                <p class="text-3xl font-extrabold text-sky-700">{{ $top->reward_points }}</p>
                <p class="mt-3 text-lg font-semibold text-gray-900">{{ $top->name }}</p>
                <p class="text-sm text-gray-600">{{ __('Referrals: :n', ['n' => $top->referrals_given_count]) }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('Rank') }}</th>
                        <th class="px-4 py-3">{{ __('Name') }}</th>
                        <th class="px-4 py-3">{{ __('Referrals') }}</th>
                        <th class="px-4 py-3">{{ __('Reward points') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($users as $row)
                        @php $rank = $users->firstItem() + $loop->index; @endphp
                        <tr class="@if ($rank <= 3) bg-sky-50/60 @endif">
                            <td class="px-4 py-3 font-semibold text-gray-900">#{{ $rank }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $row->name }}
                                @if ($row->id === auth()->id())
                                    <span class="ml-2 rounded-full bg-sky-100 px-2 py-0.5 text-xs text-sky-800">{{ __('You') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $row->referrals_given_count }}</td>
                            <td class="px-4 py-3 font-semibold text-sky-700">{{ $row->reward_points }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection
