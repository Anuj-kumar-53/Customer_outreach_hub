@extends('layouts.admin')

@section('title', __('Notifications'))

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Admin notifications') }}</h1>
            <p class="text-sm text-slate-400">{{ __('System alerts such as new user reports.') }}</p>
        </div>
        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
            @csrf
            <button type="submit" class="rounded-lg border border-slate-600 px-4 py-2 text-sm text-slate-200">{{ __('Mark all read') }}</button>
        </form>
    </div>

    <div class="space-y-3">
        @foreach ($notifications as $n)
            @php $read = $n->reads->first(); @endphp
            <div class="flex flex-wrap items-start justify-between gap-3 rounded-2xl border border-slate-800 bg-slate-900/40 p-4 {{ $read ? 'opacity-60' : '' }}">
                <div>
                    <p class="text-sm font-semibold text-white">{{ $n->title }}</p>
                    @if ($n->body)
                        <p class="mt-1 text-sm text-slate-400">{{ $n->body }}</p>
                    @endif
                    <p class="mt-2 text-xs text-slate-500">{{ $n->created_at->diffForHumans() }}</p>
                </div>
                @if (! $read)
                    <form method="POST" action="{{ route('admin.notifications.read', $n) }}">@csrf<button class="text-xs text-indigo-400 hover:underline">{{ __('Mark read') }}</button></form>
                @endif
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
