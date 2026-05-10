export default class GameEngine {
    constructor(config) {
        this.gameSlug = config.gameSlug;
        this.totalLevels = config.totalLevels;
        this.timerSeconds = config.timerSeconds || 60;
        
        this.currentLevelIndex = config.initialLevelIndex || 0;
        this.timeLeft = this.timerSeconds;
        this.timer = null;
        this.attempts = 0;
        
        // Event callbacks (to be overridden or hooked into)
        this.onCorrect = config.onCorrect || function() {};
        this.onWrong = config.onWrong || function() {};
        this.onLevelComplete = config.onLevelComplete || function() {};
        this.onGameComplete = config.onGameComplete || function() {};
        this.onTimerUpdate = config.onTimerUpdate || function() {};
        this.onTimerEnd = config.onTimerEnd || function() {};
    }

    startTimer() {
        this.stopTimer();
        this.timer = setInterval(() => {
            if (this.timeLeft > 0) {
                this.timeLeft--;
                this.onTimerUpdate(this.timeLeft);
                if (this.timeLeft === 0) {
                    this.stopTimer();
                    this.onTimerEnd();
                }
            }
        }, 1000);
    }

    stopTimer() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    }

    resetTimer() {
        this.timeLeft = this.timerSeconds;
        this.onTimerUpdate(this.timeLeft);
    }

    async submitAnswer(isCorrect, levelId, maxScore, csrfToken) {
        this.attempts++;
        this.stopTimer();

        let earnedScore = 0;

        if (isCorrect) {
            earnedScore = this.calculateScore(this.timeLeft, maxScore, this.attempts);
            this.onCorrect(earnedScore);
        } else {
            this.onWrong();
            // Don't auto-proceed on wrong, user has to retry unless time is up
            if (this.timeLeft > 0) {
                this.startTimer(); // resume timer
                return false;
            }
        }

        try {
            // Save progress
            const response = await fetch('/games/progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    level_id: levelId,
                    score: earnedScore,
                    time_taken: this.timerSeconds - this.timeLeft
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.onLevelComplete(data);
                return data;
            }
        } catch (error) {
            console.error('Error saving progress:', error);
        }

        return false;
    }

    loadNextLevel(nextLevelId = null, levelsArray = []) {
        this.attempts = 0;
        this.resetTimer();
        this.startTimer();

        if (nextLevelId && levelsArray.length > 0) {
            const idx = levelsArray.findIndex(l => l.id === nextLevelId);
            if (idx !== -1) {
                this.currentLevelIndex = idx;
                return true;
            }
        }
        
        if (this.currentLevelIndex < this.totalLevels - 1) {
            this.currentLevelIndex++;
            return true;
        } else {
            this.onGameComplete();
            return false;
        }
    }

    calculateScore(timeRemaining, maxScore = 100, attempts = 1) {
        if (timeRemaining <= 0 && attempts > 0) return 0;
        
        let score = 0;
        if (timeRemaining > 45) {
            score = maxScore;
        } else if (timeRemaining >= 30) {
            score = Math.floor(maxScore * 0.8);
        } else if (timeRemaining >= 15) {
            score = Math.floor(maxScore * 0.6);
        } else {
            score = Math.floor(maxScore * 0.4);
        }
        
        if (attempts === 1) score += 10;
        
        return score;
    }

    showSuccess(elementId = 'pond-container') {
        const el = document.getElementById(elementId);
        if (el) {
            el.classList.add('scale-105', 'transition-transform');
            setTimeout(() => el.classList.remove('scale-105'), 300);
        }
    }

    showError(elementSelector = 'textarea') {
        const el = document.querySelector(elementSelector);
        if (el) {
            el.classList.add('bg-red-900', 'bg-opacity-30', 'translate-x-2');
            setTimeout(() => el.classList.remove('bg-red-900', 'bg-opacity-30', 'translate-x-2'), 100);
            setTimeout(() => {
                el.classList.add('-translate-x-2');
                setTimeout(() => el.classList.remove('-translate-x-2'), 100);
            }, 100);
        }
    }

    static normalizeCSS(cssString) {
        if (!cssString) return '';
        let normalized = cssString.toLowerCase();
        let properties = normalized.split(';').map(prop => prop.trim()).filter(prop => prop !== '');
        
        properties = properties.map(prop => {
            let parts = prop.split(':');
            if (parts.length >= 2) {
                let key = parts[0].trim();
                let val = parts.slice(1).join(':').trim().replace(/\s+/g, ' '); 
                return `${key}:${val}`;
            }
            return prop.replace(/\s+/g, '');
        });
        
        properties.sort();
        return properties.join(';') + (properties.length > 0 ? ';' : '');
    }

    static checkAnswer(userInput, answerKey) {
        const userNormalized = this.normalizeCSS(userInput);
        const answerNormalized = this.normalizeCSS(answerKey);
        
        if (answerNormalized === '') return true;
        const answerProps = answerNormalized.split(';').filter(p => p !== '');
        const userProps = userNormalized.split(';').filter(p => p !== '');
        
        if (answerProps.length === 0) return false;
        return answerProps.every(prop => userProps.includes(prop));
    }

    static calculateScore(timeRemaining, maxScore = 100, attempts = 1) {
        if (timeRemaining <= 0 && attempts > 0 && !window.forcedSubmit) {
            return 0;
        }
        
        let score = 0;
        if (timeRemaining > 45) {
            score = maxScore;
        } else if (timeRemaining >= 30) {
            score = Math.floor(maxScore * 0.8);
        } else if (timeRemaining >= 15) {
            score = Math.floor(maxScore * 0.6);
        } else {
            score = Math.floor(maxScore * 0.4);
        }
        
        if (attempts === 1) {
            score += 10;
        }
        
        return score;
    }
}

window.GameEngine = GameEngine;
window.checkAnswer = GameEngine.checkAnswer.bind(GameEngine);
window.calculateScore = GameEngine.calculateScore.bind(GameEngine);
