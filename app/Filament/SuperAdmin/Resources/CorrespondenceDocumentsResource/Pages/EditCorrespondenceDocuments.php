<?php

namespace App\Filament\SuperAdmin\Resources\CorrespondenceDocumentsResource\Pages;

use App\Filament\SuperAdmin\Resources\CorrespondenceDocumentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorrespondenceDocuments extends EditRecord
{
    protected static string $resource = CorrespondenceDocumentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
