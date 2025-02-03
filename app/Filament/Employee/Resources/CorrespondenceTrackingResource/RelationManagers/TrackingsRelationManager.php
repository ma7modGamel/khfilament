<?php

namespace App\Filament\Employee\Resources\CorrespondenceTrackingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrackingsRelationManager extends RelationManager
{
    protected static string $relationship = 'trackings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('notes')
                    ->label('ملاحظات')->columnSpanFull(),
                Forms\Components\FileUpload::make('file')
                    ->label('الملف')
                    ->openable()->downloadable()
                    ->directory('referrals'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('notes')
            ->columns([
                Tables\Columns\TextColumn::make('fromUser.name')
                    ->label('من'),
                Tables\Columns\TextColumn::make('toUser.name')
                    ->label('إلى'),
                Tables\Columns\TextColumn::make('request_date')
                    ->label('تاريخ الإحالة')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('created_at')->formatStateUsing(function ($record) {
                    return$record->request_date->format('H:i ');
                })
                    ->label('وقت الإحالة'),
                Tables\Columns\TextColumn::make('notes')
                    ->label('ملاحظات'),
                ImageColumn::make('signature')->label('التوقيع')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض الملاحظة')->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
