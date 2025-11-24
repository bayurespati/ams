<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rak extends Model
{
    use SoftDeletes;

    protected $table = 'rak';

    protected $guarded = [];

    protected $hidden = [
        'id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}
