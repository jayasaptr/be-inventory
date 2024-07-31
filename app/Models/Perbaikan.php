<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang_masuk',
        'tanggal_perbaikan',
        'tanggal_selesai',
        'biaya',
        'keterangan',
        'status',
        'kwitansi',
        'jumlah',
        'id_user',
    ];

    // barang masuk
    public function idBarangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_barang_masuk', 'id');
    }

    public function idUser()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // kwitansi
    public function getKwitansiAttribute($value)
    {
        return url('storage/kwitansi/' . $value);
    }
}
