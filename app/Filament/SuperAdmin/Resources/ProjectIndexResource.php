<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\ItemResource\Pages\ListItems;
use App\Filament\SuperAdmin\Resources\ProjectIndexContentResource\Pages\ListProjectIndexContents;
use App\Filament\SuperAdmin\Resources\ProjectIndexResource\Pages;
use App\Filament\SuperAdmin\Resources\ProjectIndexResource\RelationManagers\ContentRelationManager;
use App\Models\ProjectIndex;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectIndexResource extends Resource
{
    protected static ?string $model = ProjectIndex::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = '  فهرس المشروع';
    protected static ?string $pluralModelLabel = '  فهرس المشاريع';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required()
                    ->preload()
                    ->columnSpan('full')
                    ->label(__('المشروع')),
                Forms\Components\Section::make()
                    ->schema(self::getProjectIndexContentSchema()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
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
//            ContentRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectIndices::route('/'),
            'create' => Pages\CreateProjectIndex::route('/create'),
            'view' => Pages\ViewProjectIndex::route('/{record}'),
            'edit' => Pages\EditProjectIndex::route('/{record}/edit'),

        ];
    }
    private static function getProjectIndexContentSchema(): array
    {
        return [
            //العقود
            Repeater::make('Contracts')
                ->label(__('العقود'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'contracts') )
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('الاسم'))
                        ->maxLength(255),
                    FileUpload::make('attachment')
                        ->label('ملف PDF ')
                        ->acceptedFileTypes(['application/pdf'])
                        ->openable()->downloadable()
                        ->directory('contracts'),
                    Forms\Components\Hidden::make('content_type')
                        ->default('contracts'),
                ])
                ->columns(2)
                ->columnSpan('full'),
            //الاعتمادات
            Repeater::make('Accreditations')
                ->label(__('الاعتمادات'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'accreditations') )

                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('اسم المهندس'))
                        ->maxLength(255),
                    FileUpload::make('attachment')
                        ->label('ملف PDF ')
                        ->acceptedFileTypes(['application/pdf'])
                        ->openable()->downloadable()
                        ->directory('accreditations'),
                    Forms\Components\Hidden::make('content_type')
                        ->default('accreditations'),
                ])
                ->columns(2)
                ->columnSpan('full'),
            //الاصول
            Repeater::make('Assets')
                ->label(__('الاصول'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'assets') )

                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('اسم المسؤل'))
                        ->maxLength(255),
                    FileUpload::make('attachment')
                        ->label('ملف PDF ')
                        ->acceptedFileTypes(['application/pdf'])
                        ->openable()->downloadable()
                        ->directory('assets'),
                    Forms\Components\Hidden::make('content_type')
                        ->default('assets'),
                ])
                ->columns(2)
                ->columnSpan('full'),
            //العمال
            Repeater::make('Workers')
                ->label(__('العمال'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'workers') )

                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('اسم العامل'))
                        ->maxLength(255),
                    Forms\Components\Hidden::make('content_type')
                        ->default('workers'),
                ])
                ->columns(1)
                ->columnSpan('full'),
            //المواصفات
            Repeater::make('Specifications')
                ->label(__('المواصفات'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'specifications') )

                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('اسم المواصفة'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('type')
                        ->required()
                        ->label(__('النوع'))
                        ->maxLength(255),
                    FileUpload::make('attachment')
                        ->label('ملف PDF ')
                        ->acceptedFileTypes(['application/pdf'])
                        ->openable()->downloadable()
                        ->directory('specifications'),
                    Forms\Components\Hidden::make('content_type')
                        ->default('specifications'),
                ])
                ->columns(3)
                ->columnSpan('full'),
            //العينات المعتمده
            Repeater::make('Approved Samples')
                ->label(__('العينات المعتمده'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'approved_samples') )

                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('اسم العينة'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('type')
                        ->required()
                        ->label(__('النوع'))
                        ->maxLength(255),
                    FileUpload::make('attachment')
                        ->label('ملف PDF ')
                        ->acceptedFileTypes(['application/pdf'])
                        ->openable()->downloadable()
                        ->directory('approved_samples'),
                    Forms\Components\Hidden::make('content_type')
                        ->default('approved_samples'),
                ])
                ->columns(3)
                ->columnSpan('full'),
            //مستخلصات الجهه المالكه
            Repeater::make('Extracts From The Owner')
                ->label(__('مستخلصات الجهه المالكة'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'extracts') )

                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('اسم المستخلص'))
                        ->maxLength(255),
                    FileUpload::make('attachment')
                        ->label('ملف PDF ')
                        ->acceptedFileTypes(['application/pdf'])
                        ->openable()->downloadable()
                        ->directory('extracts'),
                    Forms\Components\Hidden::make('content_type')
                        ->default('extracts'),
                ])
                ->columns(2)
                ->columnSpan('full'),
            //الدفعات المستلمة
            Repeater::make('Payments Received')
                ->label(__('الدفعات المستلمة'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'payments') )

                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('اسم الدفعة'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->label(__('المبلغ'))
                        ->maxValue(42949672.95),

                    FileUpload::make('attachment')
                        ->label('ملف PDF ')
                        ->acceptedFileTypes(['application/pdf'])
                        ->openable()->downloadable()
                        ->directory('payments'),
                    Forms\Components\Hidden::make('content_type')
                        ->default('payments'),
                ])
                ->columns(3)
                ->columnSpan('full'),
            //المخططات
            Repeater::make('Charts')
                ->label(__('المخططات'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'charts') )

                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('اسم المخطط'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('type')
                        ->required()
                        ->label(__('النوع'))
                        ->maxLength(255),
                    FileUpload::make('attachment')
                        ->label('ملف PDF ')
                        ->acceptedFileTypes(['application/pdf'])
                        ->openable()->downloadable()
                        ->directory('charts'),
                    Forms\Components\Hidden::make('content_type')
                        ->default('charts'),
                ])
                ->columns(3)
                ->columnSpan('full'),
            //المقاولين
            Repeater::make('Contractors')
                ->label(__('المقاولين'))
                ->relationship('content',
                    modifyQueryUsing: fn (Builder $query) =>
                    $query->where('content_type', 'contractors') )

                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('اسم المقاول'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('type')
                        ->required()
                        ->label(__('نوع العمل'))
                        ->maxLength(255),
                    Forms\Components\Hidden::make('content_type')
                        ->default('contractors'),
                ])
                ->columns(2)
                ->columnSpan('full'),



        ];
    }
}
