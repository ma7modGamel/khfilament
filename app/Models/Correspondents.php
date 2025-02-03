<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Correspondents extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'files'=>'array'
    ];
    public function modelable(): MorphTo
    {
        return $this->morphTo();
    }


}
