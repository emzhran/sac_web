<aside class="w-64 bg-white shadow-md p-6 fixed inset-y-0 left-0 flex flex-col">
    <div class="flex items-center justify-center">
        <img src="{{ asset('asset/images/logo-umy-sac-transparan-01.png') }}" alt="Sport Center UMY Logo" class="w-36 h-auto">
    </div>

    <div class="mt-4 text-center">
        <p class="text-lg font-semibold text-gray-800">
            {{ Auth::user()->name }}
        </p>
    </div>

    <nav class="mt-8 flex-1 relative">
        <ul class="space-y-2 text-gray-700">    
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                    {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/image.png') }}" alt="home" class="w-4 h-4 filter {{ request()->routeIs('admin.dashboard') ? 'brightness-0 invert' : '' }}">
                    <span>Dashboard Admin</span>
                </a>
            </li>
            @php
                $kelolaRoutes = ['admin.lapangan.futsal', 'admin.lapangan.badminton', 'admin.lapangan.voli', 'admin.lapangan.basket'];
            @endphp
            <li x-data="{ open: {{ request()->routeIs($kelolaRoutes) ? 'true' : 'false' }} }" class="relative">
                
                <button type="button" @click="open = !open"
                    class="w-full block py-2 px-4 flex items-center space-x-3 rounded text-sm transition duration-150 ease-in-out 
                    {{ request()->routeIs($kelolaRoutes) ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/kalender.png') }}" alt="fields" class="w-4 h-4 filter {{ request()->routeIs($kelolaRoutes) ? 'brightness-0 invert' : '' }}">
                    <span>Kelola Lapangan</span>
                    
                    <svg class="w-4 h-4 ml-auto transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <ul x-show="open" x-collapse.duration.300ms class="space-y-1 mt-1 pl-6 border-l border-blue-300 ml-1">
                    
                    {{-- Futsal --}}
                    <li>
                        <a href="{{ route('admin.lapangan.futsal') }}"
                            class="block py-1 pl-2 pr-2 text-sm rounded hover:bg-gray-200 
                            {{ request()->routeIs('admin.lapangan.futsal') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Futsal
                        </a>
                    </li>
                    
                    {{-- Badminton --}}
                    <li>
                        <a href="{{ route('admin.lapangan.badminton') }}"
                            class="block py-1 pl-2 pr-2 text-sm rounded hover:bg-gray-200 
                            {{ request()->routeIs('admin.lapangan.badminton') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Badminton
                        </a>
                    </li>
                    
                    {{-- Voli --}}
                    <li>
                        <a href="{{ route('admin.lapangan.voli') }}"
                            class="block py-1 pl-2 pr-2 text-sm rounded hover:bg-gray-200 
                            {{ request()->routeIs('admin.lapangan.voli') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Voli
                        </a>
                    </li>

                    {{-- Basket --}}
                    <li>
                        <a href="{{ route('admin.lapangan.basket') }}"
                            class="block py-1 pl-2 pr-2 text-sm rounded hover:bg-gray-200 
                            {{ request()->routeIs('admin.lapangan.basket') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Basket
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('admin.jadwal.index') }}"
                    class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                    {{ request()->routeIs('admin.jadwal.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/jadwal.png') }}" alt="scheduled" class="w-4 h-4 filter {{ request()->routeIs('admin.jadwal.index') ? 'brightness-0 invert' : '' }}">
                    <span>Jadwal Lapangan</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.riwayat.index') }}" 
                    class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                    {{ request()->routeIs('admin.riwayat.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/riwayat.png') }}" alt="history" class="w-4 h-4 filter {{ request()->routeIs('admin.riwayat.index') ? 'brightness-0 invert' : '' }}">
                    <span>Riwayat</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.users.index') }}" 
                    class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                    {{ request()->routeIs('admin.users.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/person.png') }}" alt="users" class="w-4 h-4 filter {{ request()->routeIs('admin.users.index') ? 'brightness-0 invert' : '' }}">
                    <span>Kelola Pengguna</span>
                </a>
            </li>

            <li class="absolute bottom-0 left-0 w-full flex flex-col">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full py-2 px-4 flex items-center space-x-3 rounded text-sm 
                    hover:bg-red-500 hover:text-white transition text-gray-700">
                        <img src="{{ asset('asset/images/keluar.png') }}" alt="out" class="w-4 h-4">
                        <span>Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>