<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Closure;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{
    protected int|string|array $columnSpan = "full";

    protected static ?int $sort = 3;

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return "created_at";
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return "desc";
    }

    protected function getTableQuery(): Builder
    {
        return Order::query()->latest();
    }

    protected function getTableHeading(): string
    {
        return __("stats.latest_orders");
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make("order_number")
                ->label(__("order.order_number"))
                ->searchable()
                ->sortable(),
            TextColumn::make("customer.name")
                ->label(__("order.customer"))
                ->searchable()
                ->sortable(),
            BadgeColumn::make("status")
                ->label(__("order.status"))
                ->sortable()
                ->enum(OrderStatus::all())
                ->colors([
                    "danger" => fn($state) => in_array($state, [
                        OrderStatus::CANCELLED->value,
                        OrderStatus::FAILED->value,
                    ]),
                    "warning" => fn($state) => in_array($state, [
                        OrderStatus::PENDING->value,
                        OrderStatus::PROCESSING->value,
                    ]),
                    "success" => fn($state) => in_array($state, [
                        OrderStatus::COMPLETED->value,
                        OrderStatus::SHIPPED->value,
                    ]),
                ]),
            TextColumn::make("total_amount")
                ->label(__("order.total_amount"))
                ->money(shouldConvert: true)
                ->sortable(),
            TextColumn::make("shipping_amount")
                ->label(__("order.shipping_amount"))
                ->money(shouldConvert: true),
            TextColumn::make("payment_method")->label(
                __("order.payment_method")
            ),
            TextColumn::make("created_at")
                ->label(__("order.created_at"))
                ->dateTime()
                ->sortable(),
        ];
    }
}
