@extends('layouts.admin')

@section('title', __('Businesses'))

@section('content')
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Business verification') }}</h1>
            <p class="text-sm text-slate-400">{{ __('Verify genuine businesses to show a trust badge on campaigns.') }}</p>
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search name…') }}"
                class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
            <select name="verified" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
                <option value="">{{ __('All') }}</option>
                <option value="1" @selected(request('verified') === '1')>{{ __('Verified') }}</option>
                <option value="0" @selected(request('verified') === '0')>{{ __('Unverified') }}</option>
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Apply') }}</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/40">
        <table class="min-w-full divide-y divide-slate-800 text-sm">
            <thead class="bg-slate-900 text-left text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">{{ __('Business') }}</th>
                    <th class="px-4 py-3">{{ __('Owner') }}</th>
                    <th class="px-4 py-3">{{ __('Badge') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 text-slate-300">
                @foreach ($businesses as $b)
                    <tr>
                        <td class="px-4 py-3 font-medium text-white">{{ $b->business_name }}</td>
                        <td class="px-4 py-3">{{ $b->user?->email }}</td>
                        <td class="px-4 py-3">
                            @if ($b->verified_at)
                                <span class="rounded-full bg-emerald-500/15 px-2 py-0.5 text-xs font-semibold text-emerald-300">{{ __('Verified') }}</span>
                            @else
                                <span class="rounded-full bg-slate-700 px-2 py-0.5 text-xs text-slate-400">{{ __('Unverified') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @if (! $b->verified_at)
                                <form method="POST" action="{{ route('admin.businesses.verify', $b) }}" class="inline">@csrf<button class="text-emerald-400 hover:underline">{{ __('Verify') }}</button></form>
                            @else
                                <form method="POST" action="{{ route('admin.businesses.unverify', $b) }}" class="inline">@csrf<button class="text-amber-400 hover:underline">{{ __('Unverify') }}</button></form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $businesses->links() }}</div>
@endsection
