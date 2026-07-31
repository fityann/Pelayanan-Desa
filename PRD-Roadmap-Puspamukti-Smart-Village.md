# Product Requirements Document (PRD)
# Puspamukti Smart Village
### Platform Digital Terpadu Desa Puspamukti — Kecamatan Cigalontang, Kabupaten Tasikmalaya

| Atribut | Keterangan |
|---|---|
| Nama Produk | Puspamukti Smart Village (Modul Inti: SIPANDA) |
| Versi Dokumen | 2.0 — Gabungan SIPANDA + Smart Village SRS |
| Status | Draft — Siap Presentasi ke Dosen Pembimbing & Perangkat Desa |
| Konteks | Proker KKN 05 — Durasi Efektif ±1 Bulan |
| Pendekatan | Pengembangan Bertahap (Fase 1–5), MVP dahulu, visi jangka panjang tetap didokumentasikan |

---

## 1. Latar Belakang

Desa Puspamukti membutuhkan digitalisasi pelayanan publik dan transparansi keuangan desa. Berdasarkan hasil wawancara langsung, Kepala Desa secara eksplisit menyebutkan dua kebutuhan utama:

1. **Layanan administrasi** (pengurusan surat, kependudukan, pengaduan warga)
2. **APBDes** (transparansi anggaran dan realisasi keuangan desa)

Di luar itu, terdapat visi jangka panjang untuk menjadikan desa ini "Smart Village" yang mencakup wisata, pertanian, peternakan, UMKM, GIS, hingga AI. Mengingat KKN hanya berlangsung **±1 bulan efektif**, dokumen ini menyusun seluruh fitur yang mungkin dibutuhkan desa dalam **satu roadmap bertahap** — fitur yang paling penting dan realistis dikerjakan diletakkan di fase teratas, sementara fitur visi jangka panjang tetap didokumentasikan lengkap sebagai peta jalan pengembangan pasca-KKN.

---

## 2. Tujuan Produk

1. Menjawab kebutuhan eksplisit Kepala Desa: administrasi pelayanan & transparansi APBDes.
2. Menghadirkan sistem yang benar-benar selesai dan terpakai dalam 1 bulan, bukan sekadar demo.
3. Menyediakan dokumentasi visi jangka panjang (Smart Village penuh) agar KKN berikutnya atau operator desa dapat melanjutkan tanpa merancang ulang dari nol.
4. Menjaga agar setiap fase tetap independen dan bisa "berhenti dengan aman" — artinya jika waktu habis di fase 1, sistem tetap utuh dan bisa dipakai, tidak setengah jadi.

---

## 3. Prinsip Penyusunan Roadmap

- **Fase 1** = fitur yang diminta langsung oleh Kades + fondasi wajib sistem. Ini target utama yang **harus** selesai dalam 1 bulan.
- **Fase 2** = penyempurnaan modul inti (alur birokrasi lengkap, notifikasi, dsb) — dikerjakan jika waktu tersisa, atau jadi target lanjutan pasca-KKN jangka pendek.
- **Fase 3 dst.** = modul perluasan visi Smart Village (wisata, pertanian, peternakan, UMKM, GIS, AI) — didokumentasikan lengkap sebagai roadmap jangka panjang, dikerjakan oleh KKN/pengembang berikutnya.
- Setiap fitur dari SRS awal (baik dari brainstorming SIPANDA maupun Smart Village) **tetap dimasukkan**, tidak ada yang dihapus — hanya dipetakan ke fase yang realistis.

---

## 4. Stakeholder & Role Pengguna

