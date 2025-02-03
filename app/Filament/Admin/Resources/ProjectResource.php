<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Title;
use App\Models\Project;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use App\Filament\Imports\ItemImporter;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ProjectResource\Pages;
use App\Filament\Admin\Resources\ProjectResource\RelationManagers;

class ProjectResource extends Resource
{
    public static function canViewAny(): bool
    {
        return false;
    }

    protected static ?string $model = Project::class;
    protected static ?string $modelLabel = '  مشروع';
    protected static ?string $pluralModelLabel = '  المشاريع';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label(__('اسم المشروع'))
                    ->maxLength(255),

                Select::make('city_id')
                    ->relationship('city', 'name')
                    ->required()
                    ->preload()
                    ->label(__('المدينة')),

                FileUpload::make('attachments')
                    // ->required()
                    ->label(__(' ملف الكميات'))->columnSpan('full'),



                Select::make('employees')
                    ->relationship('employees', 'name')
                    ->required()
                    ->preload()
                    ->multiple()
                    ->columnSpan('full')
                    ->label(__('الموظفون')),

                Repeater::make('employee')

                    ->label(__('الادارة'))
                    ->relationship('projectEmployees')
                    ->schema([


                        Select::make('title_id')
                            ->relationship('titles', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->label(__('المسمي الوطيفي')),

                        Select::make('user_id')
                            ->label(__('الموظفون'))
                            ->options(fn (Get $get): Collection => User::query()
                                ->where('title_id', $get('title_id'))
                                ->pluck('name', 'id')),


                        TextInput::make('order')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('الترتيب '),
                    ])
                    // ->reorderable(true)
                    // ->reorderableWithButtons(true)
                    // ->reorderableWithDragAndDrop(true)
                    ->columns(3)->columnSpan('full'),



            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('المعرف'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('الاسم'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('العنوان'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // ImportAction::make('items')
                // ->importer(ItemImporter::class)
                // ->headerOffset(1)


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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}