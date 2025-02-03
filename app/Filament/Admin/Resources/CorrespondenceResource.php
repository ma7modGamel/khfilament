<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CorrespondenceResource\Pages;
use App\Filament\Admin\Resources\CorrespondenceResource\RelationManagers;
use App\Models\Agency;
use App\Models\Contractor;
use App\Models\Correspondence;
use App\Models\CorrespondenceDocuments;
use App\Models\CorrespondenceTracking;
use App\Models\Correspondents;
use App\Models\Project;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\NewCorrespondenceReferral;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use function Livewire\after;

class CorrespondenceResource extends Resource
{
    protected static ?string $model = Correspondence::class;
    protected static ?string $modelLabel = '  المستند';
    protected static ?string $pluralModelLabel = '  المستندات';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationBadge(): ?string
    {
//        $count= Correspondence::query()->doesntHave('trackings')->count();
//        return $count > 0 ? $count : null;

        if (auth()->user()->titles()->where('slug', 'co_secretary')->exists()){
           return Correspondence::query()->doesntHave('trackings')->count();
        }else{
            return Correspondence::query()->whereHas('trackings',function ($q){
                $q->where('to_user_id',auth()->id());
            })->orWhereHas('users',function ($q){
                $q->where('user_id',auth()->id());
            })->count();

        }
    }



    public static function canCreate(): bool
    {
        if (auth()->user()->titles()->where('slug', 'co_secretary')->exists()) {
            return true;
        }
        return false;
    }
    public static function canEdit($record): bool
    {
        if (auth()->user()->titles()->where('slug', 'co_secretary')->exists()) {
            return true;
        }
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->string()
                    ->label('رقم المستند')
                    ->maxLength(255),
                DatePicker::make('date')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->label('تاريخ المستند'),
                Select::make('correspondent_type')
                    ->label('نوع الجهه')
                    ->options([
                         Project::class=> 'مشروع',
                        Supplier::class => 'مورد',
                        Contractor::class => 'مقاول',
                        Agency::class => 'جهه حكومية',
                    ])
                   ->afterStateUpdated(function (Forms\Set $set) { $set('correspondent_id', null); })
                ,
                Select::make('correspondent_id')
                    // ->required()
                    ->label('الجهه ')
                //    ->options(function (Forms\Get $get) {
                    ->options(function (?Correspondence $record, Forms\Get $get, Forms\Set $set) {
                        if (! empty($record) && empty($get('correspondent_type'))) {
                            $set('correspondent_type', $record->correspondent?->modelable_type);
                            $set('correspondent_id', $record->correspondent_id);
                        }
                         $correspondents= Correspondents::where('modelable_type', $get('correspondent_type'))->get();
                        $options=[];
                        foreach ($correspondents as $correspondent) {
                            if ($correspondent->modelable) {
                                $option = [$correspondent->id => $correspondent->modelable->name];
                                $options += $option;
                            }
                        }

                        return $options;
                        }
                    )->searchable(),

                Select::make('correspondence_document_id')
                    ->required()
                    ->label('نوع المستند')
                    ->relationship('correspondence_document', 'type')
                    ->live(),

                    Forms\Components\TextInput::make('total_value')
                    ->label('القيمة الإجمالية بالريال')
                    ->required()
                    ->visible(
                        fn($record, $get) => CorrespondenceDocuments::query()
                            ->where([
                                'id' => $get('correspondence_document_id'),
                                'need_total_value' => 1
                            ])->exists()
                    )
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state == 0) {
                            $set('error_message', 'اذا كانت القيمه صفر يتم احتساب الاجمالى بالمتر حسب التعاقد');
                        } else {
                            $set('error_message', null);
                        }
                    })
                    ->helperText(function ($get, $livewire) {
                        if ($get('total_value') == 0) {
                            return 'اذا كانت القيمه صفر يتم احتساب الاجمالى بالمتر حسب التعاقد';
                        }
                        return $get('error_message') ?? '';
                    })
                
                

            ,
                    
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required()
                    ->preload()
                    ->label('المشروع'),
                Select::make('receive_method_id')
                    ->relationship('receive_methods', 'name')
                    ->required()
                    ->preload()
                    ->label('طريقة استلام المستند'),
                Select::make('type')
                    ->label('نوع المستند(أصل/صورة)')
                    ->required()
                    ->options([
                        'original'=> 'أصل',
                        'copy'=> 'صورة'
                    ]),
                TextInput::make('path')
                    // ->required()
                    ->label('مسار المستند')
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('الوصف')
                    ->columnSpanFull()
                    ,
                Forms\Components\FileUpload::make('file')
                ->label('الملف')
                ->columnSpanFull()
                    ->openable()->downloadable()
                    ->directory('correspondences')
                    ->maxSize(600000)
                ->preserveFilenames(),

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
                Tables\Columns\TextColumn::make('receive_methods.name')
                    ->label('طريقة استلام المستند')->searchable()->toggleable(),
                ToggleColumn::make('finished')->visible(function (){
                    return \auth()->user()->titles()->where('slug', 'manager')->exists();
                })
                    ->label('حالة الانتهاء'),
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع المستند ')
                    ->formatStateUsing(function ($record) {
                        return $record->type=='original'?'أصل':'صورة';
                    })->toggleable(),
                Tables\Columns\TextColumn::make('path')
                    ->label('مسار المستند')->searchable()->toggleable(),

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
                    ->searchable(),
                    Tables\Columns\TextColumn::make('description')
                    ->label('الوصف ')
                  ->searchable(),
                    

                Tables\Columns\TextColumn::make('correspondence_document_id')
                    ->label('نوع المستند')
                    ->formatStateUsing(function ($record) {
                        return $record->correspondence_document?->type;
                    }) ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('trackings.type')
                    ->formatStateUsing(function ($record) {
                        return $record->trackings?->last()->type;
                    })
                    ->label('نوع الاحالة')->searchable(),
                        BadgeColumn::make('project_id')
                    ->label('الحالة')
                    ->color(static function ($record): string {
                        if ($record->trackings->isEmpty()) {
                            return 'success';
                        }else {

                            return 'primary';
                        }
                    })
                    ->icon(static function ($record): string {
                        if ($record->trackings->isEmpty()) {
                            return 'heroicon-o-sparkles';
                        }else {

                            return 'heroicon-o-arrow-path-rounded-square';
                        }
                    })
                    ->formatStateUsing(function ($record) {
                        if ($record->trackings->isEmpty()){
                            return 'جديد';
                        }else{
                            return $record->trackings->last()->toUser?->name;
                        }
                    }),

            ])
            

