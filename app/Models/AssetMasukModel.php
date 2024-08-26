<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasukModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'category',
        'quantity',
        'kondisi',
        'status',
        'tanggal_masuk',
        'satuan'
    ];

    public function assetId()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }
}
