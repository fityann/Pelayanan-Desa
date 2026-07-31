# SIPANDA — Puspamukti Smart Village

Platform digital terpadu pelayanan Desa Puspamukti, Kecamatan Cigalontang, Kabupaten Tasikmalaya.
Dibangun bertahap sesuai `PRD-Roadmap-Puspamukti-Smart-Village.md` (Fase 1 sebagai komitmen utama).

**Tech stack:** Laravel + Blade (Tailwind) + spatie/laravel-permission + barryvdh/laravel-dompdf + MySQL.

---

## Persiapan & Instalasi

```bash
composer install
npm install
cp .env.example .env   # lalu sesuaikan kredensial DB (MySQL)
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

### Akun demo (hasil seeder)

| Role | Email | Password |
|---|---|---|
| Super Admin | admin@puspamukti.local | admin123 |
| Kepala Desa | kepaladesa@puspamukti.local | kepaladesa123 |
| Sekretaris Desa | sekdes@puspamukti.local | sekdes123 |
| Bendahara | bendahara@puspamukti.local | bendahara123 |
| Admin Desa | admindesa@puspamukti.local | admindesa123 |
| Warga | warga@puspamukti.local | warga123 |
| Warga | warga2@puspamukti.local | warga123 |

Semua akun dibuat dengan `email_verified_at` terisi agar lolos middleware `verified`.

---

## Alur Pelayanan Surat (Fase 1 — sesuai PRD 7.1)

```
diajukan → diverifikasi_admin → ditolak (alasan wajib)
                              ↓
                    disetujui_kades (nomor surat digenerate)
                              ↓
        ┌─────── butuh_ttd_fisik? ───────┐
     ya (default)                        tidak
        ↓                                 ↓
 menunggu_ttd_fisik                    selesai (auto)
   (draft PDF siap cetak)              (warga download PDF)
        ↓
 selesai (setelah TTD fisik + diambil)
```

- Setiap perubahan status tercatat di tabel `riwayat_status_surats` (tracking real-time untuk warga & audit).
- Approval **hanya boleh dilakukan Kepala Desa** (`role:Kepala Desa`).
- Draft PDF dibuat otomatis via DomPDF begitu Kades approve.

### Akses warga
- `/layanan/surat` — pilih jenis surat, ajukan, pantau status.
- `/pengaduan/buat` — target QR code (PRD 1.5), parameter `?qr=1` menandai sumber akses QR.

---

## Hak Akses (middleware)

| Area | Role yang diizinkan |
|---|---|
| `/admin` (umum) | Super Admin, Kepala Desa, Sekretaris Desa, Bendahara, Admin Desa |
| `/admin/users` & `/admin/roles` | Super Admin, Admin Desa |
| Approve surat (`surat/{id}/approve`) | Kepala Desa, Super Admin |
| Layanan warga (`/layanan/*`) | Semua user login (verified) |

Catatan: middleware Spatie didaftarkan di `bootstrap/app.php` (alias `role`, `permission`, `role_or_permission`, `can_manage_users`).

---

## Keputusan Desain yang Disengaja (dicatat, bukan kelupaan)

1. **APBDes memakai 1 tabel `apbdes` (bukan 4 tabel seperti ERD).**
   ERD merancang `apbdes_tahun` + `apbdes_pendapatan/belanja/pembiayaan` untuk mendukung versi/revisi
   dan perbandingan antar tahun. Untuk batas waktu KKN (±1 bulan), Fase 1 cukup memakai 1 tabel
   ringkasan (Pendapatan/Belanja/Pembiayaan per tahun) — fitur revisi/versi & tren antar tahun ada di
   **Fase 2** (`PRD` §2.10). Jika Fase 2 dikerjakan, migrasi ke skema multi-tabel atau penambahan kolom
   `versi`/`apbdes_induk_id` dapat dilakukan tanpa merombak tampilan publik.

2. **`pengumuman` digabung ke tabel `informasis`** (kategori: `berita`, `pengumuman`, `agenda`).

3. **`jenis_surats` menggunakan kolom `deskripsi`/`syarat`/`masa_berlaku`/`butuh_ttd_fisik`**
   (lebih sederhana dari kolom ERD `syarat_dokumen`/`estimasi_hari`/`template_pdf`).

4. **Status surat di kode mengikuti PRD revisi** (`diverifikasi_admin`, `disetujui_kades`, `menunggu_ttd_fisik`),
   bukan status awal migration lama (`diproses`, `disetujui`, `siap_diambil`).

---

## Menjalankan Test

```bash
php artisan test
```

Cakupan saat ini: auth, otorisasi admin/role, endpoint `cek-nik`, alur warga (surat + pengaduan), PDF surat.
