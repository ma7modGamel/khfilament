<?php

namespace App\Filament\Employee\Resources\PurchaseOrderResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Employee\Resources\PurchaseOrderResource;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }

    protected function beforeSave(): void
    {
        $latestStatus = $this->getRecord()->Statuses()
        ->label('الحالة')
            ->where('sender_id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'asc')
            ->first();

            if ($latestStatus && $latestStatus->status_id == 2) {
                Notification::make()
                    ->warning()
                    ->title('تم قفل تقديم النموذج')
                    ->body('لا يمكنك تعديل النموذج الآن لأنه تم قبوله.')
                    ->persistent()
                    ->send();

                $this->halt();
            }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        return $data;
    }

}