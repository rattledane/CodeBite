@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20 pt-8"
     x-data="lobbyState({{ json_encode($room->code) }}, {{ json_encode($participants->pluck('user')->toArray()) }}, {{ $room->host_id === auth()->id() ? 'true' : 'false' }}, {{ $room->max_players }})">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- TOP SECTION: Room Code & Game Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            <!-- Game Info -->
            <div class="col-span-1 neo-card p-6 flex flex-col justify-between animate-slide-in">
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-3" style="border-bottom: 4px solid var(--neo-black);">
                        <span class="neo-border px-2 py-0.5 text-xs font-black uppercase" style="background: var(--neo-blue); color: white; font-family: 'Space Mono', monospace; box-shadow: 2px 2px 0px var(--neo-black);">INFO</span>
                        <h2 class="text-lg font-black uppercase" style="font-family: 'Space Mono', monospace;">Game Info</h2>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-black" style="font-family: 'Space Mono', monospace;">{{ $room->game->title }}</h3>
                    <p class="font-bold text-gray-600 mt-2">{{ $room->game->description }}</p>
                </div>

                @if($room->host_id === auth()->id())
                <div class="mt-6">
                    <button class="neo-btn w-full uppercase tracking-wider text-sm" style="font-family: 'Space Mono', monospace;">
                        Ganti Game
                    </button>
                </div>
                @endif
            </div>

            <!-- Room Code (Center) -->
            <div class="col-span-1 md:col-span-2 flex flex-col items-center justify-center neo-border p-6 sm:p-8 animate-slide-in stagger-1" style="background: var(--neo-yellow); box-shadow: 8px 8px 0px var(--neo-black);">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-3 h-3 neo-border" style="background: var(--neo-green); animation: pulse 2s ease-in-out infinite;"></div>
                    <h2 class="text-xl font-black uppercase" style="font-family: 'Space Mono', monospace; letter-spacing: 2px;">Kode Room</h2>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full sm:w-auto">
                    <div class="text-5xl sm:text-6xl md:text-8xl font-black tracking-widest bg-white px-4 sm:px-6 py-2 neo-border text-center break-all" style="font-family: 'Space Mono', monospace; box-shadow: 4px 4px 0px var(--neo-black);">
                        {{ $room->code }}
                    </div>
                    <button @click="copyCode" class="neo-btn flex sm:flex-col items-center justify-center gap-2" style="background: var(--neo-green); padding: 12px 24px; font-family: 'Space Mono', monospace;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span>Salin</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- PLAYERS SECTION -->
        <div class="neo-card p-6 sm:p-8 mb-10 animate-slide-in stagger-2" style="box-shadow: 8px 8px 0px var(--neo-black);">
            <div class="flex justify-between items-end mb-8 pb-4" style="border-bottom: 4px solid var(--neo-black);">
                <h2 class="text-3xl md:text-4xl font-black uppercase" style="font-family: 'Space Mono', monospace;">Pemain</h2>
                <span class="text-xl font-black neo-border px-4 py-1" style="background: var(--neo-yellow); box-shadow: var(--neo-shadow-sm); font-family: 'Space Mono', monospace;" x-text="`${players.length}/${maxPlayers}`"></span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                <!-- Joined Players -->
                <template x-for="(player, index) in players" :key="player.id">
                    <div class="flex flex-col items-center p-4 neo-border relative" style="background: #f9f9f4; box-shadow: var(--neo-shadow-sm); animation: bounceIn 0.5s ease-out forwards;" :style="`animation-delay: ${index * 0.1}s;`">
                        <!-- Host Badge -->
                        <template x-if="player.id === {{ $room->host_id }}">
                            <div class="absolute -top-4 -right-4 neo-border p-1.5 z-10 text-xl" style="background: var(--neo-yellow); box-shadow: 2px 2px 0px var(--neo-black);" title="Room Host">
                                👑
                            </div>
                        </template>

                        <div class="w-20 h-20 neo-border overflow-hidden bg-white mb-3" style="box-shadow: 2px 2px 0px var(--neo-black);">
                            <template x-if="player.avatar">
                                <img :src="player.avatar" :alt="player.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!player.avatar">
                                <div class="w-full h-full flex items-center justify-center font-black text-3xl" x-text="player.name.substring(0, 1)"></div>
                            </template>
                        </div>
                        <span class="font-black text-center truncate w-full" style="font-family: 'Space Mono', monospace; font-size: 13px;" x-text="player.name"></span>
                    </div>
                </template>

                <!-- Empty Slots -->
                <template x-for="i in (maxPlayers - players.length)" :key="'empty-'+i">
                    <div class="flex flex-col items-center justify-center p-4 h-[156px]" style="border: 4px dashed #ccc; background: #fafaf5;">
                        <div class="w-8 h-8 mb-2" style="border: 3px dashed #ccc; opacity: 0.5;"></div>
                        <span class="text-gray-400 font-bold text-center text-sm" style="font-family: 'Space Mono', monospace;">Menunggu...</span>
                    </div>
                </template>
            </div>
        </div>

        <!-- ACTION SECTION -->
        <div class="neo-border p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between text-center md:text-left gap-6 md:gap-0 animate-slide-in stagger-3" style="background: var(--neo-black); color: white; box-shadow: 8px 8px 0px var(--neo-yellow);">
            <div>
                <p class="text-lg font-bold" style="font-family: 'Space Mono', monospace;">Status Room:</p>
                <p class="text-2xl sm:text-3xl font-black" style="font-family: 'Space Mono', monospace; color: var(--neo-green);" x-text="players.length >= 2 ? 'Siap Dimulai!' : 'Menunggu pemain lain...'"></p>
            </div>

            <div class="w-full md:w-auto">
                <template x-if="isHost">
                    <button @click="startGame"
                            :disabled="players.length < 2 || isStarting"
                            :class="players.length < 2 ? 'opacity-50 cursor-not-allowed' : ''"
                            :style="players.length < 2 ? 'background: #666;' : 'background: var(--neo-yellow);'"
                            class="neo-btn w-full md:w-auto text-xl sm:text-2xl uppercase tracking-wider" style="color: var(--neo-black); padding: 16px 40px; font-family: 'Space Mono', monospace; border-color: white; box-shadow: 6px 6px 0px white;">
                        <span x-show="!isStarting">🚀 Mulai Race!</span>
                        <span x-show="isStarting">Memulai...</span>
                    </button>
                </template>

                <template x-if="!isHost">
                    <div class="w-full md:w-auto px-4 sm:px-8 py-4 neo-border font-black text-lg sm:text-xl uppercase tracking-wider flex justify-center items-center gap-4" style="background: #333; border-color: white; font-family: 'Space Mono', monospace;">
                        <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menunggu Host...
                    </div>
                </template>
            </div>
        </div>

    </div>

    <!-- Toast Notification -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         class="fixed bottom-10 right-10 neo-card p-4 z-50 flex items-center gap-4 max-w-sm"
         style="box-shadow: 8px 8px 0px var(--neo-black);"
         x-cloak>
        <div class="w-12 h-12 neo-border flex items-center justify-center text-2xl" style="background: var(--neo-green); box-shadow: 2px 2px 0px var(--neo-black);">
            👋
        </div>
        <div>
            <p class="font-black" style="font-family: 'Space Mono', monospace;" x-text="toast.message"></p>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
