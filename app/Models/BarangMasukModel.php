<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'category',
        'quantity',
        'kondisi',
        'status',
        'tanggal_masuk',
        'satuan'
    ];

    public function barangId()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_id', 'id');
    }
}
