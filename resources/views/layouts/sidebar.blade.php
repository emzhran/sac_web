<aside :class="sidebarOpen ? 'w-64 p-6' : 'w-20 p-4'" 
       class="bg-white shadow-md fixed inset-y-0 left-0 flex flex-col transition-all duration-300 ease-in-out z-50">
    
    <div class="flex items-center" :class="sidebarOpen ? 'justify-between' : 'justify-center'">
        <img x-show="sidebarOpen" x-transition 
             src="{{ asset('asset/images/logo-umy-sac-transparan-01.png') }}" 
             alt="Sport Center UMY Logo" class="w-36 h-auto">
        
        <button @click="sidebarOpen = !sidebarOpen" 
                class="p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <div x-show="sidebarOpen" x-transition class="mt-4 text-center">
        <p class="text-lg font-semibold text-gray-800">
            {{ Auth::user()->name }}
        </p>
    </div>

    <nav class="mt-8 flex-1 relative">
        <ul class="space-y-2 text-gray-700"> 
            
            <li>
                <a href="{{ route('dashboard') }}"
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                    
                    <img src="{{ asset('asset/images/image.png') }}" alt="home" class="w-4 h-4">
                    <span x-show="sidebarOpen" x-transition>Dashboard</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('profile.edit') }}"
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs('profile.edit') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                    
                    <img src="{{ asset('asset/images/person.png') }}" alt="Profil" class="w-4 h-4">
                    <span x-show="sidebarOpen" x-transition>Profil Saya</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('booking.index') }}"
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs(['booking.index', 'booking.create']) ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                    
                    <img src="{{ asset('asset/images/kalender.png') }}" alt="calendar" class="w-4 h-4">
                    <span x-show="sidebarOpen" x-transition>Booking Lapangan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('riwayat.index') }}" 
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs('riwayat.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                    
                    <img src="{{ asset('asset/images/riwayat.png') }}" alt="history" class="w-4 h-4">
                    <span x-show="sidebarOpen" x-transition>Riwayat</span>
                </a>
            </li>
            
            <li class="absolute bottom-0 left-0 w-full" :class="sidebarOpen ? 'px-6' : 'px-4'">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="block w-full py-2 flex items-center rounded text-sm hover:bg-red-500 hover:text-white transition text-gray-700"
                            :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                        
                        <img src="{{ asset('asset/images/keluar.png') }}" alt="out" class="w-4 h-4">
                        <span x-show="sidebarOpen" x-transition>Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>