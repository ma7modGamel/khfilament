<?php

namespace App\Filament\Admin\Resources\PurchaseOrderResource\RelationManagers;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProjectUser;
use App\Models\OrderQuotation;
use App\Models\DisbursementOrder;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DisbursementOrderRelationManager extends RelationManager
{
    protected static string $relationship = 'disbursementOrder';
    protected static string $disbursementOrderRelationship = 'disbursementOrder';
    protected static ?string $label = 'امر الصرف'; 
    protected static ?string $modelLabel = 'أمر الصرف';
    protected static ?string $pluralModelLabel = 'أمر الصرف';
    protected static ?string $navigationLabel = 'أمر الصرف';
    protected static ?string $recordTitleAttribute = 'أمر الصرف';
    protected static ?string $breadcrumb = 'أمر الصرف';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'إذن الصرف';
    }
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        if (auth()->user()->titles()->where('slug', 'accountant')->exists()) {
            return true;
        }
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('disbursementordernumber')
                ->readOnly()
                ->label('رقم إذن الصرف ')
                ->default(function () {
                    return \App\Models\DisbursementOrder::generateUniqueDisbursementOrderNumber();
                }),
               
                Forms\Components\TextInput::make('project_name')
                    ->required()
                    ->readOnly()
                    ->label('اسم المشروع')
                    ->default($this->getOwnerRecord()->project->name),
                Forms\Components\TextInput::make("project_manager")
                    ->required()
                    ->readOnly()
                    ->label('مدير المشروع')
                    ->default($this->getOwnerRecord()->project->projectEmployees->first()->user->name),
                    Hidden::make('sender_id')
                    ->default(auth()->user()->id),
                Forms\Components\TextInput::make('purchase_code')
                    ->required()
                    ->readOnly()
                    ->label('رقم الطلب')
                    ->default($this->getOwnerRecord()->ref_num),

                Forms\Components\TextInput::make('purchase_date')
                    ->required()
                    ->readOnly()
                    ->label('تاريخ الطلب')
                    ->default($this->getOwnerRecord()->created_at->format('Y-m-d')),
                Forms\Components\Select::make('order_item_id')
                    ->required()
                    ->label('البنود')
                    ->live()
                    ->options(function (){
                        return $this->getOwnerRecord()->items->pluck('name', 'id');
                    })->native(false)->searchable()->columnSpanFull()
                    ->afterStateUpdated(
                        function (?string $state, $set, $get) {
                            $order_quotation = OrderQuotation::query()->where('purchase_order_id',$this->getOwnerRecord()->id)
                            ->where('order_item_id',$state)->where('approved',1)->first();
                            if ($order_quotation) {
                                $supplier = $order_quotation->supplier;
                                $bank_name = $order_quotation->supplier->name;
                                $set('project_employee', $this->getOwnerRecord()->sender->name);
                                $set('supplier', $supplier->name);
                                $set('bank_name', $supplier->bank_name);
                                $set('iban_number', $supplier->iban_number);
                                $set('residual_value', $order_quotation->price);
                            }
                        }
                    ),
                Forms\Components\TextInput::make('project_employee')
                    ->required()
                    ->readOnly()
                    ->label('موظف المشروع')
                    ->default(fn() => $this->getOwnerRecord()?->purchaseOrder?->sender?->name ?? 'غير محدد'),

                    
                Forms\Components\TextInput::make("supplier")
                    // ->required()
                    ->readOnly()
                    ->label('المقاول/المورد')
                    ->default(fn() => $this->getOwnerRecord()?->quotations->firstWhere('approved', 1)?->supplier?->name ?? 'غير متوفر'),
                    Forms\Components\TextInput::make("bank_name")
                    ->required()
                    ->readOnly()
                    ->formatStateUsing(function ($state, $set, $get) {
                        $order_quotation = OrderQuotation::query()->where('purchase_order_id',$this->getOwnerRecord()->id)
                            ->where('order_item_id',$get('order_item_id'))->where('approved',1)->first();
                        if ($order_quotation) {
                            $supplier = $order_quotation->supplier;
                            return $supplier->bank_name;
                        }
                    })
                    ->label('اسم البنك'),
