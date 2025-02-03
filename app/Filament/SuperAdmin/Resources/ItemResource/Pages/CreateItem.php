<?php

namespace App\Filament\SuperAdmin\Resources\ItemResource\Pages;

use App\Filament\SuperAdmin\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;
}
