@extends('layouts.game')

@section('content')
@vite('resources/js/games/flexbox-froggy.js')
<div x-data="gameLogic()" x-init="init()" class="flex flex-col lg:flex-row h-screen lg:h-full w-full bg-[#f3f4f6] relative overflow-y-auto lg:overflow-hidden">

    @include('partials.game-popup')

    <!-- LEFT PANEL -->
    <div class="w-full lg:w-[30%] lg:h-full p-4 sm:p-6 border-b-4 lg:border-b-0 lg:border-r-4 border-black flex flex-col bg-white lg:overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('games.index') }}" class="neo-border px-3 py-1 bg-[#FFE500] font-bold text-sm neo-button-hover inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                BACK
            </a>
            <div class="neo-border px-4 py-1 bg-white font-bold neo-shadow-sm">
                LEVEL <span x-text="currentLevel.order"></span>
            </div>
        </div>

        <div class="mb-6 flex-grow">
            <h2 class="text-2xl font-bold mb-4 font-mono uppercase border-b-4 border-black pb-2" x-text="game.title"></h2>
            <p class="text-lg font-medium leading-relaxed" x-text="currentLevel.instruction"></p>
        </div>

        <!-- Code Editor -->
        <div class="mb-6">
            <div class="bg-[#1a1a1a] neo-border neo-shadow p-4 rounded-none relative">
                <pre class="text-gray-400 font-mono text-sm mb-1 whitespace-pre" x-text="editorBefore"></pre>
                <textarea
                    x-model="userCode"
                    class="w-full bg-transparent text-[#00ff88] font-mono text-base resize-none outline-none focus:ring-0 border-none p-0 ml-4 min-h-[80px]"
                    spellcheck="false"
                    :placeholder="'/* tulis CSS-mu di sini */'"
                ></textarea>
                <pre class="text-gray-400 font-mono text-sm mt-1 whitespace-pre" x-text="editorAfter"></pre>
            </div>
        </div>

        <div class="flex gap-4 mt-auto">
            <button @click="submit(false)" :disabled="showPopup" class="flex-1 bg-[#FFE500] neo-border neo-shadow neo-button-hover font-bold text-lg py-4 uppercase disabled:opacity-50 h-[56px]">
                Submit
            </button>
            <button @click="showHint = !showHint" class="px-6 neo-border neo-shadow neo-button-hover font-bold text-lg uppercase h-[56px] transition-colors" :class="showHint ? 'bg-[#FFE500]' : 'bg-white'">
                <span x-show="!showHint">💡 Hint</span>
                <span x-show="showHint">✕ Tutup</span>
            </button>
        </div>

        <div x-show="showHint && currentLevel.hint" x-transition class="mt-4 neo-border p-4 relative" style="background: #FFF9C4; box-shadow: 3px 3px 0px var(--neo-black);">
            <div class="flex items-start gap-3">
                <span class="text-2xl flex-shrink-0">💡</span>
                <div>
                    <div class="font-black text-sm uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Hint</div>
                    <p class="font-bold text-sm leading-relaxed" x-text="currentLevel.hint"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- CENTER PANEL: Pond -->
    <div class="w-full lg:w-[40%] lg:h-full p-4 sm:p-8 flex flex-col items-center justify-center bg-[#87CEEB] border-b-4 lg:border-b-0 lg:border-r-4 border-black relative overflow-hidden min-h-[300px]">
        <div class="w-full aspect-square max-w-[500px] relative neo-border neo-shadow overflow-hidden" id="pond-container" style="background: #1565C0;">

            <!-- Lilypad layer (target positions) -->
            <div class="absolute inset-0 p-2" id="lilypad-layer"></div>

            <!-- Frog layer (user CSS positions) -->
            <div class="absolute inset-0 p-2" id="frog-layer"></div>

        </div>
    </div>

    @include('partials.game-right-panel')
</div>

