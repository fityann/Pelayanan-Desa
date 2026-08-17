<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Validator;

class TestPenduduk extends Command
{
    protected $signature = 'app:test-penduduk';
    protected $description = 'Test insert penduduk';

    public function handle()
    {
        $data = [
            'nik' => '3201010101010199',
            'nama' => 'Test Orang Baru',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Tasikmalaya',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Jl. Test No. 1',
            'rt' => '3',
            'rw' => '1',
            'agama' => 'Islam',
            'status_perkawinan' => 'Belum Kawin',
            'pendidikan_terakhir' => 'D1/D2/D3',
            'pekerjaan' => 'Pekerjaan',
            'kewarganegaraan' => 'WNI',
        ];

        try {
            $penduduk = Penduduk::create($data);
            $this->info('Penduduk berhasil dibuat: ' . $penduduk->id);
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
