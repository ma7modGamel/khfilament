<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use App\Notifications\UserApprovedNotification;

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
