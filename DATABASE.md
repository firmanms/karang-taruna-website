# 🗄️ Dokumentasi Database & Skema Tabel
## Karang Taruna Kabupaten Bandung

Basis data sistem dirancang dengan relasi integritas referensial kuat (`Foreign Keys & Cascades`) dan indeks komposit performa tinggi.

---

## 1. Daftar Tabel Utama

| No | Nama Tabel | Domain | Deskripsi Fungsi |
|---|---|---|---|
| 1 | `roles` | Auth | Master 4 level peran (Superadmin, Verifikator, Admin Kec, Admin Desa) |
| 2 | `users` | Auth | Pengguna sistem dengan relasi `role_id` dan `unit_id` |
| 3 | `ref_districts` | Territory | Master 31 Kecamatan resmi Kabupaten Bandung |
| 4 | `ref_villages` | Territory | Master 280 Desa/Kelurahan resmi |
| 5 | `karang_taruna_units`| Units | Master lembaga Karang Taruna berjenjang & koordinat GPS |
| 6 | `unit_members` | Units | Susunan pengurus Karang Taruna per unit |
| 7 | `news_categories` | Content | Master kategori berita |
| 8 | `articles` | Content | Berita & artikel liputan daerah |
| 9 | `event_categories` | Content | Master kategori kegiatan |
| 10 | `events` | Content | Agenda kegiatan & event daerah |
| 11 | `program_divisions` | Content | Master bidang program kerja |
| 12 | `work_programs` | Content | Program kerja unggulan & anggaran |
| 13 | `announcements` | Content | Pengumuman resmi & surat edaran |
| 14 | `photo_categories` | Content | Album foto kegiatan |
| 15 | `photo_galleries` | Content | Dokumentasi foto |
| 16 | `video_galleries` | Content | Arsip video dokumentasi |
| 17 | `achievements` | Content | Prestasi pemuda & penghargaan |
| 18 | `download_categories`| Content | Master kategori unduhan berkas |
| 19 | `downloads` | Content | Berkas & dokumen unduhan publik |
| 20 | `ppks_categories` | PPKS | 26 Jenis kategori PPKS Kemensos |
| 21 | `ppks_beneficiaries`| PPKS | Basis data warga PPKS hasil usulan desa |
| 22 | `ppks_check_logs` | PPKS | Log audit penelusuran form cek NIK publik |
| 23 | `content_approval_logs`| Content | Log audit riwayat moderasi konten |
| 24 | `hero_sliders` | Settings | Banner hero slider portal beranda |
| 25 | `faqs` | Settings | Pertanyaan dan jawaban umum |
| 26 | `profile_organizations`| Settings | Profil visi, misi, dan kontak legalitas |
| 27 | `site_settings` | Settings | Key-value settings website |

---

## 2. Indeks Performa Penting

- `idx_articles_pub`: `(approval_status, is_published, published_at)`
- `idx_articles_dist`: `(district_id, approval_status)`
- `idx_events_date`: `(approval_status, event_date)`
- `idx_ppks_dist`: `(verification_status, district_id)`
- `idx_units_level`: `(is_verified, unit_level)`
