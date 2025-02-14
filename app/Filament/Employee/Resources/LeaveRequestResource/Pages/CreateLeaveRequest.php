<?php

namespace App\Filament\Employee\Resources\LeaveRequestResource\Pages;

use App\Filament\Employee\Resources\LeaveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;
}
