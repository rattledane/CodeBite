@extends('layouts.app')

@section('content')
<div class="bg-[#f3f4f6] min-h-screen pb-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">

        <!-- 1. Profile Header -->
        <div class="bg-white neo-border p-6 sm:p-8 shadow-[8px_8px_0px_#000] mb-8 sm:mb-12 flex flex-col md:flex-row items-center gap-6 sm:gap-8">
            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-4 border-black overflow-hidden bg-gray-200 flex-shrink-0 relative neo-shadow-sm">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover" loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center font-black text-4xl sm:text-5xl">{{ substr($user->name, 0, 1) }}</div>
                @endif
            </div>
            <div class="flex-grow text-center md:text-left">
                <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight">{{ $user->name }}</h1>
                <p class="text-lg sm:text-xl font-bold text-gray-600 mb-2">{{ $user->email }}</p>
                <div class="inline-block bg-[#00ff88] border-2 border-black px-3 py-1 text-sm font-bold uppercase shadow-[2px_2px_0px_#000]">
                    Member sejak {{ $user->created_at->format('M Y') }}
                </div>
            </div>
            <div class="mt-4 md:mt-0 w-full md:w-auto">
                <button class="w-full md:w-auto bg-[#FFE500] neo-border neo-button-hover shadow-[4px_4px_0px_#000] px-6 py-3 font-black uppercase tracking-wider h-[52px]">
                    Edit Profil
                </button>
            </div>
        </div>

        <!-- 2. Stats Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Total XP -->
            <div class="bg-[#FFE500] neo-border p-6 shadow-[6px_6px_0px_#000]">
                <div class="text-sm font-black uppercase mb-2">Total XP</div>
                <div class="text-4xl font-black font-mono tracking-tighter">{{ number_format($totalXp) }}</div>
            </div>
            <!-- Games Completed -->
            <div class="bg-[#1E90FF] text-white neo-border p-6 shadow-[6px_6px_0px_#000]">
                <div class="text-sm font-black uppercase mb-2">Game Selesai</div>
                <div class="text-4xl font-black font-mono tracking-tighter">{{ $completedGamesCount }}<span class="text-2xl text-blue-200">/{{ $games->count() }}</span></div>
            </div>
            <!-- Multiplayer Joined -->
            <div class="bg-[#FF1493] text-white neo-border p-6 shadow-[6px_6px_0px_#000]">
                <div class="text-sm font-black uppercase mb-2">Race Diikuti</div>
                <div class="text-4xl font-black font-mono tracking-tighter">{{ number_format($racesJoined) }}</div>
            </div>
            <!-- Best Global Rank -->
            <div class="bg-[#00ff88] neo-border p-6 shadow-[6px_6px_0px_#000]">
                <div class="text-sm font-black uppercase mb-2">Ranking Global</div>
                <div class="text-4xl font-black font-mono tracking-tighter">#{{ $rank }}</div>
            </div>
        </div>

        <!-- 5. Achievement Badges -->
        <div class="mb-12">
            <h2 class="text-3xl font-black uppercase mb-6" style="text-shadow: 2px 2px 0px #FFE500;">Pencapaian</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- First Win -->
                <div class="neo-border p-6 shadow-[4px_4px_0px_#000] flex items-center gap-4 transition-all {{ $achievements['first_win'] ? 'bg-white' : 'bg-gray-200 opacity-60' }}">
                    <div class="text-5xl">{{ $achievements['first_win'] ? '🏆' : '🔒' }}</div>
                    <div>
                        <h3 class="font-black uppercase text-lg">First Win</h3>
                        <p class="text-sm font-bold text-gray-600">Menang di mode Multiplayer.</p>
                    </div>
                </div>
                <!-- Speed Demon -->
                <div class="neo-border p-6 shadow-[4px_4px_0px_#000] flex items-center gap-4 transition-all {{ $achievements['speed_demon'] ? 'bg-white' : 'bg-gray-200 opacity-60' }}">
                    <div class="text-5xl">{{ $achievements['speed_demon'] ? '⚡' : '🔒' }}</div>
                    <div>
                        <h3 class="font-black uppercase text-lg">Speed Demon</h3>
                        <p class="text-sm font-bold text-gray-600">Selesai 1 level &lt; 10 detik.</p>
                    </div>
                </div>
                <!-- Completionist -->
                <div class="neo-border p-6 shadow-[4px_4px_0px_#000] flex items-center gap-4 transition-all {{ $achievements['completionist'] ? 'bg-white' : 'bg-gray-200 opacity-60' }}">
                    <div class="text-5xl">{{ $achievements['completionist'] ? '💯' : '🔒' }}</div>
                    <div>
                        <h3 class="font-black uppercase text-lg">Completionist</h3>
                        <p class="text-sm font-bold text-gray-600">Tamatkan satu game penuh.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Per-game Progress -->
        <div class="mb-12">
            <h2 class="text-3xl font-black uppercase mb-6" style="text-shadow: 2px 2px 0px #00ff88;">Progres Game</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($gameProgress as $gp)
                    @php
                        $progressPercentage = $gp['total_levels'] > 0 ? min(100, ($gp['completed_levels'] / $gp['total_levels']) * 100) : 0;
                    @endphp
                    <div class="bg-white neo-border p-6 shadow-[6px_6px_0px_#000] flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-2xl font-black uppercase">{{ $gp['game']->title }}</h3>
                                <p class="text-sm font-bold text-gray-500">{{ $gp['game']->description }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-black uppercase text-gray-500">Skor</div>
                                <div class="text-xl font-black font-mono">{{ number_format($gp['best_score']) }}</div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-2 flex justify-between text-sm font-bold uppercase">
                            <span>Progres</span>
                            <span>{{ $gp['completed_levels'] }}/{{ $gp['total_levels'] }} Level</span>
                        </div>
                        <div class="h-4 w-full border-2 border-black bg-gray-200 mb-6 relative overflow-hidden">
                            <div class="h-full bg-[#00ff88] border-r-2 border-black transition-all" style="width: {{ $progressPercentage }}%;"></div>
                        </div>

                        <div class="mt-auto flex gap-4">
                            @if($gp['is_completed'])
                                <a href="{{ route('games.play', $gp['game']->slug) }}" class="flex-1 bg-white border-2 border-black shadow-[4px_4px_0px_#000] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all px-4 py-2 text-center font-black uppercase">
                                    Ulangi Game
                                </a>
                            @else
                                <a href="{{ route('games.play', $gp['game']->slug) }}" class="flex-1 bg-[#FFE500] border-2 border-black shadow-[4px_4px_0px_#000] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all px-4 py-2 text-center font-black uppercase">
                                    {{ $gp['completed_levels'] > 0 ? 'Lanjutkan' : 'Mulai Main' }}
                                </a>
                            @endif
                            <div class="px-4 py-2 border-2 border-black bg-gray-100 flex items-center justify-center font-bold text-sm" title="Waktu Bermain">
                                ⏱️ {{ round($gp['time_played'] / 60) }} min
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 4. Race History Table -->
        <div class="mb-12">
            <h2 class="text-3xl font-black uppercase mb-6" style="text-shadow: 2px 2px 0px #FF69B4;">Riwayat Balapan</h2>
            <div class="bg-white neo-border shadow-[8px_8px_0px_#000] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-black text-white uppercase font-black text-sm border-b-4 border-black">
                                <th class="py-4 px-6 text-left w-32">Tanggal</th>
                                <th class="py-4 px-6 text-left">Game</th>
                                <th class="py-4 px-6 text-center">Peserta</th>
                                <th class="py-4 px-6 text-center">Rank</th>
                                <th class="py-4 px-6 text-right">Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($raceHistory as $history)
                                <tr class="border-b-2 border-black bg-white hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 font-bold text-sm">
                                        {{ $history->finished_at ? $history->finished_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="py-4 px-6 font-black uppercase">
                                        {{ $history->room->game->title ?? 'Unknown' }}
                                    </td>
                                    <td class="py-4 px-6 text-center font-bold font-mono">
                                        {{ $history->room->participants->count() ?? '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($history->rank === 1)
                                            <span class="inline-block px-3 py-1 bg-[#FFD700] border-2 border-black shadow-[2px_2px_0px_#000] font-black">1st</span>
                                        @elseif($history->rank === 2)
                                            <span class="inline-block px-3 py-1 bg-[#C0C0C0] border-2 border-black shadow-[2px_2px_0px_#000] font-black">2nd</span>
                                        @elseif($history->rank === 3)
                                            <span class="inline-block px-3 py-1 bg-[#CD7F32] border-2 border-black shadow-[2px_2px_0px_#000] font-black">3rd</span>
                                        @else
                                            <span class="font-black">{{ $history->rank ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right font-black font-mono text-lg">
                                        {{ number_format($history->score) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center font-black uppercase text-xl text-gray-400">
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
