<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\CompetitionPriceResource\Pages\ListCompetitionPrices;
use App\Filament\Employee\Resources\ProjectsWithPricesResource\Pages;
use App\Filament\Employee\Resources\ProjectsWithPricesResource\RelationManagers;
use App\Filament\SuperAdmin\Resources\ItemResource\Pages\ListItems;
use App\Models\CompetitionPrice;
use App\Models\Project;
use App\Models\ProjectsWithPrices;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectsWithPricesResource extends Resource
{
    protected static ?string $model = CompetitionPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'قسم المنافسات';
    protected static ?string $modelLabel = 'عروض الاسعار';
    protected static ?string $pluralModelLabel = 'عروض الاسعار';
    protected static ?int $navigationSort = -1;

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
                TextColumn::make('name')
                    ->label(__('الاسم'))
                    ->url(
                        fn(Project $record): string => static::getUrl('competition-prices.index', [
                            'parent' => $record->id,
                        ])

                    )
                    ->searchable(),
                TextColumn::make('prices_count')
                    ->label('عدد عروض الاسعار')
                    ->color('success')
                    ->url(
                        fn(Project $record): string => static::getUrl('competition-prices.index', [
                            'parent' => $record->id,
                        ])

                    )

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProjectsWithPrices::route('/'),
            'competition-prices.index' => ListCompetitionPrices::route('/{parent}/prices'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return Project::query()->whereHas('prices')->withCount(['prices']);
    }
}
