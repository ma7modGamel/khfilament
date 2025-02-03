<?php

namespace App\Console\Commands;

use App\Filament\Admin\Resources\CorrespondenceTrackingResource;
use App\Models\Correspondence;
use App\Models\CorrespondenceTracking;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReferralCount extends Command
{

    protected $signature = 'app:referral-count';

    protected $description = 'Send Notification To User Referral Count ';

    public function handle()
    {
        $result=[];
        $correspondences= Correspondence::query()->get();
        foreach ($correspondences as $correspondence) {
            if( $track=$correspondence->trackings->last())
            {
                if (!in_array($track->to_user_id,$result)) {
                    array_push($result, $track->to_user_id);
                }

            }
        }
        foreach ($result as $result){
            $user=User::query()->find($result);
            $role=$user->roles()->first();
            if ($role->name=='موظف')
                $url=\App\Filament\Employee\Resources\CorrespondenceTrackingResource::getUrl('index');
            else
                $url=CorrespondenceTrackingResource::getUrl('index');
            Notification::make()
                ->title('لديك إحالات يجب الاطلاع عليها ')
                ->actions([
                    Action::make('view')
                        ->url($url)
                        ->markAsRead()
                ])
                ->sendToDatabase($user);
            event(new DatabaseNotificationsSent($user));
        }

        $this->info('Done Send Notification');

    }
}
