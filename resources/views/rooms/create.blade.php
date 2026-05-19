@extends('layouts.app')

@section('content')
<div class="min-h-screen pb-20 pt-12" x-data="createRoom()">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-12 text-center animate-slide-in">
            <div class="inline-block bg-white neo-border px-4 py-1 mb-4" style="box-shadow: var(--neo-shadow-sm); font-family: 'Space Mono', monospace; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">
                ⚔️ Real-Time Battle
            </div>
            <h1 class="text-5xl md:text-7xl font-black uppercase tracking-tighter" style="font-family: 'Space Mono', monospace;">
                Multiplayer
            </h1>
            <p class="text-lg font-bold bg-white inline-block px-6 py-2 neo-border mt-4" style="box-shadow: var(--neo-shadow-sm);">
                Main bareng teman-temanmu secara Real-Time! 🚀
            </p>
        </div>

        @if($errors->any())
            <div class="neo-border p-4 mb-8 font-bold text-white animate-slide-in" style="background: #FF6B6B; box-shadow: var(--neo-shadow);">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- JOIN ROOM CARD -->
            <div class="neo-card p-8 flex flex-col animate-slide-in stagger-1" style="box-shadow: 8px 8px 0px var(--neo-black);">
                <div class="mb-8">
                    <span class="neo-border px-3 py-1 font-black text-xl inline-block" style="background: var(--neo-green); box-shadow: 2px 2px 0px var(--neo-black); font-family: 'Space Mono', monospace;">JOIN</span>
                    <h2 class="text-3xl md:text-4xl font-black uppercase mt-4 mb-2" style="font-family: 'Space Mono', monospace;">Masuk Room</h2>
                    <p class="font-bold text-gray-600">Punya kode room dari teman? Masukkan di sini.</p>
                </div>

                <form action="{{ route('rooms.join') }}" method="POST" class="flex-grow flex flex-col justify-between">
                    @csrf
                    <div class="mb-8">
                        <label class="block font-black uppercase text-base mb-2" style="font-family: 'Space Mono', monospace; letter-spacing: 1px;">Kode Room (6 Digit)</label>
                        <input type="text" name="code" required maxlength="6"
                               class="w-full neo-border p-4 text-3xl font-black tracking-widest text-center uppercase focus:outline-none transition-colors"
                               style="font-family: 'Space Mono', monospace; background: #f9f9f4; box-shadow: inset 3px 3px 0px rgba(0,0,0,0.1);"
                               onfocus="this.style.background='var(--neo-yellow)'"
                               onblur="this.style.background='#f9f9f4'"
                               placeholder="XXXXXX">
                    </div>

                    <button type="submit" class="neo-btn w-full text-xl uppercase tracking-wider" style="background: var(--neo-green); padding: 16px 24px; font-family: 'Space Mono', monospace; box-shadow: var(--neo-shadow);">
                        Join Sekarang ➡
                    </button>
                </form>
            </div>

            <!-- CREATE ROOM CARD -->
            <div class="neo-border p-8 flex flex-col animate-slide-in stagger-2" style="background: var(--neo-black); color: white; box-shadow: 8px 8px 0px var(--neo-yellow);">
                <div class="mb-6">
                    <span class="neo-border px-3 py-1 font-black text-xl inline-block" style="background: var(--neo-yellow); color: var(--neo-black); box-shadow: 2px 2px 0px white; font-family: 'Space Mono', monospace;">CREATE</span>
                    <h2 class="text-3xl md:text-4xl font-black uppercase mt-4 mb-2" style="font-family: 'Space Mono', monospace; color: var(--neo-yellow);">Buat Room</h2>
                    <p class="font-bold" style="color: #aaa;">Pilih mode dan ajak temanmu!</p>
                </div>

                <form action="{{ route('rooms.store') }}" method="POST" class="flex-grow flex flex-col justify-between">
                    @csrf

                    <!-- MAX PLAYERS INPUT -->
                    <div class="mb-6">
                        <label class="block font-black uppercase text-base mb-2" style="font-family: 'Space Mono', monospace; letter-spacing: 1px; color: var(--neo-green);">Maksimal Pemain</label>
                        <input type="number" name="max_players" value="10" min="2" max="50" required
                               class="w-full neo-border p-4 text-xl font-bold focus:outline-none transition-colors"
                               style="background: #333; color: white; border-color: white;"
                               onfocus="this.style.borderColor='var(--neo-yellow)'"
                               onblur="this.style.borderColor='white'">
                    </div>

                    <!-- GAME SELECTOR -->
                    <div class="mb-6">
                        <label class="block font-black uppercase text-base mb-2" style="font-family: 'Space Mono', monospace; letter-spacing: 1px; color: var(--neo-green);">Pilih Game</label>
                        <div class="relative">
                            <select name="game_id" required class="appearance-none w-full neo-border p-4 font-bold text-xl cursor-pointer focus:outline-none transition-colors" style="background: #333; color: white; border-color: white;" onfocus="this.style.borderColor='var(--neo-yellow)'" onblur="this.style.borderColor='white'">
                                @foreach($games as $game)
                                    <option value="{{ $game->id }}">{{ $game->title }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-white">
                                <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="neo-btn w-full text-xl uppercase tracking-wider" style="background: var(--neo-yellow); color: var(--neo-black); padding: 16px 24px; font-family: 'Space Mono', monospace; border-color: white; box-shadow: 6px 6px 0px white;">
                        ➕ Buat Room
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
