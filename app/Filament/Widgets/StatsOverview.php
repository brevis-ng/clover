<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\Section;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;
    protected static ?string $pollingInterval = '10s';
    protected function getCards(): array
    {
        $order_today = Order::whereDate("created_at", date("Y-m-d"))->get();
        $order_count = $order_today->count();
        $order_amount = money($order_today->sum("total_amount"), "PHP", true);
        $order_pending = $order_today
            ->whereIn("status", [OrderStatus::PENDING, OrderStatus::PROCESSING])
            ->count();
        $order_cancelled = $order_today
            ->where("status", OrderStatus::CANCELLED)
            ->count();

        $products = Product::all();
        $products_hidden = $products->where("is_visible", false)->count();

        $customer = Customer::count();
        $new_customer = Customer::where("created_at", date("Y-m-d"))->count();

        return [
            Card::make(__("stats.order_today"), $order_amount)
                ->description(
                    __("stats.order_today_count", [
                        "count" => $order_count,
                    ])
                )
                ->icon("heroicon-o-currency-dollar")
                ->color("success"),
            Card::make(__("stats.order_pending"), $order_pending)
                ->description(
                    __("stats.order_cancelled", ["count" => $order_cancelled])
                )
                ->icon("heroicon-o-shopping-bag")
                ->descriptionIcon("heroicon-o-exclamation")
                ->color("danger"),
            Card::make(__("stats.product_count"), $products->count())
                ->description(
                    __("stats.product_hidden", ["count" => $products_hidden])
                )
                ->icon("heroicon-o-lightning-bolt")
                ->color("warning"),
            Card::make(__("stats.customer"), $customer)
                ->description(
                    __("stats.new_customer", ["count" => $new_customer])
                )
                ->icon("heroicon-o-user-group")
                ->color("success"),
        ];
    }
}
