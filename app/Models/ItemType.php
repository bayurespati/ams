<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemType extends Model
{
    use SoftDeletes;

    protected $table = 'item_type';

    protected $guarded = [];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
