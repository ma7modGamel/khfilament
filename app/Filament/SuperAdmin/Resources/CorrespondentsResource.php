<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\CorrespondentsResource\Pages;
use App\Filament\SuperAdmin\Resources\CorrespondentsResource\RelationManagers;
use App\Models\Agency;
use App\Models\Contractor;
use App\Models\Correspondents;
use App\Models\Project;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CorrespondentsResource extends Resource
{
    protected static ?string $model = Correspondents::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = '  جهه مراسلة';
    protected static ?string $pluralModelLabel = '  جهات المراسله';
    protected static ?string $navigationGroup = 'إعدادات المراسلة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\MorphToSelect::make('modelable')->label('الجهه')
                ->types([
                    Forms\Components\MorphToSelect\Type::make(Agency::class)->label('جهه حكومية')->titleAttribute('name'),
                    Forms\Components\MorphToSelect\Type::make(Project::class)->label('مشروع')->titleAttribute('name'),
                    Forms\Components\MorphToSelect\Type::make(Supplier::class)->label('مورد')->titleAttribute('name'),
                    Forms\Components\MorphToSelect\Type::make(Contractor::class)->label('مقاول')->titleAttribute('name'),

                ])->required()->searchable()->preload()->columnSpan('full'),
                Forms\Components\TextInput::make('register_number')
                    ->label(__('رقم السجل'))
                    ->hidden(function (Forms\Get $get) {
                        return $get('modelable_type') ==Contractor::class || $get('modelable_type') ==Supplier::class;
                    })
                    ->maxLength(255),
                Forms\Components\TextInput::make('tax_number')
                    ->label(__('الرقم الضريبى'))
                    ->hidden(function (Forms\Get $get) {
                        return $get('modelable_type') ==Contractor::class || $get('modelable_type') ==Supplier::class;
                    })
                    ->maxLength(255),
                Forms\Components\FileUpload::make('files')
                    ->label(__('الملفات'))->columnSpan('full')
                    ->directory('correspondents-files')
                    ->multiple()
                    ->openable()->downloadable()
                    ->reorderable()->panelLayout('grid'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('modelable_type')
                    ->formatStateUsing(function ($state) {
                    return match ($state) {
                        Agency::class => 'جهه حكومية',
                        Supplier::class => 'مورد',
                        Contractor::class => 'مقاول',
                        Project::class => 'مشروع',
                    };
                })->label('نوع الجهه'),
                Tables\Columns\TextColumn::make('modelable.name')->label('الجهه')->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('register_number')
                    ->searchable()->label('رقم السجل ')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tax_number')
                    ->searchable()->label('الرقم الضريبى ')
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
            'index' => Pages\ListCorrespondents::route('/'),
            'create' => Pages\CreateCorrespondents::route('/create'),
            'view' => Pages\ViewCorrespondents::route('/{record}'),
            'edit' => Pages\EditCorrespondents::route('/{record}/edit'),
        ];
    }
}