</style>


<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('lobbyState', (roomCode, initialPlayers, isHost, maxPlayers) => ({
            roomCode: roomCode,
            players: initialPlayers,
            isHost: isHost,
            maxPlayers: maxPlayers,
            isStarting: false,
            toast: {
                show: false,
                message: ''
            },

            init() {
                // Listen to Laravel Echo Reverb Events
                if (window.Echo) {
                    window.Echo.channel(`room.${this.roomCode}`)
                        .listen('.player.joined', (e) => {
                            // Prevent duplicates
                            if (!this.players.find(p => p.id === e.user.id)) {
                                this.players.push(e.user);
                                this.showToast(`${e.user.name} bergabung!`);
                            }
                        })
                        .listen('.game.started', (e) => {
                            this.showToast('Game dimulai! Mengarahkan...');
                            setTimeout(() => {
                                window.location.href = `/games/${e.game_slug}/play?room=${this.roomCode}`;
                            }, 1000);
                        });
                } else {
                    console.error("Echo is not loaded!");
                }
            },

            async startGame() {
                if (this.players.length < 2 || this.isStarting) return;

                this.isStarting = true;

                try {
                    const response = await axios.post(`/rooms/${this.roomCode}/start`);
                    if (response.data.success) {
                        // The broadcast will handle the redirect for everyone, including host
                    }
                } catch (error) {
                    console.error("Failed to start game:", error);
                    alert("Gagal memulai game: " + (error.response?.data?.error || "Terjadi kesalahan"));
                    this.isStarting = false;
                }
            },

            copyCode() {
                navigator.clipboard.writeText(this.roomCode).then(() => {
                    this.showToast('Kode berhasil disalin!');
                });
            },

            showToast(message) {
                this.toast.message = message;
                this.toast.show = true;
                setTimeout(() => {
                    this.toast.show = false;
                }, 3000);
            }
        }));
    });
</script>
@endsection
