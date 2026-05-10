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
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Space Grotesk', sans-serif; }
        .font-mono { font-family: 'Space Mono', monospace; }
        .neo-border { border: 3px solid #000; }
        .neo-shadow { box-shadow: 4px 4px 0px #000; }
        .neo-shadow-sm { box-shadow: 2px 2px 0px #000; }
        .neo-button-hover:active { transform: translate(2px, 2px); box-shadow: 0px 0px 0px #000; }
        .neo-button-hover:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0px #000; }
    </style>
</head>
<body class="antialiased bg-[#f3f4f6] text-black overflow-hidden">
    <div class="h-screen w-screen flex flex-col">
        @yield('content')
    </div>
</body>
</html>
