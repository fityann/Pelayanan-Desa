<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $surat = [
            [
                'kode' => 'SKD',
                'nama' => 'Surat Keterangan Domisili',
                'deskripsi' => 'Surat keterangan tempat tinggal/domisili untuk keperluan administrasi',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat pengantar RT/RW",
                'masa_berlaku' => 90,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKU',
                'nama' => 'Surat Keterangan Usaha',
                'deskripsi' => 'Surat keterangan untuk mengurus izin usaha atau keperluan perbankan',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Pas foto 3x4 (2 lembar)\n4. Keterangan usaha dari RT/RW",
                'masa_berlaku' => null,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKTM',
                'nama' => 'Surat Keterangan Tidak Mampu',
                'deskripsi' => 'Surat keterangan untuk mengurus bantuan sosial, beasiswa, atau keringanan biaya',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat pengantar RT/RW",
                'masa_berlaku' => 30,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SPN',
                'nama' => 'Surat Pengantar Nikah',
                'deskripsi' => 'Surat pengantar untuk pendaftaran pernikahan di KUA',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Pas foto 4x6 (4 lembar)\n4. Surat izin orang tua (jika diperlukan)",
                'masa_berlaku' => 60,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKematian',
                'nama' => 'Surat Keterangan Kematian',
                'deskripsi' => 'Surat keterangan untuk melaporkan dan mengurus administrasi kematian',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP almarhum/almarhumah\n3. Surat keterangan dari saksi/RT/RW",
                'masa_berlaku' => null,
                'butuh_ttd_fisik' => true,
            ],
        ];

        foreach ($surat as $s) {
            JenisSurat::firstOrCreate(['kode' => $s['kode']], $s);
        }

        $this->command->info('5 jenis surat berhasil dibuat');
    }
}
