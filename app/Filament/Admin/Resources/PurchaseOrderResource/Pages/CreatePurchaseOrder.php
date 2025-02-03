<?php

namespace App\Filament\Admin\Resources\PurchaseOrderResource\Pages;

use App\Filament\Admin\Resources\PurchaseOrderResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;
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

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.

        $order =  $this->getRecord();
        $order->statuses()->create([
            'status_id' => 1,
            'sender_id' => auth()->user()->id,
            // 'project_user_id'=>
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        // $data['sender_id'] = auth()->user()->id;

        return $data;
        // dd ($data);
    }
}
