<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Student Activity Center') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('asset/images/logosac.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100"
    x-data="{ 
        sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen')) ?? true,
        mobileMenuOpen: false,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
        }
    }">

    <div class="min-h-screen bg-gray-100 relative flex">

        <div x-show="mobileMenuOpen" 
             @click="mobileMenuOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden">
        </div>

        <aside class="fixed inset-y-0 left-0 z-50 bg-white border-r border-gray-200 transition-all duration-300 ease-in-out transform lg:translate-x-0"
               :class="{
                   '-translate-x-full': !mobileMenuOpen, /* Mobile: Hidden */
                   'translate-x-0': mobileMenuOpen,      /* Mobile: Show */
                   'w-64': mobileMenuOpen || sidebarOpen, /* Lebar Penuh (Mobile Open / Desktop Open) */
                   'w-20': !mobileMenuOpen && !sidebarOpen /* Lebar Kecil (Desktop Collapsed) */
               }">
            
            @auth
                @if (Auth::user()->role === 'admin')
                    @include('layouts.sidebar-admin')
                @else
                    @include('layouts.sidebar') 
                @endif
            @endauth
        </aside>

        <div class="flex-1 min-h-screen transition-all duration-300 ease-in-out flex flex-col"
             :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">

            <div class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200">
                <div class="flex items-center justify-between px-4 h-16 lg:hidden">
                    <div class="flex items-center gap-3">
                        <button @click="mobileMenuOpen = true" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <span class="font-bold text-gray-700 text-lg">Sport Centre UMY</span>
                    </div>                                    
                </div>

                <div class="hidden lg:block">
                     @include('layouts.navigation')
                </div>
            </div>

            <main class="p-4 md:p-8 flex-1 overflow-x-hidden">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')
</body>
</html>