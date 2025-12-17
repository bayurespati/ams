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
    
    protected $hidden = ['id', 'updated_at', 'deleted_at'];

    public function items()
    {
        return $this->hasMany(PlanItem::class, 'plan_id');
    }
}
