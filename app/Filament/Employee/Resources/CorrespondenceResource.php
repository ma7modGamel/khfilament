<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Admin\Resources\CorrespondenceTrackingResource;
use App\Filament\Employee\Resources\CorrespondenceResource\Pages;
use App\Models\Correspondence;
use App\Models\CorrespondenceTracking;
use App\Models\User;
use App\Notifications\NewCorrespondenceReferral;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CorrespondenceResource extends Resource
{
    protected static ?string $model = Correspondence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = '  المستند';
    protected static ?string $pluralModelLabel = '  المستندات';
    public static function getNavigationBadge(): ?string
    {
        $count = Correspondence::query()
            ->where(function ($q) {
                $q->whereHas('trackings', function ($q) {
                    $q->where('to_user_id', auth()->id());
                })
                ->orWhereHas('users', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->orWhereDoesntHave('trackings'); 
            })
            ->count();
        
        return $count > 0 ? $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->numeric()
                    ->label('رقم المستند')
                    ->maxLength(255)
                    ->searchable(),
                DatePicker::make('date')
                    ->required()
                    ->label('تاريخ المستند'),
                Select::make('correspondent_id')
                    ->required()
                    ->label('الجهه ')
                    ->relationship('correspondent', 'name')
                    ->searchable(),
                Select::make('correspondence_document_id')
                    ->required()
                    ->label('نوع المستند')
                    ->relationship('correspondence_document', 'type')
                    ->live(),
                Forms\Components\TextInput::make('total_value')
                    ->numeric()
                    ->label('القيمة ')->columnSpanFull()->required()
                    ->visible(
                        fn($record, $get) => CorrespondenceDocuments::query()
                            ->where([
                                'id' => $get('correspondence_document_id'),
                                'need_total_value' => 1
                            ])->exists()
                    ),
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required()
                    ->preload()
                    ->columnSpan('full')
                    ->label('المشروع'),
                Textarea::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('file')
                    ->label('الملف')
                    ->openable()->downloadable()
                    ->columnSpanFull()
                    ->directory('correspondences'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable()
                    ->label('رقم المستند'),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('المشروع')->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable()
                    ->label('تاريخ المستند')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('تاريخ الانشاء')
                    ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('correspondent.modelable.name')
                    ->label('الجهه')
                    ->formatStateUsing(function ($record) {
                        return $record->correspondent?->modelable?->name;
                    })
                   
                    ->formatStateUsing(function ($record) {
                        return $record->correspondent?->modelable?->name;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('correspondence_document_id')
                    ->label('نوع المستند')
                    ->formatStateUsing(function ($record) {
                        return $record->correspondence_document?->type;
                    })->toggleable(isToggledHiddenByDefault: true),
//                Tables\Columns\TextColumn::make('project_id')
//                    ->label('حالة المستند')
//                    ->formatStateUsing(function ($record) {
//                        if ($record->trackings->isEmpty()) {
//                            return 'جديد';
//                        }else{
//                            return $record->trackings->last()->toUser->name;
//                        }
//
//                    }),
                BadgeColumn::make('project_id')
                    ->label('الحالة')
                    ->color(static function ($record): string {
                        if ($record->trackings->isEmpty()) {
                            return 'success';
                        } else {

                            return 'primary';
                        }
                    })
                    ->icon(static function ($record): string {
                        if ($record->trackings->isEmpty()) {
                            return 'heroicon-o-sparkles';
                        } else {

                            return 'heroicon-o-arrow-path-rounded-square';
                        }
                    })
                    ->formatStateUsing(function ($record) {
                        if ($record->trackings->isEmpty()) {
                            return 'جديد';
                        } else {
                            return $record->trackings->last()->toUser?->name;
                        }
                    }),

            ])
//            ->recordClasses(fn (Correspondence $record) => match ($record->trackings->isEmpty()) {
//                true => 'background-color:green',
//                false => 'background-color:red',
//            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('Referral')
                    ->visible(fn($record) => (!$record->check_user_make_referral() || $record->trackings->last()->to_user_id == auth()->id() && !$record->finished) && !$record->finished && $record->trackings->last()?->type == 'person')
                    ->model(CorrespondenceTracking::class)
                    ->label('إحالة')
                    ->color('success')
                    ->beforeFormFilled(
                        function (Tables\Actions\Action $action) {
                            $signature = Auth::user()->signature;
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
                            ->options(function ($record) {
                                return User::query()->where('id', '<>', auth()->id())
                                    ->pluck('name', 'id');
                            })
                            ->native(false)
                            ->searchable(),
                        Textarea::make('notes')
                            ->label('ملاحظات')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('file')
                            ->label('الملف')
                            ->openable()->downloadable()
                            ->directory('referrals'),

                    ])
                    ->action(function (Correspondence $record, array $data) {
                        CorrespondenceTracking::query()->create([
                            'correspondence_id' => $record->id,
                            'from_user_id' => Auth::user()->id,
                            'to_user_id' => $data['to_user_id'],
                            'notes' => $data['notes'],
                            'signature' => Auth::user()->signature,
                            'file' => $data['file'],
                        ]);
                    })
                    ->after(function ($record, array $data) {
                        Notification::make()
                            ->title('تمت الاحالة للموظف بنجاح')
                            ->success()
                            ->send();
                        //send email
                        $user = User::query()->find($data['to_user_id']);
                        $role = $user->roles()->first();
                        if ($role->name == 'موظف') {
                            $url = \App\Filament\Employee\Resources\CorrespondenceTrackingResource::getUrl('view', ['record' => $record->id]);
                        } else {
                            $url = CorrespondenceTrackingResource::getUrl('view', ['record' => $record->id]);
                        }

                        $user->notify(new NewCorrespondenceReferral($role));
                        //send notification
                        Notification::make()
                            ->title('إحالة جديده أرسلت لك ')
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->url($url)
                                    ->markAsRead(),
                            ])
                            ->sendToDatabase($user);
                        event(new DatabaseNotificationsSent($user));
                    }),
                Tables\Actions\Action::make('download')
                    ->label('PDF')
                    ->url(
                        fn(Correspondence $record): string => route('generate-pdf.correspondence.report', ['record' => $record]),

                        shouldOpenInNewTab: true
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCorrespondences::route('/'),
            'view' => Pages\ViewCorrespondence::route('/{record}'),

        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('trackings', function ($q) {
            $q->where('to_user_id', auth()->id());
        })->orWhereHas('users', function ($q) {
            $q->where('user_id', auth()->id());
        })
            ->orWhereHas('correspondence_document', function ($builder) {
                $builder->whereLike('type', '%عقود%')
                    ->orWhere('type', 'LIKE', '%عقد%')
                    ->orWhere('type','LIKE','%تعميدات%');
            })
            ->with(['lastTrack' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }])  ->withCount('trackings');

    }
}