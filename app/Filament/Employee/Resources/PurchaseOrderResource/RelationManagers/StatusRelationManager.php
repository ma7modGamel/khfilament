<?php

namespace App\Filament\Employee\Resources\PurchaseOrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class StatusRelationManager extends RelationManager
{
    protected static string $relationship = 'Statuses';
    protected static ?string $modelLabel = '  حاله الطلب';
    protected static ?string $pluralModelLabel = 'حالات الطلب';
    protected static ?string $label = 'أوامر الصرف'; // اسم التبويب باللغة العربية

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('status_id')
                    ->relationship('status', 'name')
                    ->native(false)
                    ->required()
                    ->label(__('تغيير الحالة'))->columnSpanFull(),

                Forms\Components\Textarea::make('notes')
                    ->label(__('الملاحظات'))
                    ->rows(4)
                    ->cols(5)
                    ->columnSpanFull(),
                Hidden::make('sender_id')
                    ->default(auth()->user()->id),
            ])->mutateFormDataUsing(function (array $data): array {
                dd($this->data);


                return $data;
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('sender.name')->label('مقدم الطلب'),
                Tables\Columns\TextColumn::make('status.name')->label('الحالة '),
                Tables\Columns\TextColumn::make('notes')->label('ملاحظات '),

                Tables\Columns\TextColumn::make('created_at')->label('تاريخ انشاء  '),
            ])
            ->filters([
                //
            ])
            ->headerActions([


                Tables\Actions\CreateAction::make()
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}