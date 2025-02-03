<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use App\Filament\Imports\ItemImporter;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ItemResource\Pages;
use App\Filament\Admin\Resources\ItemResource\RelationManagers;
use PhpParser\Node\Stmt\Label;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;
    protected static ?string $modelLabel = '  العنصر';
    protected static ?string $pluralModelLabel = '  العناصر';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label('الرقم')
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->label('الوصف')
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit')
                    ->label('الوحدة')
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->label('الكمية')
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit_price')
                    ->label('سعر الوحدة')
                    ->maxLength(255),
                Forms\Components\TextInput::make('discount_percentage')
                    ->label('نسبة الخصم')
                    ->maxLength(255),
                Forms\Components\TextInput::make('discounted_price')
                    ->label('السعر بعد الخصم')
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_price')
                    ->label('السعر الكلي')
                    ->maxLength(255),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('العناصر'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('number')
                ->label('الرقم')
                ->searchable(),
            Tables\Columns\TextColumn::make('description')
                ->label('الوصف')
                ->searchable(),
            Tables\Columns\TextColumn::make('unit')
                ->label('الوحدة')
                ->searchable(),
            Tables\Columns\TextColumn::make('quantity')
                ->label('الكمية')
                ->searchable(),
            Tables\Columns\TextColumn::make('unit_price')
                ->label('سعر الوحدة')
                ->searchable(),
            Tables\Columns\TextColumn::make('discount_percentage')
                ->label('نسبة الخصم')
                ->searchable(),
            Tables\Columns\TextColumn::make('discounted_price')
                ->label('السعر بعد الخصم')
                ->searchable(),
            Tables\Columns\TextColumn::make('total_price')
                ->label('السعر الكلي')
                ->searchable(),
            Tables\Columns\TextColumn::make('project.name')
                ->label('اسم المشروع')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('تاريخ الإنشاء')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('تاريخ التعديل')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->headerActions([
                ImportAction::make('items')
                ->importer(ItemImporter::class)
                ->headerOffset(1)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
