<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - POPTIX</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ticketor-dark text-white font-sans antialiased flex h-screen overflow-hidden selection:bg-ticketor-neon selection:text-black">

    <!-- Sidebar Menu -->
    <aside class="w-64 bg-ticketor-card border-r border-gray-800/80 flex flex-col z-20 flex-shrink-0">
        <!-- Brand Logo -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-800/80">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <span class="text-2xl font-extrabold tracking-wider text-ticketor-neon">POPTIX</span>
                <span class="text-[10px] font-mono uppercase bg-ticketor-neon/15 text-ticketor-neon border border-ticketor-neon/30 px-1.5 py-0.5 rounded font-bold">ADMIN</span>
            </a>
        </div>
        
        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-ticketor-neon text-black shadow-lg shadow-ticketor-neon/20' : 'text-ticketor-gray hover:bg-gray-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            <div class="pt-4 pb-1 px-3.5">
                <span class="text-[11px] font-mono font-semibold uppercase tracking-wider text-gray-500">Master Data</span>
            </div>
            
            <!-- Movies -->
            <a href="{{ route('admin.movies.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition duration-150 {{ request()->routeIs('admin.movies.*') ? 'bg-ticketor-neon text-black shadow-lg shadow-ticketor-neon/20' : 'text-ticketor-gray hover:bg-gray-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
                Film / Movies
            </a>

            <!-- Cinemas -->
            <a href="{{ route('admin.cinemas.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition duration-150 {{ request()->routeIs('admin.cinemas.*') && !request()->routeIs('admin.studios.*') ? 'bg-ticketor-neon text-black shadow-lg shadow-ticketor-neon/20' : 'text-ticketor-gray hover:bg-gray-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Bioskop / Cinemas
            </a>

            <!-- Studios & Seats -->
            <a href="{{ route('admin.studios.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition duration-150 {{ request()->routeIs('admin.studios.*') ? 'bg-ticketor-neon text-black shadow-lg shadow-ticketor-neon/20' : 'text-ticketor-gray hover:bg-gray-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Studio & Kursi
            </a>

            <!-- Schedules -->
            <a href="{{ route('admin.schedules.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition duration-150 {{ request()->routeIs('admin.schedules.*') ? 'bg-ticketor-neon text-black shadow-lg shadow-ticketor-neon/20' : 'text-ticketor-gray hover:bg-gray-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Jadwal / Showtimes
            </a>
        </nav>
        
        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-gray-800/80 bg-ticketor-card/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-ticketor-neon/20 border border-ticketor-neon/40 flex items-center justify-center text-ticketor-neon font-bold flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-ticketor-gray font-mono truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Sign Out" class="p-2 text-ticketor-gray hover:text-red-400 hover:bg-gray-800 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-ticketor-dark">
        <!-- Top Navigation -->
        <header class="h-16 flex items-center justify-between px-8 bg-ticketor-dark/80 backdrop-blur border-b border-gray-800/80 z-10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <h1 class="text-lg font-bold text-white tracking-wide">@yield('header', 'Dashboard Overview')</h1>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-xs text-ticketor-gray font-mono hidden sm:inline-block">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>

                <a href="{{ route('movies.index') }}" target="_blank" class="text-xs font-medium text-ticketor-gray hover:text-ticketor-neon transition flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-800 hover:border-ticketor-neon/30 bg-ticketor-card">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Lihat Web User
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <x-admin.flash-message />
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
