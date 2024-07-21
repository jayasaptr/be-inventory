<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'merk',
        'id_category',
        'jumlah',
        'satuan',
        'harga',
        'keterangan',
    ];

    public function idCategory()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }
}
