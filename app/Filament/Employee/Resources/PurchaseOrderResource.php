<?php

namespace App\Filament\Employee\Resources;

use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Status;
use App\Models\Product;
use App\Models\Project;
use Filament\Forms\Get;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\PurchaseOrder;
use App\Models\TabookProduct;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Employee\Resources\PurchaseOrderResource\Pages;
use App\Filament\Admin\Resources\PurchaseOrderResource\RelationManagers;
use App\Filament\Employee\Resources\PurchaseOrderResource\Widgets\PurchaseOrderOverview;
use App\Filament\Employee\Resources\PurchaseOrderResource\RelationManagers\StatusRelationManager;

class PurchaseOrderResource extends Resource
{
    protected static ?string $navigationGroup = 'طلباتي';


    protected static ?string $model = PurchaseOrder::class;
    protected static ?string $modelLabel = ' طلبات الشراء';
    protected static ?string $pluralModelLabel = ' طلبات الشراء';
    protected static ?int $navigationSort = -2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form


    {
      return $form
    ->schema([
        Select::make('project_id')->label('المشروع')
            ->relationship('project', 'name', function (Builder $query) {
                $query->whereHas('projectUserEmployees', function ($userQuery) {
                    $userQuery->where('user_id', auth()->id());
                })->where('disabled', false);
            })
            ->getOptionLabelFromRecordUsing(function ($record) {
                return "{$record->name}";
            })
            ->preload()
            ->required()
            ->live()
            ->searchable()
            ->afterStateUpdated(function (?string $state, $set, $get) {
                $project = Project::find($state);
                $project_manager = $project?->man?->first()?->name;
                $sales = $project?->purchasing?->first()?->name;
                $city = $project?->city?->name;

                $newData = " مدير المشروع: $project_manager\nمندوب المشتريات: $sales\nالمدينة: $city";

                $set('project_data', $newData);
            })
            ->columnSpan('full'),



        FileUpload::make('file')
            ->label('ملف حصر')
            ->directory('orders'),
//            ->columnSpan('full'),
        Forms\Components\Textarea::make('project_data')
            ->rows(3)
            ->label('بيانات المشروع')
            ->hidden(fn(Get $get): bool => !$get('project_id'))
            ->readOnly(),
        // Repeat Items
        Repeater::make('items')
            ->label('عناصر الطلب')
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(function (string $search,Get $get): array {
                        return Item::query()
                            ->where(function ($q) use($search){
                                $q->where('number', 'like', "%{$search}%")
                                    ->orWhere('description', 'like', "%{$search}%");
                            })
                            ->where('project_id', $get('../../project_id'))
                            ->pluck('description', 'id')
                            ->toArray();
                    })
                    ->options(fn(Get $get): Collection => Item::query()
                        ->where('project_id', $get('../../project_id'))
                        ->pluck('description', 'id'))
                    ->live()
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return " البند:$record->number-{$record->description}";
                    })
                    ->label('بند المادة ')
                    ->afterStateUpdated(
                        function (?string $state, $set, $get) {
                            $product = Item::find($state);
                            if ($product) {
                                $singlePrice = intval($product->unit_price);
                                $quantityAvailable = $product->quantity . " " . $product->unit;
                                $quantityNeeded = $get('qty') . " " . $product->unit;
                                $real_quantity = intval($get('qty'));
                                $totalPrice = $real_quantity * $singlePrice;
                                $unit = $product->unit;
                                $set('unit', $unit);
                                $set('test', "السعر الافرادى بجدول الكميات: $singlePrice ريال\nالكمية المتاحة بجدول الكميات: $quantityAvailable\nالكميه المطلوبة: $quantityNeeded\nالسعر النهائي للبند من جدول الكميات: $totalPrice ريال ");
                            }
                        }
                    ),

                Forms\Components\TextInput::make('qty')
                    ->required()
                    ->numeric()

                    ->label('الكمية')
                    ->live()

                    ->afterStateUpdated(
                        function (?string $state, $set, $get) {
                            $product = Item::find($get('item_id'));
                            if ($product) {
                                $singlePrice = intval($product->unit_price);
                                $quantityAvailable = $product->quantity . " " . $product->unit;
                                $quantityNeeded = $get('qty') . " " . $product->unit;
                                $real_quantity = intval($get('qty'));
                                $totalPrice = $real_quantity * $singlePrice;

                                $set('test', "السعر الافرادى بجدول الكميات: $singlePrice ريال\nالكمية المتاحة بجدول الكميات: $quantityAvailable\nالكميه المطلوبة: $quantityNeeded\nالسعر النهائي للبند من جدول الكميات: $totalPrice ريال ");
                            }
                        }
                    ),

                Forms\Components\TextInput::make('unit')
                    ->label('الوحدة')
                    ->readOnly()
                    ->hidden(fn(Get $get): bool => !$get('unit'))
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $product = Item::find($get('item_id'));
                        if ($product) {
                            $unit = $product->unit;
                            $set('unit', $unit);
                        }
                    }),

                Forms\Components\TextInput::make('item')
                    ->required()
                    ->maxLength(255)
                    ->label('اسم الصنف'),

                Forms\Components\Textarea::make('test')
                    ->label('تفاصيل البند')
                    ->rows(4)
                    ->readOnly()
                    ->hidden(fn(Get $get): bool => !$get('item_id')),
