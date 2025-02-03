<?php

namespace App\Filament\SuperAdmin\Resources\ItemResource\Pages;

use App\Filament\SuperAdmin\Resources\ItemResource;
use App\Imports\ItemsImport;
use App\Traits\ParentResource\HasParentResource;
use Filament\Actions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ListItems extends ListRecords
{
    use HasParentResource;

    protected static string $resource = ItemResource::class;


    protected function getHeaderActions(): array
    {
        session()->put('project_id', $this->parent->id);


        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("primary")
                ->use(ItemsImport::class)
                ->label("Import Items"),
            Actions\CreateAction::make(),
        ];
    }
//    public static function getParentResourceId()
//    {
//        dd($this)
//    }

}

