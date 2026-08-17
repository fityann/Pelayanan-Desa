<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Ghaib - SILAPU Desa Puspamukti</title>
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
        .body-text p { margin-bottom: 15px; text-align: justify; text-indent: 0px; }
        .data-table { margin: 15px 0 15px 0px; width: 100%; }
        .data-table td { padding: 4px 0; vertical-align: top; }
        .data-table .label { width: 35%; }
        .data-table .colon { width: 5%; }
        .ttd-box { margin-top: 50px; width: 100%; }
        .ttd-box td { width: 50%; text-align: center; vertical-align: top; }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-pemkab-tasikmalaya.jpg');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
        $rawAlamat = $surat->pemohon_alamat ?: ($surat->user?->penduduk?->alamat ?? '');
        $rtNumber = sprintf('%02d', $surat->user?->rt ?? $surat->user?->penduduk?->rt ?? '01');
        $rwNumber = sprintf('%02d', $surat->user?->rw ?? $surat->user?->penduduk?->rw ?? '01');
    @endphp

    <div class="kop-container">
        @if($logoData)
            <img class="kop-logo" src="data:image/jpeg;base64,{{ $logoData }}">
        @endif
        <div class="pemerintah">PEMERINTAH KABUPATEN TASIKMALAYA</div>
        <div class="kecamatan">KECAMATAN CIGALONTANG</div>
        <div class="desa">DESA PUSPAMUKTI</div>
        <div class="alamat">Alamat: Jalan.Desa Puspamukti Nomor.014 Provinsi Jawa Barat 46463<br>Email: puspamuktidesa@gmail.com | Web: desapuspamukti.id</div>
    </div>

    <div class="perihal-container">
        <div class="perihal-title">SURAT KETERANGAN GHAIB</div>
        <div class="perihal-nomor">Nomor : 474/{{ $surat->nomor_surat ?? $surat->kode_tracking_val }}/Des/20....</div>
    </div>

    <div class="body-text">
        <p>Yang bertanda tangan di bawah ini Kepala Desa Puspamukti.<br>Dengan ini menerangkan bahwa :</p>

        <table class="data-table">
            <tr><td class="label">Nama</td><td class="colon">:</td><td>{{ $surat->pemohon_name ?? $surat->user?->name ?? 'Warga' }}</td></tr>
            <tr><td class="label">NIK</td><td class="colon">:</td><td>{{ $surat->pemohon_nik ?? $surat->user?->penduduk?->nik ?? '-' }}</td></tr>
            <tr><td class="label">Pekerjaan</td><td class="colon">:</td><td>{{ $surat->user?->penduduk?->pekerjaan ?? '-' }}</td></tr>
            <tr><td class="label">Alamat</td><td class="colon">:</td><td>Kp. {{ $rawAlamat }} RT/RW {{ $rtNumber }}/{{ $rwNumber }} Desa Puspamukti</td></tr>
            <tr><td></td><td></td><td>Kecamatan Cigalontang Kab. Tasikmalaya</td></tr>
        </table>

        <p>Orang tersebut masih beralamat sesuai dengan data kependudukan yang ada pada administrasi kependudukan di desa Puspamukti, akan tetapi orang tersebut sudah tidak bertempat tinggal di alamat tersebut <strong>namun masih berada di wilayah kesatuan negara republik indonesia dan tidak di ketahui pasti alamat tinggal yang sekarang</strong>.</p>

        <p>Demikian surat keterangan ini untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <table class="ttd-box">
        <tr>
            <td></td>
            <td>
                <div>Tasikmalaya, {{ $surat->tanggal_disetujui?->translatedFormat('d M Y') ?? '....................' }} 20.....</div>
                <div style="margin-bottom: 70px;">Kepala Desa Puspamukti</div>
                <div style="font-weight: bold; text-decoration: underline;">ATANG RIDWAN,S.Pd.I</div>
            </td>
        </tr>
    </table>
</body>
</html>
