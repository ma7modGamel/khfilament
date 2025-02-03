<?php

namespace App\Filament\SuperAdmin\Resources\CorrespondentsResource\Pages;

use App\Filament\SuperAdmin\Resources\CorrespondentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorrespondents extends EditRecord
{
    protected static string $resource = CorrespondentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
