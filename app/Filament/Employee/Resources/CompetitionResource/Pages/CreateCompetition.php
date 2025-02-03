<?php

namespace App\Filament\Employee\Resources\CompetitionResource\Pages;

use App\Filament\Employee\Resources\CompetitionResource;
use App\Models\City;
use App\Models\Competition;
use App\Models\Contractor;
use App\Models\ContractorType;
use App\Models\ContractType;
use App\Models\Supplier;
use Closure;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Hamcrest\Core\Set;

class CreateCompetition extends CreateRecord
{
    protected static string $resource = CompetitionResource::class;
    protected function beforeFill(): void
    {
        if (auth()->user()->signature == null) {
            Notification::make()
                ->title('Warning')
                ->body('يجب ان تقوم برفع توقيعك اولا')
                ->send();
            $this->redirect('employee/profile');
        }
    }

    protected function getActions(): array
    {
        return [
            Action::make('addSupplier')
                ->label('اضافة مورد')
                ->color('primary')
                ->form([

                    Grid::make(3)
                        ->schema([
                            TextInput::make('name')
                                ->label('الاسم')
                                ->required(),
                            TextInput::make('email')
                                ->label('البريد الالكتروني')
                                ->email()
                                ->unique('suppliers', 'email')
                                ->required(),
                            TextInput::make('phone')
                                ->label('رقم الهاتف')
                                ->unique('suppliers', 'phone')
                                ->required(),

                        ]),
                    Grid::make(3)
                        ->schema([
                            Select::make('city_id')
                                ->options(
                                    City::query()->pluck('name','id')
                                )
                                ->preload()
                                ->label('اسم المدينه')
                                ->required(),
                            TextInput::make('address')
                                ->label('العنوان')
                                ->required(),
                            TextInput::make('national_address')
                                ->label(' العنوان الوطنى'),
                            FileUpload::make('national_address_file')
                                ->label('ملف العنوان الوطنى'),
                            TextInput::make('register_number')
                                ->label('رقم السجل')
                                ->required(),
                        ]),

                    Grid::make(3)
                        ->schema([

                            TextInput::make('tax_number')
                                ->label('الرقم الضريبي ')
                                ->required(),
                            TextInput::make('delivery_name')
                                ->label('اسم عامل التوصيل')
                                ->required(),
                            TextInput::make('delivery_phone')
                                ->label('رقم الهاتف لعامل التوصيل')
                                ->required(),
                        ]),
                    Grid::make(2)
                        ->schema([
                            TextInput::make('bank_name')
                                ->label('اسم البنك ')
                                ->required(),
                            TextInput::make('iban_number')
                                ->label('رقم الابيان')
                                ->required(),
                        ]),

                    Grid::make(1)
                        ->schema([
                            FileUpload::make('files')
                                ->label('رفع المستندات')
                                ->multiple()
                                ->required()
                                ->columnSpan(1)
                                ->extraAttributes([
                                    'class' => 'text-center'
                                ]),
                        ]),
                ])
                ->action(function ($data) {
                    Supplier::create($data);
                    Notification::make()
                        ->title('تم اضافة المورد بنجاح')
                        ->success()
                        ->send();
                }),
            Action::make('addContractor')
                ->label('اضافة مقاول')
                ->color('primary')
                ->form([

                    Grid::make(3)
                        ->schema([
                            Select::make('type_id')
                                ->options(
                                    ContractorType::query()->pluck('type','id')
                                )
                                ->required()
                                ->preload()
                                ->label(__('نوع المقاول'))->columnSpanFull(),
                            TextInput::make('name')
                                ->label('الاسم')
                                ->required(),
                            TextInput::make('email')
                                ->label('البريد الالكتروني')
                                ->email()
                                ->unique('suppliers', 'email')
                                ->required(),
                            TextInput::make('phone')
                                ->label('رقم الهاتف')
                                ->unique('suppliers', 'phone')
                                ->required(),

                        ]),
                    Grid::make(3)
                        ->schema([
                            Select::make('city_id')
                                ->options(
                                    City::query()->pluck('name','id')
                                )
                                ->preload()
                                ->label('اسم المدينه')
                                ->required(),
                            TextInput::make('address')
                                ->label('العنوان')
                                ->required(),
                            TextInput::make('national_address')
                                ->label(' العنوان الوطنى'),
                            FileUpload::make('national_address_file')
                                ->label('ملف العنوان الوطنى'),
                            TextInput::make('register_number')
                                ->label('رقم السجل')
                                ->required(),
                        ]),

                    Grid::make(3)
                        ->schema([

                            TextInput::make('tax_number')
                                ->label('الرقم الضريبي ')
                                ->required(),
                            TextInput::make('delivery_name')
                                ->label('اسم عامل التوصيل')
                                ->required(),
                            TextInput::make('delivery_phone')
                                ->label('رقم الهاتف لعامل التوصيل')
                                ->required(),
                        ]),
                    Grid::make(2)
                        ->schema([
                            TextInput::make('bank_name')
                                ->label('اسم البنك ')
                                ->required(),
                            TextInput::make('iban_number')
                                ->label('رقم الابيان')
                                ->required(),
                        ]),

                    Grid::make(1)
                        ->schema([
                            FileUpload::make('files')
                                ->label('رفع المستندات')
                                ->multiple()
                                ->required()
                                ->columnSpan(1)
                                ->extraAttributes([
                                    'class' => 'text-center'
                                ]),
                        ]),
                ])
                ->action(function ($data) {
                    Contractor::create($data);
                    Notification::make()
                        ->title('تم اضافة المقاول بنجاح')
                        ->success()
                        ->send();
                }),


        ];
    }

}
