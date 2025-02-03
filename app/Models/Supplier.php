<?php

namespace App\Models;

use App\Models\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $casts = [
        'files'=>'array'
    ];
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    public function city() :BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function quotations()
    {
        return $this->hasMany(OrderQuotation::class);
    }
    
    public function correspondent():MorphOne
    {
        return $this->morphOne(Correspondents::class, 'modelable');
    }
    public function competitionPrice():MorphOne
    {
        return $this->morphOne(Correspondents::class, 'modelable');
    }
    public function type() :BelongsTo
    {
        return $this->belongsTo(ContractorType::class, 'type_id');
    }
   
}