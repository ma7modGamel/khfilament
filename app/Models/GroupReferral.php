<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GroupReferral extends Model
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
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_referrals_users', 'group_referral_id', 'user_id');
    }
}
