<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Agency extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function correspondent():MorphOne
    {
        return $this->morphOne(Correspondents::class, 'modelable');
    }
}
