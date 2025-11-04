<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDoIn extends Model
{
    use HasFactory;

    protected $table = 'item_do_in';

    protected $guarded = [];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function do_in()
    {
        return $this->belongsTo(DoIn::class, 'do_in_id', 'id')->withTrashed();
    }

    public function owner()
    {
        return $this->belongsTo(Company::class, 'owner_id', 'id')->withTrashed();
    }
}
