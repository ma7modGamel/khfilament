<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extrac_government_agency extends Model
{
    protected $guarded = [];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}