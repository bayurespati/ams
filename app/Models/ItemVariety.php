<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemVariety extends Model
{
    use SoftDeletes;

    protected $table = 'item_variety';

    protected $guarded = [];
}
