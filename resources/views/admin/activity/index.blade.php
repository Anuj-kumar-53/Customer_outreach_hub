@extends('layouts.admin')

@section('title', __('Activity log'))

@section('content')
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Admin activity log') }}</h1>
            <p class="text-sm text-slate-400">{{ __('Audit trail of moderation actions.') }}</p>
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search action…') }}"
                class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
            <input type="number" name="admin_id" value="{{ request('admin_id') }}" placeholder="{{ __('Admin ID') }}"
                class="w-28 rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Apply') }}</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/40">
        <table class="min-w-full divide-y divide-slate-800 text-sm">
            <thead class="bg-slate-900 text-left text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">{{ __('When') }}</th>
                    <th class="px-4 py-3">{{ __('Admin') }}</th>
                    <th class="px-4 py-3">{{ __('Action') }}</th>
                    <th class="px-4 py-3">{{ __('Description') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 text-slate-300">
                @foreach ($logs as $log)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-xs">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ $log->admin?->email }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-indigo-300">{{ $log->action }}</td>
                        <td class="px-4 py-3">{{ $log->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
@endsection
