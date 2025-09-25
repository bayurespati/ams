<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRecap extends Model
{
    use HasFactory;

    public function assetLabels()
    {
        return $this->hasMany(AssetLabel::class, 'id_asset', 'id_asset');
    }
}
