<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\ContractorResource\Pages;
use App\Models\Contractor;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractorResource extends Resource
{
    protected static ?string $model = Contractor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = '  المقاول';
    protected static ?string $pluralModelLabel = '  المقاولين';
    protected static ?string $navigationGroup = 'المقاولين والموردين';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label(__('اسم المقاول'))
                    ->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->unique(Contractor::class, 'email',ignoreRecord: true)
                    ->validationMessages( [
                        'unique' => 'البريد الإلكتروني موجود مسبقاً',
                    ])
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->unique(Contractor::class, 'phone',ignoreRecord: true)
                    ->validationMessages( [
                        'unique' => 'رقم الهاتف موجود مسبقاً',
                    ])
                    ->maxLength(255),
                Forms\Components\TextInput::make('unified_number')
                    ->label('الرقم الموحد للمنشأة')
                    ->required()
                    ->unique(Contractor::class, 'unified_number',ignoreRecord: true)
                    ->validationMessages( [
                        'unique' => 'الرقم الموحد موجود مسبقاً',
                    ])
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('العنوان'),
                Forms\Components\TextInput::make('national_address')
                    ->label('العنوان الوطنى'),
                Forms\Components\FileUpload::make('national_address_file')
                    ->label(__('ملف العنوان الوطنى'))
                    ->directory('national_address')
                    ->openable()->downloadable(),
                Select::make('type_id')
                    ->relationship('type', 'type')
                    ->required()
                    ->preload()
                    ->label(__('نوع المقاول')),
                Select::make('city_id')
                    ->relationship('city', 'name')
                    ->required()
                    ->preload()
                    ->label(__('المدينة')),
                Forms\Components\TextInput::make('bank_name')
                    ->label('اسم البنك')
                    ->maxLength(255),

                Forms\Components\TextInput::make('iban_number')
                    ->label('رقم الابيان')
                    ->maxLength(255),
                Forms\Components\TextInput::make('delivery_name')
                    ->label('اسم المندوب')
                    ->maxLength(255),
                Forms\Components\TextInput::make('delivery_phone')
                    ->label('رقم هاتف المندوب')
                    ->tel()
                    ->maxLength(255),


                Forms\Components\TextInput::make('manger_name')
                    ->label('اسم المسئول')
                    ->maxLength(255),
                Forms\Components\TextInput::make('manger_phone')
                    ->label('رقم جوال المسئول')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\TextInput::make('register_number')
                    ->label(__('رقم السجل'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('tax_number')
                    ->label(__('الرقم الضريبى'))
                    ->maxLength(255),
                Forms\Components\FileUpload::make('files')
                    ->label(__('الملفات'))->columnSpan('full')
                    ->directory('correspondents-files')
                    ->multiple()
                    ->maxSize(100240)
                    ->openable()->downloadable()
                    ->reorderable()->panelLayout('grid'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type.type')
                    ->label('النوع')->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->label('الاسم'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()->label('البريد الالكترونى'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()->label('رقم الهاتف'),
                Tables\Columns\TextColumn::make('unified_number')
                    ->searchable()->toggleable()->label('الرقم الموحد للمنشأة'),
                Tables\Columns\TextColumn::make('city.name')
                    ->searchable()->label('المدينة'),
                Tables\Columns\TextColumn::make('delivery_name')
                    ->searchable()->label('اسم المندوب'),
                Tables\Columns\TextColumn::make('delivery_phone')
                    ->searchable()->label('رقم جوال المندوب'),


                Tables\Columns\TextColumn::make('manger_name')
                    ->searchable()->label('اسم المسؤول'),
                Tables\Columns\TextColumn::make('manger_phone')
                    ->searchable()->label('رقم جوال المسؤول'),


                Tables\Columns\TextColumn::make('bank_name')
                    ->searchable()->toggleable()->label('اسم البنك'),
                Tables\Columns\TextColumn::make('iban_number')
                    ->searchable()->toggleable()->label('رقم الابيان'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('تاريخ الانشاء')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('تاريخ التعديل')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListContractors::route('/'),
            'create' => Pages\CreateContractor::route('/create'),
            'edit' => Pages\EditContractor::route('/{record}/edit'),
        ];
    }
}