<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CodeBite') }}</title>

    <!-- Fonts (matching login page) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --neo-yellow: #FFE500;
            --neo-black: #1a1a1a;
            --neo-white: #ffffff;
            --neo-green: #00ff88;
            --neo-blue: #1E90FF;
            --neo-pink: #FF6B9D;
            --neo-purple: #A855F7;
            --neo-orange: #FF8C42;
            --neo-teal: #4ECDC4;
            --neo-border: 3px solid var(--neo-black);
            --neo-shadow: 6px 6px 0px var(--neo-black);
            --neo-shadow-sm: 4px 4px 0px var(--neo-black);
            --neo-shadow-hover: 8px 8px 0px var(--neo-black);
        }

        * {
            font-family: 'Space Grotesk', sans-serif;
        }

        code, .font-mono, [class*="mono"] {
            font-family: 'Space Mono', monospace !important;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: var(--neo-yellow);
            color: var(--neo-black);
            margin: 0;
            min-height: 100vh;
        }

        /* Neo-brutalism utility classes */
        .neo-border { border: var(--neo-border); }
        .neo-shadow { box-shadow: var(--neo-shadow); }
        .neo-shadow-sm { box-shadow: var(--neo-shadow-sm); }
        .neo-shadow-hover { box-shadow: var(--neo-shadow-hover); }

        .neo-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--neo-white);
            border: var(--neo-border);
            box-shadow: var(--neo-shadow-sm);
            padding: 10px 20px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: var(--neo-black);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .neo-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: var(--neo-shadow-hover);
        }

        .neo-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px var(--neo-black);
        }

        .neo-card {
            background: var(--neo-white);
            border: var(--neo-border);
            box-shadow: var(--neo-shadow);
        }

        /* Floating animation for decorative elements */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(var(--rotate, 0deg)); }
            50% { transform: translateY(-12px) rotate(var(--rotate, 0deg)); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.95); }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes ticker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .animate-slide-in { animation: slideIn 0.5s ease-out forwards; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        .animate-bounce-in { animation: bounceIn 0.6s ease-out forwards; }

        .float-1 { animation: float 5s ease-in-out infinite; --rotate: -6deg; }
        .float-2 { animation: float 4s ease-in-out 0.5s infinite; --rotate: 4deg; }
        .float-3 { animation: float 6s ease-in-out 1s infinite; --rotate: -3deg; }
        .float-4 { animation: float 4.5s ease-in-out 1.5s infinite; --rotate: 7deg; }

        /* Floating decorative code blocks */
        .floating-block {
            position: fixed;
            background: var(--neo-white);
            border: var(--neo-border);
            box-shadow: var(--neo-shadow-sm);
            padding: 8px 14px;
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            color: var(--neo-black);
            user-select: none;
            pointer-events: none;
            z-index: 0;
            opacity: 0.35;
        }

        .floating-block.pink { background: #FF6B9D; color: var(--neo-white); }
        .floating-block.blue { background: #4ECDC4; }
        .floating-block.purple { background: #A855F7; color: var(--neo-white); }
        .floating-block.orange { background: #FF8C42; }

        /* Marquee ticker */
        .ticker-wrap {
            overflow: hidden;
            background: var(--neo-black);
            border-top: var(--neo-border);
            border-bottom: var(--neo-border);
        }

        .ticker {
            display: inline-flex;
            white-space: nowrap;
            animation: ticker 30s linear infinite;
        }

        .ticker-item {
            padding: 6px 24px;
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            color: var(--neo-yellow);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Navigation link hover effect */
        .nav-link {
            position: relative;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 0;
            transition: all 0.15s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0%;
            height: 4px;
            background: var(--neo-yellow);
            transition: width 0.2s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active-blue::after { background: var(--neo-blue); }
        .nav-link.active-yellow::after { background: var(--neo-yellow); }
        .nav-link.active-green::after { background: var(--neo-green); }

        /* Stagger animation delays for children */
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }
        .stagger-6 { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <!-- Floating decorative blocks (subtle background) -->
    <div class="floating-block pink float-1" style="top: 15%; left: 3%;">if (true) {</div>
    <div class="floating-block blue float-2" style="top: 30%; right: 4%;">console.log("🚀")</div>
    <div class="floating-block float-3" style="bottom: 25%; left: 5%;">return 42;</div>
    <div class="floating-block purple float-4" style="top: 55%; right: 3%;">while (learning)</div>

    <div class="min-h-screen relative z-10">
        <!-- Navbar -->
        <nav class="bg-white neo-border sticky top-0 z-50 border-t-0 border-l-0 border-r-0" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">

                    <!-- Left: Logo & Links -->
                    <div class="flex items-center gap-8">
                        <a href="{{ route('games.index') }}" class="flex items-center gap-0 hover:scale-105 transition-transform">
                            <span style="font-family: 'Space Mono', monospace; font-weight: 700; font-size: 28px; letter-spacing: -1px;">Code</span>
                            <span style="font-family: 'Space Mono', monospace; font-weight: 700; font-size: 28px; letter-spacing: -1px; background: var(--neo-yellow); padding: 1px 6px; border: 2px solid var(--neo-black);">Bite</span>
                        </a>

                        <div class="hidden md:flex gap-6">
                            <a href="{{ route('games.index') }}" class="nav-link active-blue {{ request()->routeIs('games.*') ? 'active active-blue' : '' }}">Games</a>
                            <a href="{{ route('leaderboard.index') }}" class="nav-link active-yellow {{ request()->routeIs('leaderboard.*') ? 'active active-yellow' : '' }}">Leaderboard</a>
                            <a href="{{ route('rooms.create') }}" class="nav-link active-green {{ request()->routeIs('rooms.*') ? 'active active-green' : '' }}">Multiplayer</a>
                        </div>
                    </div>

                    <!-- Right: User Menu & Mobile Toggle -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 hover:scale-105 transition-transform cursor-pointer group" title="Lihat Profil">
                            <div class="w-10 h-10 neo-border overflow-hidden bg-[var(--neo-yellow)] group-hover:bg-[var(--neo-green)] transition-colors flex items-center justify-center font-bold text-xl" style="box-shadow: 2px 2px 0px var(--neo-black);">
                                @if(auth()->user() && auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                @endif
                            </div>
                            <span class="font-bold hidden md:block group-hover:underline decoration-2 underline-offset-2" style="font-family: 'Space Mono', monospace; font-size: 14px;">{{ auth()->user()->name ?? 'Guest' }}</span>
                        </a>

                        @auth
                        <form method="POST" action="{{ route('logout') }}" class="m-0 hidden sm:block">
                            @csrf
                            <button type="submit" class="neo-btn text-sm uppercase" style="padding: 8px 16px;">
                                Logout
                            </button>
                        </form>
                        @endauth

                        <!-- Mobile Hamburger Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden neo-border bg-[var(--neo-yellow)] p-2 w-[44px] h-[44px] flex items-center justify-center" style="box-shadow: 2px 2px 0px var(--neo-black);">
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
                 class="md:hidden neo-border border-l-0 border-r-0 bg-white p-4 flex flex-col gap-3">
                <a href="{{ route('games.index') }}" class="neo-btn bg-[var(--neo-blue)] text-white text-center" style="font-size: 18px;">Games</a>
                <a href="{{ route('leaderboard.index') }}" class="neo-btn bg-[var(--neo-yellow)] text-center" style="font-size: 18px;">Leaderboard</a>
                <a href="{{ route('rooms.create') }}" class="neo-btn bg-[var(--neo-green)] text-center" style="font-size: 18px;">Multiplayer</a>

                @auth
                <form method="POST" action="{{ route('logout') }}" class="m-0 w-full mt-2">
                    @csrf
                    <button type="submit" class="neo-btn bg-red-400 text-white w-full" style="font-size: 18px;">
                        Logout
                    </button>
                </form>
                @endauth
            </div>
        </nav>

        <!-- Ticker bar -->
        <div class="ticker-wrap">
            <div class="ticker">
                @for($i = 0; $i < 3; $i++)
                <span class="ticker-item">🎮 CodeBite</span>
                <span class="ticker-item">⚡ Learn to Code</span>
                <span class="ticker-item">🏆 Compete</span>
                <span class="ticker-item">🚀 Level Up</span>
                <span class="ticker-item">💻 CSS • JS • HTML</span>
                <span class="ticker-item">🔥 One Bite at a Time</span>
                @endfor
            </div>
        </div>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
