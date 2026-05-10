@extends('layouts.game')

@section('content')
@vite('resources/js/games/grid-garden.js')
<div x-data="gameLogic()" class="flex flex-col lg:flex-row h-screen lg:h-full w-full bg-[#f3f4f6] relative overflow-y-auto lg:overflow-hidden">

    <!-- Score Popup Overlay -->
    <div 
        x-show="showPopup" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-50"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-50"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        style="display: none;"
    >
        <div class="bg-white neo-border p-8 flex flex-col items-center justify-center max-w-sm w-full neo-shadow m-4" style="box-shadow: 8px 8px 0px #FFE500;">
            <h2 class="text-3xl font-black mb-4 uppercase text-center">Level Passed!</h2>
            <div class="text-6xl mb-4">🌱</div>
            <div class="text-2xl font-bold font-mono">+<span x-text="popupScore"></span> Points</div>
            <div class="text-sm font-bold mt-2 text-gray-500 uppercase">Moving to next level...</div>
        </div>
    </div>

    <!-- LEFT PANEL (30%) -->
    <div class="w-full lg:w-[30%] lg:h-full p-4 sm:p-6 border-b-4 lg:border-b-0 lg:border-r-4 border-black flex flex-col bg-white lg:overflow-y-auto">
        <!-- Header / Badge -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('games.index') }}" class="neo-border px-3 py-1 bg-[#FFE500] font-bold text-sm neo-button-hover inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                BACK
            </a>
            <div class="neo-border px-4 py-1 bg-white font-bold neo-shadow-sm">
                LEVEL <span x-text="currentLevel.order"></span>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mb-6 flex-grow">
            <h2 class="text-2xl font-bold mb-4 font-mono uppercase border-b-4 border-black pb-2" x-text="game.title"></h2>
            <p class="text-lg font-medium leading-relaxed" x-text="currentLevel.instruction"></p>
        </div>

        <!-- Code Editor -->
        <div class="mb-6">
            <div class="bg-[#1a1a1a] neo-border neo-shadow p-4 rounded-none relative">
                <div class="text-gray-400 font-mono text-sm mb-2">#water {</div>
                <textarea 
                    x-model="userCode" 
                    class="w-full bg-transparent text-[#00ff88] font-mono text-base resize-none outline-none focus:ring-0 border-none p-0 ml-4 min-h-[120px] lg:min-h-[80px]"
                    spellcheck="false"
                ></textarea>
                <div class="text-gray-400 font-mono text-sm mt-2">}</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-auto">
            <button @click="submit(false)" :disabled="showPopup" class="flex-1 bg-[#FFE500] neo-border neo-shadow neo-button-hover font-bold text-lg py-4 uppercase disabled:opacity-50 h-[56px]">
                Submit
            </button>
            <button class="px-6 bg-white neo-border neo-shadow neo-button-hover font-bold text-lg uppercase h-[56px]">
                Hint
            </button>
        </div>
    </div>

    <!-- CENTER PANEL (40%) -->
    <div class="w-full lg:w-[40%] lg:h-full p-4 sm:p-8 flex flex-col items-center justify-center bg-[#a8e6cf] border-b-4 lg:border-b-0 lg:border-r-4 border-black relative overflow-hidden min-h-[300px]">
        
        <!-- Garden Visualization Area -->
        <div class="w-full aspect-square max-w-[500px] relative neo-border neo-shadow">
            <!-- Dirt Background Grid -->
            <div class="absolute inset-0 bg-[#8B4513] p-2 grid grid-cols-5 grid-rows-5 gap-1">
                <template x-for="i in 25">
                    <div class="bg-[#A0522D] neo-border opacity-50"></div>
                </template>
            </div>
            
            <!-- Interactive Overlay Grid -->
            <div class="absolute inset-0 p-2 grid grid-cols-5 grid-rows-5 gap-1 z-10" id="pond-container">
                <!-- Water block (User styles this) -->
                <div 
                    class="bg-[#1E90FF] neo-border flex items-center justify-center font-bold text-white text-3xl neo-shadow-sm transition-all duration-300"
                    :style="userCode"
                    id="water"
                >
                    💧
                </div>
            </div>
        </div>
        
    </div>

    <!-- RIGHT PANEL (30%) -->
    @include('partials.game-right-panel')

