<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\PendudukController;
use Illuminate\Support\Facades\Auth;

class SimulateWebAddPenduduk extends Command
{
    protected $signature = 'app:simulate-penduduk';
    protected $description = 'Simulate full web controller request for Penduduk store & update';

    public function handle()
    {
        $this->info('=== SIMULASI TAMBAH DATA PENDUDUK (WEB CONTROLLER) ===');
        
        $admin = User::where('email', 'admin@puspamukti.local')->first() 
              ?? User::where('email', 'admindesa@puspamukti.local')->first()
              ?? User::first();

        if (!$admin) {
            $this->error('Admin user tidak ditemukan!');
            return 1;
        }

        Auth::login($admin);
        $this->info('Logged in as: ' . $admin->name . ' (' . $admin->email . ')');

        $controller = app()->make(PendudukController::class);

        // --- TEST 1: TAMBAH DATA PENDUDUK BARU ---
        $testNik = '320101' . rand(1000000000, 9999999999);
        $testNama = 'Asep Testing Mandiri ' . rand(100, 999);
        $testNoKk = '320101' . rand(1000000000, 9999999999);

        $formData = [
            'nik' => $testNik,
            'nama' => $testNama,
            'no_kk' => $testNoKk,
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Tasikmalaya',
            'tanggal_lahir' => '1992-04-12',
            'alamat' => 'Jl. Sukalaya No. ' . rand(1, 100),
            'rt' => '03',
            'rw' => '01',
            'agama' => 'Islam',
            'status_perkawinan' => 'Kawin',
            'pendidikan_terakhir' => 'D1/D2/D3',
            'pekerjaan' => 'Wiraswasta',
            'kewarganegaraan' => 'WNI',
        ];

        $this->info("\nMengirim Request Store (AJAX POST) data: " . $testNama . " (NIK: " . $testNik . ")");
        
        $request = Request::create('/admin/penduduk', 'POST', $formData);
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->setUserResolver(fn() => $admin);

        try {
            $response = $controller->store($request);
            $statusCode = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 200;
            $content = method_exists($response, 'getContent') ? $response->getContent() : json_encode($response);
            
            $this->info("HTTP Status Response: " . $statusCode);
            $this->line("Response Body: " . $content);
            
            $saved = Penduduk::where('nik', $testNik)->first();
            if ($saved) {
                $this->info("✔ SUKSES: Data berhasil tersimpan di database SQLite (ID: {$saved->id}, NIK: {$saved->nik}, Nama: {$saved->nama})");
            } else {
                $this->error("✖ GAGAL: Data tidak ditemukan di database!");
            }
        } catch (\Illuminate\Validation\ValidationException $ve) {
            $this->error("Validation Error: " . json_encode($ve->errors()));
        } catch (\Exception $e) {
            $this->error("Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        // --- TEST 2: VALIDASI DUPLIKAT NIK ---
        $this->info("\n--- TEST 2: MENGUJI VALIDASI NIK DUPLIKAT ---");
        $duplicateRequest = Request::create('/admin/penduduk', 'POST', $formData);
        $duplicateRequest->headers->set('Accept', 'application/json');
        $duplicateRequest->setUserResolver(fn() => $admin);

        try {
            $controller->store($duplicateRequest);
            $this->error("✖ GAGAL: Seharusnya validasi duplikat NIK menolak request!");
        } catch (\Illuminate\Validation\ValidationException $ve) {
            $this->info("✔ SUKSES: Validasi duplikat NIK bekerja dengan benar -> " . json_encode($ve->errors()));
        }

        $this->info("\n=== SEMUA TES SELESAI DENGAN BAIK ===");
        return 0;
    }
}
