<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Draft Surat</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; color: #111; line-height: 1.5; }

        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 20px; }
        .kop .pemerintah { font-size: 12pt; letter-spacing: 2px; font-weight: bold; }
        .kop .kabupaten { font-size: 15pt; font-weight: bold; }
        .kop .kecamatan { font-size: 12pt; font-weight: bold; }
        .kop .desa { font-size: 14pt; font-weight: bold; }
        .kop .alamat { font-size: 9pt; }

        .meta { margin-bottom: 14px; }
        .meta table { width: 100%; }
        .meta td { padding: 1px 0; vertical-align: top; }
        .meta td.label { width: 90px; }

        .perihal { text-align: center; margin-bottom: 16px; font-weight: bold; font-size: 12pt; text-decoration: underline; }

        .body p { margin-bottom: 10px; text-align: justify; text-indent: 35px; }
        .data { width: 100%; border-collapse: collapse; margin: 14px 0; }
        .data td { padding: 3px 0; vertical-align: top; }
        .data td.label { width: 170px; }

        .ttd { margin-top: 28px; text-align: right; }
        .ttd .tempat-tgl { margin-bottom: 80px; }
        .ttd .jabatan { margin-bottom: 4px; }
        .ttd .nama { font-weight: bold; text-decoration: underline; }

        .catatan-draft { margin-top: 30px; font-size: 9pt; color: #666; border-top: 1px dashed #999; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="kop">
        <div class="pemerintah">PEMERINTAH KABUPATEN TASIKMALAYA</div>
        <div class="kecamatan">KECAMATAN CIGALONTANG</div>
        <div class="desa">DESA PUSPAMUKTI</div>
        <div class="alamat">Jl. Raya Cigalontang No. 00, Kec. Cigalontang, Kab. Tasikmalaya, Jawa Barat</div>
    </div>

    <div class="meta">
        <table>
            <tr><td class="label">Nomor</td><td>: {{ $surat->nomor_surat }}</td></tr>
            <tr><td class="label">Sifat</td><td>: Penting</td></tr>
            <tr><td class="label">Lampiran</td><td>: -</td></tr>
            <tr><td class="label">Perihal</td><td>: {{ $surat->jenisSurat->nama }}</td></tr>
        </table>
    </div>

    <div class="perihal">{{ $surat->jenisSurat->nama }}</div>

    <div class="body">
        <p>Yang bertanda tangan di bawah ini, Kepala Desa Puspamukti Kecamatan Cigalontang Kabupaten Tasikmalaya, menerangkan bahwa:</p>

        <table class="data">
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $surat->user->name }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td>: {{ $surat->user->nik }}</td>
            </tr>
            <tr>
                <td class="label">Tempat / Tanggal Lahir</td>
                <td>: {{ $surat->user->penduduk?->tempat_lahir ?? '-' }} / {{ $surat->user->penduduk?->tanggal_lahir?->format('d-m-Y') ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td>: {{ $surat->user->penduduk?->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td>: {{ $surat->user->penduduk?->agama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Pekerjaan</td>
                <td>: {{ $surat->user->penduduk?->pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: {{ $surat->user->penduduk?->alamat ?? $surat->user->address }} RT {{ $surat->user->penduduk?->rt ?? $surat->user->rt }}/RW {{ $surat->user->penduduk?->rw ?? $surat->user->rw }}</td>
            </tr>
        </table>

        <p>Orang tersebut di atas adalah benar penduduk Desa Puspamukti Kecamatan Cigalontang Kabupaten Tasikmalaya.</p>

        <p>Surat keterangan ini dibuat untuk keperluan: <strong>{{ $surat->keterangan }}</strong></p>

        <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="ttd">
        <div class="tempat-tgl">Cigalontang, {{ $surat->tanggal_disetujui?->format('d F Y') ?? now()->format('d F Y') }}</div>
        <div class="jabatan">KEPALA DESA PUSPAMUKTI</div>
        <div class="nama">H. PEMERINTAH DESA</div>
    </div>

    <div class="catatan-draft">
        * Dokumen ini adalah DRAFT yang dibuat otomatis oleh SIPANDA. Belum sah sebelum ditandatangani
        dan distempel oleh Kepala Desa. Nomor surat: {{ $surat->nomor_surat }}.
    </div>
</body>
</html>
