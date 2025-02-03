<?php

namespace App\Filament\SuperAdmin\Resources\DocumentReceiveMethodResource\Pages;

use App\Filament\SuperAdmin\Resources\DocumentReceiveMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentReceiveMethod extends EditRecord
{
    protected static string $resource = DocumentReceiveMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
