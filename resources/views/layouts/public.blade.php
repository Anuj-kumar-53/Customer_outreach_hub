<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Campaign')) — {{ config('app.name', 'Customer Outreach Hub') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 min-h-screen text-gray-900 antialiased">
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-4 sm:px-6">
            <a href="{{ url('/') }}" class="text-lg font-bold text-sky-700">{{ config('app.name', 'Customer Outreach Hub') }}</a>
            <div class="flex flex-wrap items-center gap-3 text-sm font-medium">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-sky-700">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-sky-700">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}"
                        class="rounded-lg bg-sky-600 px-3 py-2 text-white shadow hover:bg-sky-700">{{ __('Register') }}</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
