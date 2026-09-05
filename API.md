# 🌐 Dokumentasi REST API v1
## Karang Taruna Kabupaten Bandung

Base URL: `https://karangtarunabandungkab.or.id/api/v1` (atau `http://localhost:8000/api/v1` saat development)

---

## 1. Format Standar Response JSON

```json
{
  "success": true,
  "message": "Pesan deskriptif keberhasilan",
  "data": { ... },
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 45,
    "last_page": 5
  }
}
```

---

## 2. Endpoint Publik (Read-Only)

- `GET /api/v1/news` — Mengambil daftar berita terbit (`search`, `category_id`, `news_scope`, `district_id`, `sort`, `page`).
- `GET /api/v1/news/{slug}` — Detail artikel berita dan increment view counter.
- `GET /api/v1/events` — Kalender agenda kegiatan disetujui (`status`, `search`, `page`).
- `GET /api/v1/programs` — Program kerja unggulan (`division_id`, `status`, `page`).
- `GET /api/v1/achievements` — Prestasi pemuda terverifikasi (`level`, `year`, `page`).
- `GET /api/v1/galleries` — Dokumentasi foto kegiatan.
- `GET /api/v1/downloads` — Pusat unduhan dokumen resmi.
- `GET /api/v1/karang-taruna-units` — Direktori pengurus Karang Taruna (`level`, `district_id`, `page`).
- `GET /api/v1/territory` — Master referensi 31 Kecamatan se-Kabupaten Bandung.
- `GET /api/v1/statistics` — Agregat statistik wilayah & aktivitas kepemudaan.

---

## 3. Endpoint Autentikasi (Laravel Sanctum)

### POST `/api/v1/auth/login`
- **Body**: `{ "email": "admin@karangtarunabandungkab.or.id", "password": "password" }`
- **Response**: Menghasilkan Bearer Token & metadata user (Role, Unit ID, Level).

### GET `/api/v1/user/profile`
- **Headers**: `Authorization: Bearer <TOKEN>`
- **Response**: Mengambil data profil user yang sedang login beserta data Karang Taruna Unit.

### POST `/api/v1/auth/logout`
- **Headers**: `Authorization: Bearer <TOKEN>`
- **Response**: Mencabut token sesi yang aktif.

---

## 4. Endpoint CMS Android / Web Management (`/api/v1/manage/*`)

> **Catatan Hak Akses & Multi-tenancy (Data Isolation)**:
> - **Superadmin Kabupaten**: Akses penuh seluruh data se-Kabupaten & approval/verifikasi usulan PPKS.
> - **Admin Kecamatan**: Otomatis terisolasi hanya dapat melihat dan mengelola data di wilayah kecamatannya & desa di bawahnya.
> - **Admin Desa**: Otomatis terisolasi hanya dapat mengelola data unit desanya sendiri.

Semua request wajib menyertakan header:
`Authorization: Bearer <TOKEN>`
`Accept: application/json`

### A. Kelola Berita & Artikel (`/manage/articles`)
- `GET /api/v1/manage/articles` — List artikel unit bersangkutan (dukung query `search`, `status`, `page`).
- `POST /api/v1/manage/articles` — Tambah artikel baru (mendukung upload gambar cover, max 512 KB).
- `GET /api/v1/manage/articles/{id}` — Detail artikel.
- `POST /api/v1/manage/articles/{id}` (atau `PUT`) — Update artikel.
- `DELETE /api/v1/manage/articles/{id}` — Hapus artikel.

### B. Kelola Agenda Kegiatan (`/manage/events`)
- `GET /api/v1/manage/events` — List agenda kegiatan unit.
- `POST /api/v1/manage/events` — Tambah agenda baru (dukung upload poster, max 512 KB).
- `GET /api/v1/manage/events/{id}` — Detail agenda.
- `POST /api/v1/manage/events/{id}` (atau `PUT`) — Update agenda.
- `DELETE /api/v1/manage/events/{id}` — Hapus agenda.

### C. Usulan & Verifikasi Warga PPKS (`/manage/ppks`)
- `GET /api/v1/manage/ppks` — List warga PPKS (otomatis terfilter berdasarkan desa/kecamatan user).
- `POST /api/v1/manage/ppks` — Ajukan data warga PPKS baru (input NIK, KK, Nama, Kategori PPKS, Status Bantuan).
- `GET /api/v1/manage/ppks/{id}` — Detail usulan warga PPKS.
- `PUT /api/v1/manage/ppks/{id}` — Update data / Verifikasi usulan (*Hak verifikasi status `verified`/`rejected` hanya untuk tingkat Kabupaten*).
- `DELETE /api/v1/manage/ppks/{id}` — Hapus usulan data warga PPKS.

### D. Struktur Kepengurusan Unit (`/manage/members`)
- `GET /api/v1/manage/members` — List pengurus unit yang sedang login.
- `POST /api/v1/manage/members` — Tambah pengurus baru (dukung upload avatar/foto, max 512 KB).
- `GET /api/v1/manage/members/{id}` — Detail pengurus.
- `POST /api/v1/manage/members/{id}` (atau `PUT`) — Update pengurus.
- `DELETE /api/v1/manage/members/{id}` — Hapus pengurus.

### E. Profil & Pengaturan Unit Sendiri (`/manage/unit/me`)
- `GET /api/v1/manage/unit/me` — Ambil informasi lengkap profil unit Karang Taruna user login (alamat, kontak, email, koordinat peta).
- `POST /api/v1/manage/unit/me` — Perbarui kontak, alamat kantor, koordinat GPS latitude/longitude, dan upload logo unit (max 512 KB).

---

## 5. 🚀 Postman Collection Import

File koleksi Postman resmi telah diperbarui lengkap dengan folder CRUD CMS Android & Otentikasi: [`postman_collection.json`](file:///d:/laragon/www/karang-taruna-website/postman_collection.json).

### Cara Menggunakan di Postman:
1. Buka aplikasi **Postman**.
2. Klik tombol **Import** (di pojok kiri atas).
3. Pilih atau drag-and-drop file [`postman_collection.json`](file:///d:/laragon/www/karang-taruna-website/postman_collection.json).
4. Variabel `base_url` diset ke `http://127.0.0.1:8000` (dapat disesuaikan jika menggunakan domain staging/produksi).
5. Buka folder **"0. Autentikasi (Sanctum Login)"** -> Pilih Login (misal: *Login - Superadmin Kabupaten*, *Login - Admin Kecamatan*, atau *Login - Admin Desa*).
6. Tekan **Send**; Token bearer otomatis tersimpan ke environment variable `{{bearer_token}}`.
7. Anda siap menjalankan seluruh request CRUD pada folder **1 sampai 5 (CMS Android)** dan endpoint publik pada **folder 6**.

