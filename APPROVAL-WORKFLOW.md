# 🔄 Dokumentasi Alur Moderasi & Approval Workflow
## Karang Taruna Kabupaten Bandung

Semua modul yang dipublikasikan ke portal publik (`articles`, `events`, `work_programs`, `achievements`, `photo_galleries`, `downloads`, `ppks_beneficiaries`) diatur melalui **Approval Workflow Engine**.

---

## 1. Diagram State Machine Moderasi

```
       [ Creator ]
            │
            ▼
        [ Draft ] ──( Submit )──► [ Pending Approval ]
            ▲                             │
            │                             ├───────────► [ Approved & Published ]
            │                             │
    ( Edit & Resubmit )                   ├───────────► [ Rejected ]
            │                             │
            └────── [ Revision Required ] ◄─( Request Revision )
```

---

## 2. Aksi & Peran Workflow

1. **Submit**: Penulis mengajukan konten dari status `draft` atau `revision_required` ke `pending_approval`.
2. **Approve**: Verifikator menyetujui konten. Konten otomatis diset status `approved`, `is_published = true`, dan tanggal publikasi terisi.
3. **Request Revision**: Verifikator mengembalikan konten dengan catatan wajib (`revision_notes`).
4. **Reject**: Verifikator menolak konten dengan alasan penolakan wajib.
5. **Audit Logging**: Setiap transisi status dicatat ke tabel `content_approval_logs`.
6. **Notifikasi Asinkron**: Setiap aksi memicu `ContentWorkflowNotification` ke pengguna terkait.
