<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Customer')) — {{ config('app.name', 'Customer Outreach Hub') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen text-gray-900 antialiased">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center sm:h-16 py-3 sm:py-0">
                <span class="text-xl font-bold text-sky-700">{{ config('app.name', 'Customer Outreach Hub') }}</span>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <a href="{{ route('customer.dashboard') }}"
                        class="text-sm font-medium {{ request()->routeIs('customer.dashboard') ? 'text-sky-700' : 'text-gray-600 hover:text-sky-700' }}">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('customer.campaign-feed') }}"
                        class="text-sm font-medium {{ request()->routeIs('customer.campaign-feed') ? 'text-sky-700' : 'text-gray-600 hover:text-sky-700' }}">
                        {{ __('Campaign Feed') }}
                    </a>
                    <a href="{{ route('customer.referrals') }}"
                        class="text-sm font-medium {{ request()->routeIs('customer.referrals') ? 'text-sky-700' : 'text-gray-600 hover:text-sky-700' }}">
                        {{ __('My Referrals') }}
                    </a>
                    <a href="{{ route('customer.leaderboard') }}"
                        class="text-sm font-medium {{ request()->routeIs('customer.leaderboard') ? 'text-sky-700' : 'text-gray-600 hover:text-sky-700' }}">
                        {{ __('Leaderboard') }}
                    </a>
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-900"
                        title="{{ __('Reward points') }}">
                        {{ (int) (Auth::user()->reward_points ?? 0) }} {{ __('pts') }}
                    </span>
                    <span class="text-sm text-gray-500 hidden sm:inline">|</span>
                    <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
