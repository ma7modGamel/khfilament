<?php

namespace App\Filament\SuperAdmin\Resources\ProjectIndexResource\Pages;

use App\Filament\SuperAdmin\Resources\ProjectIndexResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectIndices extends ListRecords
{
    protected static string $resource = ProjectIndexResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
