# 🚀 Panduan Lengkap Production Deployment & Infrastructure
## Sistem Informasi & Website Resmi Karang Taruna Kabupaten Bandung

Dokumen ini berisi panduan teknis deployment ke server **Ubuntu Server (LTS)** dengan web server **Nginx**, **PHP 8.3 FPM**, database **MySQL/MariaDB**, **Redis Caching & Queue**, **Supervisor Queue Worker**, integrasi **S3/MinIO**, serta proteksi **Cloudflare & SSL**.

---

## 1. Spesifikasi Server & Stack Produksi

- **Operating System**: Ubuntu 22.04 / 24.04 LTS (x86_64)
- **Web Server**: Nginx (Engine X) dengan HTTP/2 & Gzip/Brotli Compression
- **PHP**: PHP 8.3-FPM (`php8.3-fpm`, `php8.3-cli`, `php8.3-mysql`, `php8.3-mbstring`, `php8.3-xml`, `php8.3-bcmath`, `php8.3-curl`, `php8.3-zip`, `php8.3-gd`, `php8.3-redis`, `php8.3-intl`, `php8.3-opcache`)
- **Database**: MySQL 8.0+ atau MariaDB 10.11+
- **In-Memory Cache & Queue**: Redis Server 7.x
- **Process Manager**: Supervisor (Daemon untuk `php artisan queue:work`)
- **SSL / CDN**: Cloudflare (Full Strict) / Let's Encrypt Certbot
- **Storage**: Local Storage (Symlink) atau AWS S3 / MinIO Object Storage

---

## 2. Checklist Konfigurasi Environment (`.env`)

Pastikan file `.env` di server produksi dikonfigurasi dengan mode aman:

```env
APP_NAME="Karang Taruna Kabupaten Bandung"
APP_ENV=production
APP_KEY=base64:GENERATE_DENGAN_ARTISAN_KEY_GENERATE
APP_DEBUG=false
APP_URL=https://karangtarunabandungkab.or.id

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=karang_taruna_prod
DB_USERNAME=kt_db_user
DB_PASSWORD=GANTI_DENGAN_PASSWORD_DATABASE_KUAT

SESSION_DRIVER=redis
CACHE_STORE=redis
QUEUE_CONNECTION=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

FILESYSTEM_DISK=public
# Atau untuk S3 / MinIO Object Storage:
# FILESYSTEM_DISK=s3
# AWS_ACCESS_KEY_ID=YOUR_S3_KEY
# AWS_SECRET_ACCESS_KEY=YOUR_S3_SECRET
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=karangtaruna-assets
# AWS_ENDPOINT=https://minio.yourdomain.com
# AWS_USE_PATH_STYLE_ENDPOINT=true
```

---

## 3. Konfigurasi Nginx Server Block (`/etc/nginx/sites-available/karangtaruna.conf`)

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name karangtarunabandungkab.or.id www.karangtarunabandungkab.or.id;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name karangtarunabandungkab.or.id www.karangtarunabandungkab.or.id;
    root /var/www/karangtaruna/public;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/karangtarunabandungkab.or.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/karangtarunabandungkab.or.id/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    index index.php index.html;
    charset utf-8;

    # Max upload size (sesuai kebutuhan dokumen 20MB)
    client_max_body_size 25M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static Asset Caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
    }
}
```

---

## 4. Konfigurasi Process Supervisor (`/etc/supervisor/conf.d/kt-worker.conf`)

Supervisor memastikan antrean notifikasi dan email berjalan tanpa henti:

```ini
[program:kt-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/karangtaruna/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/karangtaruna/storage/logs/queue-worker.log
stopwaitsecs=3600
```

Jalankan perintah untuk memuat supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start kt-queue-worker:*
```

---

## 5. Konfigurasi Laravel Task Scheduler (Cron Job)

Tambahkan perintah berikut ke crontab server (`crontab -e` sebagai `www-data` atau `root`):

```bash
* * * * * cd /var/www/karangtaruna && php artisan schedule:run >> /dev/null 2>&1
```

---

## 6. Hak Akses Folder & Izin (*Permissions*)

```bash
sudo chown -R www-data:www-data /var/www/karangtaruna
sudo find /var/www/karangtaruna -type f -exec chmod 644 {} \;
sudo find /var/www/karangtaruna -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/karangtaruna/storage /var/www/karangtaruna/bootstrap/cache
```

---

## 7. Prosedur Deployment Langkah Demi Langkah

1. **Pull Code Terbaru**:
   ```bash
   git pull origin main
   ```
2. **Install Composer Dependencies (No Dev)**:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. **Database Migration**:
   ```bash
   php artisan migrate --force
   ```
4. **Optimasi Cache Laravel**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```
5. **Storage Symlink**:
   ```bash
   php artisan storage:link
   ```
6. **Restart Queue Worker & PHP-FPM**:
   ```bash
   php artisan queue:restart
   sudo systemctl restart php8.3-fpm
   ```

---

## 8. Prosedur Rollback Cepat (Jika Terjadi Kendala)

Jika rilis mengalami masalah kritis:
```bash
# 1. Kembali ke commit/tag stabil sebelumnya
git reset --hard <PREVIOUS_STABLE_COMMIT_HASH>

# 2. Rollback migrasi jika diperlukan
php artisan migrate:rollback --step=1

# 3. Clear & rebuild cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Restart services
php artisan queue:restart
sudo systemctl restart php8.3-fpm
```
