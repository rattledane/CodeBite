<style>
    @keyframes confettiSlide {
        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
        100% { transform: translateY(200px) rotate(360deg); opacity: 0; }
    }
    @keyframes crownBounce {
        0%, 100% { transform: scale(1) rotate(0deg); }
        25% { transform: scale(1.2) rotate(-10deg); }
        50% { transform: scale(1.1) rotate(5deg); }
        75% { transform: scale(1.15) rotate(-5deg); }
    }
    .confetti-piece {
        position: absolute;
        width: 12px;
        height: 12px;
        border: 2px solid var(--neo-black);
        animation: confettiSlide 2s infinite linear;
    }
    .confetti-piece:nth-child(2n) { background-color: var(--neo-green); animation-delay: 0.2s; }
    .confetti-piece:nth-child(3n) { background-color: var(--neo-blue); animation-delay: 0.4s; }
    .confetti-piece:nth-child(4n) { background-color: var(--neo-yellow); animation-delay: 0.6s; }
    .confetti-piece:nth-child(5n) { background-color: var(--neo-pink); animation-delay: 0.8s; }
    .confetti-piece:nth-child(6n) { background-color: var(--neo-purple); animation-delay: 1.0s; }
    .confetti-piece:nth-child(7n) { background-color: var(--neo-orange); animation-delay: 0.3s; }
    .crown-anim { animation: crownBounce 1.5s ease-in-out infinite; }
</style>

