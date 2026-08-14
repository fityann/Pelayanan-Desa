<?php

namespace App\Models;

use App\Enums\KondisiAset;
use App\Enums\StatusAset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'assets';

    protected $fillable = [
        'asset_category_id',
        'name',
        'location',
        'condition',
        'acquisition_year',
        'acquisition_source',
        'value',
        'photo',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'condition' => KondisiAset::class,
            'status' => StatusAset::class,
            'value' => 'decimal:2',
        ];
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriAset::class, 'asset_category_id');
    }
}
