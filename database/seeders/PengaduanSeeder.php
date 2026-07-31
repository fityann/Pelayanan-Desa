<?php

namespace Database\Seeders;

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $warga1 = User::where('email', 'warga@puspamukti.local')->first();
        $warga2 = User::where('email', 'warga2@puspamukti.local')->first();
        $admin = User::where('email', 'admindesa@puspamukti.local')->first();

        $pengaduan = [
            [
                'user_id' => $warga1?->id,
                'kategori' => 'Infrastruktur',
                'judul' => 'Jalan RT 01 rusak berlubang',
                'deskripsi' => 'Jalan di Kp. Cigalontang RT 01 RW 01 mengalami kerusakan berlubang cukup dalam dan membahayakan pengguna jalan.',
                'status' => 'diproses',
                'tanggapan' => 'Terima kasih atas laporannya. Saat ini sedang diusulkan untuk penanganan darurat.',
                'processed_by' => $admin?->id,
                'tanggal_diterima' => now()->subDays(5),
                'tanggal_diproses' => now()->subDays(3),
            ],
            [
                'user_id' => $warga2?->id,
                'kategori' => 'Kebersihan',
                'judul' => 'Tumpukan sampah di bantaran sungai',
                'deskripsi' => 'Terdapat tumpukan sampah rumah tangga di bantaran sungai Kp. Bojong yang perlu dibersihkan.',
                'status' => 'selesai',
                'tanggapan' => 'Sampah sudah dibersihkan bersama warga dan perangkat desa. Terima kasih atas partisipasinya.',
                'processed_by' => $admin?->id,
                'tanggal_diterima' => now()->subDays(10),
                'tanggal_diproses' => now()->subDays(8),
                'tanggal_selesai' => now()->subDays(2),
            ],
            [
                'user_id' => $warga1?->id,
                'kategori' => 'Pelayanan',
                'judul' => 'Sulit mendapatkan surat pengantar',
                'deskripsi' => 'Warga lansia mengalami kesulitan mengurus surat pengantar karena harus datang ke kantor desa.',
                'status' => 'diterima',
                'tanggal_diterima' => now()->subDay(),
            ],
        ];

        foreach ($pengaduan as $data) {
            Pengaduan::firstOrCreate(
                ['user_id' => $data['user_id'], 'judul' => $data['judul']],
                $data
            );
        }

        $this->command->info('Data pengaduan berhasil dibuat');
    }
}
