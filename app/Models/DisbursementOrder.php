<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisbursementOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'project_manager',
        'disbursementordernumber',
        'project_employee',
        'purchase_code',
        'purchase_date',
        'total_value',
        'residual_value',
        'payment',
        'notes',
        'purchase_order_id',
        'purchasing_user_id',
        'order_item_id',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function purchaseUser() :BelongsTo{
        return $this->belongsTo(User::class,'purchasing_user_id')
            ->whereHas('titles', function ($query) {
            $query->where('slug', 'purchasing');
        });
    }
    public function orderItem():BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class,'order_item_id');
    }

    public static  function generateUniqueDisbursementOrderNumber()
    {
        do {
            $randomNumber = rand(10000, 99999);
            \Log::info('Generated number: ' . $randomNumber); 
        } while (DB::table('disbursement_orders')->where('disbursementordernumber', $randomNumber)->exists());
    
        \Log::info('Final unique number: ' . $randomNumber);
        return $randomNumber;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->disbursementordernumber = $model->generateUniqueDisbursementOrderNumber();
        });
    }
    public function supplier()
{
    return $this->belongsTo(Supplier::class);
}
}