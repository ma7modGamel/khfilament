<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrespondenceDocuments extends Model
{
    use HasFactory;

    protected $fillable = ['type','need_total_value'];
    protected $casts = [
        'need_total_value' => 'boolean',
    ];
}
