<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceAsset extends Model
{
    use HasFactory;

    protected $fillable = ['asset_id', 'maintenance_date', 'description', 'cost', 'qty','status'];

    public function assetId()
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }
}
