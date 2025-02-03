<?php

namespace App\Filament\SuperAdmin\Resources\UserResource\Pages;

use App\Filament\SuperAdmin\Resources\UserResource;
use App\Models\User;
use App\Notifications\UserApprovedNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),

        ];
    }
    protected function afterSave(): void
    {
        $id = $this->data['id'];
        $user = User::find($id);
        if ($this->data['approved'] && !$user->account_approved) {
            $user->notify(new UserApprovedNotification);
            $user->account_approved = true;
            $user->save();
        }
    }
}
