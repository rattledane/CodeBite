@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">

        <!-- 1. Profile Header -->
        <div class="neo-card p-6 sm:p-8 mb-8 sm:mb-12 flex flex-col md:flex-row items-center gap-6 sm:gap-8 animate-slide-in" style="box-shadow: 8px 8px 0px var(--neo-black);">
            <div class="w-24 h-24 sm:w-32 sm:h-32 neo-border overflow-hidden bg-gray-200 flex-shrink-0 relative" style="box-shadow: var(--neo-shadow-sm);">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover" loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center font-black text-4xl sm:text-5xl" style="background: var(--neo-yellow);">{{ substr($user->name, 0, 1) }}</div>
                @endif
            </div>
            <div class="flex-grow text-center md:text-left">
                <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight" style="font-family: 'Space Mono', monospace;">{{ $user->name }}</h1>
                <p class="text-lg sm:text-xl font-bold text-gray-600 mb-2">{{ $user->email }}</p>
                <div class="inline-block neo-border px-3 py-1 text-sm font-bold uppercase" style="background: var(--neo-green); box-shadow: 2px 2px 0px var(--neo-black); font-family: 'Space Mono', monospace; letter-spacing: 1px;">
                    Member sejak {{ $user->created_at->format('M Y') }}
                </div>
            </div>
            <div class="mt-4 md:mt-0 w-full md:w-auto">
                <button class="neo-btn w-full md:w-auto uppercase tracking-wider" style="background: var(--neo-yellow); padding: 12px 24px; font-family: 'Space Mono', monospace;">
                    Edit Profil
                </button>
            </div>
        </div>

        <!-- 2. Stats Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Total XP -->
            <div class="neo-border p-6 animate-slide-in stagger-1" style="background: var(--neo-yellow); box-shadow: var(--neo-shadow);">
                <div class="text-sm font-black uppercase mb-2" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Total XP</div>
                <div class="text-4xl font-black tracking-tighter" style="font-family: 'Space Mono', monospace;">{{ number_format($totalXp) }}</div>
            </div>
            <!-- Games Completed -->
            <div class="neo-border p-6 animate-slide-in stagger-2" style="background: var(--neo-blue); color: white; box-shadow: var(--neo-shadow);">
                <div class="text-sm font-black uppercase mb-2" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Game Selesai</div>
                <div class="text-4xl font-black tracking-tighter" style="font-family: 'Space Mono', monospace;">{{ $completedGamesCount }}<span class="text-2xl" style="opacity: 0.6;">/{{ $games->count() }}</span></div>
            </div>
            <!-- Multiplayer Joined -->
            <div class="neo-border p-6 animate-slide-in stagger-3" style="background: var(--neo-pink); color: white; box-shadow: var(--neo-shadow);">
                <div class="text-sm font-black uppercase mb-2" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Race Diikuti</div>
                <div class="text-4xl font-black tracking-tighter" style="font-family: 'Space Mono', monospace;">{{ number_format($racesJoined) }}</div>
            </div>
            <!-- Best Global Rank -->
            <div class="neo-border p-6 animate-slide-in stagger-4" style="background: var(--neo-green); box-shadow: var(--neo-shadow);">
                <div class="text-sm font-black uppercase mb-2" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Ranking Global</div>
                <div class="text-4xl font-black tracking-tighter" style="font-family: 'Space Mono', monospace;">#{{ $rank }}</div>
            </div>
        </div>

        <!-- 5. Achievement Badges -->
        <div class="mb-12">
            <h2 class="text-3xl font-black uppercase mb-6 animate-slide-in" style="font-family: 'Space Mono', monospace;">
                <span style="background: var(--neo-yellow); padding: 2px 8px; border: 2px solid var(--neo-black);">Pencapaian</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- First Win -->
                <div class="neo-border p-6 flex items-center gap-4 transition-all animate-slide-in stagger-1 {{ $achievements['first_win'] ? '' : 'opacity-50' }}" style="background: {{ $achievements['first_win'] ? 'white' : '#e5e5e0' }}; box-shadow: var(--neo-shadow-sm);">
                    <div class="text-5xl">{{ $achievements['first_win'] ? '🏆' : '🔒' }}</div>
                    <div>
                        <h3 class="font-black uppercase text-lg" style="font-family: 'Space Mono', monospace;">First Win</h3>
                        <p class="text-sm font-bold text-gray-600">Menang di mode Multiplayer.</p>
                    </div>
                </div>
                <!-- Speed Demon -->
                <div class="neo-border p-6 flex items-center gap-4 transition-all animate-slide-in stagger-2 {{ $achievements['speed_demon'] ? '' : 'opacity-50' }}" style="background: {{ $achievements['speed_demon'] ? 'white' : '#e5e5e0' }}; box-shadow: var(--neo-shadow-sm);">
                    <div class="text-5xl">{{ $achievements['speed_demon'] ? '⚡' : '🔒' }}</div>
                    <div>
                        <h3 class="font-black uppercase text-lg" style="font-family: 'Space Mono', monospace;">Speed Demon</h3>
                        <p class="text-sm font-bold text-gray-600">Selesai 1 level &lt; 10 detik.</p>
                    </div>
                </div>
                <!-- Completionist -->
                <div class="neo-border p-6 flex items-center gap-4 transition-all animate-slide-in stagger-3 {{ $achievements['completionist'] ? '' : 'opacity-50' }}" style="background: {{ $achievements['completionist'] ? 'white' : '#e5e5e0' }}; box-shadow: var(--neo-shadow-sm);">
                    <div class="text-5xl">{{ $achievements['completionist'] ? '💯' : '🔒' }}</div>
                    <div>
                        <h3 class="font-black uppercase text-lg" style="font-family: 'Space Mono', monospace;">Completionist</h3>
                        <p class="text-sm font-bold text-gray-600">Tamatkan satu game penuh.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Per-game Progress -->
        <div class="mb-12">
            <h2 class="text-3xl font-black uppercase mb-6 animate-slide-in" style="font-family: 'Space Mono', monospace;">
                <span style="background: var(--neo-green); padding: 2px 8px; border: 2px solid var(--neo-black);">Progres Game</span>
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($gameProgress as $idx => $gp)
                    @php
                        $progressPercentage = $gp['total_levels'] > 0 ? min(100, ($gp['completed_levels'] / $gp['total_levels']) * 100) : 0;
                    @endphp
                    <div class="neo-card p-6 flex flex-col animate-slide-in" style="animation-delay: {{ $idx * 0.1 }}s; box-shadow: var(--neo-shadow);">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl md:text-2xl font-black uppercase" style="font-family: 'Space Mono', monospace;">{{ $gp['game']->title }}</h3>
                                <p class="text-sm font-bold text-gray-500">{{ $gp['game']->description }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-black uppercase text-gray-500" style="font-family: 'Space Mono', monospace;">Skor</div>
                                <div class="text-xl font-black" style="font-family: 'Space Mono', monospace;">{{ number_format($gp['best_score']) }}</div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-2 flex justify-between text-sm font-bold uppercase" style="font-family: 'Space Mono', monospace; font-size: 12px; letter-spacing: 1px;">
                            <span>Progres</span>
                            <span>{{ $gp['completed_levels'] }}/{{ $gp['total_levels'] }} Level</span>
                        </div>
                        <div class="h-5 w-full neo-border bg-gray-100 mb-6 relative overflow-hidden">
                            <div class="h-full transition-all duration-1000 ease-out" style="width: {{ $progressPercentage }}%; background: var(--neo-green); border-right: {{ $progressPercentage > 0 && $progressPercentage < 100 ? '3px solid var(--neo-black)' : 'none' }};"></div>
                        </div>

                        <div class="mt-auto flex gap-4">
                            @if($gp['is_completed'])
                                <a href="{{ route('games.play', $gp['game']->slug) }}" class="neo-btn flex-1 text-center uppercase text-sm" style="font-family: 'Space Mono', monospace;">
                                    Ulangi Game
                                </a>
                            @else
                                <a href="{{ route('games.play', $gp['game']->slug) }}" class="neo-btn flex-1 text-center uppercase text-sm" style="background: var(--neo-yellow); font-family: 'Space Mono', monospace;">
                                    {{ $gp['completed_levels'] > 0 ? 'Lanjutkan' : 'Mulai Main' }}
                                </a>
                            @endif
                            <div class="neo-border px-4 py-2 bg-gray-100 flex items-center justify-center font-bold text-sm" style="font-family: 'Space Mono', monospace; box-shadow: 2px 2px 0px var(--neo-black);" title="Waktu Bermain">
                                ⏱️ {{ round($gp['time_played'] / 60) }} min
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 4. Race History Table -->
        <div class="mb-12">
            <h2 class="text-3xl font-black uppercase mb-6 animate-slide-in" style="font-family: 'Space Mono', monospace;">
                <span style="background: var(--neo-pink); color: white; padding: 2px 8px; border: 2px solid var(--neo-black);">Riwayat Balapan</span>
            </h2>
            <div class="neo-card overflow-hidden animate-slide-in" style="box-shadow: 8px 8px 0px var(--neo-black);">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr style="background: var(--neo-black); color: white; font-family: 'Space Mono', monospace; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 4px solid var(--neo-black);">
                                <th class="py-4 px-6 text-left w-32">Tanggal</th>
                                <th class="py-4 px-6 text-left">Game</th>
                                <th class="py-4 px-6 text-center">Peserta</th>
                                <th class="py-4 px-6 text-center">Rank</th>
                                <th class="py-4 px-6 text-right">Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($raceHistory as $index => $history)
                                <tr class="border-b-2 border-black transition-colors hover:translate-x-1" style="background: {{ $index % 2 === 0 ? 'white' : '#f9f9f4' }};">
                                    <td class="py-4 px-6 font-bold text-sm" style="font-family: 'Space Mono', monospace;">
                                        {{ $history->finished_at ? $history->finished_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="py-4 px-6 font-black uppercase">
                                        {{ $history->room->game->title ?? 'Unknown' }}
                                    </td>
                                    <td class="py-4 px-6 text-center font-bold" style="font-family: 'Space Mono', monospace;">
                                        {{ $history->room->participants->count() ?? '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($history->rank === 1)
                                            <span class="inline-block px-3 py-1 neo-border font-black" style="background: #FFD700; box-shadow: 2px 2px 0px var(--neo-black);">1st</span>
                                        @elseif($history->rank === 2)
                                            <span class="inline-block px-3 py-1 neo-border font-black" style="background: #C0C0C0; box-shadow: 2px 2px 0px var(--neo-black);">2nd</span>
                                        @elseif($history->rank === 3)
                                            <span class="inline-block px-3 py-1 neo-border font-black" style="background: #CD7F32; box-shadow: 2px 2px 0px var(--neo-black);">3rd</span>
                                        @else
                                            <span class="font-black">{{ $history->rank ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-lg" style="font-family: 'Space Mono', monospace;">
                                        {{ number_format($history->score) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center font-black uppercase text-xl text-gray-400" style="font-family: 'Space Mono', monospace;">
                                        Belum ada riwayat balapan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">
                {{ $raceHistory->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
