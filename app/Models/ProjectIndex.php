<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectIndex extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function project():belongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function content(): HasMany
    {
        return $this->hasMany(ProjectIndexContent::class);
    }
}
