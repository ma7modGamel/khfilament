<?php

namespace App\Filament\SuperAdmin\Resources\ProjectIndexResource\Pages;

use App\Filament\SuperAdmin\Resources\ProjectIndexResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectIndex extends EditRecord
{
    protected static string $resource = ProjectIndexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    public function getRelationManagers(): array
    {
        return [];
    }
}
