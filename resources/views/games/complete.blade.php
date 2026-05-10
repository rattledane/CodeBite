@extends('layouts.game')

@section('content')
<div class="min-h-screen flex flex-col items-center py-8 sm:py-12 px-4 overflow-y-auto" x-data="{ copied: false }">

    <!-- Header -->
    <div class="text-center mb-8 sm:mb-10 mt-4 sm:mt-8 animate-slide-in">
        <div class="inline-block bg-white neo-border px-4 py-1 mb-4" style="box-shadow: var(--neo-shadow-sm); font-family: 'Space Mono', monospace; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">
            🎉 Completed
        </div>
        <h1 class="text-4xl sm:text-5xl md:text-7xl font-black uppercase mb-4 tracking-tighter" style="font-family: 'Space Mono', monospace;">
            Selesai!
        </h1>
        <p class="text-lg sm:text-xl font-bold bg-white inline-block neo-border px-4 py-1" style="box-shadow: var(--neo-shadow-sm); font-family: 'Space Mono', monospace;">
            Kamu telah menyelesaikan {{ $game->title }}
        </p>
    </div>

    <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">

        <!-- Left Column: Score & Stats -->
        <div class="flex flex-col gap-6 sm:gap-8">

            <!-- Main Score Card -->
            <div class="neo-border p-6 sm:p-8 text-center relative overflow-hidden animate-bounce-in" style="background: var(--neo-yellow); box-shadow: 8px 8px 0px var(--neo-black);">
                <div class="absolute -right-4 -top-4 text-8xl opacity-20 pointer-events-none">🏆</div>
                <h2 class="text-xl sm:text-2xl font-black uppercase mb-2" style="font-family: 'Space Mono', monospace;">Total Score</h2>
                <div class="text-5xl sm:text-7xl font-black tracking-tighter" style="font-family: 'Space Mono', monospace;">{{ $stats['total_score'] }}</div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="neo-border p-4 text-center" style="background: white; box-shadow: var(--neo-shadow-sm);">
                    <div class="text-xs font-black uppercase text-gray-500 mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Levels</div>
                    <div class="text-2xl font-black" style="font-family: 'Space Mono', monospace;">{{ $stats['completed_levels'] }}/{{ $stats['total_levels'] }}</div>
                </div>
                <div class="neo-border p-4 text-center" style="background: white; box-shadow: var(--neo-shadow-sm);">
                    <div class="text-xs font-black uppercase text-gray-500 mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Avg Time</div>
                    <div class="text-2xl font-black" style="font-family: 'Space Mono', monospace;">{{ $stats['average_time'] }}s</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 gap-4 mt-2 sm:mt-4">
                <button
                    @click="
                        navigator.clipboard.writeText(window.location.origin + '/games/{{ $game->slug }}');
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    "
                    class="neo-btn w-full text-lg sm:text-xl uppercase" style="background: var(--neo-blue); color: white; padding: 14px 20px; font-family: 'Space Mono', monospace; box-shadow: var(--neo-shadow);"
                >
                    <span x-show="!copied">🔗 Share ke Teman</span>
                    <span x-show="copied">✅ Link Tersalin!</span>
                </button>

                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('games.play', $game->slug) }}" class="neo-btn text-center text-base sm:text-lg uppercase" style="padding: 12px 16px; font-family: 'Space Mono', monospace; box-shadow: var(--neo-shadow-sm);">
                        🔄 Main Lagi
                    </a>
                    <a href="{{ route('games.index') }}" class="neo-btn text-center text-base sm:text-lg uppercase" style="background: var(--neo-green); padding: 12px 16px; font-family: 'Space Mono', monospace; box-shadow: var(--neo-shadow-sm);">
                        🏠 Ke Lobby
                    </a>
                </div>
            </div>

        </div>

        <!-- Right Column: Leaderboard Teaser -->
        <div class="neo-card p-6 animate-slide-in" style="box-shadow: 8px 8px 0px var(--neo-black); animation-delay: 0.2s;">
            <div class="flex items-center gap-3 mb-6 pb-4" style="border-bottom: 4px solid var(--neo-black);">
                <span class="neo-border p-1.5 text-xl" style="background: var(--neo-yellow); box-shadow: 2px 2px 0px var(--neo-black);">🏅</span>
                <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tight" style="font-family: 'Space Mono', monospace;">Top Players</h2>
            </div>

            <div class="space-y-4">
                @forelse($leaderboard as $index => $player)
                    <div class="flex items-center justify-between p-3 neo-border transition-all hover:translate-x-1" style="background: {{ $index % 2 === 0 ? '#f9f9f4' : 'white' }}; box-shadow: 2px 2px 0px var(--neo-black);">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 flex items-center justify-center font-black neo-border" style="font-family: 'Space Mono', monospace; background: {{ $index == 0 ? 'var(--neo-yellow)' : ($index == 1 ? '#C0C0C0' : '#CD7F32') }}; box-shadow: 2px 2px 0px var(--neo-black);">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex items-center gap-2">
                                @if($player->avatar)
                                    <div class="w-8 h-8 neo-border overflow-hidden" style="box-shadow: 2px 2px 0px var(--neo-black);">
                                        <img src="{{ $player->avatar }}" alt="{{ $player->name }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-8 h-8 neo-border flex items-center justify-center font-bold text-sm" style="background: var(--neo-blue); color: white; box-shadow: 2px 2px 0px var(--neo-black);">
                                        {{ substr($player->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="font-bold text-sm">{{ $player->name }}</span>
                            </div>
                        </div>
                        <div class="font-black text-lg" style="font-family: 'Space Mono', monospace;">
                            {{ $player->total_score }}
                        </div>
                    </div>
                @empty
                    <div class="text-center font-black py-8 text-gray-400 uppercase" style="font-family: 'Space Mono', monospace;">Belum ada pemain.</div>
                @endforelse
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('leaderboard.index') }}" class="neo-btn text-sm uppercase w-full" style="font-family: 'Space Mono', monospace; box-shadow: var(--neo-shadow-sm);">
                    Lihat Full Leaderboard →
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
