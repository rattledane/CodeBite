<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $game = Game::create([
            'slug' => 'flexbox-froggy',
            'title' => 'Flexbox Froggy',
            'description' => 'Welcome to Flexbox Froggy, a game where you help Froggy and friends by writing CSS code!',
            'thumbnail' => '/images/games/flexbox-froggy.png',
        ]);

        $levels = [
            [
                'order' => 1,
                'instruction' => 'Gunakan justify-content untuk memindahkan katak ke kanan!',
                'initial_code' => "#pond {\n  display: flex;\n  /* tambahkan kodemu di bawah ini */\n  \n}",
                'answer_key' => 'justify-content: flex-end',
                'hint' => 'Coba gunakan justify-content dengan nilai yang memindahkan item ke akhir baris. Pilihan: flex-start, flex-end, center, space-between, space-around.',
            ],
            [
                'order' => 2,
                'instruction' => 'Pindahkan katak ke tengah!',
                'initial_code' => "#pond {\n  display: flex;\n  /* tambahkan kodemu di bawah ini */\n  \n}",
                'answer_key' => 'justify-content: center',
                'hint' => 'justify-content mengatur posisi horizontal. Untuk tengah, gunakan nilai "center".',
            ],
            [
                'order' => 3,
                'instruction' => 'Sebarkan katak secara merata!',
                'initial_code' => "#pond {\n  display: flex;\n  /* tambahkan kodemu di bawah ini */\n  \n}",
                'answer_key' => 'justify-content: space-around',
                'hint' => 'Ada dua cara menyebarkan elemen: space-between (tanpa ruang di tepi) dan space-around (dengan ruang di tepi). Yang mana sesuai?',
            ],
            [
                'order' => 4,
                'instruction' => 'Beri jarak antar katak!',
                'initial_code' => "#pond {\n  display: flex;\n  /* tambahkan kodemu di bawah ini */\n  \n}",
                'answer_key' => 'justify-content: space-between',
                'hint' => 'Gunakan justify-content dengan nilai yang membuat jarak merata di ANTARA elemen, bukan di tepi.',
            ],
            [
                'order' => 5,
                'instruction' => 'Gunakan align-items untuk memindahkan katak ke bawah!',
                'initial_code' => "#pond {\n  display: flex;\n  /* tambahkan kodemu di bawah ini */\n  \n}",
                'answer_key' => 'align-items: flex-end',
                'hint' => 'align-items mengatur posisi vertikal (cross axis). Untuk bawah, gunakan flex-end.',
            ]
        ];

        foreach ($levels as $level) {
            $game->levels()->create($level);
        }

        // GRID GARDEN
        $gridGarden = Game::create([
            'slug' => 'grid-garden',
            'title' => 'Grid Garden',
            'description' => 'Siram tanamanmu dengan CSS Grid! Pelajari dasar-dasar grid layout di kebun ini.',
            'thumbnail' => 'https://ui-avatars.com/api/?name=Grid&background=1E90FF&color=fff',
        ]);

        $gridLevels = [
            [
                'order' => 1,
                'instruction' => 'Gunakan grid-column-start untuk memindahkan air ke tanaman di kolom 5.',
                'initial_code' => "#water {\n  /* tambahkan css di bawah ini */\n  \n}",
                'answer_key' => 'grid-column-start: 5',
                'max_score' => 100,
                'hint' => 'grid-column-start menentukan di kolom berapa sebuah elemen dimulai. Gunakan angka kolom secara langsung.',
            ],
            [
                'order' => 2,
                'instruction' => 'Tanaman ada di akhir kebun. Gunakan grid-column-start dengan nilai negatif atau angka besar.',
                'initial_code' => "#water {\n  /* tambahkan css di bawah ini */\n  \n}",
                'answer_key' => 'grid-column-start: 3',
                'max_score' => 100,
                'hint' => 'Coba hitung posisi tanaman dari kiri. Kolom dimulai dari 1.',
            ],
            [
                'order' => 3,
                'instruction' => 'Beri area yang lebih luas untuk air menggunakan grid-column-end.',
                'initial_code' => "#water {\n  grid-column-start: 1;\n  /* tambahkan css di bawah ini */\n  \n}",
                'answer_key' => 'grid-column-end: 4',
                'max_score' => 100,
                'hint' => 'grid-column-end menentukan di mana elemen berakhir. Jika start=1, maka end=4 berarti elemen mencakup 3 kolom.',
            ],
            [
                'order' => 4,
                'instruction' => 'Gunakan grid-column untuk menyatukan start dan end (misal: 1 / 4).',
                'initial_code' => "#water {\n  /* tambahkan css di bawah ini */\n  \n}",
                'answer_key' => 'grid-column: 2 / 5',
                'max_score' => 100,
                'hint' => 'grid-column adalah shorthand. Formatnya: grid-column: start / end. Contoh: 1 / 4 artinya kolom 1 sampai 3.',
            ],
            [
                'order' => 5,
                'instruction' => 'Sekarang pindahkan air secara vertikal menggunakan grid-row-start!',
                'initial_code' => "#water {\n  /* tambahkan css di bawah ini */\n  \n}",
                'answer_key' => 'grid-row-start: 4',
                'max_score' => 100,
                'hint' => 'grid-row-start bekerja sama seperti grid-column-start, tapi untuk baris (vertikal). Hitung posisi baris dari atas.',
            ]
        ];

        foreach ($gridLevels as $level) {
            $gridGarden->levels()->create($level);
        }

        // CSS SELECTOR CHALLENGE
        $cssSelector = Game::create([
            'slug' => 'css-selector',
            'title' => 'CSS Selector',
            'description' => 'Pilih elemen yang tepat dengan CSS selector! Berlatihlah menggunakan class, id, dan pseudo-classes.',
            'thumbnail' => 'https://ui-avatars.com/api/?name=CSS&background=FF4500&color=fff',
        ]);

        $selectorLevels = [
            [
                'order' => 1,
                'instruction' => 'Pilih semua elemen <p>',
                'initial_code' => '',
                'answer_key' => 'p',
                'max_score' => 100,
                'hint' => 'Untuk memilih semua elemen berdasarkan tag, cukup tulis nama tag-nya saja tanpa tanda apapun.',
            ],
            [
                'order' => 2,
                'instruction' => 'Pilih elemen dengan class .highlight',
                'initial_code' => '',
                'answer_key' => '.highlight',
                'max_score' => 100,
                'hint' => 'Class selector menggunakan tanda titik (.) di depan nama class. Contoh: .namaclass',
            ],
            [
                'order' => 3,
                'instruction' => 'Pilih elemen paragraf pertama dengan :first-child',
                'initial_code' => '',
                'answer_key' => 'p:first-child',
                'max_score' => 100,
                'hint' => 'Gabungkan tag selector dengan pseudo-class. Format: tag:pseudo-class. Pseudo-class :first-child memilih anak pertama.',
            ],
            [
                'order' => 4,
                'instruction' => 'Pilih elemen paragraf di dalam <div> (descendant selector)',
                'initial_code' => '',
                'answer_key' => 'div p',
                'max_score' => 100,
                'hint' => 'Descendant selector menggunakan spasi antara parent dan child. Format: parent child.',
            ],
            [
                'order' => 5,
                'instruction' => 'Pilih elemen spesifik dengan id #target',
                'initial_code' => '',
                'answer_key' => '#target',
                'max_score' => 100,
                'hint' => 'ID selector menggunakan tanda pagar (#) di depan nama id. Contoh: #namaid',
            ]
        ];

        foreach ($selectorLevels as $level) {
            $cssSelector->levels()->create($level);
        }

        // HTML TAG BUILDER
        $htmlBuilder = Game::create([
            'slug' => 'html-tag-builder',
            'title' => 'HTML Tag Builder',
            'description' => 'Susun kerangka web idamanmu! Pelajari cara membuat tag HTML dasar dengan benar.',
            'thumbnail' => 'https://ui-avatars.com/api/?name=HTML&background=E34F26&color=fff',
        ]);

        $htmlLevels = [
            [
                'order' => 1,
                'instruction' => 'Buat heading level 1 (h1) dengan teks "Hello World"',
                'initial_code' => '',
                'answer_key' => '<h1>Hello World</h1>',
                'max_score' => 100,
                'hint' => 'Tag heading h1 memiliki format: <h1>teks</h1>. Jangan lupa closing tag!',
            ],
            [
                'order' => 2,
                'instruction' => 'Buat sebuah link ke "https://google.com"',
                'initial_code' => '',
                'answer_key' => '<a href="https://google.com">Google</a>',
                'max_score' => 100,
                'hint' => 'Tag link menggunakan <a> dengan atribut href. Format: <a href="url">teks link</a>',
            ],
            [
                'order' => 3,
                'instruction' => 'Buat ordered list (ol) yang berisi tepat 3 item (li)',
                'initial_code' => '',
                'answer_key' => "<ol>\n  <li>Satu</li>\n  <li>Dua</li>\n  <li>Tiga</li>\n</ol>",
                'max_score' => 100,
                'hint' => 'Ordered list menggunakan <ol> sebagai container dan <li> untuk setiap item. Pastikan ada 3 <li> di dalam <ol>.',
            ]
        ];

        foreach ($htmlLevels as $level) {
            $htmlBuilder->levels()->create($level);
        }

        // JS VARIABLE QUEST
        $jsQuest = Game::create([
            'slug' => 'js-variable-quest',
            'title' => 'JS Variable Quest',
            'description' => 'Mulai petualangan JavaScript-mu! Pelajari variabel, tipe data, dan fungsi dasar.',
            'thumbnail' => 'https://ui-avatars.com/api/?name=JS&background=F7DF1E&color=000',
        ]);

        $jsLevels = [
            [
                'order' => 1,
                'instruction' => "Deklarasikan variabel dengan let bernama 'nama' dengan nilai 'CodeBite'",
                'initial_code' => '',
                'answer_key' => "let nama = 'CodeBite'",
                'max_score' => 100,
                'hint' => "Gunakan keyword 'let' diikuti nama variabel, tanda sama dengan, lalu nilainya dalam tanda kutip. Format: let namaVar = 'nilai'",
            ],
            [
                'order' => 2,
                'instruction' => 'Buat fungsi bernama add() yang mengembalikan hasil 2 + 2',
                'initial_code' => '',
                'answer_key' => 'function add() { return 2 + 2; }',
                'max_score' => 100,
                'hint' => 'Deklarasi fungsi: function namaFungsi() { return ekspresi; }. Gunakan keyword return untuk mengembalikan hasil.',
            ]
        ];

        foreach ($jsLevels as $level) {
            $jsQuest->levels()->create($level);
        }
    }
}