| Role | Deskripsi | Fase Aktif |
|---|---|---|
| **Warga** | Masyarakat Desa Puspamukti | Fase 1 |
| **Admin Desa / Operator** | Staf desa/PDD yang mengelola sistem harian | Fase 1 |
| **Kepala Desa** | Approval akhir surat & publish APBDes | Fase 1 |
| **Bendahara Desa** | Input ringkasan APBDes | Fase 1 |
| **Sekretaris Desa** | Review APBDes, paraf surat | Fase 1–2 |
| **RT** | Verifikasi tahap 1 pengajuan surat | Fase 2 |
| **RW** | Verifikasi tahap 2 pengajuan surat | Fase 2 |
| **Kasi/Kaur** | Verifikasi teknis per bidang | Fase 2 |
| **Pokdarwis** | Pengelola destinasi wisata | Fase 3 |
| **Karang Taruna** | Pengelola event desa | Fase 3–4 |
| **Petani / Peternak / UMKM** | Input data usaha masing-masing | Fase 4 |
| **Super Admin (Tim Pengembang)** | Kelola sistem, user, role | Semua fase |

---

## 5. Rekomendasi Tech Stack (Final, Konsolidasi)

```
Backend        : Laravel (versi stabil terbaru sesuai hosting tujuan)
Role/Permission: spatie/laravel-permission
Database       : MySQL/MariaDB (default — sesuai realita shared hosting desa)
Admin UI       : Blade + Tabler
Publik UI      : Blade + Tailwind CSS + Alpine.js (mobile-first)
PDF            : barryvdh/laravel-dompdf
Import/Export  : Laravel Excel (Maatwebsite)
Notifikasi     : Fonnte API (WhatsApp) + fallback in-app
Grafik         : Chart.js
Peta (Fase 4)  : Leaflet + OpenStreetMap
Storage        : Local storage (Cloudflare R2 opsional di fase lanjut)
Deployment     : Menyesuaikan infrastruktur desa (shared hosting/cPanel atau VPS)
```

*Catatan: Livewire/Volt, PostgreSQL, Docker+Redis+Supervisor dapat dipertimbangkan di fase lanjutan jika desa sudah punya VPS sendiri dan tim pengelola teknis yang lebih mapan — tidak direkomendasikan untuk fase KKN karena menambah kompleksitas maintenance.*

---

## 6. ROADMAP BERTAHAP (FASE 1–5)

### 🟢 FASE 1 — MVP Wajib Selesai (Minggu 1–4 / KKN Berjalan)
**Prioritas tertinggi — sesuai permintaan eksplisit Kades + fondasi sistem**

#### 1.1 Modul Autentikasi & Manajemen Pengguna Dasar
- Login/register Warga
- Role dasar: Warga, Admin Desa, Bendahara, Sekretaris Desa, Kepala Desa, Super Admin
- Reset password

#### 1.2 Modul Kependudukan (Dasar)
- Data Penduduk (input awal oleh Admin dari data desa yang sudah ada)
- Data Keluarga (KK)
- Import Excel data penduduk (mempercepat input awal)
- Data otomatis terisi saat warga mengajukan layanan (mengurangi input manual berulang)

#### 1.3 Modul Administrasi — Pelayanan Surat (Versi MVP, Direvisi)
- 3–5 jenis surat prioritas tertinggi: **Surat Domisili, Surat Keterangan Usaha, SKTM, Surat Pengantar Nikah, Surat Kematian**
- **Seluruh proses administrasi dilakukan via aplikasi** — warga tidak perlu datang ke kantor desa kecuali untuk surat yang butuh tanda tangan basah Kepala Desa
- Alur final (sesuai arahan Kepala Desa):
  `Warga ajukan (aplikasi) → Admin Desa verifikasi (aplikasi) → Kepala Desa approve (aplikasi) → Sistem generate draft PDF siap cetak → [jika butuh TTD basah] cetak draft & bawa ke Kades hanya untuk tanda tangan → update status selesai`