<style>
    .lilypad {
        border-radius: 50%;
        border: 3px solid rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    .lilypad.green { background: rgba(76, 175, 80, 0.4); }
    .lilypad.yellow { background: rgba(255, 235, 59, 0.4); }
    .lilypad.red { background: rgba(244, 67, 54, 0.4); }

    .frog {
        border-radius: 50%;
        border: 3px solid rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.4s ease;
        z-index: 2;
        box-shadow: 2px 2px 0px rgba(0,0,0,0.3);
    }
    .frog.green { background: #4CAF50; }
    .frog.yellow { background: #FFEB3B; }
    .frog.red { background: #F44336; }
</style>

<script>
    const LEVEL_CONFIGS = {
        1:  { board: 'g',               before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        2:  { board: 'gy',              before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        3:  { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        4:  { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        5:  { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        6:  { board: 'g',               before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        7:  { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        8:  { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        9:  { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        10: { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        11: { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        12: { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        13: { board: 'gyr',             before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        14: { board: 'gyr',             before: '#pond {\n  display: flex;\n}\n\n.yellow {\n', after: '}', target: '.yellow' },
        15: { board: 'gggrg',           before: '#pond {\n  display: flex;\n}\n\n.red {\n', after: '}', target: '.red' },
        16: { board: 'ggygg',           before: '#pond {\n  display: flex;\n  align-items: flex-start;\n}\n\n.yellow {\n', after: '}', target: '.yellow', containerBase: 'align-items: flex-start;' },
        17: { board: 'ygygg',           before: '#pond {\n  display: flex;\n  align-items: flex-start;\n}\n\n.yellow {\n', after: '}', target: '.yellow', containerBase: 'align-items: flex-start;' },
        18: { board: 'ygggggr',         before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        19: { board: 'gggggrrrrryyyyy', before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        20: { board: 'gggggrrrrryyyyy', before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
        21: { board: 'ggggggggggggggg', before: '#pond {\n  display: flex;\n  flex-wrap: wrap;\n', after: '}', target: 'container', containerBase: 'flex-wrap: wrap;' },
        22: { board: 'ggggggggggggggg', before: '#pond {\n  display: flex;\n  flex-wrap: wrap;\n', after: '}', target: 'container', containerBase: 'flex-wrap: wrap;' },
        23: { board: 'rgggyrgggyrgggy', before: '#pond {\n  display: flex;\n  flex-wrap: wrap;\n', after: '}', target: 'container', containerBase: 'flex-wrap: wrap;' },
        24: { board: 'rggggyy',         before: '#pond {\n  display: flex;\n', after: '}', target: 'container' },
    };

    const COLOR_MAP = { g: 'green', y: 'yellow', r: 'red' };
    const EMOJI_MAP = { g: '🐸', y: '🐸', r: '🐸' };

    function getConfig(order) {
        return LEVEL_CONFIGS[order] || LEVEL_CONFIGS[1];
    }

    function getFrogSize(boardLen) {
        if (boardLen <= 3) return 56;
        if (boardLen <= 5) return 44;
        if (boardLen <= 7) return 38;
        return 28;
    }

    function buildLayer(containerId, board, containerStyle, itemStyles, isLilypad) {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = '';

        const flexDiv = document.createElement('div');
        flexDiv.style.cssText = 'display:flex;width:100%;height:100%;' + containerStyle;

        const size = getFrogSize(board.length);
        const fontSize = size > 40 ? '1.5rem' : (size > 30 ? '1.1rem' : '0.8rem');

        board.split('').forEach((c, i) => {
            const el = document.createElement('div');
            el.className = (isLilypad ? 'lilypad ' : 'frog ') + COLOR_MAP[c];
            el.style.width = size + 'px';
            el.style.height = size + 'px';
            el.style.fontSize = fontSize;
            el.style.margin = '2px';
            el.textContent = isLilypad ? '🌿' : '🐸';

            if (itemStyles && itemStyles[i]) {
                el.style.cssText += itemStyles[i];
            }
            flexDiv.appendChild(el);
        });

        container.appendChild(flexDiv);
    }

    function renderPond(order, userCode, answerKey) {
        const cfg = getConfig(order);
        const board = cfg.board;
        const baseCSS = cfg.containerBase || '';

        // Build answer style for lilypads
        const answerCSS = answerKey.split(';').map(s => s.trim()).filter(s => s).map(s => s + ';').join(' ');

        // Determine lilypad positioning
        let lilyContainerStyle, lilyItemStyles = null;
        if (cfg.target === 'container') {
            lilyContainerStyle = baseCSS + ' ' + answerCSS;
        } else {
            lilyContainerStyle = baseCSS;
            lilyItemStyles = {};
            const targetClass = cfg.target.replace('.', '');
            board.split('').forEach((c, i) => {
                if (COLOR_MAP[c] === targetClass) {
                    lilyItemStyles[i] = answerCSS;
                }
            });
        }
        buildLayer('lilypad-layer', board, lilyContainerStyle, lilyItemStyles, true);

        // Build frog positioning from user code
        let frogContainerStyle, frogItemStyles = null;
        if (cfg.target === 'container') {
            frogContainerStyle = baseCSS + ' ' + userCode;
        } else {
            frogContainerStyle = baseCSS;
            frogItemStyles = {};
            const targetClass = cfg.target.replace('.', '');
            board.split('').forEach((c, i) => {
                if (COLOR_MAP[c] === targetClass) {
                    frogItemStyles[i] = userCode;
                }
            });
        }
        buildLayer('frog-layer', board, frogContainerStyle, frogItemStyles, false);
    }

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

            get currentLevel() {
                return this.levels[this.currentLevelIndex] || {};
            },
            get currentProgress() {
                return (this.currentLevelIndex + 1) + '/' + this.levels.length;
            },
            get timerColorClass() {
                if (this.timeLeft > 30) return 'bg-[#00ff88] text-black';
                if (this.timeLeft >= 15) return 'bg-[#FFE500] text-black';
                return 'bg-red-500 text-white';
            },
            get editorBefore() {
                const cfg = getConfig(this.currentLevel.order);
                return cfg.before;
            },
            get editorAfter() {
                const cfg = getConfig(this.currentLevel.order);
                return cfg.after;
            },

            init() {
                if (this.roomCode) {
                    this.currentLevelIndex = 0;
                    this.score = 0;
                    this.completedLevels = [];
                    this.levelScores = {};
                    this.timeLeft = 300;
                } else {
                    let firstUncompleted = this.levels.findIndex(l => !this.completedLevels.includes(l.id));
                    if (firstUncompleted !== -1) {
                        this.currentLevelIndex = firstUncompleted;
                    } else if (this.levels.length > 0) {
                        this.currentLevelIndex = this.levels.length - 1;
                    }
                    const progressData = @json($userProgress->values());
                    progressData.forEach(p => { this.levelScores[p.level_id] = p.score; });
                    this.score = progressData.reduce((acc, curr) => acc + curr.score, 0);
                }

                this.loadLevel();
                this.startTimer();
                this.displayScore = this.score;

                this.$watch('score', value => { this.animateScore(value); });
                this.$watch('userCode', () => {
                    this.updatePond();
                });
            },

            updatePond() {
                if (this.currentLevel.order) {
                    renderPond(this.currentLevel.order, this.userCode, this.currentLevel.answer_key || '');
                }
            },

            loadLevel() {
                this.userCode = '';
                if (!this.roomCode) { this.timeLeft = 60; }
                this.attempts = 0;
                this.showHint = false;
                this.$nextTick(() => { this.updatePond(); });
            },

            goToLevel(index) {
                this.currentLevelIndex = index;
                this.loadLevel();
            },

            startTimer() {
                if (this.timer) clearInterval(this.timer);
                this.timer = setInterval(() => {
                    if (this.roomCode) {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                            if (this.timeLeft === 0) { clearInterval(this.timer); this.finishRoom(); }
                        }
                    } else {
                        if (this.timeLeft > 0 && !this.showPopup) {
                            this.timeLeft--;
                            if (this.timeLeft === 0) { this.autoSubmit(); }
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
                let startTime = performance.now();
                const update = (currentTime) => {
                    let progress = Math.min((currentTime - startTime) / 500, 1);
                    this.displayScore = Math.floor(start + (target - start) * progress);
                    if (progress < 1) requestAnimationFrame(update);
                    else this.displayScore = target;
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
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                    });
                } catch (e) { console.error('Failed to finish room', e); }
            },

            async advanceToNextLevel(nextLevelId) {
                this.showPopup = false;
                let finished = false;
                if (nextLevelId) {
                    const idx = this.levels.findIndex(l => l.id === nextLevelId);
                    if (idx !== -1) this.currentLevelIndex = idx;
                    else if (this.currentLevelIndex < this.levels.length - 1) this.currentLevelIndex++;
                    else finished = true;
                } else if (this.currentLevelIndex < this.levels.length - 1) {
                    this.currentLevelIndex++;
                } else { finished = true; }

                if (finished) {
                    if (this.roomCode) { await this.finishRoom(); this.showResultPopup('stage_finished', this.score); }
                    else { window.location.href = '/games/' + this.game.slug + '/complete'; }
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
                    } else { this.streak = 0; }

                    try {
                        let response, data;
                        if (this.roomCode) {
                            let updatedScore = this.score + earnedScore;
                            response = await fetch(`/rooms/${this.roomCode}/score`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                                body: JSON.stringify({ score: updatedScore, current_level: this.currentLevelIndex + 1 + (isCorrect ? 1 : 0) })
                            });
                            data = await response.json();
                            if (data.success) {
                                this.score = data.score;
                                this.levelScores[this.currentLevel.id] = earnedScore;
                                if (!this.completedLevels.includes(this.currentLevel.id)) this.completedLevels.push(this.currentLevel.id);
                                if (isCorrect) {
                                    const pond = document.getElementById('pond-container');
                                    if (pond) { pond.classList.add('scale-105', 'transition-transform'); setTimeout(() => pond.classList.remove('scale-105'), 300); }
                                    this.showResultPopup('success', earnedScore, null);
                                } else if (isAuto) { this.showResultPopup('timeout', 0, null); }
                            }
                        } else {
                            response = await fetch('/games/progress', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                                body: JSON.stringify({ level_id: this.currentLevel.id, score: earnedScore, time_taken: 60 - this.timeLeft })
                            });
                            data = await response.json();
                            if (data.success) {
                                this.levelScores[this.currentLevel.id] = data.score;
                                this.score = data.total_score;
                                if (!this.completedLevels.includes(this.currentLevel.id)) this.completedLevels.push(this.currentLevel.id);
                                if (isCorrect) {
                                    const pond = document.getElementById('pond-container');
                                    if (pond) { pond.classList.add('scale-105', 'transition-transform'); setTimeout(() => pond.classList.remove('scale-105'), 300); }
                                    this.showResultPopup('success', data.score, data.next_level);
                                } else if (isAuto) { this.showResultPopup('timeout', 0, data.next_level); }
                            }
                        }
                    } catch (error) {
                        console.error('Error saving progress:', error);
                        if (isCorrect) this.showResultPopup('success', 0, null);
                        else this.showResultPopup('timeout', 0, null);
                    }
                } else {
                    this.streak = 0;
                    const editor = document.querySelector('textarea');
                    if (editor) {
                        editor.classList.add('bg-red-900', 'bg-opacity-30', 'translate-x-2');
                        setTimeout(() => editor.classList.remove('bg-red-900', 'bg-opacity-30', 'translate-x-2'), 100);
                        setTimeout(() => { editor.classList.add('-translate-x-2'); setTimeout(() => editor.classList.remove('-translate-x-2'), 100); }, 100);
                    }
                    this.showResultPopup('wrong');
                }
            }
        }
    }
</script>

@include('partials.multiplayer-script')
@endsection
