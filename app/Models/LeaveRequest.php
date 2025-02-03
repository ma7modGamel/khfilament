<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