</div>

<script>
    function gameLogic() {
        return {
            game: @json($game),
            levels: @json($levels),
            completedLevels: @json($userProgress->keys()),
            
            currentLevelIndex: 0,
            userCode: '',
            timeLeft: 60,
            score: 0,
            displayScore: 0,
            levelScores: {},
            streak: 0,
            attempts: 0,
            timer: null,
            
            showPopup: false,
            popupScore: 0,
            roomCode: new URLSearchParams(window.location.search).get('room'),

            init() {
                if (this.roomCode) {
                    this.currentLevelIndex = 0;
                    this.score = 0;
                    this.completedLevels = [];
                    this.levelScores = {};
                } else {
                    let firstUncompleted = this.levels.findIndex(l => !this.completedLevels.includes(l.id));
                    if (firstUncompleted !== -1) {
                        this.currentLevelIndex = firstUncompleted;
                    } else if (this.levels.length > 0) {
                        this.currentLevelIndex = this.levels.length - 1;
                    }
                    
                    const progressData = @json($userProgress->values());
                    progressData.forEach(p => {
                        this.levelScores[p.level_id] = p.score;
                    });
                    this.score = progressData.reduce((acc, curr) => acc + curr.score, 0);
                }
                
                this.loadLevel();
                this.startTimer();
                
                this.displayScore = this.score;

                this.$watch('score', value => {
                    this.animateScore(value);
                });
            },
            
            get currentLevel() {
                return this.levels[this.currentLevelIndex] || {};
            },
            
            get currentProgress() {
                return (this.currentLevelIndex + 1) + '/' + this.levels.length;
            },

            get timerColorClass() {
                if (this.timeLeft > 30) return 'bg-[#00ff88] text-black'; // Green
                if (this.timeLeft >= 15) return 'bg-[#FFE500] text-black'; // Yellow
                return 'bg-red-500 text-white'; // Red
            },
            
            loadLevel() {
                this.userCode = '';
                this.timeLeft = 60;
                this.attempts = 0;
            },
            
            goToLevel(index) {
                this.currentLevelIndex = index;
                this.loadLevel();
            },
            
            startTimer() {
                if (this.timer) clearInterval(this.timer);
                this.timer = setInterval(() => {
                    if (this.timeLeft > 0 && !this.showPopup) {
                        this.timeLeft--;
                        if (this.timeLeft === 0) {
                            this.autoSubmit();
                        }
                    }
                }, 1000);
            },
            
            formatTime() {
                const m = Math.floor(this.timeLeft / 60);
                const s = this.timeLeft % 60;
                return m + ':' + (s < 10 ? '0' : '') + s;
            },

            animateScore(target) {
                let start = this.displayScore;
                let duration = 500; // ms
                let startTime = performance.now();
                
                const update = (currentTime) => {
                    let elapsed = currentTime - startTime;
                    let progress = Math.min(elapsed / duration, 1);
                    this.displayScore = Math.floor(start + (target - start) * progress);
                    if (progress < 1) {
                        requestAnimationFrame(update);
                    } else {
                        this.displayScore = target;
                    }
                };
                requestAnimationFrame(update);
            },

            autoSubmit() {
                window.forcedSubmit = true;
                this.submit(true);
                window.forcedSubmit = false;
            },
            
            async finishRoom() {
                if (!this.roomCode) return;
                try {
                    await fetch(`/rooms/${this.roomCode}/finish`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                } catch (e) {
                    console.error('Failed to finish room', e);
                }
            },

            async advanceToNextLevel(nextLevelId) {
                this.showPopup = false;
                
                let finished = false;
                if (nextLevelId) {
                    const idx = this.levels.findIndex(l => l.id === nextLevelId);
                    if (idx !== -1) {
                        this.currentLevelIndex = idx;
                    } else if (this.currentLevelIndex < this.levels.length - 1) {
                        this.currentLevelIndex++;
                    } else {
                        finished = true;
                    }
                } else if (this.currentLevelIndex < this.levels.length - 1) {
                    this.currentLevelIndex++;
                } else {
                    finished = true;
                }
                
                if (finished) {
                    if (this.roomCode) {
                        await this.finishRoom();
                        alert("Menunggu pemain lain selesai...");
                    } else {
                        window.location.href = '/games/' + this.game.slug + '/complete';
                    }
                    return;
                }
                
                this.loadLevel();
            },

            async submit(isAuto = false) {
                this.attempts++;
                const isCorrect = window.checkAnswer(this.userCode, this.currentLevel.answer_key);
                
                if (isCorrect || isAuto) {
                    let earnedScore = 0;
                    
                    if (isCorrect) {
                        earnedScore = window.calculateScore(this.timeLeft, this.currentLevel.max_score, this.attempts);
                        if (this.attempts === 1) this.streak++;
                    } else {
                        earnedScore = 0;
                        this.streak = 0;
                    }

                    try {
                        let response;
                        let data;

                        if (this.roomCode) {
                            let updatedScore = this.score + earnedScore;
                            let nextLvl = this.currentLevelIndex + 1 + (isCorrect ? 1 : 0);
                            
                            response = await fetch(`/rooms/${this.roomCode}/score`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    score: updatedScore,
                                    current_level: nextLvl
                                })
                            });
                            
                            data = await response.json();
                            
                            if (data.success) {
                                this.score = data.score; 
                                this.levelScores[this.currentLevel.id] = earnedScore;
                                if (!this.completedLevels.includes(this.currentLevel.id)) {
                                    this.completedLevels.push(this.currentLevel.id);
                                }
                                
                                if (isCorrect) {
                                    const pond = document.getElementById('pond-container');
                                    pond.classList.add('scale-105', 'transition-transform');
                                    setTimeout(() => pond.classList.remove('scale-105'), 300);
                                    
                                    this.popupScore = earnedScore;
                                    this.showPopup = true;
                                    
                                    setTimeout(() => {
                                        this.advanceToNextLevel();
                                    }, 2000);
                                } else if (isAuto) {
                                    alert('Waktu habis! Lanjut ke level berikutnya.');
                                    this.advanceToNextLevel();
                                }
                            }
                        } else {
                            response = await fetch('/games/progress', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    level_id: this.currentLevel.id,
                                    score: earnedScore,
                                    time_taken: 60 - this.timeLeft
                                })
                            });
                            
                            data = await response.json();
                            
                            if (data.success) {
                                this.levelScores[this.currentLevel.id] = data.score;
                                this.score = data.total_score;
                                
                                if (!this.completedLevels.includes(this.currentLevel.id)) {
                                    this.completedLevels.push(this.currentLevel.id);
                                }
                                
                                if (isCorrect) {
                                    const pond = document.getElementById('pond-container');
                                    pond.classList.add('scale-105', 'transition-transform');
                                    setTimeout(() => pond.classList.remove('scale-105'), 300);
                                    
                                    this.popupScore = data.score;
                                    this.showPopup = true;
                                    
                                    setTimeout(() => {
                                        this.advanceToNextLevel(data.next_level);
                                    }, 2000);
                                } else if (isAuto) {
                                    alert('Waktu habis! Lanjut ke level berikutnya.');
                                    this.advanceToNextLevel(data.next_level);
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Error saving progress:', error);
                        alert('Gagal menyimpan, tapi tetap lanjut.');
                        if (isCorrect) this.advanceToNextLevel();
                    }
                    
                } else {
                    this.streak = 0;
                    const editor = document.querySelector('textarea');
                    editor.classList.add('bg-red-900', 'bg-opacity-30', 'translate-x-2');
                    setTimeout(() => editor.classList.remove('bg-red-900', 'bg-opacity-30', 'translate-x-2'), 100);
                    setTimeout(() => {
                        editor.classList.add('-translate-x-2');
                        setTimeout(() => editor.classList.remove('-translate-x-2'), 100);
                    }, 100);
                    
                    alert('Belum tepat. Coba lagi!');
                }
            }
        }
    }
</script>

@include('partials.multiplayer-script')

@endsection
