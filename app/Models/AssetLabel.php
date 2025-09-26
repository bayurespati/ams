<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetLabel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function assetRecap()
    {
        return $this->belongsTo(AssetRecap::class, 'id_asset', 'id');
    }
}
