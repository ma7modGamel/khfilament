<?php

namespace App\Filament\Employee\Resources\CompetitionPriceResource\Pages;

use App\Filament\Employee\Resources\CompetitionPriceResource;
use App\Traits\ParentResource\HasParentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompetitionPrices extends ListRecords
{
    use HasParentResource;
    protected static string $resource = CompetitionPriceResource::class;



}
