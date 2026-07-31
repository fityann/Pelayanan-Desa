<?php

namespace Database\Seeders;

use App\Models\Keluarga;
use Illuminate\Database\Seeder;

class KeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $keluarga = [
            [
                'no_kk' => '3206060101010001',
                'kepala_keluarga' => 'Warga Contoh',
                'alamat' => 'Kp. Cigalontang RT 01 RW 01',
                'rt' => '01',
                'rw' => '01',
                'desa' => 'Puspamukti',
                'kecamatan' => 'Cigalontang',
                'kabupaten' => 'Tasikmalaya',
                'provinsi' => 'Jawa Barat',
            ],
            [
                'no_kk' => '3206060101010002',
                'kepala_keluarga' => 'Suryana',
                'alamat' => 'Kp. Cigalontang RT 01 RW 01',
                'rt' => '01',
                'rw' => '01',
                'desa' => 'Puspamukti',
                'kecamatan' => 'Cigalontang',
                'kabupaten' => 'Tasikmalaya',
                'provinsi' => 'Jawa Barat',
            ],
            [
                'no_kk' => '3206060101010003',
                'kepala_keluarga' => 'Dadang Kosasih',
                'alamat' => 'Kp. Bojong RT 02 RW 01',
                'rt' => '02',
                'rw' => '01',
                'desa' => 'Puspamukti',
                'kecamatan' => 'Cigalontang',
                'kabupaten' => 'Tasikmalaya',
                'provinsi' => 'Jawa Barat',
            ],
            [
                'no_kk' => '3206060101020001',
                'kepala_keluarga' => 'Entin Sutisna',
                'alamat' => 'Kp. Margalaksana RT 01 RW 02',
                'rt' => '01',
                'rw' => '02',
                'desa' => 'Puspamukti',
                'kecamatan' => 'Cigalontang',
                'kabupaten' => 'Tasikmalaya',
                'provinsi' => 'Jawa Barat',
            ],
        ];

        foreach ($keluarga as $data) {
            Keluarga::firstOrCreate(['no_kk' => $data['no_kk']], $data);
        }

        $this->command->info('Data keluarga berhasil dibuat');
    }
}
