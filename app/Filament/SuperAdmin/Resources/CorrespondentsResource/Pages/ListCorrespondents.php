<?php

namespace App\Filament\SuperAdmin\Resources\CorrespondentsResource\Pages;

use App\Filament\SuperAdmin\Resources\CorrespondentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCorrespondents extends ListRecords
{
    protected static string $resource = CorrespondentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
