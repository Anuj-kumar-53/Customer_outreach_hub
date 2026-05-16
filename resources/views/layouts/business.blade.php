<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('Business')) — {{ config('app.name', 'Customer Outreach Hub') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen text-gray-900 antialiased">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center sm:h-16 py-3 sm:py-0">
                <span class="text-xl font-bold text-emerald-700">{{ config('app.name', 'Customer Outreach Hub') }}</span>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <a href="{{ route('business.dashboard') }}"
                        class="text-sm font-medium {{ request()->routeIs('business.dashboard') ? 'text-emerald-700' : 'text-gray-600 hover:text-emerald-700' }}">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('business.campaigns.index') }}"
                        class="text-sm font-medium {{ request()->routeIs('business.campaigns.index') || request()->routeIs('business.campaigns.edit') ? 'text-emerald-700' : 'text-gray-600 hover:text-emerald-700' }}">
                        {{ __('View Campaigns') }}
                    </a>
                    <a href="{{ route('business.campaigns.create') }}"
                        class="text-sm font-medium {{ request()->routeIs('business.campaigns.create') ? 'text-emerald-700' : 'text-gray-600 hover:text-emerald-700' }}">
                        {{ __('Create Campaign') }}
                    </a>
                    <a href="{{ route('business.analytics') }}"
                        class="text-sm font-medium {{ request()->routeIs('business.analytics') ? 'text-emerald-700' : 'text-gray-600 hover:text-emerald-700' }}">
                        {{ __('Analytics') }}
                    </a>
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
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
