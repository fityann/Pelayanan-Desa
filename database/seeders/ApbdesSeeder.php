<?php

namespace Database\Seeders;

use App\Models\Apbde;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApbdesSeeder extends Seeder
{
    public function run(): void
    {
        $bendahara = User::where('email', 'bendahara@puspamukti.local')->first();
        $sekdes = User::where('email', 'sekdes@puspamukti.local')->first();
        $kades = User::where('email', 'kepaladesa@puspamukti.local')->first();

        $data = [
            // === PENDAPATAN ===
            [
                'kategori' => 'Pendapatan',
                'bidang' => null,
                'sub_bidang' => 'PADes',
                'uraian' => 'Pendapatan Asli Desa (PADes)',
                'anggaran' => 75000000,
                'realisasi' => 62000000,
            ],
            [
                'kategori' => 'Pendapatan',
                'bidang' => null,
                'sub_bidang' => 'Dana Desa',
                'uraian' => 'Dana Desa (APBN)',
                'anggaran' => 900000000,
                'realisasi' => 850000000,
            ],
            [
                'kategori' => 'Pendapatan',
                'bidang' => null,
                'sub_bidang' => 'BHP DR',
                'uraian' => 'Bagian Hasil Pajak dan Retribusi',
                'anggaran' => 120000000,
                'realisasi' => 115000000,
            ],
            [
                'kategori' => 'Pendapatan',
                'bidang' => null,
                'sub_bidang' => 'ADD',
                'uraian' => 'Alokasi Dana Desa (ADD)',
                'anggaran' => 350000000,
                'realisasi' => 350000000,
            ],
            [
                'kategori' => 'Pendapatan',
                'bidang' => null,
                'sub_bidang' => 'Bantuan Keuangan',
                'uraian' => 'Bantuan Keuangan Provinsi & Kabupaten',
                'anggaran' => 150000000,
                'realisasi' => 140000000,
            ],

            // === BELANJA ===
            [
                'kategori' => 'Belanja',
                'bidang' => 'Penyelenggaraan Pemerintahan Desa',
                'sub_bidang' => 'Penghasilan Tetap',
                'uraian' => 'Penghasilan tetap dan tunjangan perangkat desa',
                'anggaran' => 480000000,
                'realisasi' => 470000000,
            ],
            [
                'kategori' => 'Belanja',
                'bidang' => 'Penyelenggaraan Pemerintahan Desa',
                'sub_bidang' => 'Operasional',
                'uraian' => 'Operasional perkantoran dan pelayanan administrasi',
                'anggaran' => 95000000,
                'realisasi' => 88000000,
            ],
            [
                'kategori' => 'Belanja',
                'bidang' => 'Pelaksanaan Pembangunan Desa',
                'sub_bidang' => 'Prasarana',
                'uraian' => 'Pembangunan/rehabilitasi jalan usaha tani',
                'anggaran' => 220000000,
                'realisasi' => 210000000,
            ],
            [
                'kategori' => 'Belanja',
                'bidang' => 'Pelaksanaan Pembangunan Desa',
                'sub_bidang' => 'Prasarana',
                'uraian' => 'Rehabilitasi saluran irigasi tersier',
                'anggaran' => 130000000,
                'realisasi' => 125000000,
            ],
            [
                'kategori' => 'Belanja',
                'bidang' => 'Pembinaan Kemasyarakatan',
                'sub_bidang' => 'Bidang Keagamaan',
                'uraian' => 'Pembinaan keagamaan dan bantuan masjid',
                'anggaran' => 50000000,
                'realisasi' => 45000000,
            ],
            [
                'kategori' => 'Belanja',
                'bidang' => 'Pemberdayaan Masyarakat',
                'sub_bidang' => 'UMKM',
                'uraian' => 'Penguatan modal dan pelatihan UMKM desa',
                'anggaran' => 60000000,
                'realisasi' => 40000000,
            ],
            [
                'kategori' => 'Belanja',
                'bidang' => 'Penanggulangan Bencana, Darurat, dan Mendesak',
                'sub_bidang' => 'Tanggap Darurat',
                'uraian' => 'Bantuan tanggap darurat bencana',
                'anggaran' => 30000000,
                'realisasi' => 22000000,
            ],

            // === PEMBIAYAAN ===
            [
                'kategori' => 'Pembiayaan',
                'bidang' => 'Penerimaan Pembiayaan',
                'sub_bidang' => 'SiLPA',
                'uraian' => 'Sisa lebih perhitungan anggaran tahun sebelumnya (SiLPA)',
                'anggaran' => 45000000,
                'realisasi' => 45000000,
            ],
            [
                'kategori' => 'Pembiayaan',
                'bidang' => 'Pengeluaran Pembiayaan',
                'sub_bidang' => 'Penyertaan Modal',
                'uraian' => 'Penyertaan modal BUMDesa',
                'anggaran' => 25000000,
                'realisasi' => 25000000,
            ],
        ];

        foreach ($data as $item) {
            Apbde::firstOrCreate(
                ['kategori' => $item['kategori'], 'uraian' => $item['uraian'], 'tahun' => '2026'],
                [
                    'tahun' => '2026',
                    'bidang' => $item['bidang'],
                    'sub_bidang' => $item['sub_bidang'],
                    'anggaran' => $item['anggaran'],
                    'realisasi' => $item['realisasi'],
                    'status' => 'dipublikasikan',
                    'created_by' => $bendahara?->id,
                    'reviewed_by' => $sekdes?->id,
                    'published_by' => $kades?->id,
                    'tanggal_publikasi' => now()->startOfYear(),
                ]
            );
        }

        $this->command->info('Data APBDes 2026 berhasil dibuat');
    }
}
