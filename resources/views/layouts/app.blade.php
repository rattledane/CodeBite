<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CodeBite') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen">
        <nav class="bg-white border-b-4 border-black sticky top-0 z-50 py-2" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    <!-- Left: Logo & Links -->
                    <div class="flex items-center gap-8">
                        <a href="{{ route('games.index') }}" class="text-3xl font-black font-mono tracking-tighter uppercase text-black hover:scale-105 transition-transform">
                            CODEBITE
                        </a>
                        
                        <div class="hidden md:flex gap-6">
                            <a href="{{ route('games.index') }}" class="font-bold text-lg hover:underline decoration-4 underline-offset-4 decoration-[#1E90FF] {{ request()->routeIs('games.*') ? 'underline' : '' }}">Games</a>
                            <a href="{{ route('leaderboard.index') }}" class="font-bold text-lg hover:underline decoration-4 underline-offset-4 decoration-[#FFE500] {{ request()->routeIs('leaderboard.*') ? 'underline' : '' }}">Leaderboard</a>
                            <a href="{{ route('rooms.create') }}" class="font-bold text-lg hover:underline decoration-4 underline-offset-4 decoration-[#00ff88] {{ request()->routeIs('rooms.*') ? 'underline' : '' }}">Multiplayer</a>
                        </div>
                    </div>

                    <!-- Right: User Menu & Mobile Toggle -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 hover:scale-105 transition-transform cursor-pointer group" title="Lihat Profil">
                            <div class="w-10 h-10 rounded-full border-2 border-black overflow-hidden bg-[#FFE500] group-hover:bg-[#00ff88] transition-colors flex items-center justify-center font-bold text-xl">
                                @if(auth()->user() && auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                @endif
                            </div>
                            <span class="font-bold hidden md:block group-hover:underline decoration-2 underline-offset-2">{{ auth()->user()->name ?? 'Guest' }}</span>
                        </a>
                        
                        @auth
                        <form method="POST" action="{{ route('logout') }}" class="m-0 hidden sm:block">
                            @csrf
                            <button type="submit" class="bg-white neo-border neo-shadow-sm px-4 py-2 font-bold text-sm uppercase hover:bg-gray-100 transition-colors h-[44px]">
                                Logout
                            </button>
                        </form>
                        @endauth

                        <!-- Mobile Hamburger Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 bg-[#FFE500] neo-border neo-shadow-sm h-[44px] w-[44px] flex items-center justify-center">
                            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" 
                 x-transition.opacity
                 style="display: none;" 
                 class="md:hidden border-t-4 border-black bg-white p-4 flex flex-col gap-4 shadow-[4px_4px_0px_#000]">
                <a href="{{ route('games.index') }}" class="font-black text-xl py-2 px-4 bg-[#1E90FF] text-white neo-border">Games</a>
                <a href="{{ route('leaderboard.index') }}" class="font-black text-xl py-2 px-4 bg-[#FFE500] text-black neo-border">Leaderboard</a>
                <a href="{{ route('rooms.create') }}" class="font-black text-xl py-2 px-4 bg-[#00ff88] text-black neo-border">Multiplayer</a>
                
                @auth
                <form method="POST" action="{{ route('logout') }}" class="m-0 w-full mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white neo-border px-4 py-3 font-black text-xl uppercase h-[48px]">
                        Logout
                    </button>
                </form>
                @endauth
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
