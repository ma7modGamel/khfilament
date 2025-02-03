<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class OrderQuotation extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($quotation) {
            Log::info('Saving OrderQuotation with importer_name:', ['importer_name' => $quotation->importer_name]);
            if (empty($quotation->importer_name) && !empty($quotation->supplier_id)) {
                $supplier = $quotation->supplier()->first();
                $quotation->importer_name = $supplier ? $supplier->name : null;
            }
        });
    }
    public function OrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class,'order_item_id');
    }
}