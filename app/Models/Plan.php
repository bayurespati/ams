<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'plan';
    protected $guarded = [];

    protected $hidden = [
        'id',
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

    public function po()
    {
        return $this->hasMany(PO::class, 'plan_id', 'id');
    }

    // many-to-many dengan companies (mitra)
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_plan', 'plan_id', 'company_id')->withTimestamps();
    }
}
