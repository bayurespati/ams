<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoOut extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'do_outs';

    protected $guarded = [];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id')->withTrashed();
    }

    public function item_do_out()
    {
        return $this->hasMany(ItemDoOut::class, 'do_out_id', 'id');
    }
}
