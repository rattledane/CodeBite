#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

echo "Starting CodeBite deployment..."

# 1. Pull the latest code
echo "Pulling latest changes from git..."
git pull origin main

# 2. Install/update Composer dependencies (no dev packages, optimized autoloader)
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Build frontend assets
echo "Building frontend assets (npm)..."
npm ci
npm run build

# 4. Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# 5. Clear and rebuild caches for production
echo "Caching configuration, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart queue workers (if any)
echo "Restarting queue workers..."
php artisan queue:restart

# 7. Restart Reverb WebSocket server via Supervisor
echo "Restarting Reverb WebSocket server..."
sudo supervisorctl restart codebite-reverb

echo "CodeBite deployment completed successfully! 🚀"
