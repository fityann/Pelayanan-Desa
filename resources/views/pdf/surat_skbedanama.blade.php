<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Beda Nama - SILAPU Desa Puspamukti</title>
    <style>
        @page { size: 210mm 297mm; margin: 0mm; }
        html, body { margin: 0; padding: 0; background: #ffffff; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000000; line-height: 1.5; padding: 1.5cm 2cm; box-sizing: border-box; }
        table { border-collapse: collapse; width: 100%; table-layout: fixed; }
        .kop-container { position: relative; width: 100%; border-bottom: 3px double #000000; padding-bottom: 10px; margin-bottom: 20px; text-align: center; }
        .kop-logo { position: absolute; left: 10px; top: 0px; width: 75px; height: auto; }
        .pemerintah { font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .kecamatan { font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .desa { font-size: 18pt; font-weight: bold; text-transform: uppercase; margin: 5px 0; }
        .alamat { font-size: 9pt; }
        .perihal-container { text-align: center; margin: 20px 0 30px 0; }
        .perihal-title { font-weight: bold; font-size: 12pt; text-decoration: underline; text-transform: uppercase; }
        .perihal-nomor { font-size: 11pt; margin-top: 5px; }
        .body-text p { margin-bottom: 15px; text-align: justify; text-indent: 30px; }
        .data-table { margin: 15px 0 15px 40px; width: calc(100% - 40px); }
        .data-table td { padding: 4px 0; vertical-align: top; }
        .data-table .label { width: 45%; }
        .data-table .colon { width: 5%; }
        .ttd-box { margin-top: 50px; width: 100%; }
        .ttd-box td { width: 50%; text-align: center; vertical-align: top; }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-pemkab-tasikmalaya.jpg');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
        $data = $surat->data_isian ?? [];
        $rawAlamat = $surat->pemohon_alamat ?: ($surat->user?->penduduk?->alamat ?? '');
        $rtNumber = sprintf('%02d', $surat->user?->rt ?? $surat->user?->penduduk?->rt ?? '01');
        $alamatLengkap = $rawAlamat . (str_contains(strtolower($rawAlamat), 'rt') ? '' : " RT $rtNumber");
    @endphp

    <div class="kop-container">
        @if($logoData)
            <img class="kop-logo" src="data:image/jpeg;base64,{{ $logoData }}">
        @endif
        <div class="pemerintah">PEMERINTAH DAERAH KABUPATEN TASIKMALAYA</div>
        <div class="kecamatan">KECAMATAN CIGALONTANG</div>
        <div class="desa">DESA PUSPAMUKTI</div>
        <div class="alamat">Alamat: Jl.DesaPuspamukti No.014 Puspamukti Cigalontang Tasikmalaya 46463<br><span style="color: #007bb5; font-style: italic;">E-mail : puspamuktidesa@gmail.com</span></div>
    </div>

    <div class="perihal-container">
        <div class="perihal-title">SURAT KETERANGAN BEDA NAMA</div>
        <div class="perihal-nomor">Nomor : 474/{{ $surat->nomor_surat ?? $surat->kode_tracking_val }}/Des</div>
    </div>

    <div class="body-text">
        <p style="text-indent: 30px;">Yang bertanda tangan dibawah ini Kepala Desa Puspamukti Kecamatan Cigalontang Kabupaten Tasikmalaya Menerangkan bahwa :</p>

        <table class="data-table">
            <tr><td class="label">NIK</td><td class="colon">:</td><td>{{ $surat->pemohon_nik ?? $surat->user?->penduduk?->nik ?? '-' }}</td></tr>
            <tr><td class="label">Nama pada e-KTP dan KK</td><td class="colon">:</td><td>{{ $surat->pemohon_name ?? $surat->user?->name ?? 'Warga' }}</td></tr>
            <tr><td class="label">Nama pada KKS</td><td class="colon">:</td><td>{{ $data['nama_kks'] ?? '........................' }}</td></tr>
            <tr><td class="label">Alamat</td><td class="colon">:</td><td>{{ $alamatLengkap }}</td></tr>
        </table>

        <p style="text-indent: 30px;">Adalah benar warga Desa kami yang telah bertempat tinggal sesuai dengan alamat diatas, ada perbedaan Nama pada e-KTP, KK dengan Nama Pada KKS. dikarenakan adanya salah Penulisan / Pencetakan pada KKS. Data sebenarnya sesuai e-KTP dan KK:</p>

        <table style="margin: 30px 0 30px 40px; font-weight: bold; width: calc(100% - 40px);">
            <tr><td style="width: 45%;">Nama pada e-KTP dan KK</td><td style="width: 5%;">:</td><td>{{ $surat->pemohon_name ?? $surat->user?->name ?? 'Warga' }}</td></tr>
        </table>

        <p style="text-indent: 30px;">Demikian Keterangan ini saya buat dengan sebenarnya, untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <table class="ttd-box">
        <tr>
            <td></td>
            <td>
                <div>Puspamukti, {{ $surat->tanggal_disetujui?->translatedFormat('d F Y') ?? '....................................' }}</div>
                <div style="margin-bottom: 70px;">Kepala Desa Puspamukti</div>
                <div style="font-weight: bold; text-decoration: underline;">ATANG RIDWAN, S.Pd.I</div>
            </td>
        </tr>
    </table>
</body>
</html>
