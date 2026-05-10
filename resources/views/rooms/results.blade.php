<style>
    @keyframes confettiSlide {
        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
        100% { transform: translateY(200px) rotate(360deg); opacity: 0; }
    }
    .confetti-piece {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #f00;
        animation: confettiSlide 2s infinite linear;
    }
    /* Add multiple colors */
    .confetti-piece:nth-child(2n) { background-color: #0f0; animation-delay: 0.2s; }
    .confetti-piece:nth-child(3n) { background-color: #00f; animation-delay: 0.4s; }
    .confetti-piece:nth-child(4n) { background-color: #ff0; animation-delay: 0.6s; }
    .confetti-piece:nth-child(5n) { background-color: #f0f; animation-delay: 0.8s; }
    .confetti-piece:nth-child(6n) { background-color: #0ff; animation-delay: 1.0s; }
</style>

<!-- Full-screen Overlay Modal with Alpine Transitions -->
<div x-show="showFinishModal" 
     class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-[#f3f4f6]" 
     style="display: none;"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="transform translate-y-full opacity-0"
     x-transition:enter-end="transform translate-y-0 opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="transform translate-y-0 opacity-100"
     x-transition:leave-end="transform translate-y-full opacity-0">

    <div class="text-center mb-8 relative">
        <h1 class="text-6xl font-black uppercase tracking-tighter" style="text-shadow: 4px 4px 0px #FFE500, -2px -2px 0 #000, 2px -2px 0 #000, -2px 2px 0 #000, 2px 2px 0 #000;">
            Race Selesai!
        </h1>
        
        <!-- Confetti container centered around the title -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden" x-show="showFinishModal">
            <template x-for="i in 20">
                <div class="confetti-piece border-2 border-black" :style="`left: ${Math.random() * 100}%; top: -50px; animation-duration: ${Math.random() * 2 + 1}s;`"></div>
            </template>
        </div>
    </div>

    <!-- Podium Container -->
    <div class="flex items-end justify-center gap-4 mb-12 h-64">
        
        <!-- 2nd Place -->
        <template x-if="finalRankings.length > 1">
            <div class="flex flex-col items-center w-32 translate-y-8 animate-bounce" style="animation-delay: 0.2s; animation-iteration-count: 1;">
                <div class="mb-2 font-bold text-lg truncate w-full text-center" x-text="finalRankings[1].username"></div>
                <div class="w-16 h-16 border-4 border-black rounded-full overflow-hidden bg-white mb-[-20px] z-10">
                    <img :src="finalRankings[1].avatar || 'https://ui-avatars.com/api/?name=' + finalRankings[1].username" class="w-full h-full object-cover">
                </div>
                <div class="w-full h-32 bg-[#C0C0C0] border-4 border-black shadow-[8px_8px_0px_#000] flex flex-col items-center justify-center pt-6">
                    <span class="text-4xl font-black">2</span>
                    <span class="font-mono font-bold mt-2" x-text="finalRankings[1].score + ' pts'"></span>
                </div>
            </div>
        </template>

        <!-- 1st Place -->
        <template x-if="finalRankings.length > 0">
            <div class="flex flex-col items-center w-40 z-20 animate-bounce" style="animation-iteration-count: 1;">
                <div class="text-4xl mb-1">👑</div>
                <div class="mb-2 font-bold text-xl truncate w-full text-center" x-text="finalRankings[0].username"></div>
                <div class="w-20 h-20 border-4 border-black rounded-full overflow-hidden bg-white mb-[-24px] z-10">
                    <img :src="finalRankings[0].avatar || 'https://ui-avatars.com/api/?name=' + finalRankings[0].username" class="w-full h-full object-cover">
                </div>
                <div class="w-full h-48 bg-[#FFD700] border-4 border-black shadow-[10px_10px_0px_#000] flex flex-col items-center justify-center pt-8">
                    <span class="text-6xl font-black">1</span>
                    <span class="font-mono font-bold mt-2 text-lg" x-text="finalRankings[0].score + ' pts'"></span>
                </div>
            </div>
        </template>

        <!-- 3rd Place -->
        <template x-if="finalRankings.length > 2">
            <div class="flex flex-col items-center w-32 translate-y-16 animate-bounce" style="animation-delay: 0.4s; animation-iteration-count: 1;">
                <div class="mb-2 font-bold text-lg truncate w-full text-center" x-text="finalRankings[2].username"></div>
                <div class="w-16 h-16 border-4 border-black rounded-full overflow-hidden bg-white mb-[-20px] z-10">
                    <img :src="finalRankings[2].avatar || 'https://ui-avatars.com/api/?name=' + finalRankings[2].username" class="w-full h-full object-cover">
                </div>
                <div class="w-full h-24 bg-[#CD7F32] border-4 border-black shadow-[8px_8px_0px_#000] flex flex-col items-center justify-center pt-6">
                    <span class="text-4xl font-black">3</span>
                    <span class="font-mono font-bold mt-2" x-text="finalRankings[2].score + ' pts'"></span>
                </div>
            </div>
        </template>
        
    </div>

    <!-- Rankings Table (Remaining Players) -->
    <template x-if="finalRankings.length > 3">
        <div class="w-full max-w-2xl bg-white border-4 border-black shadow-[8px_8px_0px_#000] mb-8 max-h-48 overflow-y-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FFE500] border-b-4 border-black">
                        <th class="p-3 font-black border-r-4 border-black w-16 text-center">#</th>
                        <th class="p-3 font-black border-r-4 border-black">Pemain</th>
                        <th class="p-3 font-black text-right">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(p, index) in finalRankings.slice(3)" :key="p.user_id">
                        <tr class="border-b-4 border-black last:border-b-0 hover:bg-gray-100">
                            <td class="p-3 border-r-4 border-black font-black text-center" x-text="index + 4"></td>
                            <td class="p-3 border-r-4 border-black font-bold flex items-center gap-3">
                                <div class="w-8 h-8 border-2 border-black rounded-full overflow-hidden bg-white">
                                    <img :src="p.avatar || 'https://ui-avatars.com/api/?name=' + p.username" class="w-full h-full object-cover">
                                </div>
                                <span x-text="p.username"></span>
                            </td>
                            <td class="p-3 font-mono font-bold text-right" x-text="p.score"></td>
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
            <button @click="restartRoom()" class="px-8 py-4 bg-[#00ff88] border-4 border-black shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-[0px_0px_0px_#000] font-black uppercase text-xl transition-all">
                Main Lagi
            </button>
        </template>
        
        <a href="{{ route('rooms.create') }}" class="px-8 py-4 bg-white border-4 border-black shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-[0px_0px_0px_#000] font-black uppercase text-xl transition-all inline-block">
            Lobby Baru
        </a>
        <a href="{{ route('leaderboards.index') }}" class="px-8 py-4 bg-[#87CEEB] border-4 border-black shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-[0px_0px_0px_#000] font-black uppercase text-xl transition-all inline-block">
            Lihat Leaderboard
        </a>
    </div>

</div>
