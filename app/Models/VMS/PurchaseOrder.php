<?php

namespace App\Models\VMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $connection = 'vms_sql';

    protected $table = 'data_po';

    public function bakn()
    {
        return $this->belongsTo(Bakn::class, 'bakn_id', 'id');
    }
}
