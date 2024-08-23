<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_asset',
        'penerima',
        'jumlah',
        'tanggal_keluar',
        'keterangan',
        'kondisi',
    ];

    public function idAsset()
    {
        return $this->belongsTo(Asset::class, 'id_asset', 'id');
    }
}
