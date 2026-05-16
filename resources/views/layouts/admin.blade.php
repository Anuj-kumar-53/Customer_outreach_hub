<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin')) — {{ config('app.name', 'Customer Outreach Hub') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    @php
        $unreadNotifications = \App\Models\AdminNotification::query()
            ->whereDoesntHave('reads', fn ($q) => $q->where('admin_user_id', auth()->id()))
            ->count();
    @endphp
    <div class="flex min-h-screen">
        <aside class="hidden w-64 shrink-0 border-r border-slate-800 bg-slate-900 lg:block">
            <div class="flex h-16 items-center border-b border-slate-800 px-5">
                <span class="text-lg font-bold tracking-tight text-indigo-400">{{ __('Super Admin') }}</span>
            </div>
            <nav class="space-y-1 px-3 py-4 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}"
                    class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">{{ __('Dashboard') }}</a>
                <a href="{{ route('admin.users.index') }}"
                    class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">{{ __('Users') }}</a>
                <a href="{{ route('admin.businesses.index') }}"
                    class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.businesses.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">{{ __('Businesses') }}</a>
                <a href="{{ route('admin.campaigns.index') }}"
                    class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.campaigns.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">{{ __('Campaigns') }}</a>
                <a href="{{ route('admin.comments.index') }}"
                    class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.comments.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">{{ __('Comments') }}</a>
                <a href="{{ route('admin.reports.index') }}"
                    class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">{{ __('Reports') }}</a>
                <a href="{{ route('admin.notifications.index') }}"
                    class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.notifications.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">{{ __('Notifications') }}</a>
                <a href="{{ route('admin.activity.index') }}"
                    class="block rounded-lg px-3 py-2 {{ request()->routeIs('admin.activity.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">{{ __('Activity log') }}</a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex h-16 items-center justify-between border-b border-slate-800 bg-slate-900/80 px-4 backdrop-blur lg:px-8">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-slate-400 lg:hidden">{{ __('Admin') }}</span>
                    <span class="hidden text-sm text-slate-400 lg:inline">{{ config('app.name', 'Customer Outreach Hub') }}</span>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    @if ($unreadNotifications > 0)
                        <a href="{{ route('admin.notifications.index') }}"
                            class="rounded-full bg-amber-500/15 px-3 py-1 text-xs font-semibold text-amber-300 ring-1 ring-amber-500/40">
                            {{ $unreadNotifications }} {{ __('new') }}
                        </a>
                    @endif
                    <span class="text-slate-300">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-rose-400 hover:text-rose-300">{{ __('Logout') }}</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 bg-slate-950 p-4 lg:p-8">
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-emerald-800 bg-emerald-950/60 px-4 py-3 text-sm text-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-xl border border-rose-800 bg-rose-950/60 px-4 py-3 text-sm text-rose-200">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
