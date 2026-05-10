@if(request()->query('room'))
    <!-- MULTIPLAYER PANEL -->
    <div class="w-full lg:w-[30%] lg:h-full p-4 sm:p-6 flex flex-col bg-white lg:border-l-4 border-black min-h-[400px]" x-data="multiplayerBoard('{{ request()->query('room') }}')">
        <h2 class="text-3xl font-black uppercase border-b-4 border-black pb-2 mb-6 flex flex-col sm:flex-row sm:items-center gap-2">
            🏆 Papan Skor Live
            <span class="text-sm bg-[#FFE500] px-2 py-1 neo-border sm:ms-auto mt-2 sm:mt-0 w-max">ROOM: {{ strtoupper(request()->query('room')) }}</span>
        </h2>

        <!-- Current Player Header -->
        <div class="flex gap-4 mb-6">
            <div class="flex-1 neo-border bg-red-500 text-white p-3 text-center">
                <div class="text-xs font-bold uppercase mb-1">Time Left</div>
                <div class="text-2xl font-mono font-black" x-text="formatTime()"></div>
            </div>
            <div class="flex-1 neo-border bg-[#00ff88] p-3 text-center">
                <div class="text-xs font-bold uppercase mb-1">My Score</div>
                <div class="text-2xl font-mono font-black" x-text="displayScore"></div>
            </div>
        </div>

        <div class="flex-grow overflow-y-auto space-y-4 pr-2">
            <template x-if="players.length === 0">
                <div class="text-center p-8 bg-gray-100 font-bold border-4 border-black border-dashed">
                    Menunggu pemain...
                </div>
            </template>

            <!-- Leaderboard List -->
            <template x-for="(p, index) in sortedPlayers" :key="p.user_id">
                <div class="bg-white neo-border p-3 transition-all relative overflow-hidden"
                     :class="{'bg-[#FFE500]': p.user_id == '{{ auth()->id() }}'}">
                    
                    <div class="flex items-center gap-3 mb-2">
                        <!-- Rank -->
                        <div class="w-8 h-8 flex items-center justify-center font-black text-lg bg-black text-white neo-border" x-text="index + 1"></div>
                        
                        <!-- Avatar -->
                        <div class="w-10 h-10 border-2 border-black bg-white overflow-hidden flex-shrink-0">
                            <img :src="p.avatar || 'https://ui-avatars.com/api/?name=' + p.username" class="w-full h-full object-cover">
                        </div>

                        <!-- Name & Score -->
                        <div class="flex-grow min-w-0">
                            <div class="font-bold truncate text-sm" x-text="p.username"></div>
                            <div class="font-mono font-black text-sm" x-text="p.score"></div>
                        </div>
                    </div>
                    <!-- Progress Bar -->
                    <div class="h-2 w-full border-2 border-black bg-white overflow-hidden mt-2">
                        <div class="h-full bg-[#00ff88] transition-all duration-500" :style="`width: ${Math.min((p.current_level / {{ count($levels) }}) * 100, 100)}%`"></div>
                    </div>
                    
                    <!-- Floating Point Animation -->
                    <div x-show="p.showPlus" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-8"
                         class="absolute right-4 top-2 text-[#00ff88] font-black text-xl"
                         style="text-shadow: 2px 2px 0px #000;"
                         x-text="'+' + p.lastScoreDelta">
                    </div>
                </div>
            </template>
        </div>

        <!-- Status Footer -->
        <div class="mt-4 p-3 bg-black text-white font-bold text-center neo-border text-sm flex justify-between items-center">
            <span x-text="myRank ? `You're in ${myRank}${getOrdinal(myRank)} place!` : 'Connecting...'"></span>
            <span class="animate-pulse">🔥</span>
        </div>

        <!-- Race Finished Modal (Now a separate partial) -->
        @include('rooms.results')
    </div>
@else
    <!-- Original Right Panel (SOLO MODE) -->
    <div class="w-full lg:w-[30%] lg:h-full p-4 sm:p-6 flex flex-col bg-white min-h-[400px]">
        <!-- Score & Timer & Streak -->
        <div class="flex flex-col gap-4 mb-8">
            <div class="flex gap-4 justify-between items-center">
                <div class="flex-1 bg-[#FFE500] neo-border neo-shadow-sm px-4 py-2 text-center relative overflow-hidden">
                    <div class="text-xs font-bold uppercase mb-1">Score</div>
                    <div class="text-3xl font-mono font-bold" x-text="displayScore"></div>
                </div>
                <div class="flex-1 neo-border neo-shadow-sm px-4 py-2 text-center transition-colors duration-300" :class="timerColorClass">
                    <div class="text-xs font-bold uppercase mb-1">Time Left</div>
                    <div class="text-3xl font-mono font-bold" x-text="formatTime()"></div>
                </div>
            </div>
            
            <div class="bg-white neo-border neo-shadow-sm px-4 py-2 flex justify-between items-center" x-show="streak > 0" x-transition>
                <div class="text-sm font-bold uppercase">🔥 Streak</div>
                <div class="text-xl font-mono font-bold text-orange-500" x-text="streak + 'x'"></div>
            </div>
        </div>

        <!-- Level Progress -->
        <div class="mb-8">
            <div class="flex justify-between font-bold mb-2 uppercase">
                <span>Progress</span>
                <span x-text="currentProgress"></span>
            </div>
            <div class="h-6 w-full neo-border bg-gray-200">
                <div class="h-full bg-[#FFE500] border-r-2 border-black transition-all duration-300" :style="`width: ${((currentLevelIndex + 1) / levels.length) * 100}%`"></div>
            </div>
        </div>

        <!-- Level List -->
        <div class="flex-grow overflow-y-auto neo-border bg-[#f3f4f6] p-4">
            <h3 class="font-bold uppercase mb-4 border-b-2 border-black pb-2">Levels</h3>
            <ul class="space-y-3">
                <template x-for="(level, index) in levels" :key="level.id">
                    <li 
                        class="flex items-center justify-between p-3 neo-border bg-white cursor-pointer transition-colors"
                        :class="{'border-l-8 border-l-[#FFE500]': currentLevelIndex === index, 'opacity-50': index > currentLevelIndex && !completedLevels.includes(level.id)}"
                        @click="if (index <= currentLevelIndex || completedLevels.includes(level.id)) goToLevel(index)"
                    >
                        <span class="font-bold font-mono">Level <span x-text="level.order"></span></span>
                        <template x-if="completedLevels.includes(level.id)">
                            <div class="w-6 h-6 bg-[#00ff88] neo-border flex items-center justify-center text-xs font-bold">✓</div>
                        </template>
                    </li>
                </template>
            </ul>
        </div>
    </div>
@endif
