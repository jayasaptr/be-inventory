<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangRuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang_masuk',
        'id_ruangan',
        'jumlah',
        'keterangan',
        'id_user',
        'tanggal',
        'status',
    ];

    public function idBarangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_barang_masuk', 'id');
    }

    public function idRuang()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruang', 'id');
    }

    public function idUser()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function idRuangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id');
    }
}
