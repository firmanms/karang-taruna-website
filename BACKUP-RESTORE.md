# 🛡️ Panduan Backup, Restore & Disaster Recovery
## Karang Taruna Kabupaten Bandung

---

## 1. Prosedur Backup Otomatis Database MySQL

Buat script backup di server: `/var/www/scripts/db-backup.sh`:

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/karangtaruna"
DATE=$(date +"%Y-%m-%d_%H%M%S")
DB_NAME="karang_taruna_prod"
DB_USER="kt_db_user"
DB_PASS="PASSWORD_ANDA"

mkdir -p $BACKUP_DIR

# Dump database dengan kompresi gzip
mysqldump -u $DB_USER -p$DB_PASS --single-transaction --quick --lock-tables=false $DB_NAME | gzip > "$BACKUP_DIR/db_backup_$DATE.sql.gz"

# Hapus backup yang lebih lama dari 30 hari
find $BACKUP_DIR -type f -name "*.sql.gz" -mtime +30 -exec rm {} \;
```

Jadwalkan cron backup harian pada jam 02:00 pagi (`crontab -e`):
```bash
0 2 * * * /bin/bash /var/www/scripts/db-backup.sh >> /var/log/db_backup.log 2>&1
```

---

## 2. Prosedur Backup Berkas Storage Upload

```bash
tar -czf /var/backups/karangtaruna/storage_backup_$(date +"%Y-%m-%d").tar.gz /var/www/karangtaruna/storage/app/public
```

---

## 3. Prosedur Pemulihan Data (Restore)

### A. Restore Database
```bash
# 1. Unzip berkas backup
gunzip < /var/backups/karangtaruna/db_backup_2026-09-05_020000.sql.gz > restore.sql

# 2. Import ke database MySQL
mysql -u kt_db_user -p karang_taruna_prod < restore.sql

# 3. Bersihkan file SQL sementara
rm restore.sql
```

### B. Restore Berkas Storage
```bash
tar -xzf /var/backups/karangtaruna/storage_backup_2026-09-05.tar.gz -C /
php /var/www/karangtaruna/artisan storage:link
```

---

## 4. Troubleshooting Umum

| Masalah / Error | Kemungkinan Penyebab | Solusi Cepat |
| :--- | :--- | :--- |
| **500 Internal Server Error** | Cache lama atau permission storage | `php artisan optimize:clear` & `sudo chmod -R 775 storage bootstrap/cache` |
| **Gambar/File tidak tampil (404)**| Symlink storage terputus | `php artisan storage:link` |
| **Queue Notifikasi tidak terkirim**| Worker supervisor mati | `sudo supervisorctl restart kt-queue-worker:*` |
| **Koneksi Database Timeout** | MySQL service berhenti | `sudo systemctl restart mysql` |
