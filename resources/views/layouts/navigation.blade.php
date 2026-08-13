<nav x-data="{ open: false }" class="bg-[#141414] border-b border-white/10 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-[#E50914] font-black text-2xl tracking-tight">
                        PoPTix
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'text-white bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                        Dashboard
                    </a>

                    @role('admin')
                        <a href="{{ route('admin.movies.index') }}"
                           class="px-3 py-2 rounded text-sm font-medium transition {{ request()->routeIs('admin.movies.*') ? 'text-white bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            Kelola Film
                        </a>
                        <a href="{{ route('admin.cinemas.index') }}"
                           class="px-3 py-2 rounded text-sm font-medium transition {{ request()->routeIs('admin.cinemas.*') ? 'text-white bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            Kelola Bioskop
                        </a>
                        <a href="{{ route('admin.schedules.index') }}"
                           class="px-3 py-2 rounded text-sm font-medium transition {{ request()->routeIs('admin.schedules.*') ? 'text-white bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            Kelola Jadwal
                        </a>
                    @endrole

                    @role('user')
                        <a href="{{ route('movies.index') }}"
                           class="px-3 py-2 rounded text-sm font-medium transition {{ request()->routeIs('movies.*') ? 'text-white bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            Katalog Film
                        </a>
                        <a href="{{ route('bookings.index') }}"
                           class="px-3 py-2 rounded text-sm font-medium transition {{ request()->routeIs('bookings.*') ? 'text-white bg-white/10' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">
                            Booking Saya
                        </a>
                    @endrole
                </div>
            </div>

            <!-- Desktop User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data="{ userOpen: false }">
                    <button @click="userOpen = ! userOpen" @click.outside="userOpen = false"
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-300 hover:text-white transition">
                        {{ Auth::user()->name }}
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="userOpen" x-transition style="display: none;"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-[#1F1F1F] border border-white/10 py-1">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                               class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white cursor-pointer">
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-white/5 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (cuma render kalau 'open' true) -->
    <div x-show="open" x-transition style="display: none;" class="sm:hidden border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded text-base font-medium {{ request()->routeIs('dashboard') ? 'text-white bg-white/10' : 'text-gray-300' }}">
                Dashboard
            </a>

            @role('admin')
                <a href="{{ route('admin.movies.index') }}" class="block px-3 py-2 rounded text-base font-medium {{ request()->routeIs('admin.movies.*') ? 'text-white bg-white/10' : 'text-gray-300' }}">
                    Kelola Film
                </a>
                <a href="{{ route('admin.cinemas.index') }}" class="block px-3 py-2 rounded text-base font-medium {{ request()->routeIs('admin.cinemas.*') ? 'text-white bg-white/10' : 'text-gray-300' }}">
                    Kelola Bioskop
                </a>
                <a href="{{ route('admin.schedules.index') }}" class="block px-3 py-2 rounded text-base font-medium {{ request()->routeIs('admin.schedules.*') ? 'text-white bg-white/10' : 'text-gray-300' }}">
                    Kelola Jadwal
                </a>
            @endrole

            @role('user')
                <a href="{{ route('movies.index') }}" class="block px-3 py-2 rounded text-base font-medium {{ request()->routeIs('movies.*') ? 'text-white bg-white/10' : 'text-gray-300' }}">
                    Katalog Film
                </a>
                <a href="{{ route('bookings.index') }}" class="block px-3 py-2 rounded text-base font-medium {{ request()->routeIs('bookings.*') ? 'text-white bg-white/10' : 'text-gray-300' }}">
                    Booking Saya
                </a>
            @endrole
        </div>

        <div class="pt-4 pb-3 border-t border-white/10 px-4">
            <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block py-2 text-sm text-gray-300">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block py-2 text-sm text-gray-300 cursor-pointer">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>