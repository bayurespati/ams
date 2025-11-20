<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanItem extends Model
{
    protected $table = 'plan_item';
    protected $guarded = [];
    
    // Relasi many-to-one ke ItemType melalui kolom 'tipe_barang_id'
    public function item_type()
    {
        return $this->belongsTo(ItemType::class, 'tipe_barang_id', 'id');
    }

    // Relasi many-to-one ke ItemVariety melalui kolom 'jenis_barang_id'
    public function item_variety()
    {
        return $this->belongsTo(ItemVariety::class, 'jenis_barang_id', 'id');
    }

    // Relasi many-to-one ke Plan (satu PlanItem milik satu Plan) melalui kolom 'plan_id'
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
}