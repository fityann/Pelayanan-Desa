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
            [
                'kode' => 'SKBB',
                'nama' => 'Surat Keterangan Belum Bekerja',
                'deskripsi' => 'Surat keterangan yang menyatakan warga tersebut belum bekerja / menganggur',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat Pengantar RT/RW",
                'masa_berlaku' => 30,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKBL',
                'nama' => 'Surat Keterangan Beda Luas',
                'deskripsi' => 'Surat keterangan beda luas tanah/bangunan pada sertifikat dan SPPT',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Fotokopi SPPT PBB / Sertifikat Tanah\n4. Surat Pengantar RT/RW",
                'masa_berlaku' => null,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKG',
                'nama' => 'Surat Keterangan Ghoib',
                'deskripsi' => 'Surat keterangan yang menyatakan suami/istri telah pergi tanpa kabar (ghoib)',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat Pengantar RT/RW\n4. Surat Pernyataan dari yang bersangkutan (bermaterai)",
                'masa_berlaku' => null,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKN',
                'nama' => 'Surat Keterangan Nikah',
                'deskripsi' => 'Surat keterangan yang berkaitan dengan status pernikahan warga',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat Pengantar RT/RW\n4. Fotokopi Akta Nikah (Jika ada)",
                'masa_berlaku' => null,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKP',
                'nama' => 'Surat Keterangan Penghasilan',
                'deskripsi' => 'Surat keterangan rincian penghasilan untuk syarat pengajuan kredit, beasiswa, dll',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat Pengantar RT/RW\n4. Surat pernyataan penghasilan / Slip Gaji",
                'masa_berlaku' => 30,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKCerai',
                'nama' => 'Surat Keterangan Perceraian',
                'deskripsi' => 'Surat keterangan status cerai / proses perceraian warga',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat Pengantar RT/RW\n4. Fotokopi Akta Cerai (Bila Janda/Duda Cerai)",
                'masa_berlaku' => null,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKWali',
                'nama' => 'Surat Keterangan Wali',
                'deskripsi' => 'Surat keterangan perwalian untuk anak di bawah umur atau pernikahan',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP Wali & Yang Diwalikan\n3. Surat Pengantar RT/RW\n4. Surat Kematian Orang Tua (Jika Yatim/Piatu)",
                'masa_berlaku' => null,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKKB',
                'nama' => 'Surat Keterangan Kelakuan Baik',
                'deskripsi' => 'Surat keterangan kelakuan baik pengantar SKCK',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat Pengantar RT/RW\n4. Pas foto berwarna ukuran 4x6",
                'masa_berlaku' => 30,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKPensiun',
                'nama' => 'Surat Keterangan Pensiun',
                'deskripsi' => 'Surat keterangan status pensiunan warga',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat Pengantar RT/RW\n4. Fotokopi SK Pensiun",
                'masa_berlaku' => null,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SPPD',
                'nama' => 'Surat Permohonan Pembuatan',
                'deskripsi' => 'Surat pengantar untuk permohonan pembuatan dokumen (KTP, KK, Akta, dll)',
                'syarat' => "1. Fotokopi KK (Jika KK lama ada)\n2. Fotokopi KTP (Jika perpanjangan/rusak)\n3. Surat Pengantar RT/RW\n4. Surat Kehilangan Kepolisian (Bila hilang)",
                'masa_berlaku' => 30,
                'butuh_ttd_fisik' => true,
            ],
            [
                'kode' => 'SKBN',
                'nama' => 'Surat Keterangan Belum Nikah',
                'deskripsi' => 'Surat keterangan resmi yang menyatakan bahwa warga bersangkutan belum pernah menikah / kawin',
                'syarat' => "1. Fotokopi KK\n2. Fotokopi KTP\n3. Surat Pengantar RT/RW\n4. Surat Pernyataan Belum Pernah Menikah (Bermaterai 10.000)",
                'masa_berlaku' => 30,
                'butuh_ttd_fisik' => true,
            ],
        ];

        foreach ($surat as $s) {
            JenisSurat::firstOrCreate(['kode' => $s['kode']], $s);
        }

        $this->command->info('Semua jenis surat berhasil disinkronisasi');
    }
}