- Status surat: `diajukan → diverifikasi_admin → ditolak (+alasan) → disetujui_kades → menunggu_ttd_fisik → selesai`
- **Kepala Desa tidak perlu memproses administrasi dari awal** — cukup approval digital + tanda tangan fisik di tahap akhir, sehingga menghemat waktu Kades secara signifikan
- Nomor surat otomatis (format sesuai tata naskah dinas), digenerate begitu Kades approve
- Cetak PDF dengan kop surat resmi, siap tanda tangan (DomPDF) — Admin/Kades tinggal print, tidak perlu ketik ulang surat
- Tracking status surat real-time untuk warga
- Arsip surat dasar (tersimpan otomatis per pengajuan)
- *Catatan: opsi Tanda Tangan Elektronik (TTE) dapat menggantikan tahap cetak+TTD fisik ini di fase lanjutan, jika desa sudah memiliki TTE tersertifikasi.*

#### 1.4 Modul APBDes — Transparansi Publik (Versi MVP)
- Struktur sesuai Permendagri 20/2018: Pendapatan (PADes, Dana Desa, ADD, dll), Belanja per bidang, Pembiayaan
- Input ringkasan oleh Bendahara → review Sekretaris Desa → publish oleh Kepala Desa
- Tampilan publik: ringkasan APBDes per tahun berjalan, grafik Pendapatan vs Belanja
- **Diposisikan sebagai portal transparansi, bukan pengganti SISKEUDES**
- Export PDF untuk laporan

#### 1.5 Modul Pengaduan Masyarakat (Dasar) + Akses via QR Code
- Form pengaduan: kategori, foto, deskripsi (lokasi peta ditunda ke Fase 4)
- **Akses cepat via QR Code**: QR ditempel di titik strategis desa (kantor desa, balai warga, papan pengumuman) — discan langsung membuka halaman form pengaduan di browser HP, tanpa perlu install aplikasi maupun ketik alamat website manual
- Warga login pakai NIK sebelum isi form (opsi pengaduan anonim dapat dipertimbangkan sesuai kebijakan desa)
- Status: Diterima → Diproses → Selesai
- Warga dapat cek kembali status pengaduannya sendiri melalui website
- Dashboard rekap pengaduan per kategori

#### 1.6 Modul Informasi & Pengumuman Desa
- Berita, pengumuman, agenda kegiatan desa

#### 1.7 Dashboard Ringkas
- Dashboard Admin: total surat per status, ringkasan APBDes, jumlah pengaduan
- Dashboard Warga: riwayat pengajuan pribadi

> **Output akhir Fase 1:** Sistem yang benar-benar bisa dipakai desa — pelayanan surat jalan, APBDes transparan ke publik, pengaduan terdokumentasi. Ini yang diserahterimakan jika waktu KKN habis di titik ini.

---

### 🟡 FASE 2 — Penyempurnaan Modul Inti (Lanjutan Pasca-KKN Jangka Pendek / jika waktu tersisa)

#### 2.1 Verifikasi Berjenjang Penuh (Opsional — Hanya Jika Desa Ingin Melibatkan RT/RW)
- Alur opsional jika suatu saat desa ingin melibatkan RT/RW dalam proses: `Warga → RT → RW → Kasi/Kaur → Sekdes (paraf) → Kades (approve+TTD)`
- Role RT & RW aktif dengan hak approve/reject + catatan wajib
- **Catatan:** sesuai arahan Kepala Desa saat ini, alur Fase 1 (Admin Desa → Kades langsung) sudah menjadi alur final yang dipakai — modul ini hanya opsi tambahan bila kebijakan desa berubah di kemudian hari

#### 2.2 Perluasan Jenis Surat
- Surat Kelahiran, Surat Pindah, Surat Datang, Surat Kehilangan, Surat Pengantar SKCK, Surat Izin Keramaian

#### 2.3 Nomor Antrian Online
- Ambil nomor antrian untuk pelayanan tatap muka di kantor desa (untuk kasus yang tetap butuh datang langsung, misalnya ambil surat fisik)

#### 2.4 QR Code Pelayanan Umum (Perluasan dari QR Pengaduan di Fase 1)
- Selain QR untuk pengaduan (sudah aktif di Fase 1), tambahkan QR code lain di kantor desa yang mengarah ke modul lain: cek status surat, cek bantuan sosial, FAQ, dll

#### 2.5 Notifikasi WhatsApp
- Notifikasi otomatis via Fonnte API saat status surat/pengaduan berubah

