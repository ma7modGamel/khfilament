<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    public static function canViewAny(): bool
    {
        return false;
    }
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
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique()

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
                    ->preload()
                // ->columnSpanFull()
                ,




                Select::make('title_id')
                    ->relationship('titles', 'name')
                    // ->required()
                    ->preload()
                    ->label(__('مسمي وظيفي')),

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
                Tables\Columns\TextColumn::make('email')
                    ->label(__('البريد الإلكتروني'))
                    ->searchable(),
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
