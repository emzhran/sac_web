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
                <a href="{{ route('dashboard') }}"
                   class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                   {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/image.png') }}" alt="home" class="w-4 h-4">
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('profile.edit') }}"
                   class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                   {{ request()->routeIs('profile.edit') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/person.png') }}" alt="Profil" class="w-4 h-4">
                    <span>Profil Saya</span>
                </a>
            </li>
            
            <li x-data="{ open: {{ request()->routeIs(['futsal', 'badminton', 'voli', 'basket', 'booking.index']) ? 'true' : 'false' }} }" class="relative">
                
                <button type="button" @click="open = !open"
                    class="w-full block py-2 px-4 flex items-center space-x-3 rounded text-sm transition duration-150 ease-in-out 
                    {{ request()->routeIs(['futsal', 'badminton', 'voli', 'basket', 'booking.index']) ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/kalender.png') }}" alt="calendar" class="w-4 h-4">
                    <span>Booking Lapangan</span>
                    
                    <svg class="w-4 h-4 ml-auto transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <ul x-show="open" x-collapse.duration.300ms class="space-y-1 mt-1 pl-6 border-l border-blue-300 ml-1">
                    
                    {{-- Futsal --}}
                    <li>
                        <a href="{{ route('futsal') }}"
                           class="block py-1 pl-2 pr-2 text-sm rounded hover:bg-gray-200 
                           {{ request()->routeIs('futsal') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Futsal
                        </a>
                    </li>
                    
                    {{-- Badminton --}}
                    <li>
                        <a href="{{ route('badminton') }}"
                           class="block py-1 pl-2 pr-2 text-sm rounded hover:bg-gray-200 
                           {{ request()->routeIs('badminton') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Badminton
                        </a>
                    </li>
                    
                    {{-- Voli --}}
                    <li>
                        <a href="{{ route('voli') }}"
                           class="block py-1 pl-2 pr-2 text-sm rounded hover:bg-gray-200 
                           {{ request()->routeIs('voli') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Voli
                        </a>
                    </li>

                    {{-- Basket --}}
                    <li>
                        <a href="{{ route('basket') }}"
                           class="block py-1 pl-2 pr-2 text-sm rounded hover:bg-gray-200 
                           {{ request()->routeIs('basket') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                            Basket
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Jadwal Lapangan --}}
            <li>
                <a href="{{ route('jadwal.index') }}"
                   class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                   {{ request()->routeIs('jadwal.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/jadwal.png') }}" alt="scheduled" class="w-4 h-4">
                    <span>Jadwal Lapangan</span>
                </a>
            </li>
            
            {{-- Riwayat --}}
            <li>
                <a href="{{ route('riwayat.index') }}" 
                   class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                   {{ request()->routeIs('riwayat.index') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/riwayat.png') }}" alt="history" class="w-4 h-4">
                    <span>Riwayat</span>
                </a>
            </li>

            {{-- Profil & Logout --}}
            <li class="absolute bottom-0 left-0 w-full flex flex-col">
                
                <div class="p-4 hidden"> 
                    <a href="{{ route('profile.edit') }}">
                        <img src="{{ asset('asset/images/person.png') }}" alt="Profil" class="w-8 h-8">
                    </a>
                </div>

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