<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function prices()
    {
        return $this->hasMany(CompetitionPrice::class, 'competition_id');
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
