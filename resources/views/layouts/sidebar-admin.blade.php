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
            
            <li>
                <a href="{{ route('admin.booking.index') }}" 
                    class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                    {{ request()->routeIs(['admin.booking.index', 'admin.booking.update_status']) ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/kalender.png') }}" alt="fields" class="w-4 h-4 filter {{ request()->routeIs(['admin.booking.index', 'admin.booking.update_status']) ? 'brightness-0 invert' : '' }}">
                    <span>Kelola Booking</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.lapangan.index') }}" 
                    class="block py-2 px-4 flex items-center space-x-3 rounded text-sm 
                    {{ request()->routeIs(['admin.lapangan.index', 'admin.lapangan.create', 'admin.lapangan.edit']) ? 'bg-blue-500 text-white' : 'hover:bg-gray-100' }}">
                    <img src="{{ asset('asset/images/person.png') }}" alt="users" class="w-4 h-4 filter {{ request()->routeIs(['admin.lapangan.index', 'admin.lapangan.create', 'admin.lapangan.edit']) ? 'brightness-0 invert' : '' }}">
                    <span>Kelola Lapangan</span>
                </a>
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