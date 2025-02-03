<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LeaveRequestResource\Pages;
use App\Filament\Admin\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'إدارة الطلبات العامة';
    protected static ?string $modelLabel = ' طلب أجازة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->label('الموظف')
                    ->relationship('employee', 'name')
                    ->required(),
                Forms\Components\Select::make('leave_type')
                    ->label('نوع الإجازة')
                    ->options([
                        'سنوية' => 'سنوية',
                        'مرضية' => 'مرضية',
                        'طارئة' => 'طارئة',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('تاريخ البداية')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('تاريخ النهاية')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('ملاحظات')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')->label('الموظف'),
                Tables\Columns\TextColumn::make('leave_type')->label('نوع الإجازة'),
                Tables\Columns\TextColumn::make('start_date')->label('تاريخ البداية'),
                Tables\Columns\TextColumn::make('end_date')->label('تاريخ النهاية'),
                Tables\Columns\TextColumn::make('status')->label('الحالة'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
