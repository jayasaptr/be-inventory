<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'tanggal_perbaikan',
        'tanggal_selesai',
        'biaya',
        'keterangan',
        'status',
        'kwitansi',
        'id_user',
    ];

    public function idBarang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
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
