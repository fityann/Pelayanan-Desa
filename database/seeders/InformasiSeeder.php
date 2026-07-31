<?php

namespace Database\Seeders;

use App\Models\Informasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class InformasiSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admindesa@puspamukti.local')->first();
        $kades = User::where('email', 'kepaladesa@puspamukti.local')->first();

        $informasi = [
            [
                'judul' => 'Pelayanan Surat Kini Bisa Via Aplikasi SIPANDA',
                'isi' => 'Warga Desa Puspamukti kini dapat mengajukan surat keterangan (domisili, usaha, SKTM, pengantar nikah, dan kematian) melalui aplikasi SIPANDA tanpa harus datang ke kantor desa. Silakan login dan ajukan surat melalui menu Pelayanan Surat.',
                'kategori' => 'pengumuman',
                'published' => true,
                'user_id' => $admin?->id,
                'published_at' => now()->subDays(7),
            ],
            [
                'judul' => 'Gotong Royong Bersih Desa Bulanan',
                'isi' => 'Kegiatan gotong royong bersih desa akan dilaksanakan di seluruh RT. Seluruh warga diharapkan hadir dan membawa peralatan kebersihan masing-masing.',
                'kategori' => 'agenda',
                'published' => true,
                'tanggal_kegiatan' => now()->addDays(5),
                'lokasi' => 'Seluruh RT Desa Puspamukti',
                'user_id' => $kades?->id,
                'published_at' => now()->subDays(2),
            ],
            [
                'judul' => 'APBDes 2026 Desa Puspamukti Dipublikasikan',
                'isi' => 'Ringkasan APBDes tahun 2026 telah dipublikasikan untuk transparansi anggaran desa. Warga dapat melihat rincian pendapatan, belanja, dan pembiayaan melalui menu Keuangan Desa.',
                'kategori' => 'berita',
                'published' => true,
                'user_id' => $admin?->id,
                'published_at' => now()->subDays(10),
            ],
        ];

        foreach ($informasi as $data) {
            Informasi::firstOrCreate(['judul' => $data['judul']], $data);
        }

        $this->command->info('Data informasi desa berhasil dibuat');
    }
}
