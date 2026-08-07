<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cetak QR Code Wilayah - SILAPU Puspamukti</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: #111;
            padding: 32px;
        }
        .toolbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            display: flex; align-items: center; justify-content: space-between;
            background: #f3f0fa; border-bottom: 1px solid #ddd;
            padding: 12px 24px; z-index: 10;
        }
        .toolbar h1 { font-size: 16px; }
        .toolbar button {
            background: #4c1d95; color: #fff; border: none;
            padding: 10px 20px; border-radius: 999px; font-weight: 700; cursor: pointer;
        }
        .toolbar a { color: #4c1d95; font-weight: 600; text-decoration: none; }
        .grid {
            margin-top: 64px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .card {
            border: 1px solid #e2e2e2;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .card img { width: 180px; height: 180px; object-fit: contain; }
        .card .label { font-size: 14px; font-weight: 700; text-align: center; }
        .card .sub { font-size: 11px; color: #666; text-align: center; }
        .card .link { font-size: 9px; color: #999; word-break: break-all; text-align: center; }
        .empty {
            margin-top: 64px; text-align: center; color: #666; font-size: 14px; padding: 40px;
        }
        @media print {
            .toolbar { display: none; }
            .grid { margin-top: 0; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <h1>QR Code Wilayah - SILAPU Puspamukti</h1>
        <div>
            <a href="{{ route('admin.qr-links.index') }}">&larr; Kembali</a>
            <button onclick="window.print()">Cetak</button>
        </div>
    </div>

    @if ($qrCodes->isEmpty())
        <div class="empty">Belum ada QR Code yang dibuat. Silakan generate QR terlebih dahulu di halaman QR &amp; Link Wilayah.</div>
    @else
        <div class="grid">
            @foreach ($qrCodes as $qrCode)
                <div class="card">
                    <div class="label">RT {{ $qrCode->rt }} / RW {{ $qrCode->rw }}</div>
                    <div class="sub">{{ $qrCode->nama_rt ?? 'Wilayah RT' }}</div>
                    <img src="{{ Storage::url($qrCode->qr_code_path) }}" alt="QR RT {{ $qrCode->rt }} RW {{ $qrCode->rw }}">
                    <div class="link">{{ $qrCode->qr_code_url }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        if ({{ $qrCodes->isEmpty() ? 'true' : 'false' }}) {
            document.querySelector('.toolbar button').style.display = 'none';
        }
    </script>
</body>
</html>