# CodeBite

Educational coding game platform for high school students.

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
