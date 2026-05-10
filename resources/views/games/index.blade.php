@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20">

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-16 pb-12">
        <div class="flex flex-col items-center text-center animate-slide-in">
            <div class="inline-block bg-white neo-border px-4 py-1 mb-4" style="box-shadow: var(--neo-shadow-sm); font-family: 'Space Mono', monospace; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">
                🎮 Game Selection
            </div>
            <h1 class="text-4xl sm:text-6xl md:text-8xl font-black uppercase mb-6 tracking-tighter" style="font-family: 'Space Mono', monospace;">
                Pilih Game-mu
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl font-bold bg-white neo-border px-6 py-2 inline-block" style="box-shadow: var(--neo-shadow-sm);">
                Selesaikan tantangan, kumpulkan XP, jadilah master! 🚀
            </p>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="max-w-4xl mx-auto px-4 mb-12 sm:mb-16 animate-slide-in stagger-1">
        <div class="neo-border p-4 flex flex-col sm:flex-row justify-around items-center gap-6 sm:gap-4" style="background: var(--neo-black); color: white; box-shadow: 8px 8px 0px var(--neo-yellow);">
            <div class="text-center w-full sm:w-auto">
                <div class="text-xs font-black uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 2px; color: var(--neo-green);">Total XP</div>
                <div class="text-3xl sm:text-4xl font-black" style="font-family: 'Space Mono', monospace;">{{ auth()->user()->userProgress->sum('score') ?? 0 }}</div>
            </div>
            <div class="w-full h-1 sm:w-1 sm:h-12 bg-white opacity-20"></div>
            <div class="text-center w-full sm:w-auto">
                <div class="text-xs font-black uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 2px; color: var(--neo-yellow);">Games Selesai</div>
                <div class="text-3xl sm:text-4xl font-black" style="font-family: 'Space Mono', monospace;">
                    {{ $games->filter(function($g) { return $g->completed_levels === $g->levels->count() && $g->levels->count() > 0; })->count() }}
                </div>
            </div>
            <div class="w-full h-1 sm:w-1 sm:h-12 bg-white opacity-20"></div>
            <div class="text-center w-full sm:w-auto">
                <div class="text-xs font-black uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 2px; color: var(--neo-pink);">Global Rank</div>
                <div class="text-3xl sm:text-4xl font-black" style="font-family: 'Space Mono', monospace;">#1</div>
            </div>
        </div>
    </div>

    <!-- Games Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($games as $idx => $game)
            <div class="neo-card flex flex-col transition-all duration-200 cursor-pointer animate-slide-in"
                 style="animation-delay: {{ $idx * 0.1 }}s; box-shadow: var(--neo-shadow);"
                 onmouseover="this.style.transform='translate(-3px, -3px)'; this.style.boxShadow='10px 10px 0px var(--neo-black)';"
                 onmouseout="this.style.transform='none'; this.style.boxShadow='var(--neo-shadow)';">

                <!-- Thumbnail -->
                <div class="h-44 sm:h-48 border-b-4 border-black relative" style="background: var(--neo-blue);">
                    @if($game->thumbnail)
                        <img src="{{ $game->thumbnail }}" alt="{{ $game->title }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl font-black text-white" style="font-family: 'Space Mono', monospace;">
                            {{ substr($game->title, 0, 2) }}
                        </div>
                    @endif

                    @if($game->total_score > 0)
                        <div class="absolute top-3 right-3 neo-border px-3 py-1 font-black text-sm" style="background: var(--neo-yellow); box-shadow: 2px 2px 0px var(--neo-black); font-family: 'Space Mono', monospace;">
                            🏆 {{ $game->total_score }} XP
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-6 flex flex-col flex-grow">
                    <h2 class="text-xl sm:text-2xl font-black uppercase mb-2" style="font-family: 'Space Mono', monospace;">{{ $game->title }}</h2>
                    <p class="text-gray-700 font-bold mb-6 flex-grow line-clamp-3">
                        {{ $game->description }}
                    </p>

                    <!-- Progress -->
                    <div class="mb-6">
                        <div class="flex justify-between font-black text-sm uppercase mb-2" style="font-family: 'Space Mono', monospace; font-size: 12px; letter-spacing: 1px;">
                            <span>Progress</span>
                            <span>{{ $game->completed_levels }} / {{ $game->levels->count() }}</span>
                        </div>
                        <div class="h-5 w-full neo-border bg-gray-100 overflow-hidden">
                            @php
                                $percent = $game->levels->count() > 0 ? ($game->completed_levels / $game->levels->count()) * 100 : 0;
                            @endphp
                            <div class="h-full" style="width: {{ $percent }}%; background: var(--neo-green); {{ $percent > 0 && $percent < 100 ? 'border-right: 3px solid var(--neo-black);' : '' }}"></div>
                        </div>
                    </div>

                    <!-- Action -->
                    <a href="{{ route('games.play', $game->slug) }}" class="neo-btn w-full text-center text-lg sm:text-xl uppercase tracking-wider" style="background: var(--neo-yellow); padding: 14px 16px; font-family: 'Space Mono', monospace; box-shadow: var(--neo-shadow-sm);">
                        {{ $game->completed_levels > 0 ? ($percent >= 100 ? '🔄 Ulangi' : '▶ Lanjutkan') : '🚀 Mainkan' }}
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
