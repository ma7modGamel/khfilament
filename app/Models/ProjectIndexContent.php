<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectIndexContent extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function projectIndex():belongsTo
    {
        return $this->belongsTo(ProjectIndex::class);
    }
}
