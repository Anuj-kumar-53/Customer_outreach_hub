@extends('layouts.admin')

@section('title', __('Users'))

@section('content')
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('Users') }}</h1>
            <p class="text-sm text-slate-400">{{ __('Search, filter, and moderate accounts.') }}</p>
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search…') }}"
                class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white placeholder:text-slate-500">
            <select name="role" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
                <option value="">{{ __('All roles') }}</option>
                <option value="admin" @selected(request('role') === 'admin')>{{ __('Admin') }}</option>
                <option value="business" @selected(request('role') === 'business')>{{ __('Business') }}</option>
                <option value="customer" @selected(request('role') === 'customer')>{{ __('Customer') }}</option>
            </select>
            <select name="status" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-white">
                <option value="">{{ __('All statuses') }}</option>
                <option value="active" @selected(request('status') === 'active')>{{ __('Active') }}</option>
                <option value="suspended" @selected(request('status') === 'suspended')>{{ __('Suspended') }}</option>
                <option value="banned" @selected(request('status') === 'banned')>{{ __('Banned') }}</option>
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">{{ __('Apply') }}</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/40">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-900 text-left text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('ID') }}</th>
                        <th class="px-4 py-3">{{ __('Name') }}</th>
                        <th class="px-4 py-3">{{ __('Email') }}</th>
                        <th class="px-4 py-3">{{ __('Role') }}</th>
                        <th class="px-4 py-3">{{ __('Status') }}</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-4 py-3 font-mono text-xs">{{ $user->id }}</td>
                            <td class="px-4 py-3 font-medium text-white">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-slate-800 px-2 py-0.5 text-xs">{{ $user->role }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @php $st = $user->account_status ?? 'active'; @endphp
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs font-semibold',
                                    'bg-emerald-500/15 text-emerald-300' => $st === 'active',
                                    'bg-amber-500/15 text-amber-300' => $st === 'suspended',
                                    'bg-rose-500/15 text-rose-300' => $st === 'banned',
                                ])>{{ $st }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-400 hover:underline">{{ __('View') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
@endsection
