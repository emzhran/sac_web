<div class="flex flex-col h-full bg-white text-gray-700 transition-all duration-300 shadow-xl rounded-2xl overflow-hidden"
     :class="sidebarOpen ? 'w-64' : 'w-20'">

    <style>
        .overflow-y-auto::-webkit-scrollbar {
            display: none;
        }
    </style>

    {{-- HEADER SECTION --}}
    <div class="flex items-center justify-between p-4 h-20 shrink-0">
        
        {{-- Logo --}}
        <div x-show="sidebarOpen" class="flex-1 flex justify-center transition-opacity duration-200">
            <img src="{{ asset('asset/images/logo-umy-sac-transparan-01.png') }}" 
                 alt="SAC Logo" class="h-10 w-auto object-contain">
        </div>

        {{-- Desktop Toggle Button --}}
        <button @click="toggleSidebar()" 
                class="hidden lg:block p-2 rounded hover:bg-gray-300 focus:outline-none transition-colors"
                :class="!sidebarOpen ? 'mx-auto' : ''"
                title="Toggle Sidebar">
            <svg class="w-6 h-6 stroke-current text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Mobile Close Button --}}
        <button @click="mobileMenuOpen = false" 
                class="lg:hidden p-2 rounded hover:bg-gray-100 text-gray-500 focus:outline-none ml-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- MENU LINKS --}}
    <div class="w-full px-3 mt-2 flex-1 overflow-y-auto overflow-x-hidden space-y-1"
         style="scrollbar-width: none; -ms-overflow-style: none;">
        
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">
            
            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            
            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Dashboard</span>
        </a>

        <a href="{{ route('admin.booking.index') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs(['admin.booking.index','admin.booking.update_status']) ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">
            
            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
            
            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Kelola Booking</span>
        </a>

        <a href="{{ route('admin.lapangan.index') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs(['admin.lapangan.index','admin.lapangan.create','admin.lapangan.edit']) ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">

            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Kelola Lapangan</span>
        </a>

        <a href="{{ route('admin.jadwal.index') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.jadwal.index') ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">

            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>

            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Jadwal Lapangan</span>
        </a>

        <a href="{{ route('admin.riwayat.index') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.riwayat.index') ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">

            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Riwayat</span>
        </a>

        <a href="{{ route('admin.kelolapengguna.index') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.kelolapengguna.index') ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">

            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>

            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Kelola Pengguna</span>
        </a>
    </div>

    {{-- FOOTER / USER SECTION --}}
    <div class="mt-auto w-full px-3 pb-4 pt-4 space-y-1 border-t border-gray-200">
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center w-full h-12 px-3 rounded-xl hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-all duration-200"
                    :class="!sidebarOpen ? 'justify-center' : ''">
                
                <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>

                <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Log out</span>
            </button>
        </form>

        <div x-show="sidebarOpen" class="flex items-center gap-3 px-3 py-3 mt-2 rounded-xl bg-indigo-50">
            <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">Admin</p>
            </div>
        </div>   
    </div>

</div>