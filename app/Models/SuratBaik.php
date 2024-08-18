<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratBaik extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nomor_surat',
        'nama_siswa',
        'nama_orang_tua',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'status'
    ];
}
