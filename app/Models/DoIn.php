<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoIn extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'do_in';

    protected $guarded = [];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function po()
    {
        return $this->belongsTo('App\Models\PO', 'po_id', 'id')->withTrashed();
    }
}
