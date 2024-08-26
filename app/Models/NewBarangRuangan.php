<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewBarangRuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_asset_barang',
        'id_ruangan',
        'jumlah',
        'keterangan',
        'id_user',
        'tanggal',
        'status',
    ];

    public function idAssetBarang()
    {
        return $this->belongsTo(AssetBarangModel::class, 'id_asset_barang', 'id');
    }

    public function idRuangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id');
    }
}
