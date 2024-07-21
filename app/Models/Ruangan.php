<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'id_user',
    ];

    public function idUser()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
