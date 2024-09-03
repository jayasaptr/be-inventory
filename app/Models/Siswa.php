<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    // $table->string('npm');
    //         $table->string('nama');
    //         $table->string('kelas');
    //         $table->string('umur');

    protected $fillable = [
        'npm',
        'nama',
        'kelas',
        'umur',
    ];
}
