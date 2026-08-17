<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar Pindah - SILAPU Desa Puspamukti</title>
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
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000000;
            line-height: 1.5;
            padding-top: 2.5cm;
            padding-bottom: 2cm;
            padding-left: 2.5cm;
            padding-right: 2.5cm;
            box-sizing: border-box;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .form-code {
            text-align: right;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-code-box {
            display: inline-block;
            border: 1px solid #000;
            padding: 5px 15px;
            font-size: 11pt;
        }
        .perihal-container {
            text-align: center;
            margin: 20px 0 30px 0;
        }
        .perihal-title {
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
        }
        .perihal-nomor {
            font-size: 11pt;
            margin-top: 5px;
        }
        .body-text p {
            margin-bottom: 10px;
            text-align: justify;
        }
        .data-table {
            margin: 15px 0;
        }
        .data-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .data-table .label {
            width: 35%;
        }
        .data-table .colon {
            width: 5%;
        }
        .ttd-box {
            margin-top: 40px;
            width: 100%;
        }
        .ttd-box td {
            text-align: center;
            vertical-align: top;
            width: 50%;
        }
    </style>
</head>
<body>
    @php
        $data = $surat->data_isian ?? [];
        $alamatSekarang = "Desa Puspamukti Kec. Cigalontang, kab. Tasikmalaya";
    @endphp

    <div class="form-code">
        <span class="form-code-box">F-1.32</span>
    </div>

    <div class="perihal-container">
        <div class="perihal-title">SURAT PENGANTAR PINDAH</div>
        <div class="perihal-title" style="text-decoration: underline;">ANTAR KECAMATAN DALAM WILAYAH KABUPATEN</div>
        <div class="perihal-nomor">Nomor : {{ $surat->nomor_surat ?? $surat->kode_tracking_val }} / Des</div>
    </div>

    <div class="body-text">
        <p>Yang bertandatangan dibawah ini, menerangkan Permohonan Pindah Penduduk WNI dengan data sebagai berikut :</p>

        <table class="data-table">
            <tr>
                <td class="label">1. NIK</td>
                <td class="colon">:</td>
                <td>{{ $surat->pemohon_nik ?? $surat->user?->penduduk?->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">2. Nama Lengkap</td>
                <td class="colon">:</td>
                <td>{{ $surat->pemohon_name ?? $surat->user?->name ?? 'Warga' }}</td>
            </tr>
            <tr>
                <td class="label">3. Nomor Kartu Keluarga</td>
                <td class="colon">:</td>
                <td>{{ $data['no_kk'] ?? $surat->user?->penduduk?->keluarga?->no_kk ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">4. Nama Kepala Keluarga</td>
                <td class="colon">:</td>
                <td>{{ $data['kepala_keluarga'] ?? $surat->user?->penduduk?->keluarga?->kepalaKeluarga?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">5. Alamat Sekarang</td>
                <td class="colon">:</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>{{ $alamatSekarang }}</td>
            </tr>
            <tr>
                <td class="label">6. Alamat Tujuan Pindah</td>
                <td class="colon">:</td>
                <td>Kp. {{ $data['kp_tujuan'] ?? '................' }} Rt {{ $data['rt_tujuan'] ?? '....' }} / Rw {{ $data['rw_tujuan'] ?? '....' }} Desa {{ $data['desa_tujuan'] ?? '................' }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Kec. {{ $data['kec_tujuan'] ?? '................' }} Kab. {{ $data['kab_tujuan'] ?? '................' }}</td>
            </tr>
            <tr>
                <td class="label">7. Jumlah Keluarga yang Pindah</td>
                <td class="colon">:</td>
                <td>{{ $data['jumlah_pindah'] ?? '.....' }} Orang</td>
            </tr>
        </table>

        <p>Adapun Permohonan Pindah Penduduk WNI yang bersangkutan sebagaimana terlampir.</p>
        <p>Demikian Surat Pengantar Pindah ini dibuat agar digunakan sebagaimana mestinya.</p>
    </div>

    <table class="ttd-box">
        <tr>
            <td></td>
            <td>
                <div>Puspamukti, {{ $surat->tanggal_disetujui?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</div>
                <div style="margin-bottom: 70px;">Kepala Desa Puspamukti</div>
                <div style="text-decoration: underline;">ATANG RIDWAN, S.Pd.I</div>
            </td>
        </tr>
    </table>
</body>
</html>
