<?php

namespace App\Filament\Admin\Resources\CorrespondenceResource\Pages;

use App\Filament\Admin\Resources\CorrespondenceResource;
use App\Models\Correspondence;
use App\Models\CorrespondenceTracking;
use App\Models\User;
use App\Notifications\NewCorrespondenceReferral;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewCorrespondence extends ViewRecord
{
    protected static string $resource = CorrespondenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('Referral')
                ->visible(fn ($record) => (!$record->check_user_make_referral() || $record->trackings->last()->to_user_id==auth()->id()  ) && (!$record->finished && $record->trackings->last()?->type=='person' ) || auth()->user()->titles()->where('slug', 'co_secretary')->exists() )
                ->model(CorrespondenceTracking::class)
                ->label('إحالة')
                ->color('success')
                ->beforeFormFilled(
                    function (Actions\Action $action) {
                        $signature=Auth::user()->signature;
                        if (!$signature) {
                            Notification::make()
                                ->title('Warning')
                                ->body('يجب ان تقوم برفع توقيعك اولا')
                                ->send();
                            $action->cancel();
                        }
                    }
                )
                ->form([
                    Select::make('to_user_id')
                        ->required()
                        ->label('الموظف ')
                        ->options(function ($record){
                            return User::query()->where('id','<>',auth()->id())
                                ->pluck('name', 'id');
                        })
                        ->native(false)
                        ->searchable(),
                    Textarea::make('notes')
                        ->label('ملاحظات')
                        ->required()
                        ->maxLength(255),

                ])
                ->action(function (Correspondence $record, array $data) {
                    CorrespondenceTracking::query()->create([
                        'correspondence_id' => $record->id,
                        'from_user_id' => Auth::user()->id,
                        'to_user_id' =>$data['to_user_id'],
                        'notes' => $data['notes'],
                        'signature' => Auth::user()->signature,
                    ]);
                })
                ->after(function ($record, array $data) {
                    Notification::make()
                        ->title('تمت الاحالة للموظف بنجاح')
                        ->success()
                        ->send();
                    //send email
                    $user=User::query()->find($data['to_user_id']);
                    $role= $user->roles()->first();
                    $user->notify(new NewCorrespondenceReferral($role));
                })
        ];
    }
}