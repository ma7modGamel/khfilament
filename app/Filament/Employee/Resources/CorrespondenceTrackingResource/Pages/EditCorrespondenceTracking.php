<?php

namespace App\Filament\Employee\Resources\CorrespondenceTrackingResource\Pages;

use App\Filament\Admin\Resources\CorrespondenceTrackingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorrespondenceTracking extends EditRecord
{
    protected static string $resource = CorrespondenceTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
