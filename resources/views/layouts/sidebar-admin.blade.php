<div class="flex flex-col h-full overflow-hidden">

    <div class="flex items-center justify-between p-4">
        <img x-show="sidebarOpen"
            src="{{ asset('asset/images/logo-umy-sac-transparan-01.png') }}" 
            alt="Sport Center UMY Logo" class="w-36 h-auto">

        <button @click="toggleSidebar()"
            class="p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <div x-show="sidebarOpen" class="mt-4 text-center">
        <p class="text-lg font-semibold text-gray-800">
            {{ Auth::user()->name }}
        </p>
    </div>

    <nav class="mt-4 px-2 flex-1 relative">
        <ul class="space-y-2 text-gray-700">

            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center py-2 rounded text-sm 
                   {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">

                    <img src="{{ asset('asset/images/image.png') }}" 
                         class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen">Dashboard Admin</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.booking.index') }}"
                   class="flex items-center py-2 rounded text-sm 
                   {{ request()->routeIs(['admin.booking.index','admin.booking.update_status']) ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">

                    <img src="{{ asset('asset/images/kalender.png') }}" 
                         class="w-4 h-4 {{ request()->routeIs(['admin.booking.index','admin.booking.update_status']) ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen">Kelola Booking</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.lapangan.index') }}"
                   class="flex items-center py-2 rounded text-sm 
                   {{ request()->routeIs(['admin.lapangan.index','admin.lapangan.create','admin.lapangan.edit']) ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">

                    <img src="{{ asset('asset/images/person.png') }}" 
                         class="w-4 h-4 {{ request()->routeIs(['admin.lapangan.index','admin.lapangan.create','admin.lapangan.edit']) ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen">Kelola Lapangan</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.jadwal.index') }}"
                   class="flex items-center py-2 rounded text-sm 
                   {{ request()->routeIs('admin.jadwal.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">

                    <img src="{{ asset('asset/images/jadwal.png') }}" 
                         class="w-4 h-4 {{ request()->routeIs('admin.jadwal.index') ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen">Jadwal Lapangan</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.riwayat.index') }}"
                   class="flex items-center py-2 rounded text-sm 
                   {{ request()->routeIs('admin.riwayat.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">

                    <img src="{{ asset('asset/images/riwayat.png') }}" 
                         class="w-4 h-4 {{ request()->routeIs('admin.riwayat.index') ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen">Riwayat</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.kelolapengguna.index') }}"
                   class="flex items-center py-2 rounded text-sm 
                   {{ request()->routeIs('admin.kelolapengguna.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}"
                   :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">

                    <img src="{{ asset('asset/images/person.png') }}" 
                         class="w-4 h-4 {{ request()->routeIs('admin.kelolapengguna.index') ? 'brightness-0 invert' : '' }}">
                    <span x-show="sidebarOpen">Kelola Pengguna</span>
                </a>
            </li>

            <li class="absolute bottom-0 left-0 w-full" :class="sidebarOpen ? 'px-4' : 'px-2'">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full py-2 rounded text-sm hover:bg-red-500 hover:text-white text-gray-700"
                        :class="sidebarOpen ? 'space-x-3 px-4' : 'justify-center'">

                        <img src="{{ asset('asset/images/keluar.png') }}" class="w-4 h-4">
                        <span x-show="sidebarOpen">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>
