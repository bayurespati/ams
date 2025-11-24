<?php

namespace App\Models\VMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spph extends Model
{
    protected $connection = 'vms_sql';

    protected $table = 'data_spph';
}
