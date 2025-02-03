<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\OrderQuatitionResource\Pages;
use App\Filament\Employee\Resources\OrderQuatitionResource\RelationManagers;
use App\Models\OrderQuatition;
use App\Models\OrderQuotation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderQuatitionResource extends Resource
{
    protected static ?string $model = OrderQuotation::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $modelLabel = 'عروض الاسعار';
    protected static ?string $pluralModelLabel = 'عروض الاسعار';

    public static string $parentResource = ProjectsWithPurchaseOrderResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                TextColumn::make('OrderItem.item.number')
                    ->label(__('رقم البند'))
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->label(__('المورد'))
                    ->searchable(),
                TextColumn::make('supplier.type.type')
                    ->label(__('نوع المورد'))
                    ->searchable(),
                TextColumn::make('price')
                    ->label(__('السعر'))
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrderQuatitions::route('/'),
        ];
    }
//    public static function getEloquentQuery(): Builder
//    {
//        return parent::getEloquentQuery()->whereHas('OrderItem',function ($query) use ($record){
//            $query->whereHas('purchaseOrder',function ($query) use ($record){
//                $query->where('project_id',$record->id);
//            });
//
//        });
//    }
}
