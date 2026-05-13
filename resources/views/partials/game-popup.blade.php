<!-- Unified Popup Overlay (Success / Wrong / Time-Out) -->
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
    <div class="bg-white neo-border p-8 flex flex-col items-center justify-center max-w-md w-full neo-shadow m-4 max-h-[90vh] overflow-y-auto" 
         :style="popupType === 'success' ? 'box-shadow: 8px 8px 0px #00ff88;' : (popupType === 'timeout' ? 'box-shadow: 8px 8px 0px #FFE500;' : 'box-shadow: 8px 8px 0px #FF4444;')">
        
        <!-- Success State -->
        <template x-if="popupType === 'success'">
            <div class="flex flex-col items-center w-full">
                <h2 class="text-3xl font-black mb-3 uppercase text-center text-[#00cc66]">Level Passed!</h2>
                <div class="text-5xl mb-3">🎉</div>
                <div class="text-2xl font-bold font-mono">+<span x-text="popupScore"></span> Points</div>
            </div>
        </template>

        <!-- Wrong Answer State -->
        <template x-if="popupType === 'wrong'">
            <div class="flex flex-col items-center w-full">
                <h2 class="text-3xl font-black mb-3 uppercase text-center text-red-500">Belum Tepat!</h2>
                <div class="text-5xl mb-3">❌</div>
                <p class="text-center font-bold text-gray-600 mb-1">Jawaban kamu belum benar.</p>
                <p class="text-center text-sm text-gray-500">Coba lagi, kamu pasti bisa!</p>
            </div>
        </template>

        <!-- Time-Out State -->
        <template x-if="popupType === 'timeout'">
            <div class="flex flex-col items-center w-full">
                <h2 class="text-3xl font-black mb-3 uppercase text-center text-[#CC9900]">Waktu Habis!</h2>
                <div class="text-5xl mb-3">⏰</div>
                <p class="text-center font-bold text-gray-600">Waktu sudah habis untuk level ini.</p>
            </div>
        </template>
        
        <!-- Stage Finished State (Babak Belur) -->
        <template x-if="popupType === 'stage_finished'">
            <div class="flex flex-col items-center w-full">
                <h2 class="text-3xl font-black mb-3 uppercase text-center text-[#00cc66]">Selamat!</h2>
                <div class="text-5xl mb-3">🏅</div>
                <p class="text-center font-bold text-gray-600 mb-2">Mohon Tunggu Player Lain</p>
                <div class="neo-border p-3 bg-gray-50 w-full text-center">
                    <div class="text-xs font-black uppercase text-gray-400">Skor Stage</div>
                    <div class="text-2xl font-black" x-text="popupScore"></div>
                </div>
                <p class="text-center text-sm text-gray-500 mt-4 animate-pulse">Menunggu pemain lain & host...</p>
            </div>
        </template>

        <!-- Explanation (shown on success and timeout) -->
        <div x-show="popupType !== 'wrong' && currentLevel.explanation" class="w-full mt-5 neo-border p-4 relative" style="background: #F0FFF4; box-shadow: 3px 3px 0px var(--neo-black);">
            <div class="flex items-start gap-3">
                <span class="text-xl flex-shrink-0">📖</span>
                <div>
                    <div class="font-black text-xs uppercase mb-1" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Penjelasan</div>
                    <p class="text-sm leading-relaxed" x-text="currentLevel.explanation"></p>
                </div>
            </div>
        </div>

        <!-- Answer Key (shown on timeout) -->
        <div x-show="popupType === 'timeout'" class="w-full mt-3 neo-border p-3 bg-gray-50" style="box-shadow: 2px 2px 0px var(--neo-black);">
            <div class="font-black text-xs uppercase mb-1" style="font-family: 'Space Mono', monospace;">Jawaban:</div>
            <code class="text-sm font-mono text-[#00aa55] break-all" x-text="currentLevel.answer_key"></code>
        </div>

        <!-- Action Buttons -->
        <div class="w-full mt-5">
            <button 
                x-show="popupType === 'wrong'" 
                @click="showPopup = false" 
                class="w-full bg-[#FFE500] neo-border neo-shadow neo-button-hover font-bold text-lg py-3 uppercase"
            >
                🔄 Coba Lagi
            </button>
            <button 
                x-show="popupType !== 'wrong' && popupType !== 'stage_finished'" 
                @click="advanceToNextLevel(popupNextLevel)" 
                class="w-full bg-[#00ff88] neo-border neo-shadow neo-button-hover font-bold text-lg py-3 uppercase"
            >
                ➡️ Lanjut
            </button>
            <div x-show="popupType === 'stage_finished'" class="w-full bg-gray-100 neo-border p-3 text-center font-black uppercase text-gray-500">
                ⏳ Menunggu Host
            </div>
        </div>
    </div>
</div>
