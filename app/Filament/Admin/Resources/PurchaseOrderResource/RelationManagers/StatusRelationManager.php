<?php

namespace App\Filament\Admin\Resources\PurchaseOrderResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Status;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProjectUser;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Notifications\NewPurchaseOrderNotificationStatus;
use Filament\Notifications\Events\DatabaseNotificationsSent;

class StatusRelationManager extends RelationManager
{
    protected static string $relationship = 'Statuses';


    protected static ?string $label = 'الحالات'; 
    protected static ?string $modelLabel = '  حاله الطلب';
    protected static ?string $pluralModelLabel='حاله الطلب';
    protected static ?string $createButtonLabel = 'اضافه حاله';
    public static function getTitle(Model $ownerRecord, string $pageClass): string 
    {
        return 'الحالات';
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('status_id')
                    ->relationship('status', 'name')
                    ->native(false)
                    ->required()
                    ->label(__('تغيير الحالة'))->columnSpanFull(),

                Forms\Components\Textarea::make('notes')
                    ->label(__('الملاحظات'))
                    ->rows(4)
                    ->cols(5)
                    ->columnSpanFull(),
                Hidden::make('sender_id')
                    ->default(auth()->user()->id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('sender.name')->label('مقدم الطلب'),
                Tables\Columns\TextColumn::make('status.name')->label('الحالة '),
                Tables\Columns\TextColumn::make('notes')->label('ملاحظات '),

                Tables\Columns\TextColumn::make('created_at')->label('تاريخ انشاء  '),
            ])
            ->filters([
                //
            ])
            ->headerActions([


                Tables\Actions\CreateAction::make()
                    ->beforeFormFilled(
                        function (Tables\Actions\Action $action) {
                            $signature=Auth::user()->signature;
                            if (!$signature) {
                                Notification::make()
                                    ->title('Warning')
                                    ->body('يجب ان تقوم برفع توقيعك اولا')
                                    ->send();
                                $action->cancel();
                            }
                            $order_items=$this->getOwnerRecord()->items;
                            foreach ($order_items as $order_item) {
                                $approvedCount = $order_item->quotations->where('approved', true)->count();
                                if ($approvedCount ==0)
                                {
                                    Notification::make()
                                        ->title('Warning')
                                        ->body('يجب ان توافق على عرض سعر اولا ')
                                        ->send();
                                    $action->cancel();
                                }
                            }

                        }
                    )
                    ->mutateFormDataUsing(function (array $data): array {
                        $last_user=$this->getOwnerRecord()->statuses->sortByDesc('created_at')->first();
                        $project_users =ProjectUser::query()->where('project_id',$this->getOwnerRecord()->project->id)
                            ->where('management_type', 'purchase_order')
                            ->orderBy('order')->get();
                        if ($last_user->status_id==1){
                            $next=$project_users->first();
                        }
                        else {
                            if ($project_users->contains('user_id',$last_user->sender_id))
                            {
                                $user = $project_users->where('user_id', $last_user->sender_id)->first();
                                $next = $project_users->skipWhile(function ($item) use ($user) {
                                    return $item->id != $user->id;
                                })->skip(1)->first();
                            }
                        }

                        if ($next){
                            $next_project_user=User::query()->find($next->user_id);
                            $next_project_user->notify(new NewPurchaseOrderNotificationStatus(Status::find($data['status_id'])->name));
                            //send notification
                            Notification::make()
                                ->title('لديك طلب شراء')
                                ->actions([
                                    Action::make('view')
                                        ->url('/admin/purchase-orders/'.$this->getOwnerRecord()->id.'/edit')
                                        ->markAsRead()
                                ])
                                ->sendToDatabase($next_project_user);
                            event(new DatabaseNotificationsSent($next_project_user));
                        }




//                        $user = $this->getOwnerRecord()->project?->nonEmployeeUsers()
//                            ->first();
//                        $next = $this->getOwnerRecord()->project?->nonEmployeeUsers()
//                            ->where('user_id', '!=', auth()->user()->id)
//                            ->first();
//                        if ($next) {
//                            $next->notify(new NewPurchaseOrderNotificationStatus(Status::find($data['status_id'])->name));
//                            //send notification
//                            Notification::make()
//                                ->title('لديك طلب شراء')
//                                ->actions([
//                                    Action::make('view')
//                                        ->url('/admin/purchase-orders/'.$this->getOwnerRecord()->id.'/edit')
//                                        ->markAsRead()
//                                ])
//                                ->sendToDatabase($next);
//                            event(new DatabaseNotificationsSent($user));
//                        }
//                        if ($data['status_id'] == 3) {
//
//                            $project =   ProjectUser::where('user_id', auth()->user()->id)->where('project_id', $this->getOwnerRecord()->project->id)->orderBy('created_at', 'asc')->first();
//                            $previousUser =   ProjectUser::where('order', $project->order - 1)->where('project_id', $this->getOwnerRecord()->project->id)->orderBy('created_at', 'asc')->first();
//                            if ($previousUser) {
//
//                                $previousUser->update(['done' => 0]);
//                            }
//
//                        } else {
//                            $project =   ProjectUser::where('user_id', auth()->user()->id)->where('project_id', $this->getOwnerRecord()->project->id)->orderBy('created_at', 'asc')->first();
//                            $project->update([
//
//                                'done' => 1
//                            ]);
//                        }
                        // dd($project);
                        return $data;
                    })
                
                    ->visible(function () {
                        $project = $this->getOwnerRecord()->project;
                        if (!$project) {
                            return false;
                        }

                        $projectEmployee = $project->purchaseOrderEmployees->first();
                        if (!$projectEmployee) {
                            return false;
                        }

                        $user = $projectEmployee->nonEmployees;
                        if (!$user) {
                            return false;
                        }

                        $last_user=$this->getOwnerRecord()->statuses->sortByDesc('created_at')->first();
                        $project_users =ProjectUser::query()->where('project_id',$this->getOwnerRecord()->project->id)
                        ->where('management_type', 'purchase_order')
                            ->orderBy('order')->get();
                        if ($last_user->status_id==1){
                            $next=$project_users->first();
                        }
                        else {
                            if ($project_users->contains('user_id',$last_user->sender_id))
                            {
                                $user = $project_users->where('user_id', $last_user->sender_id)->first();
                                $next = $project_users->skipWhile(function ($item) use ($user) {
                                    return $item->id != $user->id;
                                })->skip(1)->first();
                            }
                        }
                        if ($next){
                            return $next->user_id == auth()->user()->id;
                        }else{
                            return false;
                        }


                    })


            ])
            ->heading('حالات الطلب') 
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

}