<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\UserResource\Pages;
use App\Filament\SuperAdmin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = '  موظف';
    protected static ?string $pluralModelLabel = '  الموظفين';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label(__('الاسم'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label(__('الكود'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)

                    ->label(__(' البريد الإلكتروني الرسمي'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('second_email')
                    ->email()
                    // ->required()
                    ->label(__(' البريد الإلكتروني '))
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label(__('كلمة المرور'))
                    ->password()
                    // ->visibleOn('create')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))

                    // ->revealable()->dehydrateStateUsing(fn ($state) => Hash::make($state))

                    ->required(fn (string $context): bool => $context === 'create'),


                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->label(__('الأدوار'))
                    ->preload(),
                // ->columnSpanFull()
                Select::make('title_id')
                    ->relationship('titles', 'name')
                    // ->required()
                    ->preload()
                    ->label(__('مسمي وظيفي')),
                FileUpload::make('signature')
                    ->directory('signatures')
                    ->image()
                    ->label(__('التوقيع')),


                Toggle::make('approved')
                    ->label(__(' الموافقة')),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('الاسم'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('الكود'))
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('البريد الإلكتروني'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('titles.name')
                    ->label(__('المسمى الوظيفى'))
                    ->searchable()->toggleable(),
                TextColumn::make('roles')
                    ->label('الادوار')
                    ->color('success')->formatStateUsing(function ($record){
                        return $record->roles->pluck('name')->implode(',');
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('تاريخ الإنشاء'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('تاريخ التحديث'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('approved')
                    ->label(__('تم الموافقة'))
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