//                    ->default($this->getOwnerRecord()->quotations->firstWhere('approved',1)?->supplier->bank_name),
                Forms\Components\TextInput::make("iban_number")
                    ->required()
                    ->readOnly()
                    ->formatStateUsing(function ($state, $set, $get) {
                        $order_quotation = OrderQuotation::query()->where('purchase_order_id',$this->getOwnerRecord()->id)
                            ->where('order_item_id',$get('order_item_id'))->where('approved',1)->first();
                        if ($order_quotation) {
                            $supplier = $order_quotation->supplier;
                            return $supplier->iban_number;
                        }
                    })
                    ->label('رقم الابيان'),
//                    ->default($this->getOwnerRecord()->quotations->firstWhere('approved',1)?->supplier->iban_number),

                Forms\Components\TextInput::make('residual_value')
                    ->required()
                    ->readOnly()
                    ->label('القيمة المطلوبه'),
//                    ->default($this->getOwnerRecord()->quotations->where('approved', 1)->sum('price')),
                Forms\Components\TextInput::make('total_value')
                    ->required()
                    ->label('القيمة المقرر صرفها'),
                Forms\Components\Select::make('payment')
                    ->required()
                    ->label('طريقة الدفع')
                    ->live()
                    ->options([
                       [ 'cash' => 'عهده',
                        'cheque' => 'شيك',
                        'bank_transfer' => 'تحويل بنكي',]
                    ]),
                Forms\Components\Select::make('purchasing_user_id')
                
                    ->relationship('purchaseUser', 'name')
                    ->preload()
                    ->visible(function (Get $get){
                        return $get('payment')=='cash';

                    })
                    ->label(__('مندوب المشتريات')),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('ملاحظات'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payment')
            
            ->columns([
               
                Tables\Columns\TextColumn::make('disbursementordernumber')
                    ->label('رقم إذن الصرف'),
                Tables\Columns\TextColumn::make('project_name')
                    ->label('اسم المشروع'),
                Tables\Columns\TextColumn::make('project_manager')
                    ->label('مدير المشروع'),
                Tables\Columns\TextColumn::make('purchaseOrder.supplier.name')
                    
                    ->label('المقاول/المورد'),
                Tables\Columns\TextColumn::make('purchaseOrder.sender.name')
                    ->label('موظف المشروع'),
                Tables\Columns\TextColumn::make('purchase_code')
                    ->label('رقم الطلب'),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->label('تاريخ الطلب'),
                Tables\Columns\TextColumn::make('orderItem.item.number')
                    ->label('رقم البند'),
                Tables\Columns\TextColumn::make('total_value')
                    ->label('القيمة المقرر صرفها'),
                Tables\Columns\TextColumn::make('residual_value')
                    ->label('القيمة المطلوبه'),
                    Tables\Columns\TextColumn::make('payment')
                    ->label('طريقة الدفع')
                    ->formatStateUsing(function ($state) {
                        $paymentMethods = [
                            'cash' => 'عهده',
                            'cheque' => 'شيك',
                            'bank_transfer' => 'تحويل بنكي',
                        ];
                
                        return $paymentMethods[$state] ?? 'غير محدد';
                    }),
                Tables\Columns\TextColumn::make('purchaseUser.name')
                    ->label('مندوب المشتريات'),
                Tables\Columns\TextColumn::make('notes')
                    ->label('ملاحظات'),
            ])
          
        
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->before(function (array $data, CreateAction $action): array {
                        // Runs before the form fields are saved to the database.
                        $order_items = $this->getOwnerRecord()->disbursementOrders->pluck('order_item_id')->toArray();
                        
                        if (in_array($data['order_item_id'], $order_items)) {
                            Notification::make()
                                ->title('Warning')
                                ->body('تم اصدار اذن صرف لهذا البند من قبل')
                                ->send();
            
                            $action->cancel();
            
                            return $data;
                        }
                      
                    $data['disbursementordernumber'] = \App\Models\DisbursementOrder::generateUniqueDisbursementOrderNumber();

                      

                        
                  
                        return $data;
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
                        return $data;
                    })
                    ->hidden(function () {
                        return $this->getOwnerRecord()->finishDisbursementOrder();
                    })
            ])->heading(' أمر الصرف')
         
            ->actions([
                Tables\Actions\EditAction::make()
                ->before(function (DisbursementOrder $record) {
                    if (empty($record->disbursementordernumber)) {
                        $record->disbursementordernumber = $record->generateUniqueDisbursementOrderNumber();
                        $record->save();
                    }
                }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('PDF')
                    ->url(fn(DisbursementOrder $record): string => route('new.pdf', ['record' => $record->id]),


                        shouldOpenInNewTab: true
                    )
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
            
    }
  
}