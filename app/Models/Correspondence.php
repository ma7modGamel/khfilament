<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Correspondence extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'date',
        'correspondent_id',
        'correspondence_document_id',
        'description',
        'file',
        'total_value',
        'type',
        'receive_method_id',
        'path'
    ];


    public function correspondent()
    {
        return $this->belongsTo(Correspondents::class);
    }


    public function correspondence_document()
    {
        return $this->belongsTo(CorrespondenceDocuments::class);
    }
    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function receive_methods():BelongsTo
    {
        return $this->belongsTo(DocumentReceiveMethod::class, 'receive_method_id');
    }
    public function trackings():HasMany
    {
        return $this->hasMany(CorrespondenceTracking::class,'correspondence_id');

    }
    public function lastTrack():HasOne
    {
        return $this->hasOne(CorrespondenceTracking::class,'correspondence_id')->latest();

    }
    public function check_user_make_referral():bool
    {
       return CorrespondenceTracking::query()->where([
            'from_user_id'=>auth()->id(),
            'correspondence_id'=>$this->id,
            ])->exists();
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'correspondence_users', 'correspondence_id', 'user_id');
    }
}