#### 2.6 Survei Kepuasan Masyarakat (SKM)
- Diisi warga setelah layanan selesai, mengacu unsur SKM Permenpan RB

#### 2.7 Arsip Digital & Buku Agenda Surat Lengkap
- Pencarian arsip berdasar nomor/nama/jenis/tanggal
- Surat masuk-keluar antar instansi (bukan hanya surat warga)

#### 2.8 Cek Status Bantuan Sosial
- Warga cek status penerima bansos via NIK

#### 2.9 FAQ & Asisten Interaktif
- Menjawab syarat, estimasi waktu, biaya per jenis surat (berbasis data resmi desa, bukan pengambil keputusan)

#### 2.10 APBDes Lanjutan
- Perbandingan antar tahun (grafik tren)
- Riwayat revisi APBDes (APBDes-Perubahan)
- Bagian "Realisasi vs Rencana"
- Export Excel

#### 2.11 Jadwal & Agenda Pelayanan Desa
- Jam pelayanan, hari libur, jadwal musyawarah, posyandu

> **Output akhir Fase 2:** Sistem administrasi desa setara aplikasi pelayanan desa profesional, dengan birokrasi sesuai kondisi riil dan komunikasi otomatis ke warga.

---

### 🔵 FASE 3 — Modul Ekonomi & Promosi Desa (Jangka Menengah, KKN Berikutnya)

#### 3.1 Modul Desa Wisata
- Data Destinasi (foto, video, lokasi, harga tiket, jam operasional)
- Paket Wisata & Reservasi, Jadwal, Guide
- QR Tiket
- Galeri (album, video, event)

#### 3.2 Modul UMKM
- Data UMKM, Produk, Kategori, Galeri, Kontak, Lokasi, Promosi

#### 3.3 Modul Event Desa
- Kalender event, festival, lomba, pendaftaran online, dokumentasi

> **Output akhir Fase 3:** Desa memiliki etalase digital untuk mendukung ekonomi lokal dan promosi wisata.

---

### 🟠 FASE 4 — Modul Sektoral & Spasial (Jangka Panjang)

#### 4.1 Modul Pertanian
- Data Petani, Data Lahan, Jenis Tanaman, Musim & Jadwal Tanam/Panen, Harga Komoditas, Statistik Produksi

#### 4.2 Modul Peternakan
- Data Peternak, Data Ternak, Jenis Ternak, Berat, Vaksin, Riwayat Kesehatan, Pakan, Penjualan

#### 4.3 Modul Peta Desa (GIS)
- Lokasi rumah, sawah, peternakan, UMKM, wisata, kantor desa, sekolah, posyandu, masjid (Leaflet + OpenStreetMap)
- Lokasi pengaduan ditambahkan ke peta (peningkatan dari Fase 1)

#### 4.4 Modul Arsip Desa Lengkap
- Peraturan Desa, SK Kepala Desa, RPJMDes, RKPDes, APBDes historis, Laporan Tahunan

#### 4.5 Modul Manajemen Pengguna Lengkap
- Seluruh role: Kaur, Kasi, Operator Desa, Petani, Peternak, UMKM, Pokdarwis, Karang Taruna dengan permission granular per menu

> **Output akhir Fase 4:** Sistem menjadi basis data desa menyeluruh lintas sektor, siap jadi rujukan perencanaan pembangunan desa (RPJMDes/RKPDes).

---

### 🟣 FASE 5 — Modul AI & Inovasi Lanjutan (Eksploratif)

#### 5.1 Chatbot Informasi Desa
- Menjawab pertanyaan umum warga (syarat surat, jadwal, info desa)

#### 5.2 Ringkasan Laporan Otomatis
- AI merangkum laporan APBDes/kegiatan desa jadi bahasa awam

#### 5.3 OCR KTP/KK
- Ekstraksi data otomatis dari foto KTP/KK saat pengajuan surat (mempercepat input, kurangi typo)

