@extends('layouts.admin')

@section('title', __('Report #').$report->id)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.reports.index') }}" class="text-sm text-indigo-400 hover:underline">{{ __('← Reports') }}</a>
        <h1 class="mt-2 text-2xl font-bold text-white">{{ __('Report #').$report->id }}</h1>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-5 text-sm text-slate-300">
            <h2 class="text-sm font-semibold text-slate-200">{{ __('Details') }}</h2>
            <dl class="mt-4 space-y-2">
                <div><dt class="text-slate-500">{{ __('Category') }}</dt><dd class="text-white">{{ $report->category }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Status') }}</dt><dd>{{ $report->status }}</dd></div>
                <div><dt class="text-slate-500">{{ __('Reporter') }}</dt><dd>{{ $report->reporter?->email }}</dd></div>
                @if ($report->reportedUser)
                    <div><dt class="text-slate-500">{{ __('Reported user') }}</dt><dd>{{ $report->reportedUser->email }}</dd></div>
                @endif
                @if ($report->reportedCampaign)
                    <div><dt class="text-slate-500">{{ __('Campaign') }}</dt><dd>{{ $report->reportedCampaign->title }}</dd></div>
                @endif
                @if ($report->reportedComment)
                    <div><dt class="text-slate-500">{{ __('Comment') }}</dt><dd class="text-white">{{ $report->reportedComment->comment }}</dd></div>
                @endif
            </dl>
            <p class="mt-4 text-slate-200">{{ $report->description }}</p>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-5">
            <h2 class="text-sm font-semibold text-slate-200">{{ __('Update status') }}</h2>
            <form method="POST" action="{{ route('admin.reports.status', $report) }}" class="mt-4 space-y-3">
                @csrf
                <select name="status" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white">
                    <option value="open" @selected($report->status === 'open')>{{ __('Open') }}</option>
                    <option value="reviewing" @selected($report->status === 'reviewing')>{{ __('Reviewing') }}</option>
                    <option value="resolved" @selected($report->status === 'resolved')>{{ __('Resolved') }}</option>
                    <option value="dismissed" @selected($report->status === 'dismissed')>{{ __('Dismissed') }}</option>
                </select>
                <textarea name="resolution_notes" rows="4" class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-white" placeholder="{{ __('Internal notes…') }}">{{ old('resolution_notes', $report->resolution_notes) }}</textarea>
                <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2 text-sm font-semibold text-white">{{ __('Save') }}</button>
            </form>
        </div>
    </div>
@endsection
