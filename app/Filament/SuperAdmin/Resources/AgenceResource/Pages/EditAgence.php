<?php

namespace App\Filament\SuperAdmin\Resources\AgenceResource\Pages;

use App\Filament\SuperAdmin\Resources\AgenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgence extends EditRecord
{
    protected static string $resource = AgenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
