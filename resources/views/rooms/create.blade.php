@extends('layouts.app')

@section('content')
<div class="bg-[#f3f4f6] min-h-screen pb-20 pt-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="text-6xl font-black uppercase tracking-tighter shadow-text-yellow mb-4" style="text-shadow: 4px 4px 0px #FFE500, 6px 6px 0px #000;">
                MULTIPLAYER
            </h1>
            <p class="text-xl font-bold bg-white inline-block px-6 py-2 border-4 border-black shadow-[4px_4px_0px_#000]">
                Main bareng teman-temanmu secara Real-Time! 🚀
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-400 border-4 border-black p-4 mb-8 font-bold text-white shadow-[6px_6px_0px_#000]">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- JOIN ROOM CARD -->
            <div class="bg-white border-4 border-black p-8 shadow-[12px_12px_0px_#000] flex flex-col">
                <div class="mb-8">
                    <span class="bg-[#00ff88] px-3 py-1 font-black text-xl border-2 border-black shadow-[2px_2px_0px_#000]">JOIN</span>
                    <h2 class="text-4xl font-black uppercase mt-4 mb-2">Masuk Room</h2>
                    <p class="font-bold text-gray-600">Punya kode room dari teman? Masukkan di sini.</p>
                </div>

                <form action="{{ route('rooms.join') }}" method="POST" class="flex-grow flex flex-col justify-between">
                    @csrf
                    <div class="mb-8">
                        <label class="block font-black uppercase text-lg mb-2">Kode Room (6 Digit)</label>
                        <input type="text" name="code" required maxlength="6" class="w-full bg-[#f3f4f6] border-4 border-black p-4 text-3xl font-black font-mono tracking-widest text-center uppercase focus:outline-none focus:bg-[#FFE500] transition-colors" placeholder="XXXXXX">
                    </div>
                    
                    <button type="submit" class="w-full bg-[#00ff88] border-4 border-black p-4 font-black text-2xl uppercase tracking-wider shadow-[6px_6px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-[0px_0px_0px_#000] transition-all flex justify-center items-center gap-3">
                        Join Sekarang ➡
                    </button>
                </form>
            </div>

            <!-- CREATE ROOM CARD -->
            <div class="bg-black text-white border-4 border-black p-8 shadow-[12px_12px_0px_#FFE500] flex flex-col">
                <div class="mb-8">
                    <span class="bg-[#FFE500] text-black px-3 py-1 font-black text-xl border-2 border-black shadow-[2px_2px_0px_#fff]">CREATE</span>
                    <h2 class="text-4xl font-black uppercase mt-4 mb-2 text-[#FFE500]">Buat Room</h2>
                    <p class="font-bold text-gray-300">Pilih game dan ajak temanmu balapan coding!</p>
                </div>

                <form action="{{ route('rooms.store') }}" method="POST" class="flex-grow flex flex-col justify-between">
                    @csrf
                    <div class="mb-8">
                        <label class="block font-black uppercase text-lg mb-2 text-[#00ff88]">Pilih Game</label>
                        <div class="relative">
                            <select name="game_id" required class="appearance-none w-full bg-gray-800 text-white border-4 border-white p-4 font-bold text-xl cursor-pointer focus:outline-none focus:border-[#FFE500]">
                                @foreach($games as $game)
                                    <option value="{{ $game->id }}">{{ $game->title }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-white">
                                <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#FFE500] text-black border-4 border-white p-4 font-black text-2xl uppercase tracking-wider shadow-[6px_6px_0px_#fff] hover:translate-x-1 hover:translate-y-1 hover:shadow-[0px_0px_0px_#000] transition-all flex justify-center items-center gap-3">
                        ➕ Buat Room
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
