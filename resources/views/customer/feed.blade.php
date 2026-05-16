@extends('layouts.customer')

@section('title', __('Campaign Feed'))

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('Campaign feed') }}</h1>
        <p class="mt-1 text-gray-500">{{ __('Browse active outreach campaigns from businesses. Newest first.') }}</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search + filters (GET query string; work together with pagination) --}}
    <div class="mb-8 rounded-2xl bg-white p-4 sm:p-6 shadow border border-gray-100">
        <form method="GET" action="{{ route('customer.campaign-feed') }}" class="space-y-4">
            <div class="grid gap-4 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-5">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                    <input type="text" name="search" id="search" value="{{ $search }}"
                        placeholder="{{ __('Search by title or category…') }}"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>
                <div class="lg:col-span-4">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Category') }}</label>
                    <select name="category" id="category"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="all" @selected($selectedCategory === 'all')>{{ __('All categories') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected($selectedCategory === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <p class="block text-sm font-medium text-gray-700 mb-1">{{ __('Sort') }}</p>
                    <p class="rounded-lg border border-dashed border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">
                        {{ __('Latest (newest first)') }}
                    </p>
                </div>
                <div class="lg:col-span-12 flex flex-wrap gap-2 pt-2">
                    <button type="submit"
                        class="inline-flex flex-1 min-w-[7rem] justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                        {{ __('Apply') }}
                    </button>
                    <a href="{{ route('customer.campaign-feed') }}"
                        class="inline-flex flex-1 min-w-[7rem] justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        {{ __('Reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    @if ($campaigns->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
            <p class="text-lg font-semibold text-gray-900">{{ __('No campaigns found') }}</p>
            <p class="mt-2 text-gray-500">{{ __('Try adjusting your search or category filter.') }}</p>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($campaigns as $campaign)
                <article
                    class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow border border-gray-100 transition duration-200 hover:shadow-lg hover:-translate-y-0.5">
                    <div class="aspect-video w-full overflow-hidden bg-gray-200">
                        @if ($campaign->image)
                            <img src="{{ asset('storage/'.$campaign->image) }}" alt="{{ $campaign->title }}"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                        @else
                            <div
                                class="flex h-full w-full flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400">
                                <svg class="h-12 w-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-medium">{{ __('No image') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <h2 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $campaign->title }}</h2>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-sky-700">{{ $campaign->category }}
                        </p>
                        <p class="mt-3 text-sm text-gray-600 line-clamp-3 flex-1">{{ $campaign->description }}</p>
                        <dl class="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm text-gray-600">
                            <div class="flex justify-between gap-2">
                                <dt class="text-gray-500">{{ __('Business') }}</dt>
                                <dd class="font-medium text-gray-900 text-right">
                                    <span class="inline-flex flex-wrap items-center justify-end gap-1">
                                        {{ $campaign->business?->business_name ?? __('Unknown') }}
                                        @if ($campaign->business?->verified_at)
                                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-800" title="{{ __('Verified business') }}">{{ __('Verified') }}</span>
                                        @endif
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between gap-2">
                                <dt class="text-gray-500">{{ __('Expires') }}</dt>
                                <dd class="font-medium text-gray-900">{{ $campaign->expiry_date?->format('M j, Y') }}</dd>
                            </div>
                            <div class="flex justify-between gap-2">
                                <dt class="text-gray-500">{{ __('Posted') }}</dt>
                                <dd class="font-medium text-gray-900">{{ $campaign->created_at->format('M j, Y') }}</dd>
                            </div>
                        </dl>

                        @php
                            $referralUrl = route('campaign.public', $campaign).'?'.http_build_query(['ref' => auth()->id()]);
                            $shareText = rawurlencode($campaign->title.' — '.__('Join me on').' '.config('app.name'));
                            $whatsappHref =
                                'https://wa.me/?text='.rawurlencode($campaign->title."\n".$referralUrl);
                            $twitterHref =
                                'https://twitter.com/intent/tweet?text='.$shareText.'&url='.rawurlencode($referralUrl);
                        @endphp

                        <div class="mt-4 rounded-xl border border-sky-100 bg-sky-50/80 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-sky-800">{{ __('Refer & earn') }}</p>
                            <p class="mt-1 break-all text-xs text-gray-600">{{ $referralUrl }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="{{ route('campaign.public', $campaign) }}?{{ http_build_query(['ref' => auth()->id()]) }}"
                                    target="_blank" rel="noopener"
                                    class="inline-flex flex-1 min-w-[8rem] justify-center rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white shadow hover:bg-sky-700">
                                    {{ __('Open / share link') }}
                                </a>
                                <button type="button" data-copy-url="{{ $referralUrl }}"
                                    class="copy-referral-url inline-flex flex-1 min-w-[8rem] justify-center rounded-lg border border-sky-200 bg-white px-3 py-2 text-xs font-semibold text-sky-800 hover:bg-sky-100">
                                    {{ __('Copy link') }}
                                </button>
                                <a href="{{ $whatsappHref }}" target="_blank" rel="noopener"
                                    class="inline-flex flex-1 min-w-[8rem] justify-center rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-800 hover:bg-emerald-100">
                                    {{ __('WhatsApp') }}
                                </a>
                                <a href="{{ $twitterHref }}" target="_blank" rel="noopener"
                                    class="inline-flex flex-1 min-w-[8rem] justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-50">
                                    {{ __('X / Twitter') }}
                                </a>
                            </div>
                            <p class="copy-referral-toast mt-2 hidden text-xs font-medium text-emerald-700" aria-live="polite"></p>
                        </div>

                        @php
                            $hasLiked = $campaign->likes->isNotEmpty();
                            $hasSaved = $campaign->saves->isNotEmpty();
                        @endphp

                        {{-- Likes + Saves --}}
                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800">
                                    {{ $campaign->likes_count }} {{ \Illuminate\Support\Str::plural('Like', $campaign->likes_count) }}
                                </span>

                                @if ($hasLiked)
                                    <form method="POST" action="{{ route('campaigns.unlike', $campaign) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100">
                                            {{ __('Unlike') }}
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('campaigns.like', $campaign) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-100">
                                            {{ __('Like') }}
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div>
                                @if ($hasSaved)
                                    <form method="POST" action="{{ route('campaigns.unsave', $campaign) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100">
                                            {{ __('Unsave') }}
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('campaigns.save', $campaign) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            {{ __('Save') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- Comments --}}
                        <div class="mt-5 border-t border-gray-100 pt-4">
                            <h3 class="text-sm font-semibold text-gray-900">{{ __('Comments') }}</h3>

                            <form method="POST" action="{{ route('campaigns.comments.store', $campaign) }}" class="mt-3">
                                @csrf
                                <div class="space-y-2">
                                    <textarea name="comment" rows="3" maxlength="500"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                        placeholder="{{ __('Write a comment…') }}">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <p class="text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-2 flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center rounded-lg bg-gray-900 px-3 py-2 text-sm font-semibold text-white hover:bg-black">
                                        {{ __('Post') }}
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 border-t border-gray-100 pt-4">
                                <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ __('Report a problem') }}</h4>
                                <form method="POST" action="{{ route('customer.reports.store') }}" class="mt-2 space-y-2">
                                    @csrf
                                    <input type="hidden" name="reported_campaign_id" value="{{ $campaign->id }}">
                                    <select name="category" class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                        <option value="spam">{{ __('Spam / misleading') }}</option>
                                        <option value="abuse">{{ __('Abuse / harassment') }}</option>
                                        <option value="inappropriate_content">{{ __('Inappropriate content') }}</option>
                                        <option value="fake_business">{{ __('Misleading business') }}</option>
                                        <option value="other">{{ __('Other') }}</option>
                                    </select>
                                    <textarea name="description" rows="2" maxlength="5000" required
                                        class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                        placeholder="{{ __('Describe the issue…') }}">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                    @error('category')
                                        <p class="text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                    @error('target')
                                        <p class="text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                    <button type="submit"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-50">{{ __('Submit report') }}</button>
                                </form>
                            </div>

                            <div class="mt-4 space-y-3">
                                @forelse ($campaign->comments->sortByDesc('created_at') as $comment)
                                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm text-gray-900">{{ $comment->comment }}</p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    <span class="font-semibold text-gray-700">{{ $comment->user?->name ?? __('Unknown') }}</span>
                                                    · {{ $comment->created_at->diffForHumans() }}
                                                </p>
                                            </div>

                                            @if ($comment->user_id === auth()->id())
                                                <form method="POST" action="{{ route('campaigns.comments.destroy', $comment) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-semibold text-rose-700 hover:underline">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">{{ __('No comments yet. Be the first!') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $campaigns->links() }}
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('click', async function (e) {
                const btn = e.target.closest('.copy-referral-url');
                if (!btn) return;
                const text = btn.getAttribute('data-copy-url');
                const wrap = btn.closest('.rounded-xl');
                const toast = wrap ? wrap.querySelector('.copy-referral-toast') : null;
                try {
                    await navigator.clipboard.writeText(text);
                    if (toast) {
                        toast.textContent = @json(__('Link copied to clipboard.'));
                        toast.classList.remove('hidden');
                        setTimeout(() => toast.classList.add('hidden'), 2500);
                    }
                } catch (err) {
                    if (toast) {
                        toast.textContent = @json(__('Could not copy — select the link manually.'));
                        toast.classList.remove('hidden');
                    }
                }
            });
        </script>
    @endpush
@endsection
