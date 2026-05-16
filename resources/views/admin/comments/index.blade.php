@extends('layouts.admin')

@section('title', __('Comment moderation'))

@section('content')
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Comments') }}</h1>
            <p class="text-sm text-slate-400">{{ __('Review flagged content and spam.') }}</p>
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search text…') }}"
                class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
            <select name="status" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
                <option value="">{{ __('All statuses') }}</option>
                <option value="approved" @selected(request('status') === 'approved')>{{ __('Approved') }}</option>
                <option value="pending_review" @selected(request('status') === 'pending_review')>{{ __('Pending') }}</option>
                <option value="hidden" @selected(request('status') === 'hidden')>{{ __('Hidden') }}</option>
                <option value="spam" @selected(request('status') === 'spam')>{{ __('Spam') }}</option>
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Apply') }}</button>
        </form>
    </div>

    <div class="space-y-4">
        @foreach ($comments as $comment)
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-sm text-white">{{ $comment->comment }}</p>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $comment->user?->name }} · {{ $comment->campaign?->title }} · {{ __('Spam score') }}: {{ $comment->spam_score }}
                        </p>
                        <span class="mt-2 inline-block rounded-full bg-slate-800 px-2 py-0.5 text-xs text-slate-300">{{ $comment->moderation_status }}</span>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">@csrf<button class="rounded bg-emerald-600 px-2 py-1 text-white">{{ __('Approve') }}</button></form>
                        <form method="POST" action="{{ route('admin.comments.hide', $comment) }}">@csrf<button class="rounded bg-slate-700 px-2 py-1 text-white">{{ __('Hide') }}</button></form>
                        <form method="POST" action="{{ route('admin.comments.spam', $comment) }}">@csrf<button class="rounded bg-amber-600 px-2 py-1 text-white">{{ __('Spam') }}</button></form>
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" onsubmit="return confirm(@json(__('Delete forever?')));">
                            @csrf @method('DELETE')
                            <button class="rounded bg-rose-700 px-2 py-1 text-white">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $comments->links() }}</div>
@endsection
