<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PurchaseOrderResource\RelationManagers\DisbursementOrderRelationManager;
use App\Models\Supplier;
use Filament\Forms;
use App\Models\Item;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use App\Models\Status;
use App\Models\Product;
use App\Models\Project;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Columns\BadgeColumn;
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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\PurchaseOrderResource\Pages;
use App\Filament\Admin\Resources\PurchaseOrderResource\RelationManagers;
use App\Filament\Admin\Resources\PurchaseOrderResource\Widgets\PurchaseOrderOverview;
use App\Filament\Admin\Resources\PurchaseOrderResource\RelationManagers\StatusRelationManager;

class PurchaseOrderResource extends Resource
{
    protected static ?string $navigationGroup = 'الطلبات';


    protected static ?string $model = PurchaseOrder::class;
    protected static ?string $modelLabel = ' طلبات الشراء';
    protected static ?string $pluralModelLabel = ' طلبات الشراء';
    protected static ?int $navigationSort =2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
 

    public static function canCreate(): bool
    {

        return false;
    }

    public static function form(Form $form): Form


    {
        return $form
            ->schema([
                Select::make('project_id')->label('المشروع')
                    ->relationship('project', 'name')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return "{$record->name}";
                    })
                    ->preload()
                    ->live()
                    ->searchable()
                    ->disabled()
                    ->afterStateHydrated(function ($state, $set, $get) {
                        if ($state) {
                            $project = Project::find($state);
                            $project_manager = $project?->man?->first()?->name;
                            $sales = $project?->purchasing?->first()?->name;
                            $city = $project?->city?->name;

                            $newData = " مدير المشروع: $project_manager\nمندوب المشتريات: $sales\nالمدينة: $city";
                            $set('project_data', $newData);
                        }
                    }),

                Forms\Components\Textarea::make('project_data')
                    ->rows(4)
                    ->label('بيانات المشروع')
                    ->default(fn(Get $get): string => $get('project_data'))
                    ->readOnly(),


                Hidden::make('sender_id')
                    ->default(auth()->id()),


                FileUpload::make('file')
                    ->downloadable()
                    ->openable()
                    ->label('ملف حصر')->columnSpan('full'),

                // Repeat Items
                Repeater::make('items')
                    ->label('عناصر الطلب')
                    ->relationship('items')
                    ->schema([
                        Forms\Components\Select::make('item_id')
                            ->searchable()
                            ->required()
                            ->getSearchResultsUsing(function (string $search): array {
                                return Item::query()
                                    ->where('number', 'like', "%{$search}%")
                                    ->orWhere('description', 'like', "%{$search}%")
                                    ->pluck('description', 'number')
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
                                        $set('test', "السعر للوحدة: $singlePrice\nالكمية المتاحة: $quantityAvailable\nالكميه المطلوبة: $quantityNeeded\nالسعر النهائي: $totalPrice");
                                    }
                                }
                            ),

                        Forms\Components\Textarea::make('test')
                            ->label('تفاصيل البند')
                            ->rows(4)
                            ->readOnly()
                            ->hidden()
                        ->formatStateUsing(function ($state, $set, $get) {
                            $product = Item::find($get('item_id'));
                            if ($product) {
                                $singlePrice = intval($product->unit_price);
                                $quantityAvailable = $product->quantity . " " . $product->unit;
                                $quantityNeeded = $get('qty') . " " . $product->unit;
                                $real_quantity = intval($get('qty'));
                                $totalPrice = $real_quantity * $singlePrice;
                                $unit = $product->unit;
                                $set('unit', $unit);
                               return
                                   "السعر الافرادى بجدول الكميات: $singlePrice ريال\nالكمية المتاحة بجدول الكميات: $quantityAvailable\nالكميه المطلوبة: $quantityNeeded\nالسعر النهائي للبند من جدول الكميات: $totalPrice ريال ";
                            }
                        }),

                        Forms\Components\TextInput::make('unit')
                            ->label('الوحدة')
                            ->readOnly()
                            ->hidden()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $product = Item::find($get('item_id'));
                                if ($product) {
                                    $unit = $product->unit;
                                    $set('unit', $unit);
                                }
                            })
                            ->formatStateUsing(function ($state, $set, $get) {
                                $product = Item::find($get('item_id'));
                                if ($product) {
                                    $unit = $product->unit;
                                   return $unit;
                                }
                            }),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('اسم الصنف'),
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


                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->label('الوصف')
                            ->columnSpan(2),
                        // ->columnSpanFull(),