<!-- Full-screen Overlay Modal with Alpine Transitions -->
<div x-show="showFinishModal"
     class="fixed inset-0 z-[100] flex flex-col items-center justify-center"
     style="display: none; background: var(--neo-yellow);"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="transform translate-y-full opacity-0"
     x-transition:enter-end="transform translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="transform translate-y-0 opacity-100"
     x-transition:leave-end="transform translate-y-full opacity-0">

    <div class="text-center mb-8 relative">
        <div class="inline-block neo-border px-4 py-1 mb-4 animate-bounce-in" style="background: white; box-shadow: var(--neo-shadow-sm); font-family: 'Space Mono', monospace; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">
            🏁 Finished
        </div>
        <h1 class="text-5xl md:text-7xl font-black uppercase tracking-tighter animate-bounce-in" style="font-family: 'Space Mono', monospace;">
            Race Selesai!
        </h1>

        <!-- Confetti container centered around the title -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden" x-show="showFinishModal">
            <template x-for="i in 20">
                <div class="confetti-piece" :style="`left: ${Math.random() * 100}%; top: -50px; animation-duration: ${Math.random() * 2 + 1}s;`"></div>
            </template>
        </div>
    </div>

    <!-- Podium Container -->
    <div class="flex items-end justify-center gap-4 mb-12 h-64">

        <!-- 2nd Place -->
        <template x-if="finalRankings.length > 1">
            <div class="flex flex-col items-center w-32 translate-y-8 animate-slide-in stagger-2">
                <div class="mb-2 font-bold text-lg truncate w-full text-center" style="font-family: 'Space Mono', monospace;" x-text="finalRankings[1].username"></div>
                <div class="w-16 h-16 neo-border overflow-hidden bg-white mb-[-20px] z-10" style="box-shadow: 2px 2px 0px var(--neo-black);">
                    <img :src="finalRankings[1].avatar || 'https://ui-avatars.com/api/?name=' + finalRankings[1].username" class="w-full h-full object-cover">
                </div>
                <div class="w-full h-32 neo-border flex flex-col items-center justify-center pt-6" style="background: #C0C0C0; box-shadow: 6px 6px 0px var(--neo-black);">
                    <span class="text-4xl font-black" style="font-family: 'Space Mono', monospace;">2</span>
                    <span class="font-bold mt-2" style="font-family: 'Space Mono', monospace; font-size: 13px;" x-text="finalRankings[1].score + ' pts'"></span>
                </div>
            </div>
        </template>

        <!-- 1st Place -->
        <template x-if="finalRankings.length > 0">
            <div class="flex flex-col items-center w-40 z-20 animate-slide-in stagger-1">
                <div class="text-4xl mb-1 crown-anim">👑</div>
                <div class="mb-2 font-bold text-xl truncate w-full text-center" style="font-family: 'Space Mono', monospace;" x-text="finalRankings[0].username"></div>
                <div class="w-20 h-20 neo-border overflow-hidden bg-white mb-[-24px] z-10" style="box-shadow: 3px 3px 0px var(--neo-black);">
                    <img :src="finalRankings[0].avatar || 'https://ui-avatars.com/api/?name=' + finalRankings[0].username" class="w-full h-full object-cover">
                </div>
                <div class="w-full h-48 neo-border flex flex-col items-center justify-center pt-8" style="background: #FFD700; box-shadow: 8px 8px 0px var(--neo-black);">
                    <span class="text-6xl font-black" style="font-family: 'Space Mono', monospace;">1</span>
                    <span class="font-bold mt-2 text-lg" style="font-family: 'Space Mono', monospace;" x-text="finalRankings[0].score + ' pts'"></span>
                </div>
            </div>
        </template>

        <!-- 3rd Place -->
        <template x-if="finalRankings.length > 2">
            <div class="flex flex-col items-center w-32 translate-y-16 animate-slide-in stagger-3">
                <div class="mb-2 font-bold text-lg truncate w-full text-center" style="font-family: 'Space Mono', monospace;" x-text="finalRankings[2].username"></div>
                <div class="w-16 h-16 neo-border overflow-hidden bg-white mb-[-20px] z-10" style="box-shadow: 2px 2px 0px var(--neo-black);">
                    <img :src="finalRankings[2].avatar || 'https://ui-avatars.com/api/?name=' + finalRankings[2].username" class="w-full h-full object-cover">
                </div>
                <div class="w-full h-24 neo-border flex flex-col items-center justify-center pt-6" style="background: #CD7F32; box-shadow: 6px 6px 0px var(--neo-black);">
                    <span class="text-4xl font-black" style="font-family: 'Space Mono', monospace;">3</span>
                    <span class="font-bold mt-2" style="font-family: 'Space Mono', monospace; font-size: 13px;" x-text="finalRankings[2].score + ' pts'"></span>
                </div>
            </div>
        </template>

    </div>

    <!-- Rankings Table (Remaining Players) -->
    <template x-if="finalRankings.length > 3">
        <div class="w-full max-w-2xl neo-card mb-8 max-h-48 overflow-y-auto" style="box-shadow: 8px 8px 0px var(--neo-black);">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr style="background: var(--neo-black); color: white; font-family: 'Space Mono', monospace; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 4px solid var(--neo-black);">
                        <th class="p-3 font-black w-16 text-center" style="border-right: 4px solid var(--neo-black);">#</th>
                        <th class="p-3 font-black" style="border-right: 4px solid var(--neo-black);">Pemain</th>
                        <th class="p-3 font-black text-right">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(p, index) in finalRankings.slice(3)" :key="p.user_id">
                        <tr class="hover:translate-x-1 transition-transform" style="border-bottom: 3px solid var(--neo-black);">
                            <td class="p-3 font-black text-center" style="border-right: 3px solid var(--neo-black); font-family: 'Space Mono', monospace;" x-text="index + 4"></td>
                            <td class="p-3 font-bold flex items-center gap-3" style="border-right: 3px solid var(--neo-black);">
                                <div class="w-8 h-8 neo-border overflow-hidden bg-white" style="box-shadow: 2px 2px 0px var(--neo-black);">
                                    <img :src="p.avatar || 'https://ui-avatars.com/api/?name=' + p.username" class="w-full h-full object-cover">
                                </div>
                                <span x-text="p.username" style="font-family: 'Space Mono', monospace;"></span>
                            </td>
                            <td class="p-3 font-bold text-right" style="font-family: 'Space Mono', monospace;" x-text="p.score"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </template>

    <!-- Actions -->
    <div class="flex gap-6 mt-8 flex-wrap justify-center">
        <!-- Host Only Restart Button -->
        <template x-if="finalRankings.length > 0 && isHost()">
            <button @click="restartRoom()" class="neo-btn text-xl uppercase tracking-wider" style="background: var(--neo-green); padding: 16px 32px; font-family: 'Space Mono', monospace; box-shadow: var(--neo-shadow);">
                🔄 Main Lagi
            </button>
        </template>

        <a href="{{ route('rooms.create') }}" class="neo-btn text-xl uppercase tracking-wider" style="padding: 16px 32px; font-family: 'Space Mono', monospace; box-shadow: var(--neo-shadow);">
            🏠 Lobby Baru
        </a>
        <a href="{{ route('leaderboards.index') }}" class="neo-btn text-xl uppercase tracking-wider" style="background: var(--neo-teal); padding: 16px 32px; font-family: 'Space Mono', monospace; box-shadow: var(--neo-shadow);">
            🏆 Leaderboard
        </a>
    </div>

</div>
