<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function purchaseOrder():BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function item():BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
    public function quotations():HasMany
    {

        return $this->hasMany(OrderQuotation::class, 'order_item_id');
    }
}
