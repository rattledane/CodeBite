@if(request()->query('room'))
    <!-- MULTIPLAYER PANEL -->
    <div class="w-full lg:w-[30%] lg:h-full p-4 sm:p-6 flex flex-col bg-white lg:border-l-4 border-black min-h-[400px]" x-data="multiplayerBoard('{{ request()->query('room') }}')">
        <h2 class="text-2xl sm:text-3xl font-black uppercase pb-2 mb-6 flex flex-col sm:flex-row sm:items-center gap-2" style="font-family: 'Space Mono', monospace; border-bottom: 4px solid var(--neo-black);">
            🏆 Skor Live
            <span class="text-sm px-2 py-1 neo-border sm:ms-auto mt-2 sm:mt-0 w-max font-black" style="background: var(--neo-yellow); box-shadow: 2px 2px 0px var(--neo-black); font-family: 'Space Mono', monospace; letter-spacing: 1px;">ROOM: {{ strtoupper(request()->query('room')) }}</span>
        </h2>

        <!-- Current Player Header -->
        <div class="flex gap-4 mb-6">
            <div class="flex-1 neo-border p-3 text-center" style="background: #FF4444; color: white; box-shadow: 2px 2px 0px var(--neo-black);">
                <div class="text-xs font-black uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Time Left</div>
                <div class="text-2xl font-black" style="font-family: 'Space Mono', monospace;" x-text="formatTime()"></div>
            </div>
            <div class="flex-1 neo-border p-3 text-center" style="background: var(--neo-green); box-shadow: 2px 2px 0px var(--neo-black);">
                <div class="text-xs font-black uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">My Score</div>
                <div class="text-2xl font-black" style="font-family: 'Space Mono', monospace;" x-text="displayScore"></div>
            </div>
        </div>

        <div class="flex-grow overflow-y-auto space-y-4 pr-2">
            <template x-if="players.length === 0">
                <div class="text-center p-8 font-black border-4 border-black border-dashed uppercase" style="font-family: 'Space Mono', monospace; background: #f9f9f4;">
                    Menunggu pemain...
                </div>
            </template>

            <!-- Leaderboard List -->
            <template x-for="(p, index) in sortedPlayers" :key="p.user_id">
                <div class="neo-border p-3 transition-all relative overflow-hidden"
                     :style="p.user_id == '{{ auth()->id() }}' ? 'background: var(--neo-yellow); box-shadow: 4px 4px 0px var(--neo-black);' : 'background: white; box-shadow: 2px 2px 0px var(--neo-black);'">

                    <div class="flex items-center gap-3 mb-2">
                        <!-- Rank -->
                        <div class="w-8 h-8 flex items-center justify-center font-black text-lg neo-border" style="background: var(--neo-black); color: white; box-shadow: 2px 2px 0px var(--neo-black); font-family: 'Space Mono', monospace;" x-text="index + 1"></div>

                        <!-- Avatar -->
                        <div class="w-10 h-10 neo-border bg-white overflow-hidden flex-shrink-0" style="box-shadow: 2px 2px 0px var(--neo-black);">
                            <img :src="p.avatar || 'https://ui-avatars.com/api/?name=' + p.username" class="w-full h-full object-cover">
                        </div>

                        <!-- Name & Score -->
                        <div class="flex-grow min-w-0">
                            <div class="font-black truncate text-sm" style="font-family: 'Space Mono', monospace;" x-text="p.username"></div>
                            <div class="font-black text-sm" style="font-family: 'Space Mono', monospace;" x-text="p.score"></div>
                        </div>
                    </div>
                    <!-- Progress Bar Redesigned -->
                    <div class="mt-3 relative">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[10px] font-black uppercase text-gray-400" style="font-family: 'Space Mono', monospace;">Progress</span>
                            <span class="text-[10px] font-black" style="font-family: 'Space Mono', monospace;" x-text="Math.round(Math.min((p.current_level / {{ count($levels) }}) * 100, 100)) + '%'"></span>
                        </div>
                        <div class="h-4 w-full neo-border bg-white overflow-hidden p-[2px]" style="box-shadow: none; border-width: 2px;">
                            <div class="h-full transition-all duration-700 ease-out rounded-sm bg-[#00ff88]" 
                                 :style="`width: ${Math.min((p.current_level / {{ count($levels) }}) * 100, 100)}%`">
                            </div>
                        </div>
                    </div>

                    <!-- Floating Point Animation -->
                    <div x-show="p.showPlus"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-8"
                         class="absolute right-4 top-2 font-black text-xl"
                         style="color: var(--neo-green); text-shadow: 2px 2px 0px var(--neo-black); font-family: 'Space Mono', monospace;"
                         x-text="'+' + p.lastScoreDelta">
                    </div>
                </div>
            </template>
        </div>

        <!-- Status Footer -->
        <div class="mt-4 p-3 neo-border font-black text-center text-sm flex justify-between items-center" style="background: var(--neo-black); color: white; box-shadow: 2px 2px 0px var(--neo-yellow); font-family: 'Space Mono', monospace;">
            <span x-text="myRank ? `You're in ${myRank}${getOrdinal(myRank)} place!` : 'Connecting...'"></span>
            <span class="animate-pulse text-xl">🔥</span>
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
                <div class="flex-1 neo-border px-4 py-2 text-center relative overflow-hidden" style="background: var(--neo-yellow); box-shadow: var(--neo-shadow-sm);">
                    <div class="text-xs font-black uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Score</div>
                    <div class="text-3xl font-black" style="font-family: 'Space Mono', monospace;" x-text="displayScore"></div>
                </div>
                <div class="flex-1 neo-border px-4 py-2 text-center transition-colors duration-300" :class="timerColorClass" style="box-shadow: var(--neo-shadow-sm);">
                    <div class="text-xs font-black uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Time Left</div>
                    <div class="text-3xl font-black" style="font-family: 'Space Mono', monospace;" x-text="formatTime()"></div>
                </div>
            </div>

            <div class="neo-border px-4 py-2 flex justify-between items-center" x-show="streak > 0" x-transition style="background: white; box-shadow: var(--neo-shadow-sm);">
                <div class="text-sm font-black uppercase" style="font-family: 'Space Mono', monospace;">🔥 Streak</div>
                <div class="text-xl font-black" style="color: var(--neo-orange); font-family: 'Space Mono', monospace;" x-text="streak + 'x'"></div>
            </div>
        </div>

        <!-- Level Progress -->
        <div class="mb-8">
            <div class="flex justify-between font-black mb-2 uppercase text-sm" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">
                <span>Progress</span>
                <span x-text="currentProgress"></span>
            </div>
            <div class="h-5 w-full neo-border bg-gray-100">
                <div class="h-full transition-all duration-300" style="background: var(--neo-yellow);" :style="`width: ${((currentLevelIndex + 1) / levels.length) * 100}%; ${((currentLevelIndex + 1) / levels.length) * 100 < 100 ? 'border-right: 3px solid var(--neo-black);' : ''}`"></div>
            </div>
        </div>

        <!-- Level List -->
        <div class="flex-grow overflow-y-auto neo-border p-4" style="background: #f9f9f4; box-shadow: inset 3px 3px 0px rgba(0,0,0,0.05);">
            <h3 class="font-black uppercase mb-4 pb-2 text-sm" style="font-family: 'Space Mono', monospace; border-bottom: 3px solid var(--neo-black); letter-spacing: 1px;">Levels</h3>
            <ul class="space-y-3">
                <template x-for="(level, index) in levels" :key="level.id">
                    <li
                        class="flex items-center justify-between p-3 neo-border bg-white cursor-pointer transition-all"
                        :class="{'border-l-8': currentLevelIndex === index, 'opacity-40': index > currentLevelIndex && !completedLevels.includes(level.id)}"
                        :style="currentLevelIndex === index ? 'border-left-color: var(--neo-yellow); box-shadow: 4px 4px 0px var(--neo-black);' : 'box-shadow: 2px 2px 0px var(--neo-black);'"
                        @click="if (index <= currentLevelIndex || completedLevels.includes(level.id)) goToLevel(index)"
                    >
                        <span class="font-black text-sm" style="font-family: 'Space Mono', monospace;">Level <span x-text="level.order"></span></span>
                        <template x-if="completedLevels.includes(level.id)">
                            <div class="w-6 h-6 neo-border flex items-center justify-center text-xs font-bold" style="background: var(--neo-green); box-shadow: 2px 2px 0px var(--neo-black);">✓</div>
                        </template>
                    </li>
                </template>
            </ul>
        </div>
    </div>
@endif
