@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20 pt-8" x-data="babakBelurArena()" x-cloak>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ═══ STAGE HEADER BAR ═══ --}}
        <div class="neo-border p-4 mb-6 flex flex-wrap items-center justify-between gap-4 animate-slide-in"
             style="background: linear-gradient(135deg, #7B2FF7 0%, #FF2D87 100%); color: white; box-shadow: 8px 8px 0px var(--neo-black);">
            <div class="flex items-center gap-3">
                <span class="neo-border px-3 py-1 font-black text-sm" style="background: #FFE500; color: var(--neo-black); font-family: 'Space Mono', monospace; box-shadow: 2px 2px 0px rgba(0,0,0,0.3);">🔥 BABAK BELUR</span>
                <span class="font-black text-sm uppercase" style="font-family: 'Space Mono', monospace;">Room: {{ strtoupper($room->code) }}</span>
            </div>
            <div class="flex items-center gap-4">
                {{-- Stage Pills --}}
                <div class="flex gap-2">
                    <template x-for="s in totalStages" :key="s">
                        <div class="neo-border px-3 py-1 font-black text-xs uppercase transition-all"
                             :style="s < currentStage ? 'background: var(--neo-green); color: var(--neo-black); box-shadow: 2px 2px 0px rgba(0,0,0,0.3);' : (s === currentStage ? 'background: #FFE500; color: var(--neo-black); box-shadow: 2px 2px 0px rgba(0,0,0,0.3); animation: pulse 1.5s infinite;' : 'background: rgba(255,255,255,0.2); color: rgba(255,255,255,0.6); box-shadow: none;')"
                             style="font-family: 'Space Mono', monospace;">
                            <span x-text="s === currentStage ? '▶ Stage ' + s : (s < currentStage ? '✓ Stage ' + s : 'Stage ' + s)"></span>
                        </div>
                    </template>
                </div>
                {{-- Player Count --}}
                <div class="neo-border px-3 py-1 font-black text-sm" style="background: rgba(255,255,255,0.2); font-family: 'Space Mono', monospace;">
                    👥 <span x-text="activePlayers"></span> alive
                </div>
            </div>
        </div>

        {{-- ═══ GACHA CAROUSEL OVERLAY ═══ --}}
        <div x-show="phase === 'gacha'" x-transition class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.85); display:none;">
            <div class="text-center max-w-lg w-full mx-4">
                <h2 class="text-4xl md:text-5xl font-black text-white uppercase mb-2" style="font-family: 'Space Mono', monospace; text-shadow: 4px 4px 0px rgba(123,47,247,0.5);">
                    Stage <span x-text="currentStage"></span>
                </h2>
                <p class="text-lg font-bold text-gray-300 mb-8" style="font-family: 'Space Mono', monospace;">Gacha Game dimulai...</p>

                {{-- Slot Machine --}}
                <div class="neo-border mx-auto overflow-hidden relative" style="height: 100px; background: var(--neo-black); box-shadow: 8px 8px 0px #7B2FF7;">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div id="gacha-reel" class="text-center transition-transform" :style="`transform: translateY(${gachaOffset}px);`">
                            <template x-for="(g, i) in gachaItems" :key="i">
                                <div class="h-[100px] flex items-center justify-center px-6">
                                    <span class="text-2xl md:text-3xl font-black text-white" style="font-family: 'Space Mono', monospace;" x-text="g.title"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                    {{-- Selection Indicator --}}
                    <div class="absolute inset-y-0 left-0 w-2" style="background: #FFE500;"></div>
                    <div class="absolute inset-y-0 right-0 w-2" style="background: #FFE500;"></div>
                </div>

                {{-- Selected Game Reveal --}}
                <div x-show="gachaRevealed" x-transition class="mt-8">
                    <div class="neo-border p-6 mx-auto animate-bounce-in" style="background: var(--neo-yellow); box-shadow: 8px 8px 0px var(--neo-black);">
                        <p class="text-sm font-black uppercase mb-2" style="font-family: 'Space Mono', monospace;">🎰 Game Terpilih:</p>
                        <h3 class="text-3xl font-black uppercase" style="font-family: 'Space Mono', monospace;" x-text="selectedGame?.title || ''"></h3>
                    </div>
                    <p class="text-white font-bold mt-4 animate-pulse" style="font-family: 'Space Mono', monospace;">Memulai dalam 3 detik...</p>
                </div>
            </div>
        </div>

        {{-- ═══ MAIN CONTENT: PLAYING PHASE ═══ --}}
        <template x-if="phase === 'playing' || phase === 'waiting'">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: Game Area --}}
                <div class="lg:col-span-2">
                    {{-- Timer Bar --}}
                    <div class="neo-border p-4 mb-4 flex items-center justify-between" style="background: white; box-shadow: 4px 4px 0px var(--neo-black);">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">⏱️</span>
                            <span class="font-black text-2xl" style="font-family: 'Space Mono', monospace; color: var(--neo-black);" x-text="formatTimer()"></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-black text-sm uppercase" style="font-family: 'Space Mono', monospace;">Skor Stage:</span>
                            <span class="neo-border px-3 py-1 font-black text-xl" style="background: var(--neo-green); font-family: 'Space Mono', monospace; box-shadow: 2px 2px 0px var(--neo-black);" x-text="stageScore"></span>
                        </div>
                        {{-- Timer Progress Bar --}}
                        <div class="absolute bottom-0 left-0 h-1 transition-all duration-1000" style="background: var(--neo-green);" :style="`width: ${(stageTimer / {{ $room->stage_timer }}) * 100}%`"></div>
                    </div>

                    {{-- Game Embed --}}
                    <template x-if="phase === 'playing' && selectedGame">
                        <div class="neo-border bg-white overflow-hidden" style="box-shadow: 6px 6px 0px var(--neo-black); min-height: 500px;">
                            <iframe :src="`/games/${selectedGame.slug}/play?room={{ $room->code }}&babak_belur=1&stage=${currentStage}`"
                                    class="w-full border-0" style="height: 70vh;" id="bb-game-frame"></iframe>
                        </div>
                    </template>

                    {{-- Waiting for host --}}
                    <template x-if="phase === 'waiting'">
                        <div class="neo-border p-16 text-center" style="background: #f9f9f4; box-shadow: 6px 6px 0px var(--neo-black);">
                            <div class="text-6xl mb-4">⏳</div>
                            <h3 class="text-2xl font-black uppercase mb-2" style="font-family: 'Space Mono', monospace;">Menunggu Host</h3>
                            <p class="font-bold text-gray-500">Host akan memulai stage berikutnya...</p>
                            <template x-if="isHost">
                                <button @click="startNextStage" class="neo-btn mt-6 text-lg uppercase" style="background: var(--neo-yellow); padding: 14px 32px; font-family: 'Space Mono', monospace; box-shadow: 6px 6px 0px var(--neo-black);">
                                    🎰 Mulai Stage <span x-text="currentStage"></span>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- RIGHT: Leaderboard --}}
                <div class="lg:col-span-1">
                    <div class="neo-border p-5 sticky top-4" style="background: white; box-shadow: 6px 6px 0px var(--neo-black);">
                        <h2 class="text-xl font-black uppercase pb-3 mb-4 flex items-center gap-2" style="font-family: 'Space Mono', monospace; border-bottom: 4px solid var(--neo-black);">
                            🏆 Live Ranking
                        </h2>
                        <div class="space-y-2 max-h-[60vh] overflow-y-auto pr-1">
                            <template x-for="(p, idx) in sortedPlayers" :key="p.user_id">
                                <div class="neo-border p-3 flex items-center gap-3 transition-all"
                                     :style="p.is_eliminated ? 'background: #f0f0f0; opacity: 0.5; box-shadow: none;' : (p.user_id == myUserId ? 'background: var(--neo-yellow); box-shadow: 4px 4px 0px var(--neo-black);' : 'background: white; box-shadow: 2px 2px 0px var(--neo-black);')">
                                    <div class="w-7 h-7 flex items-center justify-center font-black text-sm neo-border" style="background: var(--neo-black); color: white; font-family: 'Space Mono', monospace;" x-text="idx + 1"></div>
                                    <div class="w-9 h-9 neo-border bg-white overflow-hidden flex-shrink-0">
                                        <img :src="p.avatar || `https://ui-avatars.com/api/?name=${p.username}`" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="font-black truncate text-sm" style="font-family: 'Space Mono', monospace;" x-text="p.username"></div>
                                            <div class="text-xs font-bold" style="font-family: 'Space Mono', monospace;" x-text="p.is_eliminated ? '💀 Eliminated' : p.stage_score + ' pts'"></div>
                                        </div>
                                        
                                        <!-- Progress Bar Redesigned -->
                                        <template x-if="!p.is_eliminated && selectedGame">
                                            <div class="mt-1">
                                                <div class="h-3 w-full neo-border bg-white overflow-hidden p-[1px]" style="box-shadow: none; border-width: 2px;">
                                                    <div class="h-full transition-all duration-700 ease-out bg-[#00ff88]" 
                                                         :style="`width: ${Math.min((p.current_level / (selectedGame.total_levels || 1)) * 100, 100)}%`">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </template>

        {{-- ═══ SPECTATOR OVERLAY ═══ --}}
        <div x-show="isEliminated" class="fixed top-0 left-0 right-0 z-40 neo-border p-3 flex items-center justify-center gap-4" style="background: #FF4444; color: white; border-top: none; border-left: none; border-right: none; display:none;">
            <span class="text-2xl">💀</span>
            <span class="font-black uppercase text-lg" style="font-family: 'Space Mono', monospace;">TERELIMINASI — Mode Spectator</span>
            <span class="text-2xl">👀</span>
        </div>

        {{-- ═══ STAGE RESULTS OVERLAY ═══ --}}
        <div x-show="phase === 'results'" x-transition class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.9); display:none;">
            <div class="max-w-xl w-full mx-4 text-center">
                <h2 class="text-4xl font-black text-white uppercase mb-2" style="font-family: 'Space Mono', monospace;">
                    Stage <span x-text="lastStageNum"></span> Selesai!
                </h2>
                <div class="flex justify-center gap-6 mb-6">
                    <div class="neo-border p-4 text-center" style="background: var(--neo-green); box-shadow: 4px 4px 0px var(--neo-black);">
                        <div class="text-3xl font-black" style="font-family: 'Space Mono', monospace;" x-text="stageResults.qualified"></div>
                        <div class="text-xs font-black uppercase" style="font-family: 'Space Mono', monospace;">Lolos</div>
                    </div>
                    <div class="neo-border p-4 text-center" style="background: #FF4444; color: white; box-shadow: 4px 4px 0px var(--neo-black);">
                        <div class="text-3xl font-black" style="font-family: 'Space Mono', monospace;" x-text="stageResults.eliminated"></div>
                        <div class="text-xs font-black uppercase" style="font-family: 'Space Mono', monospace;">Eliminasi</div>
                    </div>
                </div>

                {{-- My Status --}}
                <div class="neo-border p-6 mb-6" :style="stageResults.myQualified ? 'background: var(--neo-green); box-shadow: 8px 8px 0px var(--neo-black);' : 'background: #FF4444; color: white; box-shadow: 8px 8px 0px var(--neo-black);'">
                    <div class="text-5xl mb-2" x-text="stageResults.myQualified ? '🎉' : '💀'"></div>
                    <h3 class="text-2xl font-black uppercase" style="font-family: 'Space Mono', monospace;" x-text="stageResults.myQualified ? 'KAMU LOLOS!' : 'KAMU TERELIMINASI'"></h3>
                </div>

                {{-- Rankings Table --}}
                <div class="neo-border p-4 max-h-[40vh] overflow-y-auto" style="background: white; box-shadow: 4px 4px 0px var(--neo-black);">
                    <template x-for="(r, i) in stageResults.rankings" :key="r.user_id">
                        <div class="flex items-center gap-3 p-2" :class="i > 0 ? 'border-t-2 border-black' : ''"
                             :style="r.qualified ? '' : 'opacity: 0.4; text-decoration: line-through;'">
                            <span class="font-black text-sm w-6" style="font-family: 'Space Mono', monospace;" x-text="i + 1"></span>
                            <span class="font-black text-sm flex-grow truncate" style="font-family: 'Space Mono', monospace;" x-text="r.username"></span>
                            <span class="font-black text-sm" style="font-family: 'Space Mono', monospace;" x-text="r.stage_score + 'pts'"></span>
                            <span x-text="r.qualified ? '✅' : '❌'"></span>
                        </div>
                    </template>
                </div>

                <template x-if="isHost && !stageResults.isFinal">
                    <button @click="proceedToNextStage" class="neo-btn mt-6 text-lg uppercase" style="background: var(--neo-yellow); padding: 14px 40px; font-family: 'Space Mono', monospace; box-shadow: 6px 6px 0px white;">
                        ➡️ Lanjut Stage <span x-text="currentStage + 1"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- ═══ WINNER OVERLAY ═══ --}}
        <div x-show="phase === 'winner'" x-transition class="fixed inset-0 z-50 flex items-center justify-center" style="background: linear-gradient(135deg, #7B2FF7 0%, #FF2D87 50%, #FFE500 100%); display:none;">
            <div class="text-center max-w-lg mx-4">
                <div class="text-8xl mb-4 animate-bounce-in">🏆</div>
                <h1 class="text-5xl md:text-6xl font-black text-white uppercase mb-4" style="font-family: 'Space Mono', monospace; text-shadow: 4px 4px 0px rgba(0,0,0,0.3);">
                    WINNER!
                </h1>
                <div class="neo-border p-8 mb-6 animate-bounce-in" style="background: var(--neo-yellow); box-shadow: 8px 8px 0px var(--neo-black);">
                    <div class="w-24 h-24 mx-auto neo-border bg-white overflow-hidden mb-4" style="box-shadow: 4px 4px 0px var(--neo-black);">
                        <img :src="winnerData.avatar || `https://ui-avatars.com/api/?name=${winnerData.username}`" class="w-full h-full object-cover">
                    </div>
                    <h2 class="text-3xl font-black uppercase" style="font-family: 'Space Mono', monospace;" x-text="winnerData.username"></h2>
                    <p class="font-black text-xl mt-2" style="font-family: 'Space Mono', monospace;" x-text="winnerData.total_score + ' Total Points'"></p>
                </div>
                <div class="neo-border p-4 mb-6 max-h-[30vh] overflow-y-auto" style="background: white; box-shadow: 4px 4px 0px var(--neo-black);">
                    <h3 class="font-black uppercase mb-3 text-sm border-b-2 border-black pb-1" style="font-family: 'Space Mono', monospace;">Final Leaderboard</h3>
                    <template x-for="(r, i) in finalRankings" :key="r.user_id">
                        <div class="flex items-center gap-3 p-2" :class="i > 0 ? 'border-t border-gray-200' : ''">
                            <span class="font-black text-sm w-6" style="font-family: 'Space Mono', monospace;" x-text="i + 1"></span>
                            <span class="font-black text-sm flex-grow truncate" style="font-family: 'Space Mono', monospace;" x-text="r.username"></span>
                            <span class="font-black text-sm" style="font-family: 'Space Mono', monospace;" x-text="r.total_score + 'pts'"></span>
                        </div>
                    </template>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('rooms.create') }}" class="neo-btn text-lg uppercase" style="background: white; padding: 14px 40px; font-family: 'Space Mono', monospace; box-shadow: 6px 6px 0px var(--neo-black);">
                        🏠 Create New Room
                    </a>
                    <a href="{{ route('games.index') }}" class="neo-btn text-lg uppercase" style="background: var(--neo-yellow); padding: 14px 40px; font-family: 'Space Mono', monospace; box-shadow: 6px 6px 0px var(--neo-black);">
                        🎮 Back to Games
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('babakBelurArena', () => ({
        roomCode: @json($room->code),
        isHost: {{ $room->host_id === auth()->id() ? 'true' : 'false' }},
        myUserId: {{ auth()->id() }},
        totalStages: {{ $room->total_stages }},
        stageTimerMax: {{ $room->stage_timer }},

        // State
        phase: '{{ $currentStage ? "playing" : "waiting" }}',
        currentStage: {{ $room->current_stage ?? 1 }},
        activePlayers: {{ $room->activePlayers()->count() }},
        isEliminated: {{ $participant->is_eliminated ? 'true' : 'false' }},
        selectedGame: @json($currentStage ? [
            'id' => $currentStage->game->id, 
            'slug' => $currentStage->game->slug, 
            'title' => $currentStage->game->title,
            'total_levels' => $currentStage->game->levels->count()
        ] : null),

        // Timer
        stageTimer: {{ $room->stage_timer }},
        timerInterval: null,

        // Score
        stageScore: {{ $participant->stage_score ?? 0 }},

        // Gacha
        gachaItems: [],
        gachaOffset: 0,
        gachaRevealed: false,

        // Players
        players: @json($room->participants->map(fn($p) => [
            'user_id' => $p->user_id,
            'username' => $p->user->name ?? 'Unknown',
            'avatar' => $p->user->avatar ?? null,
            'stage_score' => $p->stage_score ?? 0,
            'total_score' => $p->score ?? 0,
            'current_level' => $p->current_level ?? 1,
            'is_eliminated' => $p->is_eliminated,
        ])),

        // Results
        lastStageNum: 0,
        stageResults: { qualified: 0, eliminated: 0, myQualified: false, rankings: [], isFinal: false },
        winnerData: { username: '', avatar: '', total_score: 0 },
        finalRankings: [],

        get sortedPlayers() {
            return this.players.slice()
                .sort((a, b) => a.is_eliminated === b.is_eliminated ? b.stage_score - a.stage_score : a.is_eliminated ? 1 : -1);
        },

        init() {
            if (window.Echo) {
                window.Echo.channel(`room.${this.roomCode}`)
                    .listen('.stage.starting', (e) => this.handleStageStarting(e))
                    .listen('.score.updated', (e) => this.handleScoreUpdate(e))
                    .listen('.stage.ended', (e) => this.handleStageEnded(e))
                    .listen('.babakbelur.finished', (e) => this.handleFinished(e));
            }

            // Listen for score messages from iframe
            window.addEventListener('message', (e) => {
                if (e.data?.type === 'bb_score_update') {
                    this.stageScore = e.data.score;
                    this.syncScore(e.data.score, e.data.current_level);
                }
            });

            // Start timer if already playing
            if (this.phase === 'playing') this.startTimer();
        },

        // ── GACHA CAROUSEL ──
        handleStageStarting(data) {
            this.currentStage = data.stage_number;
            this.activePlayers = data.active_players;
            this.phase = 'gacha';
            this.gachaRevealed = false;

            // Build reel: all games repeated + selected at end
            const allGames = data.all_games;
            let reel = [];
            for (let i = 0; i < 4; i++) {
                reel = reel.concat(allGames.sort(() => Math.random() - 0.5));
            }
            reel.push(data.selected_game);
            this.gachaItems = reel;
            this.gachaOffset = 0;

            // Animate spin
            const totalItems = reel.length;
            const targetOffset = -((totalItems - 1) * 100);
            let current = 0;
            const step = targetOffset / 60;
            let frame = 0;

            const animate = () => {
                frame++;
                // Ease out
                const progress = frame / 60;
                const eased = 1 - Math.pow(1 - progress, 3);
                this.gachaOffset = targetOffset * eased;

                if (frame < 60) {
                    requestAnimationFrame(animate);
                } else {
                    this.gachaOffset = targetOffset;
                    setTimeout(() => {
                        this.gachaRevealed = true;
                        this.selectedGame = data.selected_game;

                        setTimeout(() => {
                            this.phase = 'playing';
                            this.stageScore = 0;
                            this.players.forEach(p => {
                                if (!p.is_eliminated) {
                                    p.stage_score = 0;
                                    p.current_level = 1;
                                }
                            });
                            this.stageTimer = this.stageTimerMax;
                            this.startTimer();
                        }, 3000);
                    }, 500);
                }
            };
            requestAnimationFrame(animate);
        },

        startTimer() {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.timerInterval = setInterval(() => {
                this.stageTimer--;
                if (this.stageTimer <= 0) {
                    clearInterval(this.timerInterval);
                    if (this.isHost) this.endCurrentStage();
                }
            }, 1000);
        },

        formatTimer() {
            const m = Math.floor(Math.max(0, this.stageTimer) / 60);
            const s = Math.max(0, this.stageTimer) % 60;
            return `${m}:${String(s).padStart(2, '0')}`;
        },

        // ── SCORE SYNC ──
        async syncScore(score, level) {
            try {
                await fetch(`/rooms/${this.roomCode}/babak-belur/score`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ stage_score: score, current_level: level })
                });
            } catch (e) { console.error('Score sync failed', e); }
        },

        handleScoreUpdate(data) {
            let p = this.players.find(pl => pl.user_id === data.user_id);
            if (p) {
                p.stage_score = data.score;
                p.current_level = data.current_level;
            }
        },

        // ── STAGE END ──
        async endCurrentStage() {
            try {
                await fetch(`/rooms/${this.roomCode}/babak-belur/end-stage`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
            } catch (e) { console.error('End stage failed', e); }
        },

        handleStageEnded(data) {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.lastStageNum = data.stage_number;
            const myQualified = data.qualified_ids.includes(this.myUserId);

            if (!myQualified) {
                this.isEliminated = true;
            }

            // Update players
            data.eliminated_ids.forEach(uid => {
                let p = this.players.find(pl => pl.user_id === uid);
                if (p) p.is_eliminated = true;
            });

            this.stageResults = {
                qualified: data.qualified_ids.length,
                eliminated: data.eliminated_ids.length,
                myQualified: myQualified,
                rankings: data.rankings,
                isFinal: data.is_final,
            };
            this.activePlayers = data.qualified_ids.length;
            this.phase = 'results';
        },

        handleFinished(data) {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.winnerData = data.winner;
            this.finalRankings = data.final_rankings;
            this.phase = 'winner';
        },

        // ── HOST CONTROLS ──
        async startNextStage() {
            try {
                await fetch(`/rooms/${this.roomCode}/babak-belur/start-stage`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
            } catch (e) { console.error('Start stage failed', e); }
        },

        proceedToNextStage() {
            this.currentStage++;
            this.phase = 'waiting';
            // Reset stage scores for display
            this.players.forEach(p => { if (!p.is_eliminated) p.stage_score = 0; });
            this.startNextStage();
        },
    }));
});
</script>
@endsection
