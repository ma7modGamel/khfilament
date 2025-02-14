<?php

namespace App\Filament\SuperAdmin\Resources\TitleResource\Pages;

use App\Filament\SuperAdmin\Resources\TitleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTitle extends EditRecord
{
    protected static string $resource = TitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
