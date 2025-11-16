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
                <a href="{{ route('admin.dashboard') }}"
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                    
                    <img src="{{ asset('asset/images/image.png') }}" alt="home" class="w-4 h-4 filter {{ request()->routeIs('admin.dashboard') ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen" x-transition>Dashboard Admin</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.booking.index') }}" 
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs(['admin.booking.index', 'admin.booking.update_status']) ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                    
                    <img src="{{ asset('asset/images/kalender.png') }}" alt="fields" class="w-4 h-4 filter {{ request()->routeIs(['admin.booking.index', 'admin.booking.update_status']) ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen" x-transition>Kelola Booking</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.lapangan.index') }}" 
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs(['admin.lapangan.index', 'admin.lapangan.create', 'admin.lapangan.edit']) ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">

                    <img src="{{ asset('asset/images/person.png') }}" alt="users" class="w-4 h-4 filter {{ request()->routeIs(['admin.lapangan.index', 'admin.lapangan.create', 'admin.lapangan.edit']) ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen" x-transition>Kelola Lapangan</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.jadwal.index') }}"
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs('admin.jadwal.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                    
                    <img src="{{ asset('asset/images/jadwal.png') }}" alt="scheduled" class="w-4 h-4 filter {{ request()->routeIs('admin.jadwal.index') ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen" x-transition>Jadwal Lapangan</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.riwayat.index') }}" 
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs('admin.riwayat.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                    
                    <img src="{{ asset('asset/images/riwayat.png') }}" alt="history" class="w-4 h-4 filter {{ request()->routeIs('admin.riwayat.index') ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen" x-transition>Riwayat</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.kelolapengguna.index') }}" 
                   class="block py-2 flex items-center rounded text-sm 
                   {{ request()->routeIs('admin.kelolapengguna.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">
                    
                    <img src="{{ asset('asset/images/person.png') }}" alt="users" class="w-4 h-4 filter {{ request()->routeIs('admin.kelolapengguna.index') ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen" x-transition>Kelola Pengguna</span>
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