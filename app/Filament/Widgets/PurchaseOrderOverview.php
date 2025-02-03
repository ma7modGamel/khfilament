<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PurchaseOrderOverview extends BaseWidget
{
//     protected function getStats(): array
//     {
//         return [
//             Stat::make('order purchase', PurchaseOrder::count())
//                 ->color('success')
//                 ->extraAttributes([
//                     'class' => 'cursor-pointer',
//                     'wire:click' => "\$dispatch('navigateToPurchaseOrders')",
//                 ]),
//         ];
//     }
}
