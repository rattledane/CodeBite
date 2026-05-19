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
        $game = Game::updateOrCreate(
            ['slug' => 'flexbox-froggy'],
            [
                'title' => 'Flexbox Froggy',
                'description' => 'Welcome to Flexbox Froggy, a game where you help Froggy and friends by writing CSS code!',
                'thumbnail' => '/images/games/flexbox-froggy.png',
            ]
        );

        $game->levels()->delete();

        $levels = [
            ['order' => 1, 'instruction' => 'Gunakan justify-content untuk memindahkan katak ke sebelah kanan!', 'initial_code' => '', 'answer_key' => 'justify-content: flex-end', 'hint' => 'justify-content mengatur posisi horizontal. Pilihan: flex-start, flex-end, center, space-between, space-around.', 'max_score' => 100],
            ['order' => 2, 'instruction' => 'Bantu dua katak ini mencapai daun teratai mereka! Gunakan justify-content.', 'initial_code' => '', 'answer_key' => 'justify-content: center', 'hint' => 'Untuk memposisikan ke tengah horizontal, gunakan nilai "center".', 'max_score' => 100],
            ['order' => 3, 'instruction' => 'Sebarkan ketiga katak secara merata dengan ruang di setiap sisi!', 'initial_code' => '', 'answer_key' => 'justify-content: space-around', 'hint' => 'space-around memberi ruang yang sama di kiri dan kanan setiap elemen.', 'max_score' => 100],
            ['order' => 4, 'instruction' => 'Daun teratai di tepi sudah menempel ke pinggir. Beri jarak merata antar katak!', 'initial_code' => '', 'answer_key' => 'justify-content: space-between', 'hint' => 'space-between menempatkan elemen pertama di awal dan terakhir di akhir.', 'max_score' => 100],
            ['order' => 5, 'instruction' => 'Gunakan align-items untuk memindahkan katak-katak ke bawah kolam!', 'initial_code' => '', 'answer_key' => 'align-items: flex-end', 'hint' => 'align-items mengatur posisi vertikal. flex-end = bawah.', 'max_score' => 100],
            ['order' => 6, 'instruction' => 'Pindahkan katak ke tepat di tengah-tengah kolam! Butuh DUA properti.', 'initial_code' => '', 'answer_key' => 'justify-content: center; align-items: center', 'hint' => 'Gunakan justify-content: center dan align-items: center.', 'max_score' => 100],
            ['order' => 7, 'instruction' => 'Katak-katak ingin berkumpul di bawah kolam tapi tetap berjarak! Butuh DUA properti.', 'initial_code' => '', 'answer_key' => 'justify-content: space-around; align-items: flex-end', 'hint' => 'Gabungkan align-items: flex-end dengan justify-content: space-around.', 'max_score' => 100],
            ['order' => 8, 'instruction' => 'Balikkan urutan katak-katak! Gunakan flex-direction.', 'initial_code' => '', 'answer_key' => 'flex-direction: row-reverse', 'hint' => 'flex-direction: row-reverse membalik urutan horizontal.', 'max_score' => 100],
            ['order' => 9, 'instruction' => 'Susun katak-katak dari atas ke bawah!', 'initial_code' => '', 'answer_key' => 'flex-direction: column', 'hint' => 'flex-direction: column mengubah sumbu utama menjadi vertikal.', 'max_score' => 100],
            ['order' => 10, 'instruction' => 'Balikkan urutan katak, lalu pindahkan ke sisi kiri kolam!', 'initial_code' => '', 'answer_key' => 'flex-direction: row-reverse; justify-content: flex-end', 'hint' => 'Dengan row-reverse, flex-end justru memindahkan ke KIRI.', 'max_score' => 100],
            ['order' => 11, 'instruction' => 'Susun katak secara vertikal di bagian bawah kolam!', 'initial_code' => '', 'answer_key' => 'flex-direction: column; justify-content: flex-end', 'hint' => 'Dengan column, justify-content mengatur sumbu vertikal.', 'max_score' => 100],
            ['order' => 12, 'instruction' => 'Susun katak dari bawah ke atas dengan jarak merata!', 'initial_code' => '', 'answer_key' => 'flex-direction: column-reverse; justify-content: space-between', 'hint' => 'column-reverse membalik vertikal, space-between memberi jarak merata.', 'max_score' => 100],
            ['order' => 13, 'instruction' => 'Balikkan urutan katak, pusatkan horizontal, dan taruh di bawah! Butuh TIGA properti.', 'initial_code' => '', 'answer_key' => 'flex-direction: row-reverse; justify-content: center; align-items: flex-end', 'hint' => 'Gabungkan flex-direction: row-reverse, justify-content: center, dan align-items: flex-end.', 'max_score' => 100],
            ['order' => 14, 'instruction' => 'Pindahkan katak KUNING ke posisi paling akhir! Tulis CSS untuk .yellow saja.', 'initial_code' => '', 'answer_key' => 'order: 2', 'hint' => 'Property "order" mengatur urutan. Nilai lebih besar = posisi lebih akhir. Default: 0.', 'max_score' => 100],
            ['order' => 15, 'instruction' => 'Pindahkan katak MERAH ke posisi paling awal! Tulis CSS untuk .red saja.', 'initial_code' => '', 'answer_key' => 'order: -1', 'hint' => 'Gunakan nilai negatif untuk order agar muncul sebelum default (0).', 'max_score' => 100],
            ['order' => 16, 'instruction' => 'Pindahkan HANYA katak kuning ke bawah! Tulis CSS untuk .yellow saja.', 'initial_code' => '', 'answer_key' => 'align-self: flex-end', 'hint' => 'align-self bekerja seperti align-items tapi hanya untuk SATU elemen.', 'max_score' => 100],
            ['order' => 17, 'instruction' => 'Pindahkan katak kuning ke akhir DAN ke bawah! Butuh DUA properti untuk .yellow.', 'initial_code' => '', 'answer_key' => 'align-self: flex-end; order: 2', 'hint' => 'Gabungkan order (urutan) dan align-self (posisi vertikal).', 'max_score' => 100],
            ['order' => 18, 'instruction' => 'Kolam terlalu sempit! Bungkus katak ke baris berikutnya dengan flex-wrap.', 'initial_code' => '', 'answer_key' => 'flex-wrap: wrap', 'hint' => 'flex-wrap: wrap membuat elemen yang tidak muat pindah ke baris baru.', 'max_score' => 100],
            ['order' => 19, 'instruction' => 'Susun katak secara vertikal dan bungkus ke kolom berikutnya!', 'initial_code' => '', 'answer_key' => 'flex-direction: column; flex-wrap: wrap', 'hint' => 'Gabungkan flex-direction: column dengan flex-wrap: wrap.', 'max_score' => 100],
            ['order' => 20, 'instruction' => 'Sama seperti sebelumnya, tapi gunakan flex-flow (shorthand)!', 'initial_code' => '', 'answer_key' => 'flex-flow: column wrap', 'hint' => 'flex-flow adalah shorthand: flex-flow: direction wrap.', 'max_score' => 100],
            ['order' => 21, 'instruction' => 'Katak sudah terbungkus. Pindahkan semua baris ke atas! Gunakan align-content.', 'initial_code' => '', 'answer_key' => 'align-content: flex-start', 'hint' => 'align-content mengatur jarak antar baris. flex-start = ke atas.', 'max_score' => 100],
            ['order' => 22, 'instruction' => 'Pindahkan semua baris katak ke bawah!', 'initial_code' => '', 'answer_key' => 'align-content: flex-end', 'hint' => 'align-content: flex-end memindahkan baris ke bawah container.', 'max_score' => 100],
            ['order' => 23, 'instruction' => 'Balikkan kolom dan pusatkan konten! Butuh DUA properti.', 'initial_code' => '', 'answer_key' => 'flex-direction: column-reverse; align-content: center', 'hint' => 'Gabungkan flex-direction: column-reverse dengan align-content: center.', 'max_score' => 100],
            ['order' => 24, 'instruction' => '🏆 TANTANGAN AKHIR! Balikkan kolom, bungkus terbalik, pusatkan horizontal, dan beri jarak merata antar kolom!', 'initial_code' => '', 'answer_key' => 'flex-direction: column-reverse; flex-wrap: wrap-reverse; justify-content: center; align-content: space-between', 'hint' => 'Gabungkan: column-reverse, wrap-reverse, justify-content: center, align-content: space-between.', 'max_score' => 200],
        ];

        foreach ($levels as $level) {
            $game->levels()->create($level);
        }

        // GRID GARDEN
        $gridGarden = Game::updateOrCreate(
            ['slug' => 'grid-garden'],
            [
                'title' => 'Grid Garden',
                'description' => 'Siram tanamanmu dengan CSS Grid! Pelajari dasar-dasar grid layout di kebun ini.',
                'thumbnail' => 'https://ui-avatars.com/api/?name=Grid&background=1E90FF&color=fff',
            ]
        );

        $gridGarden->levels()->delete();

        $gridLevels = [
            ['order' => 1, 'instruction' => 'Selamat datang di Grid Garden, dimana kamu menulis kode CSS untuk menumbuhkan kebun wortel anda! Siram hanya area yang memiliki wortel dengan menggunakan properti grid-column-start.Sebagai contoh, grid-column-start: 3; akan menyiram area dimulai dari garis vertikal grid ke-3, yang merupakan cara lain untuk mengatakan batasan vertikal ke-3 dari kiri di grid.', 'initial_code' => '', 'answer_key' => 'grid-column-start: 3', 'hint' => 'Grid Garden Level 1', 'max_score' => 100],
            ['order' => 2, 'instruction' => 'Uh oh, sepertinya rumput liar tumbuh di sudut kebunmu. Gunakan grid-column-start untuk meracuninya. Perhatikan bahwa rumput liarnya bermula di garis vertikal grid ke-5.', 'initial_code' => '', 'answer_key' => 'grid-column-start: 5', 'hint' => 'Grid Garden Level 2', 'max_score' => 100],
            ['order' => 3, 'instruction' => 'Saat grid-column-start digunakan sendiri, secara default item grid akan menjangkau secara tepat satu kolom. Namun, kamu dapat memperluas item di beberapa kolom dengan menambahkan properti grid-column-end.Menggunakan grid-column-end, siram semua wortelmu sambil menghindari tanah. Kita tidak ingin membuang-buang air! Perhatikan bahwa wortelnya bermula di garis vertikal grid ke-1 dan berakhir di garis ke-4.', 'initial_code' => '', 'answer_key' => 'grid-column-end: 4', 'hint' => 'Grid Garden Level 3', 'max_score' => 100],
            ['order' => 4, 'instruction' => 'Saat memasangkan grid-column-start dan grid-column-end, kamu mungkin berasumsi bahwa nilai akhir lebih besar dari nilai awal. Tapi ternyata tidak demikian!Coba mengatur grid-column-end ke nilai yang kurang dari 5 untuk menyiram wortelmu.', 'initial_code' => '', 'answer_key' => 'grid-column-end: 2', 'hint' => 'Grid Garden Level 4', 'max_score' => 100],
            ['order' => 5, 'instruction' => 'Jika kamu ingin menghitung garis grid dari kanan bukannya dari kiri, kamu bisa memberi grid-column-start dan grid-column-end nilai negatif. Sebagai contoh, kamu bisa memasangnya menjadi -1 untuk menentukan garis grid pertama dari kanan.Coba atur grid-column-end ke nilai negatif.', 'initial_code' => '', 'answer_key' => 'grid-column-end: -2', 'hint' => 'Grid Garden Level 5', 'max_score' => 100],
            ['order' => 6, 'instruction' => 'Sekara coba atur grid-column-start ke nilai negatif.', 'initial_code' => '', 'answer_key' => 'grid-column-start: -3', 'hint' => 'Grid Garden Level 6', 'max_score' => 100],
            ['order' => 7, 'instruction' => 'Alih-alih mendefinisikan item grid berdasarkan posisi awal dan akhir garis grid, kamu bisa mendefinisikannya berdasarkan lebar kolom yang diinginkan dengan menggunakan kata kunci span. Ingatlah bahwa span hanya bekerja dengan nilai positif.Sebagai contoh, siram wortel-wortel ini dengan aturan grid-column-end: span 2;.', 'initial_code' => '', 'answer_key' => 'grid-column-end: span 2', 'hint' => 'Grid Garden Level 7', 'max_score' => 100],
            ['order' => 8, 'instruction' => 'Coba gunakan grid-column-end dengan kata kunci span lagi untuk menyiram wortelmu.', 'initial_code' => '', 'answer_key' => 'grid-column-end: span 5', 'hint' => 'Grid Garden Level 8', 'max_score' => 100],
            ['order' => 9, 'instruction' => 'Kamu juga bisa menggunakan kata kunci span dengan grid-column-start untuk mengatur lebar item relatif terhadap posisi akhir.', 'initial_code' => '', 'answer_key' => 'grid-column-start: span 3', 'hint' => 'Grid Garden Level 9', 'max_score' => 100],
            ['order' => 10, 'instruction' => 'Mengetik kedua grid-column-start dan grid-column-end setiap saat bisa jadi sangat melelahkan. Untungnya, grid-column adalah properti singkatan yang dapat menerima kedua nilai sekaligus, dipisahkan dengan slash (garis miring).Sebagai contoh, grid-column: 2 / 4; akan mengatur item grid untuk memulai pada garis grid vertikal ke-2 dan berakhir pada garis grid ke-4.', 'initial_code' => '', 'answer_key' => 'grid-column: 4 / 6', 'hint' => 'Grid Garden Level 10', 'max_score' => 100],
            ['order' => 11, 'instruction' => 'Coba gunakan grid-column untuk menyiram wortel-wortel ini. Kata kunci span juga berfungsi dengan properti singkatan ini, jadi cobalah!', 'initial_code' => '', 'answer_key' => 'grid-column: 2 / 5', 'hint' => 'Grid Garden Level 11', 'max_score' => 100],
            ['order' => 12, 'instruction' => 'Salah satu hal yang membedakan grid CSS dari flexbox adalah kamu dapat dengan mudah mengatur letak item di dua dimensi: kolom dan baris. grid-row-start bekerja layaknya grid-column-start kecuali sepanjang sumbu vertikal.Gunakan grid-row-start untuk menyiram wortel-wortel ini.', 'initial_code' => '', 'answer_key' => 'grid-row-start: 3', 'hint' => 'Grid Garden Level 12', 'max_score' => 100],
            ['order' => 13, 'instruction' => 'Sekarang coba berikan properti singkatan grid-row.', 'initial_code' => '', 'answer_key' => 'grid-row: 3 / 6', 'hint' => 'Grid Garden Level 13', 'max_score' => 100],
            ['order' => 14, 'instruction' => 'Gunakan grid-column dan grid-row di waktu yang bersamaan untuk mengatur letak di kedua dimensi.', 'initial_code' => '', 'answer_key' => 'grid-column: 2; grid-row: 5', 'hint' => 'Grid Garden Level 14', 'max_score' => 100],
            ['order' => 15, 'instruction' => 'Kamu juga bisa menggunakan grid-column dan grid-row secara bersama-sama untuk menjangkau area yang lebih luas di dalam grid. Cobalah!', 'initial_code' => '', 'answer_key' => 'grid-column: 2 / 6; grid-row: 1 / 6', 'hint' => 'Grid Garden Level 15', 'max_score' => 100],
            ['order' => 16, 'instruction' => 'Jika kamu mengetik kedua grid-column dan grid-row terlalu berlebihan untukmu, masih ada singkatan lain untuk itu. grid-area menerima empat nilai yang dipisahkan dengan slash: grid-row-start, grid-column-start, grid-row-end, diikuti oleh grid-column-end.Salah satu contohnya adalah grid-area: 1 / 1 / 3 / 6;.', 'initial_code' => '', 'answer_key' => 'grid-area: 1 / 2 / 4 / 6', 'hint' => 'Grid Garden Level 16', 'max_score' => 100],
            ['order' => 17, 'instruction' => 'Bagaiman dengan item yang banyak? Kamu dapat menimpannya tanpa masalah. Gunakan grid-area untuk menentukan area kedua yang mencakup semua wortel yang tidak disiram.', 'initial_code' => '', 'answer_key' => 'grid-area: 2 / 3 / 5 / 6', 'hint' => 'Grid Garden Level 17', 'max_score' => 100],
            ['order' => 18, 'instruction' => 'Jika item grid tidak ditempatkan secara eksplisit dengan grid-area, grid-column, grid-row, dll., mereka secara otomatis ditempatkan sesuai dengan urutannya di source code. Kita dapat menimpa ini menggunakan properti order, yang merupakan salah satu keuntungan dari grid daripada tata letak berbasis tabel.Secara default, semua item grid mempunyai order 0, tetapi ini dapat diatur ke nilai positif atau negatif, mirip dengan z-index.Saat ini, wortel di kolom kedua diracuni dan rumput liar di kolom terakhir disiram. Ubah nilai order dari racun untuk segera memperbaiki hal ini!', 'initial_code' => '', 'answer_key' => 'order: 2', 'hint' => 'Grid Garden Level 18', 'max_score' => 100],
            ['order' => 19, 'instruction' => 'Sekarang air dan racun itu bergantian, meskipun semua rumput liar ada di awal kebunmu. Atur order dari racun untuk memperbaiki hal ini.', 'initial_code' => '', 'answer_key' => 'order: -1', 'hint' => 'Grid Garden Level 19', 'max_score' => 100],
            ['order' => 20, 'instruction' => 'Sampai saat ini, kamu telah menyiapkan taman sebagai grid dengan lima kolom, masing-masing 20% dari lebar penuh, dan lima baris, masing-masing 20% dari tinggi penuh.Ini dilakukan dengan aturan grid-template-columns: 20% 20% 20% 20% 20%; dan grid-template-rows: 20% 20% 20% 20% 20%; Setiap aturan memiliki lima nilai yang membuat lima kolom, masing-masing diatur ke 20% dari keseluruhan lebar taman.Tetapi kamu dapat mengatur grid sesukamu. Berikan grid-template-columns nilai yang baru untuk menyiram wortelmu. Kamu ingin mengatur lebar kolom pertama menjadi 50%.', 'initial_code' => '', 'answer_key' => 'grid-template-columns: 50% 50%', 'hint' => 'Grid Garden Level 20', 'max_score' => 100],
            ['order' => 21, 'instruction' => 'Menentukan sekelompok kolom dengan lebar yang sama bisa menjadi membosankan. Untung ada fungsi repeat untuk membantumu.Sebagai contoh, kita sebelumnya mendefinisikan lima kolom 20% dengan aturan grid-template-columns: 20% 20% 20% 20% 20%;. Hal ini bisa lebih diringkas menjadi grid-template-columns: repeat(5, 20%);Menggunakan grid-template-columns dengan fungsi repeat, buat delapan kolom masing-masing dengan lebar 12,5%. Dengan cara ini kamu tidak akan menyirami tamanmu secara berlebihan.', 'initial_code' => '', 'answer_key' => 'grid-template-columns: repeat(8, 12.5%)', 'hint' => 'Grid Garden Level 21', 'max_score' => 100],
            ['order' => 22, 'instruction' => 'grid-template-columns tidak hanya menerima nilai persen, tetapi juga satuan panjang seperti pixels dan ems. Kamu bahkan dapat mencampur unit yang berbeda bersama-sama.Disini, atur tiga kolom menjadi 100px, 3em, dan 40% masing-masing.', 'initial_code' => '', 'answer_key' => 'grid-template-columns: 100px 3em 40%;', 'hint' => 'Grid Garden Level 22', 'max_score' => 100],
            ['order' => 23, 'instruction' => 'Grid juga memperkenalkan unit baru, pecahan fr. Setiap unit fr mengalokasikan satu bagian dari ruang yang tersedia. Sebagai contoh, jika dua elemen diatur ke 1fr dan 3fr masing-masing, ruang dibagi menjadi 4 bagian yang sama; elemen pertama menempati 1/4 dan elemen kedua 3/4 dari sisa ruang.Di sini, rumput liar terdiri dari 1/6 kiri dari baris pertama kamu dan wortel 5/6 sisanya. Buat dua kolom dengan lebar ini menggunakan satuan fr.', 'initial_code' => '', 'answer_key' => 'grid-template-columns: 1fr 5fr;', 'hint' => 'Grid Garden Level 23', 'max_score' => 100],
            ['order' => 24, 'instruction' => 'Saat kolom disetel dengan pixel, persentase, atau em, kolom lainnya disetel dengan fr akan membagi ruang yang tersisa.Di sini wortel membentuk kolom 50 piksel di sebelah kiri, dan rumput liar membentuk kolom 50 piksel di sebelah kanan. Dengan grid-template-columns, buat dua kolom ini, dan gunakan fr untuk membuat tiga kolom lagi yang mengambil ruang yang tersisa di antaranya.', 'initial_code' => '', 'answer_key' => 'grid-template-columns: 50px 1fr 1fr 1fr 50px;', 'hint' => 'Grid Garden Level 24', 'max_score' => 100],
            ['order' => 25, 'instruction' => 'Sekarang ada kolom rumput liar 75 pixel di sisi kiri kebunmu. 3/5 dari ruang yang tersisa ditanami wortel, sementara 2/5 telah ditumbuhi rumput liar.Gunakan grid-template-columns dengan kombinasi px dan satuan fr untuk membuat kolom yang diperlukan.', 'initial_code' => '', 'answer_key' => 'grid-template-columns: 75px 3fr 2fr;', 'hint' => 'Grid Garden Level 25', 'max_score' => 100],
            ['order' => 26, 'instruction' => 'grid-template-rows bekerja layaknya grid-template-columns.Gunakan grid-template-rows untuk menyirami semua kecuali 50 pixel teratas tamanmu. Perhatikan bahwa air diatur hanya untuk mengisi baris ke-5 Anda, jadi kamu harus membuat total 5 baris.', 'initial_code' => '', 'answer_key' => 'grid-template-rows: 1fr 100px;', 'hint' => 'Grid Garden Level 26', 'max_score' => 100],
            ['order' => 27, 'instruction' => 'grid-template adalah properti singkatan yang menggabungkan grid-template-rows dan grid-template-columns.Sebagai contoh, grid-template: 50% 50% / 200px; akan membuat grid dengan dua baris yang masing-masing 50%, dan satu kolom dengan lebar 200 piksel.Coba gunakan grid-template untuk menyirami area yang mencakup 60% teratas dan menyisakan 200 piksel tamanmu.', 'initial_code' => '', 'answer_key' => 'grid-template: 60% 1fr / 200px 1fr', 'hint' => 'Grid Garden Level 27', 'max_score' => 100],
            ['order' => 28, 'instruction' => 'Tamanmu terlihat bagus. Di sini kamu telah menyisakan jalur 50 pixel di bagian bawah tamanmu dan mengisi sisanya dengan wortel.Sayangnya, 20% wortelmu yang tersisa telah ditempati rumput liar. Gunakan grid CSS untuk terakhir kalinya untuk merawat tamanmu.', 'initial_code' => '', 'answer_key' => 'grid-template: 1fr 50px / 20% 1fr', 'hint' => 'Grid Garden Level 28', 'max_score' => 200],
        ];

        foreach ($gridLevels as $level) {
            $gridGarden->levels()->create($level);
        }

        // CSS SELECTOR CHALLENGE
        $cssSelector = Game::updateOrCreate(
            ['slug' => 'css-selector'],
            [
                'title' => 'CSS Selector',
                'description' => 'Pilih elemen yang tepat dengan CSS selector! Berlatihlah menggunakan class, id, dan pseudo-classes.',
                'thumbnail' => 'https://ui-avatars.com/api/?name=CSS&background=FF4500&color=fff',
            ]
        );

        $cssSelector->levels()->delete();

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
        $htmlBuilder = Game::updateOrCreate(
            ['slug' => 'html-tag-builder'],
            [
                'title' => 'HTML Tag Builder',
                'description' => 'Susun kerangka web idamanmu! Pelajari cara membuat tag HTML dasar dengan benar.',
                'thumbnail' => 'https://ui-avatars.com/api/?name=HTML&background=E34F26&color=fff',
            ]
        );

        $htmlBuilder->levels()->delete();

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
        $jsQuest = Game::updateOrCreate(
            ['slug' => 'js-variable-quest'],
            [
                'title' => 'JS Variable Quest',
                'description' => 'Mulai petualangan JavaScript-mu! Pelajari variabel, tipe data, dan fungsi dasar.',
                'thumbnail' => 'https://ui-avatars.com/api/?name=JS&background=F7DF1E&color=000',
            ]
        );

        $jsQuest->levels()->delete();

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
