<?php

namespace App\Support;

/**
 * Sumber tunggal definisi resource, aksi CRUD, dan pemetaan permission per role.
 * Dipakai oleh RoleController (sinkronisasi) dan RolePermissionSeeder.
 */
class PermissionDefinitions
{
    /**
     * Resource/modul + aksi CRUD yang dikelola.
     */
    public const RESOURCES = [
        'Penduduk' => ['C', 'R', 'U', 'D'],
        'Keluarga' => ['C', 'R', 'U', 'D'],
        'Surat' => ['C', 'R', 'U', 'D'],
        'Pengajuan Surat' => ['C', 'R', 'U', 'D'],
        'Arsip Surat' => ['R'],
        'Pengaduan' => ['C', 'R', 'U', 'D'],
        'Informasi' => ['C', 'R', 'U', 'D'],
        'APBDes' => ['C', 'R', 'U', 'D'],
        'Manajemen User' => ['C', 'R', 'U', 'D'],
        'Role & Permission' => ['R', 'U'],
        'Pengaturan' => ['R', 'U'],
    ];

    /**
     * Izin yang diberikan ke tiap role. Array kosong = semua izin.
     */
    public const ROLE_PERMISSIONS = [
        'Super Admin' => [],
        'Kepala Desa' => [
            'R Penduduk', 'R Keluarga', 'R Surat', 'R Pengajuan Surat', 'U Pengajuan Surat',
            'R Arsip Surat', 'R Pengaduan', 'R Informasi', 'R APBDes', 'U APBDes',
        ],
        'Sekretaris Desa' => [
            'R Penduduk', 'R Keluarga', 'R Surat', 'R Pengajuan Surat',
            'R Arsip Surat', 'R Pengaduan', 'R Informasi', 'R APBDes', 'U APBDes',
        ],
        'Bendahara' => [
            'R Penduduk', 'R Keluarga', 'R Surat', 'R Pengajuan Surat',
            'R Arsip Surat', 'R Pengaduan', 'R Informasi', 'C APBDes', 'R APBDes', 'U APBDes',
        ],
        'Admin Desa' => [
            'C Penduduk', 'R Penduduk', 'U Penduduk', 'D Penduduk',
            'C Keluarga', 'R Keluarga', 'U Keluarga', 'D Keluarga',
            'C Surat', 'R Surat', 'U Surat', 'D Surat',
            'R Pengajuan Surat', 'U Pengajuan Surat', 'R Arsip Surat',
            'C Pengaduan', 'R Pengaduan', 'U Pengaduan', 'D Pengaduan',
            'C Informasi', 'R Informasi', 'U Informasi', 'D Informasi',
            'C APBDes', 'R APBDes',
            'C Manajemen User', 'R Manajemen User', 'U Manajemen User', 'D Manajemen User',
            'R Role & Permission', 'U Role & Permission',
        ],
        'Warga' => [
            'R Penduduk', 'C Pengaduan', 'R Informasi', 'C Pengajuan Surat',
        ],
        'RT' => [
            'R Penduduk', 'R Pengajuan Surat', 'R Pengaduan', 'R Informasi',
        ],
        'RW' => [
            'R Penduduk', 'R Pengajuan Surat', 'R Pengaduan', 'R Informasi',
        ],
    ];
}
