<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PendudukController extends Controller
{
    public function index(Request $request): View
    {
        // Query base dengan eager loading
        $query = Penduduk::with('keluarga');
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }
        
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }
        
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }
        
        if ($request->filled('status_perkawinan')) {
            $query->where('status_perkawinan', $request->status_perkawinan);
        }
        
        if ($request->filled('pendidikan')) {
            $query->where('pendidikan_terakhir', $request->pendidikan);
        }
        
        if ($request->filled('agama')) {
            $query->where('agama', $request->agama);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSort = ['nik', 'nama', 'tanggal_lahir', 'rt', 'rw', 'created_at'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }
        
        // Get filter lists for dropdowns
        $rtList = Penduduk::select('rt')->distinct()->whereNotNull('rt')->orderBy('rt')->pluck('rt');
        $rwList = Penduduk::select('rw')->distinct()->whereNotNull('rw')->orderBy('rw')->pluck('rw');
        
        $penduduk = $query->paginate(15)->withQueryString();
        
        return view('admin.penduduk.index', compact('penduduk', 'rtList', 'rwList'));
    }

    public function create(): View
    {
        $keluargaList = Keluarga::all();
        return view('admin.penduduk.create', compact('keluargaList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'unique:penduduk,nik'],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:3'],
            'rw' => ['nullable', 'string', 'max:3'],
            'agama' => ['nullable', 'string', 'max:50'],
            'status_perkawinan' => ['nullable', 'string', 'max:50'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'kewarganegaraan' => ['nullable', 'string', 'max:50'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'no_kk' => ['nullable', 'string', 'size:16'],
            'hubungan_keluarga' => ['nullable', 'string', 'max:50'],
            'keluarga_id' => ['nullable', 'exists:keluarga,id'],
        ]);

        Penduduk::create($request->all());

        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil ditambahkan');
    }

    public function edit(Penduduk $penduduk): View
    {
        $keluargaList = Keluarga::all();
        $users = User::whereDoesntHave('penduduk')->orWhereHas('penduduk', fn($q) => $q->where('id', $penduduk->id))->get();
        return view('admin.penduduk.edit', compact('penduduk', 'keluargaList', 'users'));
    }

    public function update(Request $request, Penduduk $penduduk): RedirectResponse
    {
        $request->validate([
            'nik' => ['required', 'string', 'size:16', 'unique:penduduk,nik,' . $penduduk->id],
            'nama' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:3'],
            'rw' => ['nullable', 'string', 'max:3'],
            'agama' => ['nullable', 'string', 'max:50'],
            'status_perkawinan' => ['nullable', 'string', 'max:50'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'kewarganegaraan' => ['nullable', 'string', 'max:50'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'no_kk' => ['nullable', 'string', 'size:16'],
            'hubungan_keluarga' => ['nullable', 'string', 'max:50'],
            'keluarga_id' => ['nullable', 'exists:keluarga,id'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $penduduk->update($request->all());

        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil diperbarui');
    }

    public function destroy(Penduduk $penduduk): RedirectResponse
    {
        $penduduk->delete();
        return redirect()->route('admin.penduduk.index')->with('success', 'Data penduduk berhasil dihapus');
    }

    public function import(): View
    {
        return view('admin.penduduk.import');
    }

    public function importStore(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (empty($rows) || count($rows) < 2) {
            return back()->with('error', 'File Excel kosong atau tidak memiliki data');
        }

        $headers = array_map('strtolower', $rows[0]);
        $fillable = (new Penduduk)->getFillable();
        $imported = 0;
        $errors = [];

        foreach (array_slice($rows, 1) as $index => $row) {
            $data = array_combine($headers, $row);

            $data = array_filter($data, fn($key) => in_array($key, $fillable), ARRAY_FILTER_USE_KEY);

            if (empty($data['nik'])) {
                $errors[] = "Baris " . ($index + 2) . ": NIK kosong, dilewati";
                continue;
            }

            if (Penduduk::where('nik', $data['nik'])->exists()) {
                $errors[] = "Baris " . ($index + 2) . ": NIK {$data['nik']} sudah ada, dilewati";
                continue;
            }

            try {
                Penduduk::create($data);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        $message = "Berhasil mengimpor {$imported} data penduduk.";
        if (!empty($errors)) {
            $message .= " " . count($errors) . " baris dilewati.";
            \Illuminate\Support\Facades\Log::warning('Import penduduk errors', $errors);
        }

        return redirect()->route('admin.penduduk.index')->with('success', $message);
    }
}
