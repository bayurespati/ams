<?php

namespace App\Models\Star;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LOP extends Model
{
    protected $connection = 'star_sql';

    protected $table = 'data_lop';
}
