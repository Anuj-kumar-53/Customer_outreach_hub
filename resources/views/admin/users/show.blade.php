@extends('layouts.admin')

@section('title', $user->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-400 hover:underline">{{ __('← Back to users') }}</a>
        <h1 class="mt-2 text-2xl font-bold text-white">{{ $user->name }}</h1>
        <p class="text-sm text-slate-400">{{ $user->email }} · {{ $user->role }}</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-5 lg:col-span-2">
            <h2 class="text-sm font-semibold text-slate-200">{{ __('Profile') }}</h2>
            <dl class="mt-4 grid gap-3 text-sm text-slate-300 sm:grid-cols-2">
                <div><dt class="text-slate-500">{{ __('Status') }}</dt>
                    <dd class="font-semibold text-white">{{ $user->account_status ?? 'active' }}</dd>
                </div>
                @if ($user->suspension_reason)
                    <div class="sm:col-span-2"><dt class="text-slate-500">{{ __('Moderation note') }}</dt>
                        <dd class="text-slate-200">{{ $user->suspension_reason }}</dd>
                    </div>
                @endif
                <div><dt class="text-slate-500">{{ __('Reward points') }}</dt>
                    <dd class="font-semibold text-amber-300">{{ $user->reward_points }}</dd>
                </div>
                <div><dt class="text-slate-500">{{ __('Joined') }}</dt>
                    <dd>{{ $user->created_at->format('M j, Y') }}</dd>
                </div>
                @if ($user->business)
                    <div><dt class="text-slate-500">{{ __('Business') }}</dt>
                        <dd>{{ $user->business->business_name }}</dd>
                    </div>
                @endif
            </dl>
            <div class="mt-6 grid gap-3 text-xs text-slate-500 sm:grid-cols-2">
                <p>{{ __('Reports filed') }}: <span class="text-slate-300">{{ $user->submitted_reports_count }}</span></p>
                <p>{{ __('Reports against') }}: <span class="text-slate-300">{{ $user->received_reports_count }}</span></p>
                <p>{{ __('Referrals') }}: <span class="text-slate-300">{{ $user->referrals_given_count }}</span></p>
                <p>{{ __('Comments') }}: <span class="text-slate-300">{{ $user->comments_count }}</span></p>
            </div>
        </div>

        <div class="space-y-4">
            @if (! $user->isAdmin())
                <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4">
                    <h3 class="text-sm font-semibold text-amber-200">{{ __('Suspend') }}</h3>
                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}" class="mt-3 space-y-2">
                        @csrf
                        <textarea name="reason" rows="2" placeholder="{{ __('Optional reason…') }}"
                            class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white"></textarea>
                        <button type="submit" class="w-full rounded-lg bg-amber-600 py-2 text-sm font-semibold text-white">{{ __('Suspend user') }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full rounded-lg border border-slate-600 py-2 text-sm text-slate-200">{{ __('Clear suspension') }}</button>
                    </form>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4">
                    <h3 class="text-sm font-semibold text-rose-200">{{ __('Ban') }}</h3>
                    <form method="POST" action="{{ route('admin.users.ban', $user) }}" class="mt-3 space-y-2">
                        @csrf
                        <textarea name="reason" rows="2" placeholder="{{ __('Optional reason…') }}"
                            class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white"></textarea>
                        <button type="submit" class="w-full rounded-lg bg-rose-600 py-2 text-sm font-semibold text-white">{{ __('Ban user') }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.unban', $user) }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full rounded-lg border border-slate-600 py-2 text-sm text-slate-200">{{ __('Remove ban') }}</button>
                    </form>
                </div>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm(@json(__('Permanently delete this user?')));">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-lg border border-rose-900 bg-rose-950/40 py-2 text-sm font-semibold text-rose-300">{{ __('Delete user (soft)') }}</button>
                </form>
            @else
                <p class="text-sm text-slate-500">{{ __('Administrator accounts cannot be moderated from this panel.') }}</p>
            @endif
        </div>
    </div>
@endsection
