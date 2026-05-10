@extends('layouts.app')

@section('content')
<div class="bg-[#f3f4f6] min-h-screen pb-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
        
        <!-- Header -->
        <div class="flex flex-col items-center text-center mb-12">
            <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter" style="text-shadow: 4px 4px 0px #FFE500, 8px 8px 0px #000;">Leaderboard</h1>
            <p class="text-xl md:text-2xl font-bold bg-white neo-border px-6 py-2 shadow-[4px_4px_0px_#000] mt-6">
                @if($currentSlug === 'global')
                    Peringkat Global Seluruh Pemain 🌍
                @else
                    Peringkat Terbaik di {{ $game->title }} 🎮
                @endif
            </p>
        </div>

        <!-- User Position Card -->
        @if(auth()->check() && $userRank)
        <div class="mb-12 bg-[#00ff88] neo-border p-4 sm:p-6 shadow-[8px_8px_0px_#000] flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 sm:gap-0">
            <div>
                <h2 class="text-2xl font-black uppercase mb-1">Status Kamu</h2>
                <p class="font-bold text-lg">Kamu saat ini berada di posisi yang luar biasa!</p>
            </div>
            <div class="text-center sm:text-right">
                <div class="text-sm font-bold uppercase mb-1">Posisi Kamu</div>
                <div class="text-5xl font-black font-mono tracking-tighter">#{{ $userRank['position'] }}</div>
            </div>
        </div>
        @elseif(auth()->check())
        <div class="mb-12 bg-white neo-border p-6 shadow-[8px_8px_0px_#000] text-center">
            <h2 class="text-2xl font-black uppercase">Belum Ada Skor</h2>
            <p class="font-bold">Ayo mainkan game dan jadilah yang terbaik!</p>
        </div>
        @endif

        <!-- Tab Switcher -->
        <div class="flex flex-wrap justify-center sm:justify-start gap-3 sm:gap-4 mb-8">
            <a href="{{ route('leaderboard.index') }}" 
               class="px-4 sm:px-6 py-3 font-black uppercase tracking-wider transition-all neo-border text-sm sm:text-base {{ $currentSlug === 'global' ? 'bg-[#FFE500] shadow-[4px_4px_0px_#000]' : 'bg-white hover:bg-gray-100 shadow-[2px_2px_0px_#000]' }}">
                Global
            </a>
            @foreach($games as $gameItem)
                <a href="{{ route('leaderboard.game', $gameItem->slug) }}" 
                   class="px-4 sm:px-6 py-3 font-black uppercase tracking-wider transition-all neo-border text-sm sm:text-base {{ $currentSlug === $gameItem->slug ? 'bg-[#1E90FF] text-white shadow-[4px_4px_0px_#000]' : 'bg-white hover:bg-gray-100 shadow-[2px_2px_0px_#000]' }}">
                    {{ $gameItem->title }}
                </a>
            @endforeach
        </div>

        <!-- Leaderboard Table -->
        <div class="bg-white neo-border shadow-[8px_8px_0px_#000] sm:shadow-[12px_12px_0px_#000] overflow-x-auto">
            <table class="w-full border-collapse min-w-[320px]">
                <thead>
                    <tr class="bg-black text-white uppercase font-black text-xs sm:text-sm md:text-base border-b-4 border-black">
                        <th class="py-3 sm:py-4 px-2 sm:px-6 text-left w-12 sm:w-20">Rank</th>
                        <th class="py-3 sm:py-4 px-2 sm:px-6 text-left">Pemain</th>
                        <th class="py-3 sm:py-4 px-2 sm:px-6 text-right">XP Total</th>
                        <th class="py-3 sm:py-4 px-2 sm:px-6 text-right hidden md:table-cell">Levels</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaderboard as $index => $row)
                        @php
                            $rank = $index + 1;
                            $isCurrentUser = auth()->id() === $row->user_id;
                        @endphp
                        <tr class="border-b-2 border-black transition-colors {{ $isCurrentUser ? 'bg-[#FFE500]' : ($index % 2 === 0 ? 'bg-white' : 'bg-gray-100') }}">
                            <td class="py-5 px-6 font-black text-xl md:text-2xl font-mono">
                                @if($rank === 1)
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#FFD700] border-2 border-black shadow-[2px_2px_0px_#000]">🥇</span>
                                @elseif($rank === 2)
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#C0C0C0] border-2 border-black shadow-[2px_2px_0px_#000]">🥈</span>
                                @elseif($rank === 3)
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-[#CD7F32] border-2 border-black shadow-[2px_2px_0px_#000]">🥉</span>
                                @else
                                    #{{ $rank }}
                                @endif
                            </td>
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full border-2 border-black overflow-hidden bg-gray-200">
                                        @if($row->user->avatar)
                                            <img src="{{ $row->user->avatar }}" alt="{{ $row->user->name }}" class="w-full h-full object-cover" loading="lazy">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center font-bold text-xl">{{ substr($row->user->name, 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-black text-lg leading-none uppercase">{{ $row->user->name }}</span>
                                        <span class="text-xs font-bold text-gray-500 uppercase">Member sejak {{ $row->user->created_at->format('M Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-right font-black text-2xl font-mono">
                                {{ number_format($row->total_score) }}
                            </td>
                            <td class="py-5 px-6 text-right font-bold text-lg font-mono hidden md:table-cell">
                                {{ $row->levels_completed }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center font-black uppercase text-2xl text-gray-400">
                                Belum ada data leaderboard.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