                        Repeater::make('quotations')
                            ->label('عروض الأسعار')
                            ->relationship('quotations')
                            ->schema([
                                Select::make('supplier_id')
                                    ->label('اسم المورد')
                                    ->relationship('supplier', 'name')
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->live()
                                    ->hidden()
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

                                TextInput::make('importer_name')
                                    ->label('اسم المورد')
                                    ->disabled()
                                    ->default(fn($get) => $get('importer_name')),

                                TextInput::make('supplier_email')
                                    ->label('البريد الالكتروني')
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->email;
                                        }
                                    })
                                    ->disabled()

                                    ->default(fn($get) => $get('supplier_email')),

                                TextInput::make('supplier_phone')
                                    ->label('رقم الهاتف')
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->phone;
                                        }
                                    })
                                    ->disabled()
                                    ->default(fn($get) => $get('supplier_phone')),

                                TextInput::make('supplier_city')
                                    ->label('اسم المدينة')
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->city;
                                        }
                                    })
                                    ->disabled()
                                    ->default(fn($get) => $get('supplier_city')),

                                TextInput::make('supplier_address')
                                    ->label('العنوان')
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->address;
                                        }
                                    })
                                    ->disabled()
                                    ->default(fn($get) => $get('supplier_address'))
                                    ->hidden(fn($get) => !$get('supplier_address')),
                                TextInput::make('supplier_bank_name')
                                    ->label('اسم البنك')
                                    ->disabled()
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->bank_name;
                                        }
                                    })
                                    ->default(fn($get) => $get('supplier_bank_name')),
                                TextInput::make('supplier_iban_number')
                                    ->label('رقم الابيان')
                                    ->disabled()
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->iban_number;
                                        }
                                    })
                                    ->default(fn($get) => $get('supplier_iban_number')),

                                TextInput::make('supplier_register_number')
                                    ->label('رقم السجل')
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->register_number;
                                        }
                                    })
                                    ->disabled()
                                    ->default(fn($get) => $get('supplier_register_number')),

                                TextInput::make('supplier_tax_number')
                                    ->label('الرقم الضريبي')
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->tax_number;
                                        }
                                    })
                                    ->disabled()
                                    ->default(fn($get) => $get('supplier_tax_number')),

                                TextInput::make('delivery_name')
                                    ->label('اسم المندوب')
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->delivery_name;
                                        }
                                    })
                                    ->disabled()
                                    ->default(fn($get) => $get('delivery_name')),

                                TextInput::make('delivery_phone')
                                    ->label('رقم جوال المندوب')
                                    ->formatStateUsing(function ($state, $set, $get) {
                                        $supplier = Supplier::find($get('supplier_id'));
                                        if ($supplier) {
                                            return $supplier->delivery_phone;
                                        }
                                    })
                                    ->disabled()
                                    ->default(fn($get) => $get('delivery_phone')),


                                TextInput::make('price')
                                    ->label(' السعر الافرادى')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('qty')
                                    ->required()
                                    ->numeric()
                                    ->label('الكمية'),

                                FileUpload::make('file')
                                    ->columnSpan('full')
                                    ->downloadable()
                                    ->openable()

                                    ->label('ملف عرض السعر'),
                                Checkbox::make('approved')
                                    ->label("الموافقة على هذا العرض")
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

//                Tables\Columns\TextColumn::make('productItem.number')
//                    ->label('رقم البند')
//                    ->searchable()
//                    ->sortable(),
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
                        if ($status && $status->status_id==1) {
                            return 'heroicon-o-sparkles';
                        }else {

                            return 'heroicon-o-arrow-path-rounded-square';
                        }
                    })
                    ->formatStateUsing(function ($record) {
                        $status= $record->Statuses->last();
                        if ($status && $status->status_id==1){
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
                    })
                  ,

                Tables\Columns\TextColumn::make('sender.name')->label('مقد م الطلب')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')->label(' اسم المشروع')
                    ->searchable()
                    ->sortable(),


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
            StatusRelationManager::class , // اسم التبويب "الحالات"
            DisbursementOrderRelationManager::class // اسم التبويب "أوامر الصرف"
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
            'view' => Pages\ViewPurchaseOrderResource::route('/{record}'),

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
        return parent::getEloquentQuery()->whereHas('project', function ($query) {
            $query->whereHas('users', function ($q) {
                $q->where('users.id', auth()->user()->id);
            });
        })->orderBy('created_at', 'desc');
    }
    public static function getNavigationBadge(): ?string
    {
        $count=PurchaseOrder::query()->whereHas('project', function ($query) {
            $query->whereHas('users', function ($q) {
                $q->where('users.id', auth()->user()->id);
            });
        })->whereHas('Statuses',function ($q){
            $q->where('sender_id','<>', auth()->user()->id);
        })->count();
        return $count > 0 ? $count : null;
    }

}