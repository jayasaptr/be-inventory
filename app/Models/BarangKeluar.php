<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang_masuk',
        'penerima',
        'jumlah',
        'tanggal_keluar',
        'keterangan',
        'id_kondisi',
    ];

    public function idBarangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_barang_masuk', 'id');
    }

    public function idKondisi()
    {
        return $this->belongsTo(Kondisi::class, 'id_kondisi', 'id');
    }
}
