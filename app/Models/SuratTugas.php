<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'menimbang',
        'dasar',
        'user_id',
        'keterangan',
        'status'
    ];

    public function userId()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
