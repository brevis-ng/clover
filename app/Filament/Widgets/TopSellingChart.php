<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class TopSellingChart extends BarChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = "10s";
    protected function getHeading(): string
    {
        return __("stats.top_selling_heading");
    }

    protected function getData(): array
    {
        $data = DB::table("order_product")
            ->whereMonth("order_product.created_at", date("m"))
            ->groupBy("order_product.product_id", "order_product.created_at")
            ->join("products", "order_product.product_id", "=", "products.id")
            ->select(
                "order_product.product_id",
                "products.name as product_name",
                DB::raw("sum(order_product.quantity) as product_sum"),
                "order_product.created_at"
            )
            ->orderBy("product_sum", "desc")
            ->take(10)
            ->get();

        return [
            "labels" => $data->map(fn($value) => $value->product_name),
            "datasets" => [
                [
                    "label" => __("stats.top_selling_label"),
                    "data" => $data->map(fn($value) => $value->product_sum),
                    "backgroundColor" => [
                        "#FF55BB",
                        "#FE0000",
                        "#00DFA2",
                        "#F7D060",
                        "#3C486B",
                        "#F86F03",
                        "#8062D6",
                        "#272829",
                        "#FD8D14",
                        "#B0DAFF",
                    ],
                ],
            ],
        ];
    }
}
