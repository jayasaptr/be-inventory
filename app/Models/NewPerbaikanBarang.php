<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewPerbaikanBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_asset_barang',
        'tanggal_perbaikan',
        'tanggal_selesai',
        'biaya',
        'keterangan',
        'status',
        'kwitansi',
        'jumlah',
        'id_user',
    ];

    public function idAssetBarang()
    {
        return $this->belongsTo(AssetBarangModel::class, 'id_asset_barang', 'id');
    }

    public function idUser()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function getKwitansiAttribute($value)
    {
        return url('storage/kwitansi/' . $value);
    }
}
