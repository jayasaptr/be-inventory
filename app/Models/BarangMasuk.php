<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'id_kondisi',
        'jumlah',
        'tanggal_masuk',
    ];

    public function idBarang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function idKondisi()
    {
        return $this->belongsTo(Kondisi::class, 'id_kondisi', 'id');
    }
}
