<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class credit extends Model
{

    protected $guarded = [];

    
    public function ProjectIndex()
    {
        return $this->belongsTo(ProjectIndex::class);
    }

}