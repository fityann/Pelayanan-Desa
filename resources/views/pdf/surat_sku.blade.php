<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Usaha - SILAPU Desa Puspamukti</title>
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
            font-family: Arial, Helvetica, sans-serif; /* Fallback to Arial/Helvetica for better PDF compatibility */
            font-size: 11pt;
            color: #000000;
            line-height: 1.5;
            padding-top: 1.5cm;
            padding-bottom: 1.2cm;
            padding-left: 2.5cm;
            padding-right: 2.5cm;
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
            vertical-align: top;
        }
        .kop-container {
            position: relative;
            width: 100%;
            border-bottom: 3px double #000000;
            padding-bottom: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
        .kop-logo {
            position: absolute;
            left: 0;
            top: 2px;
            width: 70px;
            height: auto;
        }
        .pemerintah {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kecamatan {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .desa {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }
        .alamat {
            font-size: 9pt;
            color: #000000;
        }
        .email {
            font-size: 9pt;
            color: #0066cc;
            font-style: italic;
        }
        .perihal-container {
            text-align: center;
            margin: 20px 0 30px 0;
        }
        .perihal-title {
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .perihal-nomor {
            font-size: 11pt;
            margin-top: 2px;
        }
        .body-text p {
            margin-bottom: 15px;
            text-align: justify;
        }
        .data-table {
            margin: 15px 0 20px 0;
            width: 100%;
        }
        .data-table td {
            padding: 4px 0;
            font-size: 11pt;
        }
        .ttd-box {
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .ttd-box td {
            text-align: center;
            font-size: 11pt;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 70px;
            display: inline-block;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-pemkab-tasikmalaya.jpg');
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
        
        $dataIsian = $surat->data_isian ?? [];
    @endphp

    <!-- Kop Surat Sesuai Format -->
    <div class="kop-container">
        @if($logoData)
            <img class="kop-logo" src="data:image/jpeg;base64,{{ $logoData }}">
        @endif
        <div class="pemerintah">PEMERINTAH DAERAH KABUPATEN TASIKMALAYA</div>
        <div class="kecamatan">KECAMATAN CIGALONTANG</div>
        <div class="desa">DESA PUSPAMUKTI</div>
        <div class="alamat">Alamat: Jl.DesaPuspamukti No.014 Puspamukti Cigalontang Tasikmalaya 46463</div>
        <div class="email">E-mail : puspamuktidesa@gmail.com</div>
    </div>

    <!-- Judul & Nomor Surat Resmi -->
    <div class="perihal-container">
        <div class="perihal-title">SURAT KETERANGAN USAHA</div>
        <div class="perihal-nomor">Nomor: {{ $surat->nomor_surat ?? '517/     /Des' }}</div>
    </div>

    <!-- Isi Surat -->
    <div class="body-text">
        <p>Yang bertanda tangan dibawah ini Kepala Desa Puspamukti Kecamatan Cigalontang Kabupaten Tasikmalaya Menerangkan bahwa :</p>

        <table class="data-table">
            <col style="width: 35%;">
            <col style="width: 3%;">
            <col style="width: 62%;">
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $surat->nik_pemohon ?? $surat->user?->penduduk?->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>{{ $surat->nama_pemohon ?? $surat->user?->name ?? 'Warga' }}</td>
            </tr>
            <tr>
                <td>Bentuk Perusahaan</td>
                <td>:</td>
                <td>{{ $dataIsian['bentuk_perusahaan'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nomor NPWP</td>
                <td>:</td>
                <td>{{ $dataIsian['npwp'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td>:</td>
                <td>{{ $cleanAlamat }}</td>
            </tr>
            <tr>
                <td>Alamat Perusahaan</td>
                <td>:</td>
                <td>{{ $dataIsian['alamat_perusahaan'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>Bidang Usaha</td>
                <td>:</td>
                <td>{{ $dataIsian['bidang_usaha'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Barang/Jasa Utama</td>
                <td>:</td>
                <td>{{ $dataIsian['jenis_barang'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>Lama Usaha</td>
                <td>:</td>
                <td>{{ $dataIsian['lama_usaha'] ?? '-' }}</td>
            </tr>
        </table>

        <p>Demikian surat keterangan usaha ini kami buat dengan sebenar-benarnya, untuk dapat dijadikan bahan pertimbangan. Atas perhatiannya kami ucapkan Terimakasih.</p>
    </div>

    <!-- Blok Tanda Tangan Rata Kanan -->
    <div class="ttd-box">
        <table>
            <col style="width: 50%;">
            <col style="width: 50%;">
            <tr>
                <td></td>
                <td>
                    Puspamukti, {{ \Carbon\Carbon::parse($surat->tanggal_disetujui ?? now())->translatedFormat('d F Y') }}<br>
                    Kepala Desa Puspamukti
                    <br><br><br><br><br>
                    <span class="ttd-nama">ATANG RIDWAN,S.Pd.I</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
