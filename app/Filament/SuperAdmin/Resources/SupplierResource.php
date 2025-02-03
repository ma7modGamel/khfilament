<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\SupplierResource\Pages;
use App\Filament\SuperAdmin\Resources\SupplierResource\RelationManagers;
use App\Models\Contractor;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static ?string $modelLabel = '  مورد';
    protected static ?string $pluralModelLabel = '  الموردين';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'المقاولين والموردين';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->unique(Supplier::class, 'email',ignoreRecord: true)
                    ->validationMessages( [
                        'unique' => 'البريد الإلكتروني موجود مسبقاً',
                    ])
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->unique(Supplier::class, 'phone',ignoreRecord: true)
                    ->validationMessages( [
                        'unique' => 'رقم الهاتف موجود مسبقاً',
                    ])
                    ->maxLength(255),
                Forms\Components\TextInput::make('unified_number')
                    ->label('الرقم الموحد للمنشأة')
                    ->required()
                    ->unique(Supplier::class, 'unified_number',ignoreRecord: true)
                    ->validationMessages( [
                        'unique' => 'الرقم الموحد موجود مسبقاً',
                    ])
                    ->maxLength(255),
                Select::make('city_id')
                    ->relationship('city', 'name')
                    ->required()
                    ->preload()
                    ->label(__('المدينة')),
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
                    ->label(__('النوع')),
                Forms\Components\TextInput::make('register_number')
                    ->label(__('رقم السجل'))
                    ->maxLength(255),

                Forms\Components\TextInput::make('bank_name')
                    ->label('اسم البنك')
                    ->maxLength(255),

                Forms\Components\TextInput::make('iban_number')
                    ->label('رقم الابيان')
                    ->maxLength(255),
                Forms\Components\TextInput::make('delivery_name')
                    ->label('اسم عامل التوصيل')
                    ->maxLength(255),
                Forms\Components\TextInput::make('delivery_phone')
                    ->label('رقم هاتف عامل التوصيل')
                    ->tel()
                    ->maxLength(255),


                Forms\Components\TextInput::make('manger_name')
                    ->label('اسم المسئول')
                    ->maxLength(255),
                Forms\Components\TextInput::make('manger_phone')
                    ->label('رقم هاتف المسئول')
                    ->tel()
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
                    ->searchable()->label('اسم عامل التوصيل'),
                Tables\Columns\TextColumn::make('delivery_phone')
                    ->searchable()->label('رقم هاتف عامل التوصيل'),

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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'view' => Pages\ViewSupplier::route('/{record}'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}