#### 5.4 Rekomendasi Wisata
- Rekomendasi destinasi/paket wisata berdasarkan preferensi pengunjung

#### 5.5 Analisis Data Penduduk
- Insight otomatis dari data kependudukan (tren usia, migrasi, dll) untuk bahan kebijakan desa

> **Output akhir Fase 5:** Sistem menjadi showcase inovasi digital desa, relevan untuk lomba/inovasi daerah.

---

## 7. FLOW UTAMA SISTEM

### 7.1 Flow Pengajuan Surat — Fase 1 (MVP, Direvisi — Seluruh Proses via Aplikasi)

```
Warga login (NIK) → Data kependudukan otomatis terisi
        ↓
Pilih jenis surat + upload dokumen pendukung (KK/KTP), semua via aplikasi
        ↓
Admin Desa verifikasi via aplikasi (approve/reject + alasan jika ditolak)
        ↓
Kepala Desa approve via aplikasi (TIDAK perlu proses administrasi manual dari awal)
        ↓
Nomor surat digenerate otomatis + draft PDF siap cetak dibuat sistem
        ↓
     ┌───────────────────────┴───────────────────────┐
     │                                                 │
Surat tidak butuh TTD basah                   Surat butuh TTD basah Kades
     │                                                 │
Warga langsung download PDF                   Admin cetak draft PDF
     │                                                 ↓
     │                                      Dibawa ke Kades HANYA untuk
     │                                      ditandatangani (bukan proses
     │                                      administrasi dari nol)
     │                                                 ↓
     │                                      Kades tanda tangan fisik
     │                                                 ↓
     │                                      Admin update status "Selesai"
     │                                      di aplikasi
     └───────────────────────┬───────────────────────┘
                              ↓
              Warga ambil surat fisik / download versi final
                              ↓
              Otomatis tersimpan di Arsip Surat
```

### 7.2 Flow Pengajuan Surat — Fase 2 (Versi Lengkap, target lanjutan)

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

### 7.3 Flow Modul APBDes (Fase 1)

```
Bendahara input ringkasan APBDes (Pendapatan, Belanja, Pembiayaan)
        ↓
Sekretaris Desa review
        ↓
Kepala Desa approve & publish
        ↓
Tampil ke publik (warga akses tanpa login) — ringkasan + grafik
```

### 7.4 Flow Pengaduan Masyarakat (Fase 1, dengan Akses QR Code)

```
QR Code ditempel di titik strategis desa
(kantor desa, balai warga, papan pengumuman, dll)
        ↓
Warga scan QR pakai kamera HP → langsung terbuka
halaman "Form Pengaduan" di browser (tanpa install apapun)
        ↓
Login pakai NIK (atau opsi anonim, sesuai kebijakan desa)
        ↓
Isi form pengaduan (kategori, foto, deskripsi, lokasi)
        ↓
Masuk ke dashboard Admin sesuai kategori
        ↓
Status: Diterima → Diproses → Selesai
        ↓
Warga cek balik status pengaduannya via website
        ↓
Notifikasi ke warga saat status berubah (in-app di Fase 1, WhatsApp di Fase 2)
```

*Catatan teknis: QR Code cukup berisi URL statis ke halaman `/pengaduan/buat` — tidak perlu QR dinamis khusus, karena tujuannya murni mempercepat akses tanpa warga harus mengetik alamat website secara manual.*

### 7.5 Flow Reservasi Wisata (Fase 3, untuk referensi jangka panjang)

```
Pengunjung pilih destinasi/paket wisata
        ↓
Isi data reservasi + jadwal kunjungan
        ↓
Konfirmasi admin/Pokdarwis
        ↓
QR Tiket diterbitkan
        ↓
Scan QR saat kunjungan → status selesai
```

---

## 8. Struktur Menu Sidebar (Final, Bertahap)

