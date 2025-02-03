<?php

namespace App\Filament\Admin\Resources\PurchaseOrderResource\Pages;

use App\Filament\Admin\Resources\PurchaseOrderResource;
use App\Models\ProjectUser;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        if (auth()->user()->signature == null) {
            Notification::make()
                ->title('Warning')
                ->body('يجب ان تقوم برفع توقيعك اولا')
                ->send();
            $this->halt();
        }
        $items = collect($this->data['items']);
        foreach ($items as $item) {
            $quotations =collect($item['quotations']);
            $approvedCount = $quotations->where('approved', true)->count();
            if ($approvedCount > 1)
            {
                Notification::make()
                    ->title('Warning')
                    ->body('يجب ان تختار عرض واحد')
                    ->send();
                $this->halt();
              }
        }
        $latestStatus = $this->getRecord()->Statuses()
        ->where('sender_id', auth()->id())
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}