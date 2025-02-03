<?php

namespace App\Filament\Admin\Resources;

use App\Models\CompetitionPrice;
use App\Models\Contractor;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompetitionResource extends Resource
{
    protected static ?string $model = CompetitionPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'قسم المنافسات';
    protected static ?string $modelLabel = 'عروض الاسعار';
    protected static ?string $pluralModelLabel = 'عروض الاسعار - قسم المنافسات';
    protected static ?int $navigationSort = -1;
    public static function canCreate(): bool
    {
        if (auth()->user()->titles()->where('slug', 'tenders-department')->exists()) {
            return true;
        }
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('project_id')->label('المشروع')
                    ->relationship('project', 'name')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return "{$record->name}";
                    })
                    ->preload()
                    ->required()
                    ->live()
                    ->searchable()->columnSpanFull(),
                Forms\Components\MorphToSelect::make('modelable')->label('النوع')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(Contractor::class)->label('مقاول')->titleAttribute('name'),
                        Forms\Components\MorphToSelect\Type::make(Supplier::class)->label('مورد')->titleAttribute('name'),

                    ])->required()->searchable()->preload()->columnSpan('full'),
                Select::make('items')
                    ->multiple()
                    ->searchable()
                    ->relationship('items', 'number', function (Builder $query,$get) {
                        $query->where('project_id',$get('project_id'));
                    })
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return " البند:$record->number-{$record->description}";
                    })
                    ->label(__('البنود'))
                    ->preload()
                    ->columnSpanFull(),


                TextInput::make('price')
                    ->label('السعر')
                    ->numeric()
                    ->required(),
                TextInput::make('qty')
                    ->label('الكمية')
                    ->numeric()
                    ->default(1)
                    ->required(),

                FileUpload::make('file')
                    ->label('ملف'),

                Checkbox::make('approved')
                    ->hidden(fn(string $context): bool => $context === 'create'),
                Hidden::make('sender_id')
                    ->default(auth()->user()->id),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
//            ->groups([
//                Group::make('project.name')
//                    ->label('المشروع')
//                    ->titlePrefixedWithLabel(false)
//                    ->collapsible()
//
//            ])->defaultGroup('project.name')->groupingDirectionSettingHidden()
            ->columns([
                 Tables\Columns\TextColumn::make('sender.name')->label('مقدم عرض السعر')
                     ->searchable()
                     ->sortable(),
                Tables\Columns\TextColumn::make('project.name')->label(' اسم المشروع')
                    ->searchable()
                    ->sortable(),
//                TextColumn::make('id')
//                    ->label('عروض الاسعار')
//                    ->summarize(Tables\Columns\Summarizers\Count::make()),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ الانشاء ')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => \App\Filament\Admin\Resources\CompetitionResource\Pages\ListCompetitions::route('/'),
            'create' => \App\Filament\Admin\Resources\CompetitionResource\Pages\CreateCompetition::route('/create'),
            'edit' => \App\Filament\Admin\Resources\CompetitionResource\Pages\EditCompetition::route('/{record}/edit'),
            'view' => \App\Filament\Admin\Resources\CompetitionResource\Pages\ViewCompetition::route('/{record}'),
        ];
    }
//    public static function getEloquentQuery(): Builder
//    {
//        return parent::getEloquentQuery()
//            ->select(DB::raw(' count(*) as `count`,competition_id'))
//            ->groupBy(['competition_id'])
//            ->orderBy('competition_id', 'desc');
//
//    }
}
