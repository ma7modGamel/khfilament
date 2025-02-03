<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\ProjectResource\Pages;
use App\Filament\SuperAdmin\Resources\ItemResource\Pages\EditItem;
use App\Filament\SuperAdmin\Resources\ItemResource\Pages\ListItems;
use App\Filament\SuperAdmin\Resources\ItemResource\Pages\CreateItem;
use App\Filament\SuperAdmin\Resources\ProjectResource\RelationManagers;

class ProjectResource extends Resource
{
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

//                FileUpload::make('attachments')
//                    // ->required()
//                    ->label(__(' ملف الكميات'))->columnSpan('full'),


                Select::make('employees')
                    ->relationship('projectUserEmployees', 'name')
                        ->options(function (){
                            return User::query()->whereHas('roles', function ($query) {
                                $query->where('name','موظف');
                            })->pluck('name', 'id');
                    })
                    ->required()
                    ->preload()
                    ->multiple()
                    ->columnSpan('full')
                    ->label(__('الموظفون')),
                    Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('ادارة طلب الشراء')
                            ->schema([
                                Repeater::make('purchase_order_management')
                                    ->label(__('ادارة طلب الشراء'))
                                    ->relationship('purchaseOrderEmployees')
                                    ->schema(self::getEmployeeRepeaterSchema('purchase_order'))
                                    ->columns(3)
                                    ->columnSpan('full'),
                            ]),
                        Tabs\Tab::make('ادارة اذن الصرف')
                            ->schema([
                                Repeater::make('disbursement_order_management')
                                    ->label(__('ادارة اذن الصرف'))
                                    ->relationship('disbursementOrderEmployees')
                                    ->schema(self::getEmployeeRepeaterSchema('disbursement_order'))
                                    ->columns(3)
                                    ->columnSpan('full'),
                            ]),
                    ])->columnSpan('full'),
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
                ToggleColumn::make('disabled')->label('منتهى')

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // ImportAction::make('items')
                // ->importer(ItemImporter::class)
                // ->headerOffset(1)
                Action::make('ادارة الكميات')
                    ->color('success')
                    ->icon('heroicon-m-academic-cap')
                    ->url(
                        fn(Project $record): string => static::getUrl('items.index', [
                            'parent' => $record->id,
                        ])

                    )


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

            'items.index' => ListItems::route('/{parent}/items'),
            'items.create' => CreateItem::route('/{parent}/items/create'),
            'items.edit' => EditItem::route('/{parent}/items/{record}/edit'),
        ];
    }
    private static function getEmployeeRepeaterSchema(string $managementType): array
    {
        return [
            Select::make('title_id')
                ->relationship('titles', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->live()
                ->label(__('المسمي الوطيفي')),

            Select::make('user_id')
                ->label(__('الموظفون'))
                ->options(fn(Get $get): Collection => User::query()
                    ->where('title_id', $get('title_id'))
                    ->pluck('name', 'id')),

            TextInput::make('order')
                ->required()
                ->numeric()
                ->minValue(1)
                ->label('الترتيب '),

            Forms\Components\Hidden::make('management_type')
                ->default($managementType),
        ];
    }
}
