# 🟡 FASE 2 — Penyempurnaan Modul Inti (Lanjutan Pasca-KKN Jangka Pendek / jika waktu tersisa)

---

## 📋 Checklist Implementasi Fase 2

### 2.1 Verifikasi Berjenjang Penuh
- [ ] Alur lengkap: `Warga → RT → RW → Kasi/Kaur → Sekdes (paraf) → Kades (TTD)`
- [ ] Role RT & RW aktif dengan hak approve/reject + catatan wajib

### 2.2 Perluasan Jenis Surat
- [ ] Surat Kelahiran, Surat Pindah, Surat Datang, Surat Kehilangan, Surat Pengantar SKCK, Surat Izin Keramaian

### 2.3 Nomor Antrian Online
- [ ] Ambil nomor antrian untuk pelayanan tatap muka di kantor desa

### 2.4 QR Code Pelayanan
- [ ] QR ditempel di kantor desa, mengarah langsung ke modul terkait

### 2.5 Notifikasi WhatsApp
- [ ] Notifikasi otomatis via Fonnte API saat status surat/pengaduan berubah

### 2.6 Survei Kepuasan Masyarakat (SKM)
- [ ] Diisi warga setelah layanan selesai, mengacu unsur SKM Permenpan RB

### 2.7 Arsip Digital & Buku Agenda Surat Lengkap
- [ ] Pencarian arsip berdasar nomor/nama/jenis/tanggal
- [ ] Surat masuk-keluar antar instansi (bukan hanya surat warga)

### 2.8 Cek Status Bantuan Sosial
- [ ] Warga cek status penerima bansos via NIK

### 2.9 FAQ & Asisten Interaktif
- [ ] Menjawab syarat, estimasi waktu, biaya per jenis surat (berbasis data resmi desa, bukan pengambil keputusan)

### 2.10 APBDes Lanjutan
- [ ] Perbandingan antar tahun (grafik tren)
- [ ] Riwayat revisi APBDes (APBDes-Perubahan)
- [ ] Bagian "Realisasi vs Rencana"
- [ ] Export Excel

### 2.11 Jadwal & Agenda Pelayanan Desa
- [ ] Jam pelayanan, hari libur, jadwal musyawarah, posyandu

---

## 🎯 Output Akhir Fase 2
> Sistem administrasi desa setara aplikasi pelayanan desa profesional, dengan birokrasi sesuai kondisi riil dan komunikasi otomatis ke warga.

---

## 📊 Status Progress
**Progress:** [ ] 0% | [ ] 25% | [ ] 50% | [ ] 75% | [✅] 100%

**Timeline:** Pasca-KKN / Minggu 5-8 jika waktu tersisa  
**Status:** Perencanaan lanjutan

---

## 📁 Flow Utama Sistem (Fase 2)

### Flow Pengajuan Surat — Fase 2 (Versi Lengkap)
```
Warga ajukan surat
        ↓
Verifikasi RT (approve/reject + catatan)
        ↓
Verifikasi RW (approve/reject + catatan)
        ↓
Verifikasi Kasi/Kaur terkait
        ↓
Draft surat otomatis + Nomor surat otomatis
        ↓
Paraf Sekretaris Desa
        ↓
Tanda tangan & stempel Kepala Desa
        ↓
Notifikasi WhatsApp ke warga
        ↓
Download PDF / ambil fisik → tersimpan di Arsip Digital & Buku Agenda Surat
        ↓
Warga isi Survei Kepuasan Masyarakat (SKM)
```

---

## 🚀 Peningkatan dari Fase 1
- **Alur birokrasi lengkap** dengan semua level pemerintahan desa
- **Komunikasi otomatis** via WhatsApp ke warga
- **Sistem feedback** dengan Survei Kepuasan Masyarakat
- **Data historis** yang lebih lengkap untuk analisis
- **Integrasi QR code** untuk kemudahan akses

---

## 🔧 Teknologi Tambahan
- Fonnte API untuk notifikasi WhatsApp
- QR code generator
- Excel export/import untuk data APBDes
- Sistem pencarian arsip yang lebih canggih

---

## 📝 Prasyarat Implementasi
- Fase 1 harus sudah berjalan stabil
- Perangkat RT/RW/Kasi/Kaur sudah dilatih
- Akses ke layanan WhatsApp Business API (Fonnte)
- Data historis surat sudah terinput minimal 3 bulan