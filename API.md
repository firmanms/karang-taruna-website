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
- **Body**: `{ "email": "user@karangtaruna.id", "password": "password" }`
- **Response**: Menghasilkan Bearer Token.

### GET `/api/v1/user/profile`
- **Headers**: `Authorization: Bearer <TOKEN>`
- **Response**: Mengambil data profil user yang sedang login.

### POST `/api/v1/auth/logout`
- **Headers**: `Authorization: Bearer <TOKEN>`
- **Response**: Mencabut token sesi yang aktif.
