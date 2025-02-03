<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\CompetitionPriceResource\Pages;
use App\Filament\Employee\Resources\CompetitionPriceResource\RelationManagers;
use App\Filament\SuperAdmin\Resources\ProjectResource;
use App\Models\CompetitionPrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetitionPriceResource extends Resource
{
    protected static ?string $model = CompetitionPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $modelLabel = 'عروض الاسعار';
    protected static ?string $pluralModelLabel = 'عروض الاسعار';

    public static string $parentResource = ProjectsWithPricesResource::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender.name')
                    ->label(__('الاسم'))
                    ->searchable(),
                TextColumn::make('modelable.name')
                    ->label(__('المقاول/المورد'))
                    ->searchable(),
                TextColumn::make('modelable.type.type')
                    ->label(__('نوع المقاول/ المورد'))
                    ->searchable(),
                TextColumn::make('items')
                    ->label('البنود')
                    ->color('success')->formatStateUsing(function ($record){
                        return $record->items->pluck('number')->implode(',');
                    }),
                TextColumn::make('price')
                    ->label(__('السعر'))
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompetitionPrices::route('/'),

        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('items')
            ->withCount(['items']);
    }

}
