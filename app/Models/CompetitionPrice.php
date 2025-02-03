<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CompetitionPrice extends Model
{
    protected $table = 'competitions_prices';
    use HasFactory;
    protected $guarded=[];

    public function modelable(): MorphTo
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'competitions_prices_items', 'competitions_price_id', 'item_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

}
