<?php

namespace App\Filament\Admin\Resources\ItemResource\Pages;

use Filament\Actions;
use App\Filament\Imports\ItemImporter;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\ImportAction;
use App\Filament\Admin\Resources\ItemResource;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // ImportAction::make('items')
            //     ->importer(ItemImporter::class)
            //     ->headerOffset(1)
        ];
    }
}
