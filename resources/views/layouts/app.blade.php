<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Student Activity Center') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('asset/images/logosac.png') }}">
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: true }">
        <div class="min-h-screen bg-gray-100">

            @if (Auth::check() && Auth::user()->role === 'admin')
                @include('layouts.sidebar-admin')
            @else
                @include('layouts.sidebar')
            @endif

            <div class="fixed top-0 left-0 right-0 z-40 transition-all duration-300 ease-in-out"
                :class="sidebarOpen ? 'ml-64' : 'ml-20'">
                @include('layouts.navigation')
            </div>

            <div class="transition-all duration-300 ease-in-out pt-16" 
                :class="sidebarOpen ? 'ml-64' : 'ml-20'">

                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-full py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="p-10">
                    {{ $slot ?? '' }} 
                    @yield('content')
                </main>
            </div>
        </div>
        @stack('scripts')

    </body>
</html>