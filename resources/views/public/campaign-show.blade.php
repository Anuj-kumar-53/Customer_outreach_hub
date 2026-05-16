@extends('layouts.public')

@section('title', $campaign->title)

@section('content')
    <article class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow">
        <div class="aspect-video w-full overflow-hidden bg-gray-200">
            @if ($campaign->image)
                <img src="{{ asset('storage/'.$campaign->image) }}" alt="{{ $campaign->title }}"
                    class="h-full w-full object-cover">
            @else
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400">
                    <span class="text-sm font-medium">{{ __('No image') }}</span>
                </div>
            @endif
        </div>
        <div class="p-6 sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-wide text-sky-700">{{ $campaign->category }}</p>
            <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">{{ $campaign->title }}</h1>
            <p class="mt-4 text-gray-600">{{ $campaign->description }}</p>

            <dl class="mt-6 grid gap-4 text-sm text-gray-600 sm:grid-cols-2">
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <dt class="text-gray-500">{{ __('Business') }}</dt>
                    <dd class="mt-1 font-semibold text-gray-900">{{ $campaign->business?->business_name ?? __('Unknown') }}</dd>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                    <dt class="text-gray-500">{{ __('Expires') }}</dt>
                    <dd class="mt-1 font-semibold text-gray-900">{{ $campaign->expiry_date?->format('M j, Y') }}</dd>
                </div>
            </dl>

            @if (! $isActive)
                <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    {{ __('This campaign is no longer active.') }}
                </div>
            @endif

            @guest
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}"
                        class="inline-flex flex-1 min-w-[10rem] justify-center rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow hover:bg-sky-700">
                        {{ __('Create an account') }}
                    </a>
                    <a href="{{ route('login') }}"
                        class="inline-flex flex-1 min-w-[10rem] justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                        {{ __('Log in') }}
                    </a>
                </div>
                @if (session()->has('referral_referrer_id') && session()->has('referral_campaign_id'))
                    <p class="mt-4 text-sm text-emerald-700">
                        {{ __('Your invite will be linked when you complete registration.') }}
                    </p>
                @endif
            @else
                <div class="mt-8 rounded-xl border border-sky-100 bg-sky-50 px-4 py-3 text-sm text-sky-900">
                    {{ __('You are signed in. Browse the campaign feed for interactions.') }}
                    @if (auth()->user()->role === 'customer')
                        <a href="{{ route('customer.campaign-feed') }}" class="ml-1 font-semibold underline">{{ __('Open feed') }}</a>
                    @endif
                </div>
            @endguest
        </div>
    </article>
@endsection
