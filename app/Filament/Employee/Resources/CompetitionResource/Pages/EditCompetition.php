<?php

namespace App\Filament\Employee\Resources\CompetitionResource\Pages;

use App\Filament\Employee\Resources\CompetitionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompetition extends EditRecord
{
    protected static string $resource = CompetitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
