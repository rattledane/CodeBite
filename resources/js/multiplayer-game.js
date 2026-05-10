window.multiplayerBoard = function(roomCode) {
    return {
        roomCode: roomCode,
        players: [],
        myRank: 0,
        showFinishModal: false,
        finalRankings: [],

        init() {
            this.fetchInitialPlayers();

            if (window.Echo) {
                window.Echo.channel(`room.${this.roomCode}`)
                    .listen('.player.joined', (e) => {
                        this.fetchInitialPlayers();
                    })
                    .listen('.score.updated', (e) => {
                        this.updatePlayerScore(e);
                    })
                    .listen('.race.finished', (e) => {
                        this.finalRankings = e.rankings;
                        this.showFinishModal = true;
                    });
            }
        },

        async fetchInitialPlayers() {
            try {
                if (window.roomParticipants) {
                    this.players = window.roomParticipants.map(p => ({
                        user_id: p.user_id,
                        username: p.user?.name || 'Unknown',
                        avatar: p.user?.avatar,
                        score: p.score || 0,
                        current_level: p.current_level || 1,
                        showPlus: false,
                        lastScoreDelta: 0
                    }));
                    this.updateRank();
                }
            } catch (err) {
                console.error(err);
            }
        },

        get sortedPlayers() {
            return this.players.slice().sort((a, b) => b.score - a.score);
        },

        updatePlayerScore(data) {
            let player = this.players.find(p => p.user_id === data.user_id);
            if (player) {
                let delta = data.score - player.score;
                if (delta > 0) {
                    player.lastScoreDelta = delta;
                    player.showPlus = true;
                    setTimeout(() => { player.showPlus = false; }, 2000);
                }
                player.score = data.score;
                player.current_level = data.current_level;
            } else {
                this.players.push({
                    user_id: data.user_id,
                    username: data.username,
                    avatar: data.avatar,
                    score: data.score,
                    current_level: data.current_level,
                    showPlus: false,
                    lastScoreDelta: 0
                });
            }
            this.updateRank();
        },

        updateRank() {
            const sorted = this.sortedPlayers;
            const myId = document.querySelector('meta[name="user-id"]')?.content;
            if (myId) {
                const index = sorted.findIndex(p => p.user_id == myId);
                this.myRank = index + 1;
            }
        },

        getOrdinal(n) {
            let s = ["th", "st", "nd", "rd"],
                v = n % 100;
            return (s[(v - 20) % 10] || s[v] || s[0]);
        }
    };
};
