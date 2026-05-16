@extends('layouts.admin')

@section('title', __('Reports'))

@section('content')
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('User reports') }}</h1>
            <p class="text-sm text-slate-400">{{ __('Triage abuse, spam, and policy issues.') }}</p>
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            <select name="status" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
                <option value="">{{ __('All statuses') }}</option>
                <option value="open" @selected(request('status') === 'open')>{{ __('Open') }}</option>
                <option value="reviewing" @selected(request('status') === 'reviewing')>{{ __('Reviewing') }}</option>
                <option value="resolved" @selected(request('status') === 'resolved')>{{ __('Resolved') }}</option>
                <option value="dismissed" @selected(request('status') === 'dismissed')>{{ __('Dismissed') }}</option>
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Apply') }}</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/40">
        <table class="min-w-full divide-y divide-slate-800 text-sm">
            <thead class="bg-slate-900 text-left text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">{{ __('Category') }}</th>
                    <th class="px-4 py-3">{{ __('Status') }}</th>
                    <th class="px-4 py-3">{{ __('Reporter') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 text-slate-300">
                @foreach ($reports as $r)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $r->id }}</td>
                        <td class="px-4 py-3">{{ $r->category }}</td>
                        <td class="px-4 py-3">{{ $r->status }}</td>
                        <td class="px-4 py-3">{{ $r->reporter?->email }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.reports.show', $r) }}" class="text-indigo-400 hover:underline">{{ __('Review') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $reports->links() }}</div>
@endsection
