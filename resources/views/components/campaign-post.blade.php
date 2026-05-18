@props(['campaign'])

@php
    $hasLiked = $campaign->likes->isNotEmpty();
    $hasSaved = $campaign->saves->isNotEmpty();
    $referralUrl = route('campaign.public', $campaign).'?'.http_build_query(['ref' => auth()->id()]);
    $shareText = rawurlencode($campaign->title.' — '.__('Join me on').' '.config('app.name'));
    $whatsappHref = 'https://wa.me/?text='.rawurlencode($campaign->title."\n".$referralUrl);
    $twitterHref = 'https://twitter.com/intent/tweet?text='.$shareText.'&url='.rawurlencode($referralUrl);
    $businessName = $campaign->business?->business_name ?? __('Unknown');
    $initials = strtoupper(substr($businessName, 0, 1));
@endphp

<article class="campaign-post bg-white rounded-2xl shadow border border-gray-100 overflow-hidden mb-6 flex flex-col h-full transition-shadow hover:shadow-md" data-campaign-id="{{ $campaign->id }}">
    <!-- Post Header -->
    <div class="p-4 flex items-start justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-sky-500 to-emerald-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                {{ $initials }}
            </div>
            <div>
                <h3 class="font-bold text-gray-900 leading-tight flex items-center gap-1">
                    {{ $businessName }}
                    @if ($campaign->business?->verified_at)
                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    @endif
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $campaign->created_at->diffForHumans() }} &bull; {{ $campaign->category }}
                </p>
            </div>
        </div>
        
        <!-- 3-dot menu -->
        <div class="relative">
            <button type="button" class="post-menu-btn p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
            </button>
            <div class="post-menu-dropdown hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-10">
                <button type="button" class="report-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                    {{ __('Report post') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Report Form (Hidden initially) -->
    <div class="report-form-container hidden px-4 pb-3 border-b border-gray-100">
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm">
            <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">{{ __('Report a problem') }}</h4>
            <form method="POST" action="{{ route('customer.reports.store') }}" class="report-form space-y-2">
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
                    placeholder="{{ __('Describe the issue…') }}"></textarea>
                <div class="flex gap-2 justify-end mt-2">
                    <button type="button" class="cancel-report-btn px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-200 rounded-lg">{{ __('Cancel') }}</button>
                    <button type="submit" class="rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-black">{{ __('Submit report') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Description -->
    <div class="px-4 pb-3">
        <h2 class="text-base font-semibold text-gray-900 mb-1">{{ $campaign->title }}</h2>
        <div class="text-sm text-gray-800 break-words">
            <div class="post-description line-clamp-2 transition-all duration-300">
                {{ $campaign->description }}
            </div>
            @if (strlen($campaign->description) > 100)
                <button type="button" class="read-more-btn text-gray-500 hover:text-gray-800 font-medium text-sm mt-1 focus:outline-none">
                    {{ __('...more') }}
                </button>
            @endif
        </div>
    </div>

    <!-- Image -->
    <div class="w-full bg-gray-100 flex items-center justify-center border-t border-b border-gray-50">
        @if ($campaign->image)
            <img src="{{ asset('storage/'.$campaign->image) }}" alt="{{ $campaign->title }}"
                class="w-full h-64 sm:h-72 object-cover">
        @else
            <div class="flex h-64 sm:h-72 w-full flex-col items-center justify-center bg-gradient-to-br from-gray-50 to-gray-200 text-gray-400">
                <svg class="h-16 w-16 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
    </div>
    
    <!-- Stats Row -->
    <div class="px-4 py-2 flex items-center justify-between text-[13px] text-gray-500 border-b border-gray-100 mt-auto">
        <div class="flex items-center gap-1">
            <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-sky-100 text-sky-600">
                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path></svg>
            </span>
            <span class="like-count" data-count="{{ $campaign->likes_count }}">
                {{ $campaign->likes_count }} {{ \Illuminate\Support\Str::plural('Like', $campaign->likes_count) }}
            </span>
        </div>
        <div class="flex items-center gap-3">
            <span class="comment-count-text">{{ $campaign->comments->count() }} {{ \Illuminate\Support\Str::plural('Comment', $campaign->comments->count()) }}</span>
            <span class="save-count-text">{{ $campaign->saves->count() }} {{ \Illuminate\Support\Str::plural('Save', $campaign->saves->count()) }}</span>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="px-2 py-1 flex items-center justify-between gap-1">
        <!-- Like -->
        <form class="like-form flex-1" action="{{ $hasLiked ? route('campaigns.unlike', $campaign) : route('campaigns.like', $campaign) }}" method="POST" data-liked="{{ $hasLiked ? 'true' : 'false' }}">
            @csrf
            @if($hasLiked) @method('DELETE') @endif
            <button type="submit" class="like-btn w-full py-3 rounded-lg flex items-center justify-center gap-2 text-sm font-semibold transition-colors hover:bg-gray-100 {{ $hasLiked ? 'text-sky-600' : 'text-gray-500 hover:text-gray-700' }}">
                <svg class="w-5 h-5 {{ $hasLiked ? 'fill-current' : 'fill-none stroke-current stroke-2' }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg>
                <span>{{ __('Like') }}</span>
            </button>
        </form>

        <!-- Comment -->
        <button type="button" class="toggle-comment-btn flex-1 py-3 rounded-lg flex items-center justify-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <span>{{ __('Comment') }}</span>
        </button>

        <!-- Save -->
        <form class="save-form flex-1" action="{{ $hasSaved ? route('campaigns.unsave', $campaign) : route('campaigns.save', $campaign) }}" method="POST" data-saved="{{ $hasSaved ? 'true' : 'false' }}">
            @csrf
            @if($hasSaved) @method('DELETE') @endif
            <button type="submit" class="save-btn w-full py-3 rounded-lg flex items-center justify-center gap-2 text-sm font-semibold transition-colors hover:bg-gray-100 {{ $hasSaved ? 'text-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                <svg class="w-5 h-5 {{ $hasSaved ? 'fill-current' : 'fill-none stroke-current stroke-2' }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                <span>{{ __('Save') }}</span>
            </button>
        </form>

        <!-- Share -->
        <div class="relative flex-1">
            <button type="button" class="share-toggle-btn w-full py-3 rounded-lg flex items-center justify-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                <span>{{ __('Share') }}</span>
            </button>
            <div class="share-dropdown hidden absolute bottom-full mb-2 right-0 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-10">
                <p class="px-4 py-1 text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('Share & Earn') }}</p>
                <button type="button" data-copy-url="{{ $referralUrl }}" class="copy-referral-btn w-full text-left px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-3 transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    {{ __('Copy link') }}
                </button>
                <a href="{{ $whatsappHref }}" target="_blank" rel="noopener" class="w-full text-left px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 flex items-center gap-3 transition-colors">
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                    {{ __('WhatsApp') }}
                </a>
                <a href="{{ $twitterHref }}" target="_blank" rel="noopener" class="w-full text-left px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 flex items-center gap-3 transition-colors">
                    <svg class="w-4 h-4 text-gray-900" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
                    {{ __('X / Twitter') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-section hidden bg-gray-50/50 px-4 py-4 border-t border-gray-100">
        <form class="comment-form flex gap-3" action="{{ route('campaigns.comments.store', $campaign) }}" method="POST">
            @csrf
            <div class="w-8 h-8 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center text-gray-500 font-semibold text-xs border border-gray-300">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 relative">
                <input type="text" name="comment" required
                    class="block w-full rounded-full border-gray-300 bg-white shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-2 pl-4 pr-12 transition-colors"
                    placeholder="{{ __('Write a comment…') }}">
                <button type="submit" class="absolute right-1.5 top-1.5 p-1 rounded-full text-sky-600 hover:bg-sky-50 focus:outline-none transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
        </form>

        <div class="comments-list mt-4 space-y-3">
            @forelse ($campaign->comments->sortByDesc('created_at') as $comment)
                <div class="comment-item flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center text-gray-500 font-semibold text-xs border border-gray-300 mt-0.5">
                        {{ strtoupper(substr($comment->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl rounded-tl-none px-3.5 py-2 inline-block">
                            <p class="text-[13px] font-semibold text-gray-900">{{ $comment->user?->name ?? __('Unknown') }}</p>
                            <p class="text-sm text-gray-800 mt-0.5">{{ $comment->comment }}</p>
                        </div>
                        <div class="flex items-center gap-3 mt-1 px-1">
                            <span class="text-[11px] font-medium text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            @if ($comment->user_id === auth()->id())
                                <form method="POST" action="{{ route('campaigns.comments.destroy', $comment) }}" class="delete-comment-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] font-medium text-gray-500 hover:text-rose-600 transition-colors">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="no-comments-text text-sm text-center text-gray-500 py-2">{{ __('No comments yet. Be the first!') }}</p>
            @endforelse
        </div>
    </div>
</article>
