<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Student Activity Center') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('asset/images/logosac.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100"
    x-data="{
        sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen')) ?? true,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
        }
    }">

    <div class="min-h-screen bg-gray-100">

        <aside class="bg-white border-r fixed inset-y-0 left-0 flex flex-col z-50 overflow-hidden"
            :class="sidebarOpen ? 'w-64' : 'w-16'">
            @auth
                @if (Auth::user()->role === 'admin')
                    @include('layouts.sidebar-admin')
                @else
                    @include('layouts.sidebar') 
                @endif
            @endauth
        </aside>

        <div class="fixed top-0 left-0 right-0 z-40"
            :class="sidebarOpen ? 'ml-64' : 'ml-16'">
            @include('layouts.navigation')
        </div>

        <div class="pt-20"
            :class="sidebarOpen ? 'ml-64' : 'ml-16'">

            <main class="p-10">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')
</body>
</html>