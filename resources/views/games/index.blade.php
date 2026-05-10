@extends('layouts.app')

@section('content')
<div class="bg-[#f3f4f6] min-h-screen pb-20">
    
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
        <div class="flex flex-col items-center text-center">
            <h1 class="text-6xl md:text-8xl font-black uppercase mb-6 tracking-tighter" style="text-shadow: 4px 4px 0px #00ff88, 8px 8px 0px #000;">Pilih Game-mu 🎮</h1>
            <p class="text-xl md:text-2xl font-bold bg-white neo-border px-6 py-2 shadow-[4px_4px_0px_#000] inline-block">
                Selesaikan tantangan, kumpulkan XP, jadilah master!
            </p>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="max-w-4xl mx-auto px-4 mb-16">
        <div class="bg-white neo-border p-4 shadow-[8px_8px_0px_#000] flex flex-col sm:flex-row justify-around items-center gap-6 sm:gap-4">
            <div class="text-center w-full sm:w-auto">
                <div class="text-sm font-bold uppercase text-gray-500 mb-1">Total XP</div>
                <div class="text-3xl font-black font-mono text-[#1E90FF]">{{ auth()->user()->userProgress->sum('score') ?? 0 }}</div>
            </div>
            <div class="w-full h-1 sm:w-1 sm:h-12 bg-black opacity-10 sm:opacity-100"></div>
            <div class="text-center w-full sm:w-auto">
                <div class="text-sm font-bold uppercase text-gray-500 mb-1">Games Selesai</div>
                <div class="text-3xl font-black font-mono text-[#00ff88]">
                    {{ $games->filter(function($g) { return $g->completed_levels === $g->levels->count() && $g->levels->count() > 0; })->count() }}
                </div>
            </div>
            <div class="w-full h-1 sm:w-1 sm:h-12 bg-black opacity-10 sm:opacity-100"></div>
            <div class="text-center w-full sm:w-auto">
                <div class="text-sm font-bold uppercase text-gray-500 mb-1">Global Rank</div>
                <div class="text-3xl font-black font-mono text-[#FFE500]">#1</div>
            </div>
        </div>
    </div>

    <!-- Games Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($games as $game)
            <div class="bg-white border-4 border-black flex flex-col transition-all duration-200"
                 style="box-shadow: 5px 5px 0px #000;"
                 onmouseover="this.style.transform='translate(-3px, -3px)'; this.style.boxShadow='8px 8px 0px #000';"
                 onmouseout="this.style.transform='none'; this.style.boxShadow='5px 5px 0px #000';">
                
                <!-- Thumbnail -->
                <div class="h-48 border-b-4 border-black relative bg-[#1E90FF]">
                    @if($game->thumbnail)
                        <img src="{{ $game->thumbnail }}" alt="{{ $game->title }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl font-black text-white">
                            {{ substr($game->title, 0, 2) }}
                        </div>
                    @endif
                    
                    @if($game->total_score > 0)
                        <div class="absolute top-4 right-4 bg-[#FFE500] neo-border px-3 py-1 font-bold text-sm shadow-[2px_2px_0px_#000]">
                            🏆 {{ $game->total_score }} XP
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-6 flex flex-col flex-grow">
                    <h2 class="text-2xl font-black uppercase mb-2">{{ $game->title }}</h2>
                    <p class="text-gray-700 font-medium mb-6 flex-grow line-clamp-3">
                        {{ $game->description }}
                    </p>

                    <!-- Progress -->
                    <div class="mb-6">
                        <div class="flex justify-between font-bold text-sm uppercase mb-2">
                            <span>Progress</span>
                            <span>{{ $game->completed_levels }} / {{ $game->levels->count() }}</span>
                        </div>
                        <div class="h-4 w-full neo-border bg-gray-200 overflow-hidden">
                            @php
                                $percent = $game->levels->count() > 0 ? ($game->completed_levels / $game->levels->count()) * 100 : 0;
                            @endphp
                            <div class="h-full bg-[#00ff88] border-r-2 border-black" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    <!-- Action -->
                    <a href="{{ route('games.play', $game->slug) }}" class="block text-center w-full bg-[#FFE500] text-black font-black text-xl uppercase py-4 border-2 border-black shadow-[4px_4px_0px_#000] hover:bg-yellow-300 transition-colors">
                        Mainkan
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
