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

        {{-- Desktop Toggle Button (Hanya muncul di Desktop) --}}
        <button @click="toggleSidebar()" 
                class="hidden lg:block p-2 rounded hover:bg-gray-300 focus:outline-none transition-colors"
                :class="!sidebarOpen ? 'mx-auto' : ''"
                title="Toggle Sidebar">
            <svg class="w-6 h-6 stroke-current text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Mobile Close Button (Hanya muncul di Mobile) --}}
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
        
        <a href="{{ route('dashboard') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs('dashboard') ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">
            
            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            
            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Dashboard</span>
        </a>

        <a href="{{ route('profile.edit') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs('profile.edit') ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">
            
            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            
            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Profil Saya</span>
        </a>

        <a href="{{ route('booking.index') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs(['booking.index', 'booking.create']) ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">
            
            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            
            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Booking Lapangan</span>
        </a>

        <a href="{{ route('riwayat.index') }}"
           class="flex items-center w-full h-12 px-3 rounded-xl transition-all duration-200 group
           {{ request()->routeIs('riwayat.index') ? 'bg-indigo-500 text-white shadow-md' : 'hover:bg-gray-100 text-gray-600 hover:text-gray-900' }}"
           :class="!sidebarOpen ? 'justify-center' : ''">
            
            <svg class="w-5 h-5 stroke-current shrink-0" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            
            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium whitespace-nowrap">Riwayat</span>
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
                <p class="text-xs text-gray-500">Mahasiswa</p>
            </div>
        </div>   
    </div>

</div>