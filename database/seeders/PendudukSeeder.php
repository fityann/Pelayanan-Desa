<?php

namespace Database\Seeders;

use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Database\Seeder;

class PendudukSeeder extends Seeder
{
    public function run(): void
    {
        $keluarga1 = Keluarga::where('no_kk', '3206060101010001')->first();
        $keluarga2 = Keluarga::where('no_kk', '3206060101010002')->first();
        $keluarga3 = Keluarga::where('no_kk', '3206060101010003')->first();
        $keluarga4 = Keluarga::where('no_kk', '3206060101020001')->first();

        $warga1 = User::where('email', 'warga@puspamukti.local')->first();
        $warga2 = User::where('email', 'warga2@puspamukti.local')->first();

        $penduduk = [
            [
                'nik' => '3201010101010102',
                'nama' => 'Warga Contoh',
                'tempat_lahir' => 'Tasikmalaya',
                'tanggal_lahir' => '1990-01-15',
                'jenis_kelamin' => 'L',
                'alamat' => 'Kp. Cigalontang RT 01 RW 01',
                'rt' => '01',
                'rw' => '01',
                'agama' => 'Islam',
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => 'Wiraswasta',
                'pendidikan_terakhir' => 'SMA/Sederajat',
                'no_kk' => $keluarga1?->no_kk,
                'hubungan_keluarga' => 'Kepala Keluarga',
                'keluarga_id' => $keluarga1?->id,
                'user_id' => $warga1?->id,
            ],
            [
                'nik' => '3201010101010103',
                'nama' => 'Warga Dua',
                'tempat_lahir' => 'Tasikmalaya',
                'tanggal_lahir' => '1995-05-20',
                'jenis_kelamin' => 'P',
                'alamat' => 'Kp. Bojong RT 02 RW 01',
                'rt' => '02',
                'rw' => '01',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Kawin',
                'pekerjaan' => 'Petani',
                'pendidikan_terakhir' => 'SMP/Sederajat',
                'no_kk' => $keluarga3?->no_kk,
                'hubungan_keluarga' => 'Keluarga',
                'keluarga_id' => $keluarga3?->id,
                'user_id' => $warga2?->id,
            ],
            [
                'nik' => '3201010101010001',
                'nama' => 'Suryana',
                'tempat_lahir' => 'Tasikmalaya',
                'tanggal_lahir' => '1975-03-10',
                'jenis_kelamin' => 'L',
                'alamat' => 'Kp. Cigalontang RT 01 RW 01',
                'rt' => '01',
                'rw' => '01',
                'agama' => 'Islam',
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => 'Petani',
                'pendidikan_terakhir' => 'SD/Sederajat',
                'no_kk' => $keluarga2?->no_kk,
                'hubungan_keluarga' => 'Kepala Keluarga',
                'keluarga_id' => $keluarga2?->id,
            ],
            [
                'nik' => '3201010101010004',
                'nama' => 'Iis Sumiati',
                'tempat_lahir' => 'Tasikmalaya',
                'tanggal_lahir' => '1982-08-25',
                'jenis_kelamin' => 'P',
                'alamat' => 'Kp. Cigalontang RT 01 RW 01',
                'rt' => '01',
                'rw' => '01',
                'agama' => 'Islam',
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'pendidikan_terakhir' => 'SMA/Sederajat',
                'no_kk' => $keluarga2?->no_kk,
                'hubungan_keluarga' => 'Istri',
                'keluarga_id' => $keluarga2?->id,
            ],
            [
                'nik' => '3201010101010005',
                'nama' => 'Dadang Kosasih',
                'tempat_lahir' => 'Tasikmalaya',
                'tanggal_lahir' => '1970-12-05',
                'jenis_kelamin' => 'L',
                'alamat' => 'Kp. Bojong RT 02 RW 01',
                'rt' => '02',
                'rw' => '01',
                'agama' => 'Islam',
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => 'Buruh Tani',
                'pendidikan_terakhir' => 'SD/Sederajat',
                'no_kk' => $keluarga3?->no_kk,
                'hubungan_keluarga' => 'Kepala Keluarga',
                'keluarga_id' => $keluarga3?->id,
            ],
            [
                'nik' => '3201010101010006',
                'nama' => 'Entin Sutisna',
                'tempat_lahir' => 'Tasikmalaya',
                'tanggal_lahir' => '1985-06-18',
                'jenis_kelamin' => 'L',
                'alamat' => 'Kp. Margalaksana RT 01 RW 02',
                'rt' => '01',
                'rw' => '02',
                'agama' => 'Islam',
                'status_perkawinan' => 'Kawin',
                'pekerjaan' => 'Wiraswasta',
                'pendidikan_terakhir' => 'SMA/Sederajat',
                'no_kk' => $keluarga4?->no_kk,
                'hubungan_keluarga' => 'Kepala Keluarga',
                'keluarga_id' => $keluarga4?->id,
            ],
        ];

        foreach ($penduduk as $data) {
            Penduduk::firstOrCreate(['nik' => $data['nik']], $data);
        }

        $this->command->info('Data penduduk berhasil dibuat');
    }
}
