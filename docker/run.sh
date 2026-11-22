#!/bin/bash
set -e

# Sesuaikan port Apache dengan variabel lingkungan PORT dari Render (default 80 jika lokal)
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/" /etc/apache2/sites-available/000-default.conf

# Pastikan izin direktori storage dan log valid untuk www-data
mkdir -p /var/www/html/storage/logs /var/www/html/storage/framework/views /var/www/html/storage/framework/sessions /var/www/html/storage/framework/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 666 /var/www/html/storage/logs/laravel.log

# Jalankan migrasi dan seeder database terkelola (PostgreSQL Supabase)
echo "Menjalankan migrasi database..."
php artisan migrate --force --seed || {
    echo "=========================================================="
    echo "PERINGATAN: Migrasi database gagal atau koneksi bermasalah."
    echo "Server Apache tetap dijalankan agar web service tidak crash."
    echo "=========================================================="
}

# Optimasi cache untuk performa produksi di cloud
echo "Mengoptimasi cache Laravel..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Jalankan Apache web server
echo "Memulai server Apache pada port ${PORT}..."
exec apache2-foreground

