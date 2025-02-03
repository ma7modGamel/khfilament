<?php

namespace App\Filament\SuperAdmin\Resources\ProjectIndexResource\Pages;

use App\Filament\SuperAdmin\Resources\ProjectIndexResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectIndex extends ViewRecord
{
    protected static string $resource = ProjectIndexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
