<?php

namespace App\Enums;

enum KondisiAset: string
{
    case BAIK = 'baik';
    case RUSAK_RINGAN = 'rusak_ringan';
    case RUSAK_BERAT = 'rusak_berat';

    public function label(): string
    {
        return match ($this) {
            self::BAIK => 'Baik',
            self::RUSAK_RINGAN => 'Rusak Ringan',
            self::RUSAK_BERAT => 'Rusak Berat',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::BAIK => 'text-green-700 bg-green-100',
            self::RUSAK_RINGAN => 'text-yellow-700 bg-yellow-100',
            self::RUSAK_BERAT => 'text-red-700 bg-red-100',
        };
    }
}
