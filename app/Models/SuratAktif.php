<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratAktif extends Model
{
    use HasFactory;

    // fillable
    protected $fillable = [
        'no_surat',
        'nama_siswa',
        'tempat_lahir',
        'tanggal_lahir',
        'kelas',
        'tahun_ajaran',
        'alamat',
        'status',
        'nisn',
    ];
}
