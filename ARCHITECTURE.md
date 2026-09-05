# 🏗️ Dokumentasi Arsitektur Sistem
## Karang Taruna Kabupaten Bandung

Aplikasi ini dibangun menggunakan arsitektur **Domain-Driven Modular** berbasis **Laravel 12** dan **Filament 5**.

---

## 1. Struktur Direktori Proyek

```
karang-taruna-website/
├── app/
│   ├── Domain/                 # Domain-Driven Core Logic
│   │   ├── Auth/               # Autentikasi, Role, & User Permissions
│   │   ├── Content/            # Berita, Agenda, Program, Prestasi, Unduhan, Galeri
│   │   ├── PPKS/               # Kategori & Basis Data Warga PPKS
│   │   ├── Settings/           # Pengaturan Website, Profil Organisasi, Slider, FAQ
│   │   ├── Statistics/         # Visitor Analytics & Territory Statistics
│   │   ├── Territory/          # Referensi 31 Kecamatan & 280 Desa/Kelurahan
│   │   └── Units/              # Lembaga Karang Taruna & Struktur Pengurus
│   ├── Filament/               # Admin Panel CMS (Filament 5)
│   │   ├── Pages/              # Kustom Dashboard & Settings
│   │   ├── Resources/          # Filament CRUD Resources (20+ Modul)
│   │   └── Widgets/            # Dashboard Analytics, Charts & Approval Queue
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/V1/         # Controller REST API v1
│   │   │   └── Public/         # Controller Portal Publik (PublicPortalController)
│   │   └── Resources/Api/V1/   # Eloquent JSON Resources API
│   ├── Notifications/          # Asynchronous Queued Notifications
│   ├── Services/               # ApprovalWorkflowService & Business Engines
│   └── Traits/                 # HasTenantScope (Isolasi Data Wilayah)
├── database/
│   ├── migrations/             # Migrasi Struktur 20+ Tabel Database
│   └── seeders/                # DatabaseSeeder (Data Awal Lengkap)
├── public/
│   └── frontend/               # Aset CSS, JS, Gambar dari template 'desainforntend'
├── resources/
│   └── views/
│       ├── layouts/public.blade.php   # Layout Master Website Publik
│       └── public/                    # 13 View Halaman Publik
└── routes/
    ├── api.php                 # Rute REST API v1
    └── web.php                 # Rute Website Publik
```

---

## 2. Prinsip Arsitektur Utama

1. **Separation of Concerns**: Logika bisnis domain dipisahkan dari controller tampilan dan Filament form schemas.
2. **Multi-Tenancy Hierarchy**: Isolasi data otomatis menggunakan trait `HasTenantScope`.
3. **Approval State Machine**: Seluruh konten daerah dimoderasi oleh `ApprovalWorkflowService` sebelum dapat berstatus `published`.
4. **Fast & Memory-Efficient Excel Processing**: Layanan `ExcelImportExportService` berbasis OpenSpout untuk template & impor XLSX tanpa duplikasi data.
5. **Realtime Analytics Tracking**: Pencatatan pengunjung non-bot unik berbasis hash IP harian dan durasi sesi aktif.
6. **Global Media Security**: Konfigurasi batas upload global 512 KB per file dengan sanitasi format dan pemberitahuan langsung pada form Filament.
7. **Dynamic Referential Masters**: Master Sumber Anggaran (`BudgetSource`) dan Sub-Site Terintegrasi per Kecamatan & Desa.
8. **Performance by Default**: Penggunaan eager loading (`with()`), database indexing komposit, pagination, dan query scoping.
