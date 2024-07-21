<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangRuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'id_ruangan',
        'jumlah',
        'keterangan',
        'id_user',
        'tanggal',
        'status',
    ];

    public function idBarang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function idRuang()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruang', 'id');
    }

    public function idUser()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
