#!/bin/bash
set -e

# Sesuaikan port Apache dengan variabel lingkungan PORT dari Render (default 80 jika lokal)
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/" /etc/apache2/sites-available/000-default.conf

# Jalankan migrasi dan seeder database terkelola (PostgreSQL Supabase)
echo "Menjalankan migrasi database..."
php artisan migrate --force --seed

# Optimasi cache untuk performa produksi di cloud
echo "Mengoptimasi cache Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan Apache web server
echo "Memulai server Apache pada port ${PORT}..."
exec apache2-foreground

