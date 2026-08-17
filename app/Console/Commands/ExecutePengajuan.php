<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Http\Controllers\Admin\SuratController;
use Illuminate\Http\Request;

class ExecutePengajuan extends Command
{
    protected $signature = 'app:execute-pengajuan';
    protected $description = 'Simulate running pengajuan from verifikasi to selesai';

    public function handle()
    {
        $controller = app()->make(SuratController::class);
        $request = Request::create('/', 'POST');

        $user = User::whereHas('roles', function($q) { $q->where('name', 'Kepala Desa'); })->first();
        auth()->login($user);

        $pengajuanList = PengajuanSurat::whereNotIn('status', ['selesai', 'ditolak', 'dibatalkan'])->get();
        
        if ($pengajuanList->isEmpty()) {
            $this->error("Tidak ada pengajuan aktif.");
            return;
        }

        foreach ($pengajuanList as $pengajuan) {
            $this->info("----------------------------------------");
            $this->info("Memproses pengajuan ID: {$pengajuan->id} dengan status: {$pengajuan->status}");

            if (in_array($pengajuan->status, ['diajukan', 'diajukan_warga'])) {
                $this->info("- Memverifikasi pengajuan...");
                $controller->verifikasi($pengajuan);
                $pengajuan->refresh();
            }

            if ($pengajuan->status === 'diverifikasi_admin') {
                $this->info("- Menyetujui pengajuan...");
                $controller->approve($request, $pengajuan);
                $pengajuan->refresh();
            }

            if ($pengajuan->status === 'menunggu_ttd_fisik') {
                $this->info("- Menyelesaikan pengajuan...");
                $controller->selesai($pengajuan);
                $pengajuan->refresh();
            }

            $this->info("-> Status akhir: {$pengajuan->status}");
        }

        $this->info("========================================");
        $this->info("Selesai memproses semua pengajuan.");
    }
}
