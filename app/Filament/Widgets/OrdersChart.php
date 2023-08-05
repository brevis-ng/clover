<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\BarChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersChart extends BarChartWidget
{
    protected static ?int $sort = 1;

    protected function getHeading(): string
    {
        return __("stats.order_chart_heading");
    }

    protected function getData(): array
    {
        $data = Trend::query(Order::where("status", "<>", OrderStatus::CANCELLED))
            ->between(start: now()->startOfMonth(), end: now()->endOfMonth())
            ->perDay();
        $total_amount = $data->sum("total_amount");
        $shipping_amount = $data->sum("shipping_amount");

        return [
            "datasets" => [
                [
                    "label" => __("stats.total_amount"),
                    "data" => $total_amount->map(fn(TrendValue $value) => $value->aggregate),
                    "borderWidth" => 1,
                    "backgroundColor" => "blue",
                ],
                [
                    "label" => __("stats.shipping_amount"),
                    "data" => $shipping_amount->map(fn(TrendValue $value) => $value->aggregate),
                    "borderWidth" => 1,
                    "backgroundColor" => "red",
                ]
            ],
            "labels" => $total_amount->map(fn(TrendValue $value) => $value->date),
        ];
    }
}
