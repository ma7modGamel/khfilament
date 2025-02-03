<?php

namespace App\Filament\SuperAdmin\Resources\CorrespondenceDocumentsResource\Pages;

use App\Filament\SuperAdmin\Resources\CorrespondenceDocumentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCorrespondenceDocuments extends ListRecords
{
    protected static string $resource = CorrespondenceDocumentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
