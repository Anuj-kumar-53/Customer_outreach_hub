@extends('layouts.admin')

@section('title', __('Campaign moderation'))

@section('content')
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Campaigns') }}</h1>
            <p class="text-sm text-slate-400">{{ __('Remove inappropriate campaigns from the public feed.') }}</p>
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search title…') }}"
                class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
            <select name="moderation" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
                <option value="">{{ __('All') }}</option>
                <option value="active" @selected(request('moderation') === 'active')>{{ __('Active') }}</option>
                <option value="removed" @selected(request('moderation') === 'removed')>{{ __('Removed') }}</option>
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Apply') }}</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/40">
        <table class="min-w-full divide-y divide-slate-800 text-sm">
            <thead class="bg-slate-900 text-left text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">{{ __('Campaign') }}</th>
                    <th class="px-4 py-3">{{ __('Business') }}</th>
                    <th class="px-4 py-3">{{ __('Status') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 text-slate-300">
                @foreach ($campaigns as $c)
                    <tr>
                        <td class="px-4 py-3 font-medium text-white">{{ \Illuminate\Support\Str::limit($c->title, 48) }}</td>
                        <td class="px-4 py-3">{{ $c->business?->business_name }}</td>
                        <td class="px-4 py-3">
                            @php $ms = $c->moderation_status ?? 'active'; @endphp
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $ms === 'active' ? 'bg-emerald-500/15 text-emerald-300' : 'bg-rose-500/15 text-rose-300' }}">{{ $ms }}</span>
                        </td>
                        <td class="px-4 py-3 text-right align-top">
                            @if (($c->moderation_status ?? 'active') === 'active')
                                <form method="POST" action="{{ route('admin.campaigns.remove', $c) }}" class="inline-block max-w-xs space-y-2 text-left" onsubmit="return confirm(@json(__('Remove from public feed?')));">
                                    @csrf
                                    <textarea name="reason" rows="2" required placeholder="{{ __('Reason (required)') }}"
                                        class="w-full rounded border border-slate-700 bg-slate-950 px-2 py-1 text-xs text-white"></textarea>
                                    <button type="submit" class="block w-full rounded bg-rose-600 px-2 py-1 text-xs font-semibold text-white">{{ __('Remove') }}</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.campaigns.restore', $c) }}" class="inline">@csrf<button class="text-emerald-400 hover:underline">{{ __('Restore') }}</button></form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $campaigns->links() }}</div>
@endsection
