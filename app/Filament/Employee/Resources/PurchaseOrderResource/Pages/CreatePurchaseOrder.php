<?php

namespace App\Filament\Employee\Resources\PurchaseOrderResource\Pages;

use App\Models\City;
use App\Models\User;
use Filament\Actions;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Notifications\NewPurchaseOrderNotification;
use App\Filament\Employee\Resources\PurchaseOrderResource;
use Filament\Notifications\Events\DatabaseNotificationsSent;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;

//    protected function beforeCreate()
//    {
//        //dd($this->data);
//    }

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
            Actions\Action::make('addSupplier')
                ->label('اضافة مورد جديد')
                ->color('primary')
                ->form([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('name')
                                ->label('اسم المورد')
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
                            TextInput::make('unified_number')
                                ->label('الرقم الموحد للمنشأة')
                                ->unique('suppliers', 'unified_number')
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
        ];
    }


    protected function beforeCreate(): void
    {
       // dd($this->data);
    }

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.

        $order = $this->getRecord();
//        $order->statuses()->create([
//            'status_id' => 1,
//            'sender_id' => auth()->user()->id,
//
//        ]);

        $mangerId = ($order->project?->man);
        $user = ($order->project?->users->first());

        if ($user) {
            //send email
            $user->notify(new NewPurchaseOrderNotification);
            //send notification
            Notification::make()
                ->title('لديك طلب شراء جديد')
                ->actions([
                    Action::make('view')
                            ->url('/admin/purchase-orders/'.$order->id.'/edit')
                        ->markAsRead()
                ])
                ->sendToDatabase($user);
            event(new DatabaseNotificationsSent($user));
        }
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $project_id = $data['project_id'];
        $file = $data['file'];
        $order = PurchaseOrder::query()->create([
            'sender_id' => auth()->user()->id,
            'project_id' => $project_id,
            'file' => $file,
        ]);
        foreach ($data['items'] as $item) {
            $order_item = PurchaseOrderItem::query()->create([
                'purchase_order_id' => $order->id,
                'name' => $item['item'],
                'description' => $item['description'],
                'qty' => $item['qty'],
                'item_id' => $item['item_id'],
//                'price' => $item['price'],
            ]);
            foreach ($item['quotations'] as $quotation) {
                $supplier=Supplier::query()->find($quotation['supplier_id']);
                $order_item->quotations()->create([
                    'supplier_id' => $quotation['supplier_id'],
                    'importer_name' => $supplier->name,
                    'project_id'=>$project_id,
                    'purchase_order_id'=>$order->id,
                    'price' => $quotation['price'],
                    'unit' => $order_item->item->unit??null,
                    // 'qty' => $quotation['qty'],
                    'file' => $quotation['file'],
                ]);
            }
        }
        $order->statuses()->create([
            'status_id' => 1,
            'sender_id' => auth()->user()->id,
        ]);
        return $order;
    }

//    protected function handleRecordCreation(array $data): Model
//    {
//        return parent::handleRecordCreation($data); // TODO: Change the autogenerated stub
//    }

}