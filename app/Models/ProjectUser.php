<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    use HasFactory;

    protected $guarded;

    public function employees()
    {
        return $this->belongsTo(User::class, 'user_id')

            ->whereHas('titles', function ($query) {
                $query->where('slug', 'employee');
            });
    }
    public function nonEmployees()
    {
        return $this->belongsTo(User::class, 'user_id')

            ->whereHas('titles', function ($query) {
                $query->where('slug','!=', 'employee');
            });
    }

    public function titles()
    {

        return $this->belongsTo(Title::class, 'title_id')->where('slug','!=','employee');
    }

    public function user()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
    public function next()
    {
        return $this->orderBy('id')
            ->where('id', '>', $this->id)
            ->first();
    }
}
