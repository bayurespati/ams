<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'city';

    protected $guarded = [];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
