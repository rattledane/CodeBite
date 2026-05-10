@extends('layouts.game')

@section('content')
<div class="min-h-screen bg-[#f3f4f6] flex flex-col items-center py-12 px-4 overflow-y-auto" x-data="{ copied: false }">

    <!-- Header -->
    <div class="text-center mb-10 mt-8">
        <h1 class="text-5xl md:text-7xl font-black uppercase mb-4 tracking-tighter" style="text-shadow: 4px 4px 0px #FFE500, 6px 6px 0px #000;">🎉 Selesai!</h1>
        <p class="text-xl font-bold font-mono bg-white inline-block neo-border px-4 py-1">Kamu telah menyelesaikan {{ $game->title }}</p>
    </div>

    <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Left Column: Score & Stats -->
        <div class="flex flex-col gap-8">
            
            <!-- Main Score Card -->
            <div class="bg-[#FFE500] neo-border p-8 text-center relative overflow-hidden" style="box-shadow: 8px 8px 0px #000;">
                <div class="absolute -right-4 -top-4 text-8xl opacity-20">🏆</div>
                <h2 class="text-2xl font-bold uppercase mb-2">Total Score</h2>
                <div class="text-7xl font-mono font-black tracking-tighter">{{ $stats['total_score'] }}</div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white neo-border neo-shadow-sm p-4 text-center">
                    <div class="text-sm font-bold uppercase text-gray-500 mb-1">Levels</div>
                    <div class="text-2xl font-black font-mono">{{ $stats['completed_levels'] }}/{{ $stats['total_levels'] }}</div>
                </div>
                <div class="bg-white neo-border neo-shadow-sm p-4 text-center">
                    <div class="text-sm font-bold uppercase text-gray-500 mb-1">Avg Time</div>
                    <div class="text-2xl font-black font-mono">{{ $stats['average_time'] }}s</div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 gap-4 mt-4">
                <button 
                    @click="
                        navigator.clipboard.writeText(window.location.origin + '/games/{{ $game->slug }}');
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    " 
                    class="w-full bg-[#1E90FF] text-white neo-border neo-shadow neo-button-hover font-bold text-xl py-4 uppercase flex justify-center items-center gap-2"
                >
                    <span x-show="!copied">🔗 Share ke Teman</span>
                    <span x-show="copied">✅ Link Tersalin!</span>
                </button>
                
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('games.play', $game->slug) }}" class="bg-white neo-border neo-shadow neo-button-hover font-bold text-lg py-3 uppercase text-center block">
                        Main Lagi
                    </a>
                    <a href="{{ route('games.index') }}" class="bg-[#ff4444] text-white neo-border neo-shadow neo-button-hover font-bold text-lg py-3 uppercase text-center block">
                        Ke Lobby
                    </a>
                </div>
            </div>

        </div>

        <!-- Right Column: Leaderboard Teaser -->
        <div class="bg-white neo-border p-6" style="box-shadow: 8px 8px 0px #000;">
            <div class="flex items-center gap-3 mb-6 border-b-4 border-black pb-4">
                <span class="text-3xl">🏅</span>
                <h2 class="text-2xl font-black uppercase tracking-tight">Top Players</h2>
            </div>
            
            <div class="space-y-4">
                @forelse($leaderboard as $index => $player)
                    <div class="flex items-center justify-between p-3 border-2 border-black bg-[#f3f4f6]">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 flex items-center justify-center font-black {{ $index == 0 ? 'bg-[#FFE500]' : ($index == 1 ? 'bg-gray-300' : 'bg-orange-300') }} border-2 border-black rounded-full">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex items-center gap-2">
                                @if($player->avatar)
                                    <img src="{{ $player->avatar }}" alt="{{ $player->name }}" class="w-8 h-8 rounded-full border-2 border-black">
                                @else
                                    <div class="w-8 h-8 bg-[#1E90FF] rounded-full border-2 border-black"></div>
                                @endif
                                <span class="font-bold">{{ $player->name }}</span>
                            </div>
                        </div>
                        <div class="font-mono font-black text-xl">
                            {{ $player->total_score }}
                        </div>
                    </div>
                @empty
                    <div class="text-center font-bold py-8 text-gray-500">Belum ada pemain.</div>
                @endforelse
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ route('leaderboard.index') }}" class="inline-block font-bold uppercase underline hover:text-[#1E90FF]">Lihat Full Leaderboard →</a>
            </div>
        </div>
        
    </div>
</div>
@endsection
