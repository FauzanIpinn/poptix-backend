<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - POPTIX</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ticketor-dark text-white font-sans antialiased flex h-screen overflow-hidden">

    <!-- Sidebar Menu -->
    <aside class="w-64 bg-ticketor-card border-r border-gray-800 flex flex-col">
        <div class="h-16 flex items-center px-6 border-b border-gray-800">
            <span class="text-2xl font-bold tracking-widest text-ticketor-neon">POPTIX<span class="text-white text-sm ml-2">ADMIN</span></span>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Gunakan request()->routeIs() untuk active state -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-ticketor-neon text-black font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <a href="{{ route('admin.movies.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-ticketor-gray hover:bg-gray-800 hover:text-white transition">
                <!-- Ikon Film -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                Movies
            </a>

            <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-ticketor-gray hover:bg-gray-800 hover:text-white transition">
                <!-- Ikon Kalender -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Schedules
            </a>
        </nav>
        
        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-800 rounded-lg transition">
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Top Navigation -->
        <header class="h-16 flex items-center justify-between px-8 bg-ticketor-dark border-b border-gray-800">
            <h2 class="text-xl font-semibold">@yield('header', 'Dashboard Overview')</h2>
            
            <div class="flex items-center gap-4">
                <div class="w-8 h-8 rounded-full bg-ticketor-neon flex items-center justify-center text-black font-bold">
                    A
                </div>
                <span class="text-sm font-medium">{{ Auth::user()->name ?? 'Administrator' }}</span>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>
</body>
</html>