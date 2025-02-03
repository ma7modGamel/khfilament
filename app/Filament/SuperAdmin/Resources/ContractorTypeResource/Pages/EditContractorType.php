<?php

namespace App\Filament\SuperAdmin\Resources\ContractorTypeResource\Pages;

use App\Filament\SuperAdmin\Resources\ContractorTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContractorType extends EditRecord
{
    protected static string $resource = ContractorTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