//                    TextInput::make('price')
//                    ->required()
//                    ->label('السعر'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->label('الوصف')
                    ->columnSpan(2)
                     ->columnSpanFull(),


                Repeater::make('quotations')
                    ->label('عروض الأسعار')
                    ->schema([
                        Select::make('supplier_id')
                            ->label('اسم المورد')
                            ->relationship('supplier', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $supplier = Supplier::find($get('supplier_id'));
                                if ($supplier) {
                                    $set('importer_name', $supplier->name);
                                    $set('supplier_email', $supplier->email);
                                    $set('supplier_phone', $supplier->phone);
                                    $set('supplier_city', $supplier->city);
                                    $set('supplier_register_number', $supplier->register_number);
                                    $set('supplier_tax_number', $supplier->tax_number);
                                    $set('delivery_phone', $supplier->delivery_phone);
                                    $set('delivery_name', $supplier->delivery_name);
                                    $set('supplier_bank_name', $supplier->bank_name);
                                    $set('supplier_iban_number', $supplier->iban_number);
                                }
                            }),

//                            TextInput::make('importer_name')
//                            ->label('اسم المورد')
//                            ->disabled()
//                            ->default(fn($get) => $get('importer_name')),

                        TextInput::make('supplier_email')
                            ->label('البريد الالكتروني')
                            ->disabled()
                            ->default(fn($get) => $get('supplier_email')),

                        TextInput::make('supplier_phone')
                            ->label('رقم الهاتف')
                            ->disabled()
                            ->default(fn($get) => $get('supplier_phone')),

                        TextInput::make('supplier_city')
                            ->label('اسم المدينة')
                            ->disabled()
                            ->default(fn($get) => $get('supplier_city')),

                        TextInput::make('supplier_address')
                            ->label('العنوان')
                            ->disabled()
                            ->default(fn($get) => $get('supplier_address'))
                            ->hidden(fn($get) => !$get('supplier_address')),
                        TextInput::make('supplier_bank_name')
                            ->label('اسم البنك')
                            ->disabled()
                            ->default(fn($get) => $get('supplier_bank_name')),
                        TextInput::make('supplier_iban_number')
                            ->label('رقم الابيان')
                            ->disabled()
                            ->default(fn($get) => $get('supplier_iban_number')),

                        TextInput::make('supplier_register_number')
                            ->label('رقم السجل')
                            ->disabled()
                            ->default(fn($get) => $get('supplier_register_number')),

                        TextInput::make('supplier_tax_number')
                            ->label('الرقم الضريبي')
                            ->disabled()
                            ->default(fn($get) => $get('supplier_tax_number')),

                        TextInput::make('delivery_name')
                            ->label('اسم المندوب')
                            ->disabled()
                            ->default(fn($get) => $get('delivery_name')),

                        TextInput::make('delivery_phone')
                            ->label('رقم جوال المندوب')
                            ->disabled()
                            ->default(fn($get) => $get('delivery_phone')),
                        Textarea::make('qty')
                            ->required()
                            ->label('الكمية'),

                            Textarea::make('price')
                            ->label('السعر الافرادى')
                            ->required(),

                        FileUpload::make('file')
                            ->label('ملف')->directory('quotations'),



                        Checkbox::make('approved')
                            ->hidden(fn(string $context): bool => $context === 'create')

                    ])->maxItems(3)->columns(3)->columnSpan('full'),

            ])->columns(3)->columnSpanFull(),

    ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('ref_num')
                    ->label('الرقم المرجعي')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('project_id')
                    ->label('الحالة')
                    ->color(static function ($record): string {
                        $status= $record->Statuses->last();
                        if ($status && $status->status_id==1) {
                            return 'success';
                        }else {

                            return 'primary';
                        }
                    })
                    ->icon(static function ($record): string {
                        $status= $record->Statuses->last();
                        if ($status &&  $status->status_id==1) {
                            return 'heroicon-o-sparkles';
                        }else {

                            return 'heroicon-o-arrow-path-rounded-square';
                        }
                    })
                    ->formatStateUsing(function ($record) {
                        $status= $record->Statuses->last();
                        if ($status &&  $status->status_id==1){
                            return 'جديد';
                        }else{
                            $next=$record->getNextUser();
                            $distribution_order=$record->finishDisbursementOrder();
                            if ($next){
                                return  $next->name;
                            }else{
                                if ($distribution_order){
                                    return 'منتهى وتم اصدار اذن صرف';
                                }else{
                                    return 'منتهى وفى انتظار اذن صرف';
                                }
                            }
                        }
                    }),
                Tables\Columns\TextColumn::make('sender.name')->label('مقد م الطلب')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')->label(' اسم المشروع')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')->label('مواصفات العنصر')
                    ->searchable()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('productItem.unit')->label('الوحده')
                    ->searchable()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('productItem.unit_price')->label('سعر الوحده')
                    ->searchable()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('qty')
                    ->label('الكمية')
                    ->searchable()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('total_price_with_additions')->label('اجمالي المبلغ')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ انشاء الطلب ')->sortable(),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('PDF')
                    ->url(
                        fn(PurchaseOrder $record): string => route('generate-pdf.order.report', ['record' => $record]),

                        shouldOpenInNewTab: true
                    )
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
            StatusRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
        ];
    }


    public static function getWidgets(): array
    {
        return [
            PurchaseOrderOverview::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('sender_id', auth()->user()->id)
            ->orderBy('created_at','desc');
    }
    public static function getNavigationBadge(): ?string
    {
        $count=PurchaseOrder::query()->where('sender_id', auth()->user()->id)
           ->doesntHave('actions')->count();
        return $count > 0 ? $count : null;
    }

}

