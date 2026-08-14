<?php

namespace App\Enums;

enum StatusAset: string
{
    case AKTIF = 'aktif';
    case DIPINJAMKAN = 'dipinjamkan';
    case DIHAPUS = 'dihapus';

    public function label(): string
    {
        return match ($this) {
            self::AKTIF => 'Aktif',
            self::DIPINJAMKAN => 'Dipinjamkan',
            self::DIHAPUS => 'Dihapus',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AKTIF => 'text-green-700 bg-green-100',
            self::DIPINJAMKAN => 'text-blue-700 bg-blue-100',
            self::DIHAPUS => 'text-gray-700 bg-gray-100',
        };
    }
}
