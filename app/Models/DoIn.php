<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DoIn extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;

    protected $table = 'do_in';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function po()
    {
        return $this->belongsTo('App\Models\PO', 'po_id', 'id')->withTrashed();
    }
}
