# 👥 Matriks Hak Akses & Role Permissions
## Karang Taruna Kabupaten Bandung

Aplikasi menerapkan sistem Role-Based Access Control (RBAC) dengan pemisahan wewenang yang tegas:

---

## 1. Matriks Peran Pengguna

| Fitur / Modul | Superadmin (Kabupaten) | Verifikator (Kabupaten) | Admin Kecamatan | Admin Desa / Kelurahan |
| :--- | :---: | :---: | :---: | :---: |
| **Kelola Master Wilayah & User** | ✅ Ya | ❌ Tidak | ❌ Tidak | ❌ Tidak |
| **Setujui / Tolak / Minta Revisi Konten** | ✅ Ya | ✅ Ya | ❌ Tidak | ❌ Tidak |
| **Verifikasi Usulan Warga PPKS** | ✅ Ya | ✅ Ya | ❌ Tidak | ❌ Tidak |
| **Kelola Berita & Event Kabupaten** | ✅ Ya | ✅ Ya | ❌ Tidak | ❌ Tidak |
| **Input Berita & Event Wilayah Sendiri**| ✅ Ya | ✅ Ya | ✅ Ya (Scoped) | ✅ Ya (Scoped) |
| **Input Usulan Warga PPKS Desa** | ✅ Ya | ✅ Ya | ✅ Ya (Binaan) | ✅ Ya (Desa Sendiri) |
| **Kelola Pengurus Unit Sendiri** | ✅ Ya | ✅ Ya | ✅ Ya | ✅ Ya |
| **Akses Dashboard Analytics** | 👑 Full Kab | ⚖️ Verifikasi | 🏛️ Scoped Kec | 🏘️ Scoped Desa |

---

## 2. Aturan Multi-Tenancy Scope (`HasTenantScope`)

- **Tingkat Kabupaten (`superadmin`, `verifikator`)**: Menampilkan seluruh data tanpa batasan filter wilayah.
- **Tingkat Kecamatan (`admin-kecamatan`)**: Otomatis membatasi data hanya untuk `district_id` kecamatan bersangkutan dan seluruh unit desa di bawahnya.
- **Tingkat Desa (`admin-desa`)**: Otomatis membatasi data hanya untuk `unit_id` milik desanya sendiri.
