<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetBarangModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'category',
        'kondisi',
        'jumlah'
    ];
}
