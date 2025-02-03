<?php

namespace App\Filament\Employee\Pages\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;


use Illuminate\Database\Eloquent\Model;

class EditProfile extends BaseEditProfile
{

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                FileUpload::make('signature')
                    ->directory('signatures')
                    ->image()
                ->label(__('التوقيع'))
                ->disabled(function (Model $record) {
                  
                    return auth()->user()->role !== 'سوبر أدمن' && $record->signature != null;
                })
            
                ->visible(function (Model $record) {
                    return true;
                }) ->required(function (Model $record) {
                    // Only require the signature if no signature exists
                    return $record->signature == null;
                }),
                TextInput::make('address')
                 ->label('العنوان')
                ->maxLength(255),
            ]);
    }
}