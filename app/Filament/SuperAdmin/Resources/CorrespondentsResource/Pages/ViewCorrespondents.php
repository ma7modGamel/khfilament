<?php

namespace App\Filament\SuperAdmin\Resources\CorrespondentsResource\Pages;

use App\Filament\SuperAdmin\Resources\CorrespondentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCorrespondents extends ViewRecord
{
    protected static string $resource = CorrespondentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
