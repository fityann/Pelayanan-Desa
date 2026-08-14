<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan APBDes {{ $tahun }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 16px; font-weight: bold; }
        .subtitle { font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN REALISASI PELAKSANAAN ANGGARAN PENDAPATAN DAN BELANJA DESA (APBDes)</div>
        <div class="subtitle">PEMERINTAH DESA PUSPAMUKTI</div>
        <div class="subtitle">TAHUN ANGGARAN {{ $tahun }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Kategori / Uraian</th>
                <th width="20%" class="text-right">Anggaran (Rp)</th>
                <th width="20%" class="text-right">Realisasi (Rp)</th>
                <th width="20%" class="text-center">Persentase (%)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($data as $kategori => $nilai)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td><strong>{{ strtoupper($kategori) }}</strong></td>
                <td class="text-right">{{ number_format($nilai['anggaran'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($nilai['realisasi'], 0, ',', '.') }}</td>
                <td class="text-center">{{ $nilai['persentase'] }} %</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; width: 100%;">
        <div style="float: right; width: 250px; text-align: center;">
            <p>Puspamukti, {{ date('d F Y') }}</p>
            <p>Kepala Desa Puspamukti</p>
            <br><br><br><br>
            <p><strong>( ______________________ )</strong></p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
