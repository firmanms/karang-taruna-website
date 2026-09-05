# 🏛️ Website Resmi & Sistem Informasi Karang Taruna Kabupaten Bandung

Portal Resmi dan Sistem Informasi Manajemen Terpadu Pengurus Karang Taruna Kabupaten Bandung, menjangkau **31 Karang Taruna Kecamatan** dan **280 Karang Taruna Desa/Kelurahan**.

---

## 🌟 Fitur Utama Sistem

1. **Website Publik Modern & Responsif**: Menggunakan Blade, Vanilla CSS kustom dari template `desainforntend/`, ApexCharts, dan Leaflet GIS.
2. **Sub-Site Unit Terintegrasi**: Halaman sub-site interaktif untuk setiap unit Kecamatan (`/kecamatan/{slug}`) dan Desa/Kelurahan (`/desa/{kecamatan}-{desa}`) yang menampilkan warta, program kerja, agenda, dan struktur pengurus masing-masing.
3. **Peta Interaktif GIS OpenStreetMap**: Pemetaan koordinat seluruh unit Karang Taruna se-Kabupaten Bandung dengan pembedaan warna pin (Merah: Kabupaten, Biru: Kecamatan, Hijau: Desa), popup info, tautan langsung ke sub-site, serta filter wilayah.
4. **Filament CMS Admin Panel**: Panel administrasi berbasis Filament 5 dengan 20+ modul konten.
5. **Multi-Tenancy & Data Isolation Scope**: Hak akses terisolasi berjenjang (Kabupaten, Kecamatan, Desa).
6. **Approval Engine & Workflow Moderasi**: Alur peninjauan konten berjenjang (`Draft` ➔ `Submit` ➔ `Revisi/Tolak/Setujui` ➔ `Terbit`).
7. **Modul PPKS & Proteksi NIK**: Usulan warga Pemerlu Pelayanan Kesejahteraan Sosial (PPKS) 26 kategori Kemensos dengan sakelar aktif/nonaktif dari pengaturan situs dan form cek mandiri publik yang terlindungi Captcha & data masking.
8. **Master Sumber Anggaran Dinamis**: Pengelolaan referensi sumber pendanaan program kerja (`BudgetSource`) yang fleksibel dengan fitur pembuatan opsi langsung dari form (*Create Option Form*).
9. **Import & Template XLSX Cerdas**: Pengunduhan template Excel `.xlsx` resmi dan impor data tanpa duplikasi (*upsert*) untuk Master Kecamatan, Desa, Unit, dan Pengurus Unit.
10. **Pengamanan Upload Media**: Batasan unggah global 512 KB per berkas pada seluruh form CMS untuk efisiensi penyimpanan server.
11. **Statistik Pengunjung Realtime**: Pelacak pengunjung harian, bulanan, tahunan, dan pengguna online realtime non-bot pada footer portal.
12. **REST API v1 (`/api/v1`)**: API terstruktur dengan otentikasi Laravel Sanctum dan rate limiting untuk integrasi aplikasi mobile.

---

## ⚙️ Persyaratan Sistem

- PHP 8.3+ (Ekstensi: `pdo_mysql`, `mbstring`, `xml`, `curl`, `gd`, `zip`, `bcmath`, `redis`)
- Composer 2.7+
- Node.js 18+ & NPM
- Database MySQL 8.0+ atau MariaDB 10.11+
- Web Server: Nginx atau Apache (Laragon / Herd / Docker)

---

## 🚀 Panduan Instalasi Lokal (Development)

1. **Clone repositori dan masuk ke direktori**:
   ```bash
   git clone <repo-url> karang-taruna-website
   cd karang-taruna-website
   ```

2. **Install Dependensi Composer**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**:
   Salin `.env.example` ke `.env` dan atur kredensial database lokal Anda:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database & Seeder Data Awal**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Generate Storage Symlink**:
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Server Development**:
   ```bash
   php artisan serve
   ```
   - **Portal Publik**: `http://localhost:8000`
   - **Admin Panel CMS**: `http://localhost:8000/admin`
   - **REST API v1**: `http://localhost:8000/api/v1/news`

---

## 🔑 Akun Demo Pengujian (Hasil Seeder)

> [!WARNING]
> Akun di bawah ini hanya untuk keperluan development/demo lokal. Ganti seluruh password pada environment produksi.

| Tingkatan / Peran | Email Login | Password | Cakupan Hak Akses |
| :--- | :--- | :--- | :--- |
| **Superadmin (Kabupaten)** | `superadmin@karangtaruna.id` | `password` | Akses penuh seluruh modul, approval konten, dan wilayah |
| **Admin Kecamatan (Soreang)** | `kecamatan.soreang@karangtaruna.id` | `password` | Mengelola data & liputan di wilayah Kec. Soreang |
| **Admin Desa (Soreang)** | `desa.soreang@karangtaruna.id` | `password` | Mengelola data desa, pengurus, dan usulan warga PPKS |

---

## 📚 Indeks Dokumentasi Proyek

Untuk rincian teknis lengkap, silakan merujuk pada dokumen berikut:
- 🏗️ [ARCHITECTURE.md](file:///d:/laragon/www/karang-taruna-website/ARCHITECTURE.md) — Arsitektur teknis, Clean Domain structure, dan alur request.
- 🗄️ [DATABASE.md](file:///d:/laragon/www/karang-taruna-website/DATABASE.md) — Skema tabel database, kamus data, dan indexing.
- 👥 [ROLES-PERMISSIONS.md](file:///d:/laragon/www/karang-taruna-website/ROLES-PERMISSIONS.md) — Matriks hak akses RBAC dan aturan isolasi wilayah.
- 🔄 [APPROVAL-WORKFLOW.md](file:///d:/laragon/www/karang-taruna-website/APPROVAL-WORKFLOW.md) — Alur kerja moderasi dan notifikasi antrean.
- 🌐 [API.md](file:///d:/laragon/www/karang-taruna-website/API.md) — Panduan integrasi REST API v1 dan otentikasi Sanctum.
- 🚀 [DEPLOYMENT.md](file:///d:/laragon/www/karang-taruna-website/DEPLOYMENT.md) — Panduan deployment produksi Ubuntu, Nginx, Redis & Supervisor.
- 🛡️ [BACKUP-RESTORE.md](file:///d:/laragon/www/karang-taruna-website/BACKUP-RESTORE.md) — Prosedur pencadangan, pemulihan database, dan disaster recovery.
