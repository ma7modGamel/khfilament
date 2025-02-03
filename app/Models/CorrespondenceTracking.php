<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorrespondenceTracking extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $casts = [
        'request_date' => 'datetime:Y-m-d',
    ];
    public function correspondence() :BelongsTo{
        return $this->belongsTo(Correspondence::class);
    }
    public function fromUser() :BelongsTo{
        return $this->belongsTo(User::class,'from_user_id');
    }
    public function toUser() :BelongsTo{
        return $this->belongsTo(User::class,'to_user_id');
    }

}
