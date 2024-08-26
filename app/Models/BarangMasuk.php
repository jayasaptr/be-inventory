<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_barang',
        'nama',
        'merk',
        'id_category',
        'jumlah',
        'satuan',
        'harga',
        'keterangan',
        'id_kondisi',
        'tanggal_masuk',
        'stock',
        'status'
    ];

    public function idCategory()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }

    public function idKondisi()
    {
        return $this->belongsTo(Kondisi::class, 'id_kondisi', 'id');
    }
}
