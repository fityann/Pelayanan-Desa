# 🟢 FASE 1 — MVP Wajib Selesai (Minggu 1–4 / KKN Berjalan)

**Prioritas tertinggi — sesuai permintaan eksplisit Kades + fondasi sistem**

---

## 📋 Checklist Implementasi Fase 1

### 1.1 Modul Autentikasi & Manajemen Pengguna Dasar
- [x] Login/register Warga
- [x] Role dasar: Warga, Admin Desa, Bendahara, Sekretaris Desa, Kepala Desa, Super Admin
- [x] Reset password

### 1.2 Modul Kependudukan (Dasar)
- [x] Data Penduduk (input awal oleh Admin dari data desa yang sudah ada)
- [x] Data Keluarga (KK)
- [x] Import Excel data penduduk (mempercepat input awal)
- [x] Data otomatis terisi saat warga mengajukan layanan (mengurangi input manual berulang)

### 1.3 Modul Administrasi — Pelayanan Surat (Versi MVP)
- [x] 5 jenis surat prioritas tertinggi: **Surat Domisili, Surat Keterangan Usaha, SKTM, Surat Pengantar Nikah, Surat Kematian**
- [x] Alur simplified: `Warga ajukan → Admin Desa verifikasi → Kepala Desa approve → Surat siap`
- [x] Nomor surat otomatis (format sesuai tata naskah dinas)
- [x] Tracking status surat (Diajukan, Diproses, Ditolak+alasan, Siap Diambil, Selesai)
- [ ] Cetak PDF dengan kop surat resmi (DomPDF) — *menunggu library DomPDF*
- [x] Arsip surat dasar (tersimpan otomatis per pengajuan)

### 1.4 Modul APBDes — Transparansi Publik (Versi MVP)
- [x] Struktur sesuai Permendagri 20/2018: Pendapatan, Belanja, Pembiayaan
- [x] Input ringkasan oleh Bendahara → review Sekretaris Desa → publish oleh Kepala Desa
- [x] Tampilan publik: ringkasan APBDes per tahun berjalan, grafik Pendapatan vs Belanja
- [x] **Diposisikan sebagai portal transparansi, bukan pengganti SISKEUDES**

### 1.5 Modul Pengaduan Masyarakat (Dasar)
- [x] Form pengaduan: kategori, foto, deskripsi (lokasi peta ditunda ke Fase 4)
- [x] Status: Diterima → Diproses → Selesai
- [x] Dashboard rekap pengaduan per kategori

### 1.6 Modul Informasi & Pengumuman Desa
- [x] Berita, pengumuman, agenda kegiatan desa
- [x] Halaman publik informasi desa

### 1.7 Dashboard Ringkas
- [x] Dashboard Admin: total surat per status, ringkasan APBDes, jumlah pengaduan
- [x] Dashboard Warga: riwayat pengajuan pribadi

---

## 🎯 Output Akhir Fase 1
> Sistem yang benar-benar bisa dipakai desa — pelayanan surat jalan, APBDes transparan ke publik, pengaduan terdokumentasi. Ini yang diserahterimakan jika waktu KKN habis di titik ini.

---

## 📊 Status Progress
**Progress:** [ ] 0% | [ ] 25% | [✅] 50% | [ ] 75% | [ ] 100%

**Tanggal Target:** Minggu 4 KKN  
**Status:** ✅ Dalam pengerjaan — Modul inti Fase 1 selesai (kurang cetak PDF DomPDF)

---

## 📁 Flow Utama Sistem (Fase 1)

### Flow Pengajuan Surat — Fase 1 (MVP)
```
Warga login (NIK) → Data kependudukan otomatis terisi
        ↓
Pilih jenis surat + upload dokumen pendukung (KK/KTP)
        ↓
Admin Desa verifikasi (approve/reject + alasan jika ditolak)
        ↓
Kepala Desa approve akhir
        ↓
Nomor surat digenerate otomatis
        ↓
Cetak PDF (kop resmi) → Warga download / ambil fisik di kantor desa
        ↓
Otomatis tersimpan di Arsip Surat
```

### Flow Modul APBDes (Fase 1)
```
Bendahara input ringkasan APBDes (Pendapatan, Belanja, Pembiayaan)
        ↓
Sekretaris Desa review
        ↓
Kepala Desa approve & publish
        ↓
Tampil ke publik (warga akses tanpa login) — ringkasan + grafik
```

### Flow Pengaduan Masyarakat (Fase 1)
```
Warga isi form pengaduan (kategori, foto, deskripsi)
        ↓
Masuk ke dashboard Admin sesuai kategori
        ↓
Status: Diterima → Diproses → Selesai
        ↓
Notifikasi ke warga saat status berubah (in-app di Fase 1, WhatsApp di Fase 2)
```

---

## 📝 Catatan Implementasi
- Gunakan tech stack Laravel + MySQL + Blade + Tailwind CSS
- Implementasi mobile-first untuk akses warga
- Pastikan keamanan data NIK/KK dengan enkripsi
- Siapkan backup manual untuk warga yang tidak melek digital
- Layout admin menggunakan Material Design (Google-style) dengan sidebar navigasi
- Semua view admin konsisten menggunakan `layouts.admin`
- Halaman publik (informasi desa, APBDes) dapat diakses tanpa login
