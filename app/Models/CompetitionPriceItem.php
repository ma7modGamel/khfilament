<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionPriceItem extends Model
{
    protected $table = 'competitions_prices_items';
    use HasFactory;
    protected $guarded=[];
}
