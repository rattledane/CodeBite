<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CodeBite') }} - Game</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

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

        * { font-family: 'Space Grotesk', sans-serif; }
        code, .font-mono, [class*="mono"] { font-family: 'Space Mono', monospace !important; }

        body { font-family: 'Space Grotesk', sans-serif; color: var(--neo-black); }
        .neo-border { border: var(--neo-border); }
        .neo-shadow { box-shadow: var(--neo-shadow); }
        .neo-shadow-sm { box-shadow: var(--neo-shadow-sm); }
        .neo-button-hover:active { transform: translate(2px, 2px); box-shadow: 0px 0px 0px var(--neo-black); }
        .neo-button-hover:hover { transform: translate(-2px, -2px); box-shadow: var(--neo-shadow-hover); }

        .neo-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            background: var(--neo-white); border: var(--neo-border); box-shadow: var(--neo-shadow-sm);
            padding: 10px 20px; font-family: 'Space Grotesk', sans-serif; font-weight: 700;
            color: var(--neo-black); cursor: pointer; text-decoration: none; transition: all 0.15s ease;
        }
        .neo-btn:hover { transform: translate(-2px, -2px); box-shadow: var(--neo-shadow-hover); }
        .neo-btn:active { transform: translate(2px, 2px); box-shadow: 2px 2px 0px var(--neo-black); }

        .neo-card { background: var(--neo-white); border: var(--neo-border); box-shadow: var(--neo-shadow); }

        @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes bounceIn { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); } 100% { transform: scale(1); opacity: 1; } }
        .animate-slide-in { animation: slideIn 0.5s ease-out forwards; }
        .animate-bounce-in { animation: bounceIn 0.6s ease-out forwards; }
    </style>
</head>
<body class="antialiased bg-[var(--neo-yellow)] text-black overflow-hidden">
    <div class="h-screen w-screen flex flex-col">
        @yield('content')
    </div>
</body>
</html>
