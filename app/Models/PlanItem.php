<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanItem extends Model
{
    protected $table = 'plan_item';
    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function item_type()
    {
        return $this->belongsTo(ItemType::class, 'tipe_barang_id');
    }

    public function item_variety()
    {
        return $this->belongsTo(ItemVariety::class, 'jenis_barang_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function brands()
    {
        return $this->belongsToMany(
            Brand::class,
            'plan_item_brand',
            'plan_item_id',
            'brand_id'
        );
    }
}
