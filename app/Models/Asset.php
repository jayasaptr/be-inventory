<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'purchase_date', 'type', 'price', 'quantity', 'kondisi', 'satuan'];
}
