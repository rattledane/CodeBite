@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">

        <!-- Header -->
        <div class="flex flex-col items-center text-center mb-12 animate-slide-in">
            <div class="inline-block bg-white neo-border px-4 py-1 mb-4" style="box-shadow: var(--neo-shadow-sm); font-family: 'Space Mono', monospace; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">
                🏆 Hall of Fame
            </div>
            <h1 class="text-5xl md:text-7xl font-black uppercase tracking-tighter" style="font-family: 'Space Mono', monospace;">Leaderboard</h1>
            <p class="text-lg font-bold mt-4 bg-white neo-border px-6 py-2" style="box-shadow: var(--neo-shadow-sm);">
                @if($currentSlug === 'global')
                    Peringkat Global Seluruh Pemain 🌍
                @else
                    Peringkat Terbaik di {{ $game->title }} 🎮
                @endif
            </p>
        </div>

        <!-- User Position Card -->
        @if(auth()->check() && $userRank)
        <div class="mb-10 neo-card p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 sm:gap-0 animate-slide-in stagger-1" style="background: var(--neo-green);">
            <div>
                <h2 class="text-xl font-black uppercase mb-1" style="font-family: 'Space Mono', monospace;">Status Kamu</h2>
                <p class="font-bold text-lg">Kamu saat ini berada di posisi yang luar biasa!</p>
            </div>
            <div class="text-center sm:text-right">
                <div class="text-sm font-bold uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Posisi</div>
                <div class="text-5xl font-black tracking-tighter" style="font-family: 'Space Mono', monospace;">#{{ $userRank['position'] }}</div>
            </div>
        </div>
        @elseif(auth()->check())
        <div class="mb-10 neo-card p-6 text-center animate-slide-in stagger-1">
            <h2 class="text-2xl font-black uppercase" style="font-family: 'Space Mono', monospace;">Belum Ada Skor</h2>
            <p class="font-bold mt-1">Ayo mainkan game dan jadilah yang terbaik!</p>
        </div>
        @endif

        <!-- Tab Switcher -->
        <div class="flex flex-wrap justify-center sm:justify-start gap-3 sm:gap-4 mb-8 animate-slide-in stagger-2">
            <a href="{{ route('leaderboard.index') }}"
               class="neo-btn text-sm sm:text-base uppercase tracking-wider {{ $currentSlug === 'global' ? '' : 'bg-white' }}"
               style="{{ $currentSlug === 'global' ? 'background: var(--neo-yellow);' : '' }}">
                🌍 Global
            </a>
            @foreach($games as $gameItem)
                <a href="{{ route('leaderboard.game', $gameItem->slug) }}"
                   class="neo-btn text-sm sm:text-base uppercase tracking-wider {{ $currentSlug === $gameItem->slug ? '' : 'bg-white' }}"
                   style="{{ $currentSlug === $gameItem->slug ? 'background: var(--neo-blue); color: white;' : '' }}">
                    {{ $gameItem->title }}
                </a>
            @endforeach
        </div>

        <!-- Leaderboard Table -->
        <div class="neo-card overflow-x-auto animate-slide-in stagger-3" style="box-shadow: 8px 8px 0px var(--neo-black);">
            <table class="w-full border-collapse min-w-[320px]">
                <thead>
                    <tr style="background: var(--neo-black); color: white; font-family: 'Space Mono', monospace; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 4px solid var(--neo-black);">
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
                        <tr class="border-b-2 border-black transition-all duration-200 hover:translate-x-1 {{ $isCurrentUser ? '' : '' }}"
                            style="background: {{ $isCurrentUser ? 'var(--neo-yellow)' : ($index % 2 === 0 ? 'white' : '#f9f9f4') }}; animation: slideIn 0.4s ease-out forwards; animation-delay: {{ $index * 0.05 }}s; opacity: 0;">
                            <td class="py-5 px-6 font-black text-xl md:text-2xl" style="font-family: 'Space Mono', monospace;">
                                @if($rank === 1)
                                    <span class="inline-flex items-center justify-center w-10 h-10 neo-border" style="background: #FFD700; box-shadow: 2px 2px 0px var(--neo-black);">🥇</span>
                                @elseif($rank === 2)
                                    <span class="inline-flex items-center justify-center w-10 h-10 neo-border" style="background: #C0C0C0; box-shadow: 2px 2px 0px var(--neo-black);">🥈</span>
                                @elseif($rank === 3)
                                    <span class="inline-flex items-center justify-center w-10 h-10 neo-border" style="background: #CD7F32; box-shadow: 2px 2px 0px var(--neo-black);">🥉</span>
                                @else
                                    #{{ $rank }}
                                @endif
                            </td>
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 neo-border overflow-hidden bg-gray-200" style="box-shadow: 2px 2px 0px var(--neo-black);">
                                        @if($row->user->avatar)
                                            <img src="{{ $row->user->avatar }}" alt="{{ $row->user->name }}" class="w-full h-full object-cover" loading="lazy">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center font-bold text-xl">{{ substr($row->user->name, 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-black text-lg leading-none uppercase">{{ $row->user->name }}</span>
                                        <span class="text-xs font-bold text-gray-500 uppercase" style="font-family: 'Space Mono', monospace;">Member sejak {{ $row->user->created_at->format('M Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-right font-black text-2xl" style="font-family: 'Space Mono', monospace;">
                                {{ number_format($row->total_score) }}
                            </td>
                            <td class="py-5 px-6 text-right font-bold text-lg hidden md:table-cell" style="font-family: 'Space Mono', monospace;">
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
