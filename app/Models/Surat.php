<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'instansi',
        'keterangan',
        'jenis_surat',
        'tanggal_surat',
        'image',
    ];

    public function getImageAttribute($value)
    {
        return url('storage/images/' . $value);
    }
}
