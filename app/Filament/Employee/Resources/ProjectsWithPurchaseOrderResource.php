<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\OrderQuatitionResource\Pages\ListOrderQuatitions;
use App\Filament\Employee\Resources\ProjectsWithPurchaseOrderResource\Pages;
use App\Filament\Employee\Resources\ProjectsWithPurchaseOrderResource\RelationManagers;
use App\Models\OrderQuotation;
use App\Models\Project;
use App\Models\ProjectsWithPurchaseOrder;
use App\Models\PurchaseOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectsWithPurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'طلباتي';
    protected static ?string $modelLabel = 'عروض الاسعار';
    protected static ?string $pluralModelLabel = 'عروض الاسعار';

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
                        fn(Project $record): string => static::getUrl('order-quatition.index', [
                            'parent' => $record->id,
                        ])

                    )
                    ->searchable(),
                TextColumn::make('purchase_orders_count')
                    ->label('عدد عروض الاسعار')
                    ->color('success')
                ->formatStateUsing(function ($record) {
                return OrderQuotation::query()->whereHas('OrderItem',function ($query) use ($record){
                        $query->whereHas('purchaseOrder',function ($query) use ($record){
                            $query->where('project_id',$record->id);
                        });

                    })->count();
                })
                    ->url(
                        fn(Project $record): string => static::getUrl('order-quatition.index', [
                            'parent' => $record->id,
                        ])

                    )
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
            'index' => Pages\ListProjectsWithPurchaseOrders::route('/'),
            'order-quatition.index' => ListOrderQuatitions::route('/{parent}/prices'),

//            'create' => Pages\CreateProjectsWithPurchaseOrder::route('/create'),
//            'edit' => Pages\EditProjectsWithPurchaseOrder::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return Project::query()->whereHas('purchaseOrders')->withCount(['purchaseOrders']);

    }
}
