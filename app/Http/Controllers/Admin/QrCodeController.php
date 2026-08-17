<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\RtQrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class QrCodeController extends Controller
{
    private function qrFolder(): string
    {
        return 'qr-codes';
    }

    public function index()
    {
        // Pastikan RT 01 sampai RT 19 terdaftar di database & QR Code-nya ter-generate otomatis
        for ($i = 1; $i <= 19; $i++) {
            $rtStr = str_pad($i, 2, '0', STR_PAD_LEFT);
            $qr = RtQrCode::firstOrCreate(
                ['rt' => $rtStr],
                [
                    'rw' => '01',
                    'nama_rt' => "RT {$rtStr} Desa Puspamukti",
                    'deskripsi' => "Akses Layanan Publik RT {$rtStr}",
                    'status' => 'aktif',
                    'created_by' => auth()->id(),
                ]
            );

            // Auto-generate QR code image if not generated yet
            if (!$qr->qr_code_path || !Storage::disk('public')->exists($qr->qr_code_path)) {
                $this->generateForRecord($qr);
            }
        }

        $qrCodes = RtQrCode::orderByRaw('CAST(rt AS UNSIGNED) ASC')
            ->get()
            ->keyBy(fn($item) => str_pad($item->rt, 2, '0', STR_PAD_LEFT));

        $list = collect();
        for ($i = 1; $i <= 19; $i++) {
            $rtStr = str_pad($i, 2, '0', STR_PAD_LEFT);
            $qr = $qrCodes->get($rtStr);

            $pendudukCount = Penduduk::where('rt', $rtStr)->orWhere('rt', (string)$i)->count();
            $pengaduanCount = Pengaduan::where('rt', $rtStr)->orWhere('rt', (string)$i)->count();

            $qrImage = null;
            if ($qr?->qr_code_path && Storage::disk('public')->exists($qr->qr_code_path)) {
                $qrImage = asset('storage/' . $qr->qr_code_path) . '?v=' . ($qr->tanggal_generate?->timestamp ?? time());
            }

            $list->push([
                'rt' => $rtStr,
                'rw' => $qr?->rw ?? '01',
                'nama_rt' => $qr?->nama_rt ?? "RT {$rtStr} Desa Puspamukti",
                'deskripsi' => $qr?->deskripsi,
                'url' => route('warga.rt.landing', ['rt' => $rtStr]),
                'pengaduan_count' => $pengaduanCount,
                'penduduk_count' => $pendudukCount,
                'scan_count' => $qr?->scan_count ?? 0,
                'status' => $qr?->status ?? 'aktif',
                'qr' => $qr,
                'qr_image' => $qrImage,
                'tanggal_generate' => $qr?->tanggal_generate,
            ]);
        }

        return view('admin.qr-codes.index', compact('list'));
    }

    public function create()
    {
        return view('admin.qr-codes.form', ['rtQrCode' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        // Cek duplikat kombinasi rt-rw
        $exists = RtQrCode::where('rt', $data['rt'])->where('rw', $data['rw'])->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['rt' => "RT {$data['rt']} / RW {$data['rw']} sudah terdaftar."]);
        }

        $rtQrCode = RtQrCode::create([
            'rt' => $data['rt'],
            'rw' => $data['rw'],
            'nama_rt' => $data['nama_rt'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => $data['status'],
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.qr-links.index')
            ->with('success', "Wilayah RT {$rtQrCode->rt} / RW {$rtQrCode->rw} berhasil ditambahkan.");
    }

    public function edit(RtQrCode $rtQrCode)
    {
        return view('admin.qr-codes.form', ['rtQrCode' => $rtQrCode]);
    }

    public function update(Request $request, RtQrCode $rtQrCode)
    {
        $data = $this->validated($request);

        $exists = RtQrCode::where('rt', $data['rt'])
            ->where('rw', $data['rw'])
            ->where('id', '!=', $rtQrCode->id)
            ->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['rt' => "RT {$data['rt']} / RW {$data['rw']} sudah terdaftar pada wilayah lain."]);
        }

        // Jika RT/RW berubah, QR lama tidak relevan lagi -> hapus gambar
        if ($rtQrCode->rt !== $data['rt'] || $rtQrCode->rw !== $data['rw']) {
            $this->deleteQrImage($rtQrCode);
            $rtQrCode->qr_code_path = null;
        }

        $rtQrCode->rt = $data['rt'];
        $rtQrCode->rw = $data['rw'];
        $rtQrCode->nama_rt = $data['nama_rt'];
        $rtQrCode->deskripsi = $data['deskripsi'] ?? null;
        $rtQrCode->status = $data['status'];
        $rtQrCode->save();

        return redirect()
            ->route('admin.qr-links.index')
            ->with('success', "Wilayah RT {$rtQrCode->rt} / RW {$rtQrCode->rw} berhasil diperbarui.");
    }

    public function destroy(RtQrCode $rtQrCode)
    {
        $label = "RT {$rtQrCode->rt} / RW {$rtQrCode->rw}";
        $this->deleteQrImage($rtQrCode);
        $rtQrCode->delete();

        return back()->with('success', "Wilayah {$label} dan QR-nya berhasil dihapus.");
    }

    public function generate(RtQrCode $rtQrCode)
    {
        $this->generateForRecord($rtQrCode);

        return back()->with('success', "QR Code untuk RT {$rtQrCode->rt} / RW {$rtQrCode->rw} berhasil dibuat.");
    }

    public function generateByRtRw(Request $request, $rt, $rw)
    {
        if (!preg_match('/^\d{1,3}$/', $rt) || !preg_match('/^\d{1,3}$/', $rw)) {
            return back()->with('error', 'Format RT/RW tidak valid.');
        }

        $rtQrCode = RtQrCode::firstOrCreate(
            ['rt' => $rt, 'rw' => $rw],
            ['nama_rt' => "RT $rt RW $rw", 'status' => 'aktif', 'created_by' => auth()->id()]
        );

        $this->generateForRecord($rtQrCode);

        return back()->with('success', "QR Code untuk RT {$rtQrCode->rt} berhasil dibuat.");
    }

    private function generateForRecord(RtQrCode $rtQrCode): void
    {
        $url = route('warga.rt.landing', ['rt' => $rtQrCode->rt]);

        $writer = new PngWriter();
        $qr = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 600,
            margin: 20,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255),
        );

        $filename = 'rt-' . $rtQrCode->rt . '-rw-' . $rtQrCode->rw . '.png';
        $path = $this->qrFolder() . '/' . $filename;

        Storage::disk('public')->makeDirectory($this->qrFolder());
        $result = $writer->write($qr);
        $result->saveToFile(Storage::disk('public')->path($path));

        $rtQrCode->qr_code_path = $path;
        $rtQrCode->qr_code_url = $url;
        $rtQrCode->tanggal_generate = now();
        $rtQrCode->save();
    }

    public function download(RtQrCode $rtQrCode)
    {
        if (!$rtQrCode->qr_code_path || !Storage::disk('public')->exists($rtQrCode->qr_code_path)) {
            return redirect()->route('admin.qr-links.index')->with('error', 'QR belum dibuat. Silakan generate terlebih dahulu.');
        }

        $rtPadded = str_pad($rtQrCode->rt, 2, '0', STR_PAD_LEFT);

        return Storage::disk('public')->download(
            $rtQrCode->qr_code_path,
            "QR RT {$rtPadded}.png"
        );
    }

    public function cetak()
    {
        $qrCodes = RtQrCode::with('createdBy')
            ->whereNotNull('qr_code_path')
            ->active()
            ->orderBy('rt')
            ->get()
            ->filter(fn($item) => $item->qr_code_path && Storage::disk('public')->exists($item->qr_code_path));

        return view('admin.qr-codes.cetak', compact('qrCodes'));
    }

    public function toggleStatus(Request $request, $rt, $rw = '01')
    {
        $rw = $rw ?: '01';
        $qrCode = RtQrCode::firstOrCreate(
            ['rt' => $rt],
            ['rw' => '01', 'nama_rt' => "RT $rt Desa Puspamukti", 'status' => 'aktif', 'created_by' => auth()->id()]
        );

        $qrCode->status = $qrCode->status === 'aktif' ? 'nonaktif' : 'aktif';
        $qrCode->save();

        return back()->with('success', "QR RT $rt kini " . ($qrCode->status === 'aktif' ? 'aktif' : 'nonaktif'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'rt' => ['required', 'string', 'max:3', 'regex:/^\d{1,3}$/'],
            'rw' => ['required', 'string', 'max:3', 'regex:/^\d{1,3}$/'],
            'nama_rt' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ]);
    }

    private function deleteQrImage(RtQrCode $rtQrCode): void
    {
        if ($rtQrCode->qr_code_path && Storage::disk('public')->exists($rtQrCode->qr_code_path)) {
            Storage::disk('public')->delete($rtQrCode->qr_code_path);
        }
    }
}