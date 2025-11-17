<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';
    protected $guarded = [];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'company_plan', 'company_id', 'plan_id')->withTimestamps();
    }
}
