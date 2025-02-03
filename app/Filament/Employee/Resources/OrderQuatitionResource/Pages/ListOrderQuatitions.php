<?php

namespace App\Filament\Employee\Resources\OrderQuatitionResource\Pages;

use App\Filament\Employee\Resources\OrderQuatitionResource;
use App\Traits\ParentResource\HasParentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderQuatitions extends ListRecords
{
    use HasParentResource;
    protected static string $resource = OrderQuatitionResource::class;


}