//            ->recordClasses('border-l-4 border-red-600'
////                fn (Correspondence $record) => match ($record->trackings->isEmpty()) {
////                true => 'background-color:green',
////                false => 'background-color:red',
////            }
//            )
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                        ->label('المشاريع')
                        ->options(Project::all()->pluck('name', 'id'))
                        ->searchable(),

                Tables\Filters\SelectFilter::make('correspondence_document_id')
                    ->label('نوع المستند')
                    ->options(CorrespondenceDocuments::all()->pluck('type', 'id'))
                    ->searchable(),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Referral')
                    ->visible(fn ($record) => (!$record->check_user_make_referral() || $record->trackings->last()->to_user_id==auth()->id()  ) && (!$record->finished && $record->trackings->last()?->type=='person' ) || auth()->user()->titles()->where('slug', 'co_secretary')->exists() )
                    ->model(CorrespondenceTracking::class)
                    ->label('إحالة')
                    ->color('success')
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
                        }
                    )

                    ->form([
                        Select::make('type')
                            ->required()
                            ->label('نوع الاحالة ')
                            ->options([
                                'person'=> 'شخص',
                                'group'=> 'مجموعه',
                            ])
                            ->native(false)
                            ->live()
                            ->searchable()->visible(function (){
                                return auth()->user()->titles()->where('slug', 'co_secretary')->exists();
                            }),
                        Select::make('users')
                            ->label('الموظفون ')
                            ->visible(function (Forms\Get $get) {
                                return $get('type') == 'group';
                            })
                            ->options(function ($record){
                                return User::query()->where('id','<>',auth()->id())
                                    ->pluck('name', 'id');
                            })
//
                            ->native(false)
                            ->multiple()
                            ->searchable(),

                        Select::make('to_user_id')
                            ->label('الموظف ')
                            ->visible(function (Forms\Get $get) {
                                return $get('type') == 'person';
                            })
                            ->options(function ($record){
                               return User::query()->where('id','<>',auth()->id())
                                   ->pluck('name', 'id');
                            })
//
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
                            'type' =>$data['type'],
                            'from_user_id' => Auth::user()->id,
                            'to_user_id' =>isset($data['to_user_id'])?$data['to_user_id']:null,
                            'notes' => $data['notes'],
                            'signature' => Auth::user()->signature,
                            'file'=>$data['file']
                        ]);
                        if (isset($data['users'])) {
                            $record->users()->attach($data['users']);
                        }
                    })
                    ->after(function ($record, array $data) {
                        Notification::make()
                            ->title('تمت الاحالة بنجاح')
                            ->success()
                            ->send();
                        //send email
                        if (isset($data['to_user_id'])) {
                            $user = User::query()->find($data['to_user_id']);
                            $role = $user->roles()->first();
                            if ($role->name == 'موظف')
                                $url = \App\Filament\Employee\Resources\CorrespondenceTrackingResource::getUrl('view', ['record' => $record->id]);
                            else
                                $url = CorrespondenceTrackingResource::getUrl('view', ['record' => $record->id]);
                            $user->notify(new NewCorrespondenceReferral($role));
                            //send notification
                            Notification::make()
                                ->title('إحالة جديده أرسلت لك ')
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('view')
                                        ->url($url)
                                        ->markAsRead()
                                ])
                                ->sendToDatabase($user);
                            event(new DatabaseNotificationsSent($user));
                        }
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
            'create' => Pages\CreateCorrespondence::route('/create'),
            'view' => Pages\ViewCorrespondence::route('/{record}'),
            'edit' => Pages\EditCorrespondence::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
    
        if (auth()->user()->titles()->where('slug', 'co_secretary')->exists()) {
            return $query->orderBy('created_at', 'desc')
                ->whereHas('correspondence_document', function ($builder) {
                    $builder->where('type', 'LIKE', '%عقود%')
                        ->orWhere('type', 'LIKE', '%عقد%')
                        ->orWhere('type', 'LIKE', '%تعميدات%');
                });
        } else {
            return $query->where(function ($q) {
                $q->whereHas('trackings', function ($q) {
                    $q->where('to_user_id', auth()->id());
                })
                ->orWhereHas('users', function ($q) {
                    $q->where('user_id', auth()->id());
                });
            })
            ->orWhereHas('correspondence_document', function ($builder) {
                $builder->where('type', 'LIKE', '%عقود%')
                    ->orWhere('type', 'LIKE', '%عقد%')
                    ->orWhere('type', 'LIKE', '%تعميدات%');
            })  ->withCount('trackings');
        }
    
    }
    

}