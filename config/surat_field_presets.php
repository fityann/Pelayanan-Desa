<?php

return [

    'SKU' => [
        ['name' => 'bentuk_perusahaan', 'label' => 'Bentuk Perusahaan', 'type' => 'text', 'required' => true],
        ['name' => 'npwp', 'label' => 'Nomor NPWP', 'type' => 'text', 'required' => true],
        ['name' => 'alamat_perusahaan', 'label' => 'Alamat Perusahaan', 'type' => 'textarea', 'required' => true],
        ['name' => 'bidang_usaha', 'label' => 'Bidang Usaha', 'type' => 'text', 'required' => true],
        ['name' => 'jenis_barang', 'label' => 'Jenis Barang/Jasa Utama', 'type' => 'text', 'required' => true],
        ['name' => 'lama_usaha', 'label' => 'Lama Usaha', 'type' => 'text', 'required' => true],
    ],

    'SKBL' => [
        ['name' => 'luas_sertifikat', 'label' => 'Luas pada Sertifikat', 'type' => 'text', 'required' => true],
        ['name' => 'luas_sppt', 'label' => 'Luas pada SPPT', 'type' => 'text', 'required' => true],
    ],

    'SKBedaNama' => [
        ['name' => 'nama_kks', 'label' => 'Nama pada KKS', 'type' => 'text', 'required' => true],
    ],

    'SKKehilangan' => [
        ['name' => 'barang_hilang', 'label' => 'Barang/Dokumen yang Hilang', 'type' => 'textarea', 'required' => true],
    ],

    'SPPAD' => [
        ['name' => 'no_kk', 'label' => 'Nomor KK', 'type' => 'text', 'required' => true],
        ['name' => 'desa_tujuan', 'label' => 'Desa Tujuan', 'type' => 'text', 'required' => true],
        ['name' => 'jumlah_pindah', 'label' => 'Jumlah Orang Pindah', 'type' => 'text', 'required' => true],
    ],

    'SPPAK' => [
        ['name' => 'no_kk', 'label' => 'Nomor KK', 'type' => 'text', 'required' => true],
        ['name' => 'kepala_keluarga', 'label' => 'Kepala Keluarga', 'type' => 'text', 'required' => true],
        ['name' => 'kp_tujuan', 'label' => 'Kampung Tujuan', 'type' => 'text', 'required' => true],
        ['name' => 'rt_tujuan', 'label' => 'RT Tujuan', 'type' => 'text', 'required' => true],
        ['name' => 'rw_tujuan', 'label' => 'RW Tujuan', 'type' => 'text', 'required' => true],
        ['name' => 'desa_tujuan', 'label' => 'Desa Tujuan', 'type' => 'text', 'required' => true],
        ['name' => 'kec_tujuan', 'label' => 'Kecamatan Tujuan', 'type' => 'text', 'required' => true],
        ['name' => 'kab_tujuan', 'label' => 'Kabupaten Tujuan', 'type' => 'text', 'required' => true],
        ['name' => 'jumlah_pindah', 'label' => 'Jumlah Orang Pindah', 'type' => 'text', 'required' => true],
    ],

    'SPPDK' => [
        ['name' => 'no_kk', 'label' => 'Nomor KK', 'type' => 'text', 'required' => true],
        ['name' => 'kepala_keluarga', 'label' => 'Kepala Keluarga', 'type' => 'text', 'required' => true],
        ['name' => 'alamat_tujuan', 'label' => 'Alamat Tujuan', 'type' => 'textarea', 'required' => true],
        ['name' => 'jumlah_pindah', 'label' => 'Jumlah Orang Pindah', 'type' => 'text', 'required' => true],
    ],

    'SKBB' => [
        ['name' => 'status_kepemilikan_rumah', 'label' => 'Status Kepemilikan Rumah', 'type' => 'text', 'required' => true],
        ['name' => 'kepentingan_skbb', 'label' => 'Kepentingan Surat', 'type' => 'textarea', 'required' => true],
    ],

    'SKBN' => [
        ['name' => 'keperluan_skbn', 'label' => 'Keperluan Surat', 'type' => 'textarea', 'required' => true],
    ],

    'SKCerai' => [
        ['name' => 'tanggal_cerai', 'label' => 'Tanggal Perceraian', 'type' => 'text', 'required' => true],
        ['name' => 'alasan_cerai', 'label' => 'Alasan Perceraian', 'type' => 'textarea', 'required' => true],
    ],

    'SKD' => [
        ['name' => 'lama_tinggal', 'label' => 'Lama Tinggal di Domisili', 'type' => 'text', 'required' => true],
    ],

    'SKematian' => [
        ['name' => 'tanggal_meninggal', 'label' => 'Tanggal Meninggal', 'type' => 'text', 'required' => true],
        ['name' => 'tempat_meninggal', 'label' => 'Tempat Meninggal', 'type' => 'text', 'required' => true],
        ['name' => 'sebab_meninggal', 'label' => 'Sebab Meninggal', 'type' => 'textarea', 'required' => true],
    ],

    'SKG' => [
        ['name' => 'nama_yang_ghoib', 'label' => 'Nama yang Ghoib/Pergi', 'type' => 'text', 'required' => true],
        ['name' => 'sejak_kapan', 'label' => 'Pergi Sejak Kapan', 'type' => 'text', 'required' => true],
        ['name' => 'catatan', 'label' => 'Catatan Tambahan', 'type' => 'textarea', 'required' => false],
    ],

    'SKKB' => [
        ['name' => 'instansi_tujuan', 'label' => 'Instansi Tujuan', 'type' => 'text', 'required' => true],
    ],

    'SKN' => [
        ['name' => 'nama_pasangan', 'label' => 'Nama Pasangan', 'type' => 'text', 'required' => true],
        ['name' => 'tanggal_nikah', 'label' => 'Tanggal Nikah', 'type' => 'text', 'required' => true],
    ],

    'SKP' => [
        ['name' => 'pekerjaan', 'label' => 'Pekerjaan', 'type' => 'text', 'required' => true],
        ['name' => 'sumber_penghasilan', 'label' => 'Sumber Penghasilan', 'type' => 'text', 'required' => true],
        ['name' => 'penghasilan_per_bulan', 'label' => 'Penghasilan per Bulan', 'type' => 'text', 'required' => true],
    ],

    'SKPensiun' => [
        ['name' => 'nama_instansi', 'label' => 'Nama Instansi/Perusahaan', 'type' => 'text', 'required' => true],
        ['name' => 'tanggal_pensiun', 'label' => 'Tanggal Pensiun', 'type' => 'text', 'required' => true],
    ],

    'SKTM' => [
        ['name' => 'pekerjaan', 'label' => 'Pekerjaan', 'type' => 'text', 'required' => true],
        ['name' => 'penghasilan_per_bulan', 'label' => 'Penghasilan per Bulan', 'type' => 'text', 'required' => true],
        ['name' => 'status_rumah', 'label' => 'Status Rumah', 'type' => 'text', 'required' => true],
    ],

    'SKWali' => [
        ['name' => 'nama_anak', 'label' => 'Nama Anak yang Diwalikan', 'type' => 'text', 'required' => true],
        ['name' => 'hubungan_wali', 'label' => 'Hubungan Wali', 'type' => 'text', 'required' => true],
        ['name' => 'keperluan_wali', 'label' => 'Keperluan Perwalian', 'type' => 'textarea', 'required' => true],
    ],

    'SPN' => [
        ['name' => 'nama_calon', 'label' => 'Nama Calon Pasangan', 'type' => 'text', 'required' => true],
        ['name' => 'tanggal_nikah', 'label' => 'Tanggal Rencana Nikah', 'type' => 'text', 'required' => true],
        ['name' => 'tempat_nikah', 'label' => 'Tempat Nikah', 'type' => 'text', 'required' => true],
    ],

    'SPPD' => [
        ['name' => 'dokumen_dimohon', 'label' => 'Dokumen yang Dimohon', 'type' => 'text', 'required' => true],
        ['name' => 'alasan_pembuatan', 'label' => 'Alasan Pembuatan', 'type' => 'textarea', 'required' => true],
    ],

];