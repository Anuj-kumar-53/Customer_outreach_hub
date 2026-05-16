@extends('layouts.business')

@section('title', __('Campaigns'))

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Your campaigns') }}</h1>
            <p class="mt-1 text-gray-500">{{ __('Create and manage outreach campaigns for your business.') }}</p>
        </div>
        <a href="{{ route('business.campaigns.create') }}"
            class="inline-flex justify-center items-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            {{ __('Create campaign') }}
        </a>
    </div>

    @if ($campaigns->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
            <p class="text-lg font-medium text-gray-900">{{ __('No campaigns yet') }}</p>
            <p class="mt-2 text-gray-500">{{ __('Get started by creating your first outreach campaign.') }}</p>
            <a href="{{ route('business.campaigns.create') }}"
                class="mt-6 inline-flex rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                {{ __('Create campaign') }}
            </a>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($campaigns as $campaign)
                <article class="overflow-hidden rounded-2xl bg-white shadow border border-gray-100 flex flex-col">
                    <div class="aspect-video w-full bg-gray-100 overflow-hidden">
                        @if ($campaign->image)
                            <img src="{{ asset('storage/'.$campaign->image) }}" alt="{{ $campaign->title }}"
                                class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-gray-400 text-sm">{{ __('No image') }}
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <h2 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $campaign->title }}</h2>
                        <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-emerald-700">{{ $campaign->category }}
                        </p>
                        <dl class="mt-3 space-y-1 text-sm text-gray-600">
                            <div class="flex justify-between gap-2">
                                <dt>{{ __('Expires') }}</dt>
                                <dd class="font-medium text-gray-900">
                                    {{ $campaign->expiry_date?->format('M j, Y') }}</dd>
                            </div>
                            <div class="flex justify-between gap-2">
                                <dt>{{ __('Created') }}</dt>
                                <dd class="font-medium text-gray-900">
                                    {{ $campaign->created_at->format('M j, Y') }}</dd>
                            </div>
                        </dl>
                        <div class="mt-4 flex flex-wrap gap-2 border-t border-gray-100 pt-4">
                            <a href="{{ route('business.campaigns.edit', $campaign) }}"
                                class="inline-flex flex-1 justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                {{ __('Edit') }}
                            </a>
                            <form action="{{ route('business.campaigns.destroy', $campaign) }}" method="POST" class="inline flex-1"
                                onsubmit="return confirm(@json(__('Are you sure you want to delete this campaign?')));">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100">
                                    {{ __('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
