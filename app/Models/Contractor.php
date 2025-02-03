<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Contractor extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'files'=>'array'
    ];

    public function type() :BelongsTo
    {
        return $this->belongsTo(ContractorType::class, 'type_id');
    }
    public function city() :BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function correspondent():MorphOne
    {
        return $this->morphOne(Correspondents::class, 'modelable');
    }
    public function competitionPrice():MorphOne
    {
        return $this->morphOne(Correspondents::class, 'modelable');
    }
}