```text
Dashboard

Administrasi Desa                 [Fase 1–2]
├── Data Penduduk                 [Fase 1]
├── Data Keluarga                 [Fase 1]
├── Pelayanan Surat                [Fase 1 → lengkap Fase 2]
├── Pengaduan                      [Fase 1 → lokasi peta Fase 4]
├── Pengumuman                     [Fase 1]
└── Agenda Pelayanan               [Fase 2]

Keuangan Desa (APBDes)            [Fase 1–2]
├── Ringkasan APBDes               [Fase 1]
├── Pendapatan & Belanja           [Fase 1]
├── Realisasi vs Rencana           [Fase 2]
├── Riwayat/Revisi APBDes          [Fase 2]
└── Laporan (PDF/Excel)            [Fase 1–2]

Desa Wisata                        [Fase 3]
├── Destinasi
├── Paket Wisata & Reservasi
├── Event
└── Galeri

UMKM                                [Fase 3]

Pertanian                           [Fase 4]

Peternakan                          [Fase 4]

Peta Desa (GIS)                     [Fase 4]

Arsip Desa                          [Fase 4]

Manajemen Pengguna                  [Fase 1 dasar → lengkap Fase 4]

Asisten AI & Fitur Cerdas           [Fase 5]

Pengaturan                          [Semua fase]
```

---

## 9. Kebutuhan Non-Fungsional (Berlaku Semua Fase)

| Aspek | Kebutuhan |
|---|---|
| Keamanan | Enkripsi data NIK/KK, log akses, kebijakan privasi jelas |
| Performa | Waktu respons < 2 detik pada koneksi standar desa |
| Ketersediaan | Mode hybrid — petugas bisa bantu input untuk warga yang tidak melek digital |
| Skalabilitas | Struktur modular, fase baru tidak mengubah struktur inti yang sudah berjalan |
| Kompatibilitas | Mobile-first untuk warga, desktop-first untuk admin |
| Legalitas | Disahkan melalui SK Kepala Desa agar sistem punya dasar hukum operasional |
| Kesesuaian Regulasi | APBDes mengikuti Permendagri 20/2018, SKM mengikuti Permenpan RB, tidak menggantikan SISKEUDES |

---

## 10. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| Waktu 1 bulan tidak cukup untuk semua Fase 1 | Prioritaskan urutan 1.1 → 1.7 sesuai nomor; jika mepet, modul 1.5–1.7 boleh disederhanakan lebih lanjut |
| Warga lansia/tidak melek digital | Mode dibantu petugas di kantor desa |
| Perangkat desa belum terbiasa sistem baru | Pelatihan singkat + manual pengguna bergambar |
| APBDes disalahpahami sebagai sistem akuntansi resmi | Tegaskan sebagai portal transparansi, data bersumber dari SISKEUDES yang sudah final |
| Sistem tidak dilanjutkan setelah KKN | Dokumentasi teknis lengkap + draf usulan SK Kepala Desa + roadmap Fase 2–5 sebagai panduan tim berikutnya |
| Scope Fase 3–5 dianggap janji yang harus dipenuhi KKN ini | Sampaikan eksplisit ke dosen/Kades bahwa Fase 3–5 adalah **visi jangka panjang**, bukan target KKN saat ini |

---

## 11. Kesimpulan

Dokumen ini menyatukan seluruh ide dari brainstorming SIPANDA maupun SRS "Puspamukti Smart Village" tanpa menghilangkan satupun fitur — hanya memetakannya ke fase yang realistis. **Fase 1 adalah komitmen KKN** (administrasi pelayanan + APBDes transparansi, sesuai permintaan eksplisit Kepala Desa), sementara **Fase 2–5 adalah peta jalan** yang membuktikan bahwa proyek ini punya visi jangka panjang dan layak dilanjutkan — baik oleh KKN berikutnya, mahasiswa magang, maupun operator desa sendiri.

Pendekatan bertahap ini menjadikan presentasi ke dosen pembimbing dan perangkat desa lebih meyakinkan: **bukan janji besar tanpa bukti, tapi sistem kecil yang benar-benar selesai dan terpakai, dengan arah pengembangan yang jelas.**
