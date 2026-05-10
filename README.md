# CodeBite

## Deskripsi (Indonesian)
CodeBite adalah platform permainan edukatif interaktif yang dirancang khusus untuk siswa sekolah menengah agar belajar pemrograman menjadi lebih menyenangkan dan kompetitif. Dengan fitur permainan multipemain waktu nyata (real-time), pengguna dapat bersaing dalam berbagai tantangan coding seperti menyelesaikan teka-teki CSS, HTML, dan JavaScript. Dibangun menggunakan Laravel dan Reverb, CodeBite menawarkan pengalaman bermain yang mulus yang dilengkapi dengan papan peringkat, ruang kompetisi (rooms), dan pelacakan kemajuan pengguna, sehingga sangat ideal bagi pemula maupun pengembang yang ingin mengasah keterampilan mereka.

## Description (English)
CodeBite is an interactive educational gaming platform specifically designed for high school students to make learning programming more fun and competitive. With real-time multiplayer game features, users can compete in various coding challenges such as solving CSS, HTML, and JavaScript puzzles. Built with Laravel and Reverb, CodeBite offers a seamless gaming experience complete with leaderboards, competition rooms, and user progress tracking, making it highly ideal for both beginners and developers looking to hone their skills.

---

## Tech Stack
- **Framework**: Laravel 11
- **Database**: MySQL
- **CSS**: Tailwind CSS
- **JS**: Alpine.js
- **Real-time**: Laravel Reverb

## Setup Instructions

1. **Clone the repository**
2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```
3. **Configure Environment**:
   - Copy `.env.example` to `.env`
   - Configure database settings in `.env`
   - Generate app key: `php artisan key:generate`
4. **Database Setup**:
   - Create the database
   - Run migrations: `php artisan migrate`
5. **Run the application**:
   - Start Vite: `npm run dev`
   - Start Laravel server: `php artisan serve`
   - Start Reverb server: `php artisan reverb:start`
