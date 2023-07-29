<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;
    protected function getCards(): array
    {
        return [
            Card::make("Total products", Product::count()),
            Card::make("Customers", Customer::count()),
            Card::make("Orders today", Order::whereDate("created_at", date("Y-m-d"))->count()),
        ];
    }
}
