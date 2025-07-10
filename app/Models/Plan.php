<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'plan';

    protected $guarded = [];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function item_type()
    {
        return $this->belongsTo(ItemType::class, 'tipe_barang_id', 'id');
    }

    public function item_variety()
    {
        return $this->belongsTo(ItemVariety::class, 'jenis_barang_id', 'id');
    }
}
