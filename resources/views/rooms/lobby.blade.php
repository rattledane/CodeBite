@extends('layouts.app')

@section('content')
<div class="bg-[#f3f4f6] min-h-screen pb-20 pt-8" 
     x-data="lobbyState({{ json_encode($room->code) }}, {{ json_encode($participants->pluck('user')->toArray()) }}, {{ $room->host_id === auth()->id() ? 'true' : 'false' }}, {{ $room->max_players }})">
    
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- TOP SECTION: Room Code & Game Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            
            <!-- Game Info -->
            <div class="col-span-1 bg-white neo-border p-6 shadow-[8px_8px_0px_#000] flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-black uppercase mb-4 border-b-4 border-black pb-2">Game Info</h2>
                    <h3 class="text-3xl font-black">{{ $room->game->title }}</h3>
                    <p class="font-bold text-gray-600 mt-2">{{ $room->game->description }}</p>
                </div>
                
                @if($room->host_id === auth()->id())
                <div class="mt-6">
                    <button class="w-full py-3 bg-white neo-border font-black uppercase tracking-wider shadow-[4px_4px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-[0px_0px_0px_#000] transition-all">
                        Ganti Game
                    </button>
                </div>
                @endif
            </div>

            <!-- Room Code (Center) -->
            <div class="col-span-1 md:col-span-2 flex flex-col items-center justify-center bg-[#FFE500] neo-border p-6 sm:p-8 shadow-[8px_8px_0px_#000]">
                <h2 class="text-2xl font-black uppercase mb-2 text-center">Kode Room</h2>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full sm:w-auto">
                    <div class="text-5xl sm:text-6xl md:text-8xl font-black font-mono tracking-widest bg-white px-4 sm:px-6 py-2 neo-border text-center break-all">
                        {{ $room->code }}
                    </div>
                    <button @click="copyCode" class="h-auto sm:h-full py-3 px-6 bg-[#00ff88] neo-border font-black shadow-[4px_4px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-[0px_0px_0px_#000] transition-all flex sm:flex-col items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span>Salin</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- PLAYERS SECTION -->
        <div class="bg-white neo-border p-8 shadow-[12px_12px_0px_#000] mb-10">
            <div class="flex justify-between items-end mb-8 border-b-4 border-black pb-4">
                <h2 class="text-4xl font-black uppercase">Pemain</h2>
                <span class="text-2xl font-black bg-[#FFE500] px-4 py-1 neo-border shadow-[4px_4px_0px_#000]" x-text="`${players.length}/${maxPlayers}`"></span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                <!-- Joined Players -->
                <template x-for="player in players" :key="player.id">
                    <div class="flex flex-col items-center p-4 neo-border bg-[#f3f4f6] relative animate-fade-in shadow-[4px_4px_0px_#000]">
                        <!-- Host Badge -->
                        <template x-if="player.id === {{ $room->host_id }}">
                            <div class="absolute -top-4 -right-4 bg-[#FFE500] p-2 rounded-full neo-border shadow-[2px_2px_0px_#000] z-10 text-xl" title="Room Host">
                                👑
                            </div>
                        </template>

                        <div class="w-20 h-20 rounded-full neo-border overflow-hidden bg-white mb-3">
                            <template x-if="player.avatar">
                                <img :src="player.avatar" :alt="player.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!player.avatar">
                                <div class="w-full h-full flex items-center justify-center font-black text-3xl" x-text="player.name.substring(0, 1)"></div>
                            </template>
                        </div>
                        <span class="font-black text-center truncate w-full" x-text="player.name"></span>
                    </div>
                </template>

                <!-- Empty Slots -->
                <template x-for="i in (maxPlayers - players.length)" :key="'empty-'+i">
                    <div class="flex flex-col items-center justify-center p-4 border-4 border-dashed border-gray-300 bg-gray-50 h-[156px]">
                        <span class="text-gray-400 font-bold text-center">Menunggu...</span>
                    </div>
                </template>
            </div>
        </div>

        <!-- ACTION SECTION -->
        <div class="bg-black text-white neo-border p-6 sm:p-8 shadow-[8px_8px_0px_#FFE500] flex flex-col md:flex-row items-center justify-between text-center md:text-left gap-6 md:gap-0">
            <div>
                <p class="text-xl font-bold">Status Room:</p>
                <p class="text-2xl sm:text-3xl font-black text-[#00ff88]" x-text="players.length >= 2 ? 'Siap Dimulai!' : 'Menunggu pemain lain...'"></p>
            </div>

            <div class="w-full md:w-auto">
                <template x-if="isHost">
                    <button @click="startGame" 
                            :disabled="players.length < 2 || isStarting"
                            :class="players.length < 2 ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-[#FFE500] hover:translate-x-1 hover:translate-y-1 hover:shadow-[0px_0px_0px_#000]'"
                            class="w-full md:w-auto px-6 sm:px-10 py-4 text-black neo-border font-black text-xl sm:text-2xl uppercase tracking-wider shadow-[6px_6px_0px_#fff] transition-all flex justify-center items-center gap-3">
                        <span x-show="!isStarting">🚀 Mulai Race!</span>
                        <span x-show="isStarting">Memulai...</span>
                    </button>
                </template>

                <template x-if="!isHost">
                    <div class="w-full md:w-auto px-4 sm:px-8 py-4 bg-gray-800 border-2 border-white font-black text-lg sm:text-xl uppercase tracking-wider flex justify-center items-center gap-4">
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
         class="fixed bottom-10 right-10 bg-white neo-border p-4 shadow-[8px_8px_0px_#000] z-50 flex items-center gap-4 max-w-sm"
         style="display: none;">
        <div class="w-12 h-12 bg-[#00ff88] rounded-full neo-border flex items-center justify-center text-2xl">
            👋
        </div>
        <div>
            <p class="font-black" x-text="toast.message"></p>
        </div>
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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
