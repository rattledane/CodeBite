@extends('layouts.game')

@section('content')
@vite('resources/js/games/css-selector.js')
<div x-data="gameLogic()" class="flex flex-col lg:flex-row h-screen lg:h-full w-full bg-[#f3f4f6] relative overflow-y-auto lg:overflow-hidden">

    <!-- Unified Popup Overlay -->
    @include('partials.game-popup')

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
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-gray-400 font-mono text-sm sm:text-base">document.querySelectorAll('</span>
                    <input 
                        type="text"
                        x-model="userCode" 
                        class="flex-1 bg-transparent text-[#00ff88] font-mono text-base sm:text-lg outline-none focus:ring-0 border-none p-0 min-w-0"
                        spellcheck="false"
                        autocomplete="off"
                        placeholder="selector"
                    >
                    <span class="text-gray-400 font-mono text-sm sm:text-base">');</span>
                </div>
                <div id="selector-error" class="text-red-500 font-bold mt-2 text-sm" style="display: none;">
                    ❌ Selector tidak valid
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-auto">
            <button @click="submit(false)" :disabled="showPopup" class="flex-1 bg-[#FFE500] neo-border neo-shadow neo-button-hover font-bold text-lg py-4 uppercase disabled:opacity-50 h-[56px]">
                Submit
            </button>
            <button @click="showHint = !showHint" class="px-6 neo-border neo-shadow neo-button-hover font-bold text-lg uppercase h-[56px] transition-colors" :class="showHint ? 'bg-[#FFE500]' : 'bg-white'">
                <span x-show="!showHint">💡 Hint</span>
                <span x-show="showHint">✕ Tutup</span>
            </button>
        </div>

        <!-- Hint Panel -->
        <div x-show="showHint && currentLevel.hint" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4 neo-border p-4 relative" style="background: #FFF9C4; box-shadow: 3px 3px 0px var(--neo-black);">
            <div class="flex items-start gap-3">
                <span class="text-2xl flex-shrink-0">💡</span>
                <div>
                    <div class="font-black text-sm uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Hint</div>
                    <p class="font-bold text-sm leading-relaxed" x-text="currentLevel.hint"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- CENTER PANEL (40%) -->
    <div class="w-full lg:w-[40%] lg:h-full p-4 sm:p-8 flex flex-col items-center justify-center bg-[#FFB6C1] border-b-4 lg:border-b-0 lg:border-r-4 border-black relative overflow-hidden min-h-[300px]">
        
        <!-- DOM Visualization Area -->
        <div class="w-full h-full max-w-2xl bg-white neo-border neo-shadow p-8 flex flex-col font-mono text-lg overflow-y-auto" id="dom-playground">
            
            <div class="p-4 border-l-4 border-black mb-4 bg-gray-50 neo-shadow-sm transition-all duration-200">
                <span class="text-gray-400">&lt;div class="container"&gt;</span>
                <div class="ml-8 mt-2 space-y-4">
                    
                    <p class="p-3 border-2 border-dashed border-gray-400 inline-block w-full transition-all duration-200">
                        <span class="text-gray-400">&lt;p&gt;</span> Biasa <span class="text-gray-400">&lt;/p&gt;</span>
                    </p>
                    
                    <p class="highlight p-3 border-2 border-dashed border-gray-400 inline-block w-full transition-all duration-200">
                        <span class="text-gray-400">&lt;p class="highlight"&gt;</span> Penting! <span class="text-gray-400">&lt;/p&gt;</span>
                    </p>
                    
                </div>
                <span class="text-gray-400 mt-2 block">&lt;/div&gt;</span>
            </div>

            <div class="p-4 border-l-4 border-black bg-gray-50 neo-shadow-sm transition-all duration-200">
                <span class="text-gray-400">&lt;div class="container"&gt;</span>
                <div class="ml-8 mt-2 space-y-4">
                    
                    <p id="target" class="p-3 border-2 border-dashed border-gray-400 inline-block w-full transition-all duration-200">
                        <span class="text-gray-400">&lt;p id="target"&gt;</span> Sangat Penting! <span class="text-gray-400">&lt;/p&gt;</span>
                    </p>
                    
                    <p class="highlight p-3 border-2 border-dashed border-gray-400 inline-block w-full transition-all duration-200">
                        <span class="text-gray-400">&lt;p class="highlight"&gt;</span> Biasa <span class="text-gray-400">&lt;/p&gt;</span>
                    </p>
                    
                </div>
                <span class="text-gray-400 mt-2 block">&lt;/div&gt;</span>
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
            showHint: false,
            popupType: 'success',
            popupNextLevel: null,
            popupScore: 0,
            roomCode: new URLSearchParams(window.location.search).get('room'),
            isBabakBelur: new URLSearchParams(window.location.search).get('babak_belur') === '1',

            init() {
                if (this.roomCode) {
                    this.currentLevelIndex = 0;
                    this.score = 0;
                    this.completedLevels = [];
                    this.levelScores = {};
                    if (!this.isBabakBelur) {
                        this.timeLeft = 300;
                    }
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

                // Watch the userCode and trigger live highlighting
                this.$watch('userCode', value => {
                    if (window.updateHighlights) {
                        window.updateHighlights(value);
                    }
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
                if (!this.roomCode || this.isBabakBelur) {
                    this.timeLeft = 60;
                }
                this.attempts = 0;
                this.showHint = false;
                if (window.updateHighlights) {
                    window.updateHighlights('');
                }
            },
            
            goToLevel(index) {
                this.currentLevelIndex = index;
                this.loadLevel();
            },
            
            startTimer() {
                if (this.timer) clearInterval(this.timer);
                this.timer = setInterval(() => {
                    if (this.roomCode && !this.isBabakBelur) {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                            if (this.timeLeft === 0) {
                                clearInterval(this.timer);
                                this.finishRoom();
                            }
                        }
                    } else {
                        if (this.timeLeft > 0 && !this.showPopup) {
                            this.timeLeft--;
                            if (this.timeLeft === 0) {
                                this.autoSubmit();
                            }
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
                        if (this.isBabakBelur) {
                            this.showResultPopup('stage_finished', this.score);
                            return;
                        }
                        await this.finishRoom();
                        this.showResultPopup('stage_finished', this.score);
                    } else {
                        window.location.href = '/games/' + this.game.slug + '/complete';
                    }
                    return;
                }
                
                this.loadLevel();
            },

            showResultPopup(type, score = 0, nextLevel = null) {
                this.popupType = type;
                this.popupScore = score;
                this.popupNextLevel = nextLevel;
                this.showPopup = true;
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
                                    this.showResultPopup('success', earnedScore, null);
                                } else if (isAuto) {
                                    this.showResultPopup('timeout', 0, null);
                                }

                                if (this.isBabakBelur) {
                                    window.parent.postMessage({
                                        type: 'bb_score_update',
                                        score: this.score,
                                        current_level: nextLvl
                                    }, '*');
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
                                    this.showResultPopup('success', data.score, data.next_level);
                                } else if (isAuto) {
                                    this.showResultPopup('timeout', 0, data.next_level);
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Error saving progress:', error);
                        if (isCorrect) {
                            this.showResultPopup('success', 0, null);
                        } else {
                            this.showResultPopup('timeout', 0, null);
                        }
                    }
                    
                } else {
                    this.streak = 0;
                    const editor = document.querySelector('.bg-\\[\\#1a1a1a\\]');
                    if (editor) {
                        editor.classList.add('bg-red-900');
                        setTimeout(() => editor.classList.remove('bg-red-900'), 100);
                        setTimeout(() => {
                            editor.style.transform = 'translateX(8px)';
                            setTimeout(() => editor.style.transform = 'none', 100);
                        }, 100);
                    }
                    this.showResultPopup('wrong');
                }
            }
        }
    }
</script>

@include('partials.multiplayer-script')

@endsection
