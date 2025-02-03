<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'description',
        'qty',
        'single_price',
        'total_price',
        'user_id',
        'manager_id',
        'sales_id',
        'attachments',
        'sender_id',
        'file', 'product_id', 'ref_num', 'item_id', 'supplier_id',
    ];


    protected $casts = [
        'attachments' => 'array',
    ];


    public function additions()
    {

        return $this->hasMany(OrderAddition::class);
    }

    public function quotations()
    {

        return $this->hasMany(OrderQuotation::class);
    }


    public function Statuses()
    {
        return $this->hasMany(FormStatusRole::class, 'purchase_order_id');
    }
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }
    public function actions()
    {
        return $this->hasMany(FormStatusRole::class, 'purchase_order_id')
            ->where('status_id','<>',1);
    }


    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function product()
    {

        return $this->belongsTo(Product::class);
    }

    public function productItem()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function disbursementOrder()
    {
        return $this->hasOne(DisbursementOrder::class);
    }
    public function disbursementOrders()
    {
        return $this->hasMany(DisbursementOrder::class);
    }




    public function getTotalPriceAttribute()
    {
        $singlePrice = intval($this->productItem?->unit_price);
        // dd($singlePrice);
        $quantity = intval($this->qty);

        $totalPrice = $quantity * $singlePrice;

        return $totalPrice;
    }


    public function getTotalPriceWithAdditionsAttribute()
    {
        $total = $this->total_price;
        foreach ($this->additions as $addition) {
            $total += $addition->price * $addition->qty;
        }
        return $total;
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ref_num = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        });
    }
    public function getNextUser()
    {
        $user = null;
        $last_user=$this->statuses->sortByDesc('created_at')->first();
        $project_users =ProjectUser::query()->where('project_id',$this->project->id)
            ->where('management_type', 'purchase_order')
            ->orderBy('order')->get();
        if ($last_user->status_id==1){
            $next=$project_users->first();
        }
        else {
            if ($project_users->contains('user_id',$last_user->sender_id))
            {
                $project_user = $project_users->where('user_id', $last_user->sender_id)->first();
//                $next = $project_user->next();
                $next = $project_users->skipWhile(function ($item) use ($project_user) {
                    return $item->id != $project_user->id;
                })->skip(1)->first();

            }
        }
        if ($next){
            $user= User::query()->find($next->user_id);
        }
        return $user;


    }
    public function finishDisbursementOrder()
    {
        $order_items=$this->items()->pluck('id')->toArray();
        $disbursement_order_items=$this->disbursementOrders()->pluck('order_item_id')->toArray();
     $diff=   array_diff($order_items,$disbursement_order_items);
        if ($diff=== []){
            return true;
        }
        else{
            return false;
        }

    }
}
