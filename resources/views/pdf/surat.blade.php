<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan - SILAPU Desa Puspamukti</title>
    <style>
        @page {
            size: 210mm 297mm;
            margin: 0mm;
        }
        html, body {
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            color: #000000;
            line-height: 1.4;
            padding-top: 1.5cm;    /* Compact top margin to fit exactly on 1 page */
            padding-bottom: 1.2cm; /* Compact bottom margin */
            padding-left: 2.0cm;   /* Left margin: 2.0cm (Aman & Bebas Potong) */
            padding-right: 2.0cm;  /* Right margin: 2.0cm (Aman & Bebas Potong) */
            box-sizing: border-box;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }
        td, th {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .kop-container {
            position: relative;
            width: 100%;
            border-bottom: 3px double #000000;
            padding-bottom: 6px;
            margin-bottom: 12px;
            text-align: center;
        }
        .kop-logo {
            position: absolute;
            left: 0;
            top: 2px;
            width: 60px;
            height: auto;
        }
        .pemerintah {
            font-size: 11pt;
            letter-spacing: 1px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kecamatan {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 1px;
        }
        .desa {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }
        .alamat {
            font-size: 8pt;
            font-style: italic;
            color: #222222;
        }
        .perihal-container {
            text-align: center;
            margin: 12px 0 14px 0;
        }
        .perihal-title {
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .perihal-nomor {
            font-size: 10pt;
            margin-top: 2px;
        }
        .body-text p {
            margin-bottom: 8px;
            text-align: justify;
            text-indent: 28px;
        }
        .data-table {
            margin: 10px 0 12px 0;
        }
        .data-table td {
            padding: 2.5px 0;
            vertical-align: top;
            font-size: 10pt;
        }
        .ttd-box {
            margin-top: 18px;
            width: 100%;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .catatan-draft {
            margin-top: 20px;
            font-size: 8pt;
            color: #555555;
            border-top: 1px dashed #aaaaaa;
            padding-top: 6px;
            page-break-inside: avoid;
            break-inside: avoid;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-desa-puspamukti.jpg');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;

        $rawAlamat = $surat->pemohon_alamat ?: ($surat->user?->penduduk?->alamat ?? '');
        $cleanAlamat = trim(preg_replace('/(\/\s*RW\s*\d+)|(RW\s*\d+)|(RT\s*\d+\s*RW\s*\d+)/i', '', $rawAlamat));
        if (empty($cleanAlamat)) {
            $cleanAlamat = $rawAlamat;
        }
        $rtNumber = sprintf('%02d', $surat->user?->rt ?? $surat->user?->penduduk?->rt ?? '01');
        if (!str_contains(strtolower($cleanAlamat), 'rt')) {
            $cleanAlamat .= " RT $rtNumber";
        }
    @endphp

    <!-- Kop Surat Resmi Desa -->
    <div class="kop-container">
        @if($logoData)
            <img class="kop-logo" src="data:image/jpeg;base64,{{ $logoData }}">
        @endif
        <div class="pemerintah">PEMERINTAH KABUPATEN TASIKMALAYA</div>
        <div class="kecamatan">KECAMATAN CIGALONTANG</div>
        <div class="desa">DESA PUSPAMUKTI</div>
        <div class="alamat">Jl. Raya Cigalontang No. 00, Kec. Cigalontang, Kab. Tasikmalaya, Jawa Barat 46463</div>
    </div>

    <!-- Judul & Nomor Surat Resmi -->
    <div class="perihal-container">
        <div class="perihal-title">{{ strtoupper($surat->jenisSurat->nama) }}</div>
        <div class="perihal-nomor">Nomor: {{ $surat->nomor_surat }}</div>
    </div>

    <!-- Isi Surat -->
    <div class="body-text">
        <p>Yang bertanda tangan di bawah ini, Kepala Desa Puspamukti Kecamatan Cigalontang Kabupaten Tasikmalaya, menerangkan dengan sebenarnya bahwa:</p>

        <table class="data-table">
            <col style="width: 30%;">
            <col style="width: 70%;">
            <tr>
                <td style="font-weight: bold;">Nama Lengkap</td>
                <td>: {{ $surat->pemohon_name }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">NIK</td>
                <td>: {{ $surat->pemohon_nik }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tempat / Tanggal Lahir</td>
                <td>: {{ $surat->user?->penduduk?->tempat_lahir ?? 'Tasikmalaya' }} / {{ $surat->user?->penduduk?->tanggal_lahir?->format('d-m-Y') ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Jenis Kelamin</td>
                <td>: {{ $surat->user?->penduduk?->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Agama</td>
                <td>: {{ $surat->user?->penduduk?->agama ?? 'Islam' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Pekerjaan</td>
                <td>: {{ $surat->user?->penduduk?->pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Alamat Lengkap</td>
                <td>: {{ $cleanAlamat }}</td>
            </tr>
        </table>

        <p>Orang tersebut di atas adalah benar warga penduduk yang berdomisili di Desa Puspamukti Kecamatan Cigalontang Kabupaten Tasikmalaya.</p>

        <p>Surat keterangan ini dibuat untuk keperluan: <strong>{{ $surat->keterangan ?? $surat->keperluan ?? 'Administrasi Desa' }}</strong></p>

        <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <!-- Blok Tanda Tangan Rata Kanan -->
    <div class="ttd-box">
        <table>
            <col style="width: 50%;">
            <col style="width: 50%;">
            <tr>
                <td></td>
                <td style="text-align: center; vertical-align: top;">
                    <div style="margin-bottom: 2px;">Cigalontang, {{ $surat->tanggal_disetujui?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</div>
                    <div style="font-weight: bold; margin-bottom: 45px;">KEPALA DESA PUSPAMUKTI</div>
                    <div style="font-weight: bold; text-decoration: underline; text-transform: uppercase;">ATANG RIDWAN, S.Pd.I.</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Catatan Draft Disclaimer -->
    <div class="catatan-draft">
        * Dokumen ini adalah DRAFT yang dibuat otomatis oleh SILAPU. Belum sah sebelum ditandatangani
        dan distempel oleh Kepala Desa. Nomor surat: {{ $surat->nomor_surat }}.
    </div>
</body>
</html>
