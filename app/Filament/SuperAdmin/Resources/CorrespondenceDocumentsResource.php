<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\CorrespondenceDocumentsResource\Pages;
use App\Filament\SuperAdmin\Resources\CorrespondenceDocumentsResource\RelationManagers;
use App\Models\CorrespondenceDocuments;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CorrespondenceDocumentsResource extends Resource
{
    protected static ?string $model = CorrespondenceDocuments::class;
    protected static ?string $modelLabel = '  مستند المراسله';
    protected static ?string $pluralModelLabel = '  مستندات المراسله';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'إعدادات المراسلة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->label(__('النوع'))
                    ->maxLength(255)
                ->columnSpanFull(),
                Forms\Components\Checkbox::make('need_total_value')
                    ->inline()
                ->label('له قيمة اجمالية')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListCorrespondenceDocuments::route('/'),
            'create' => Pages\CreateCorrespondenceDocuments::route('/create'),
            'view' => Pages\ViewCorrespondenceDocuments::route('/{record}'),
            'edit' => Pages\EditCorrespondenceDocuments::route('/{record}/edit'),
        ];
    }
}
