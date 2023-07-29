<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CustomersChart extends LineChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $data = Trend::model(Customer::class)
            ->between(start: now()->startOfMonth(), end: now()->endOfMonth())
            ->perDay()
            ->count();

        return [
            "datasets" => [
                [
                    "label" => "Customer",
                    "data" => $data->map(
                        fn(TrendValue $value) => $value->aggregate
                    ),
                ],
            ],
            "labels" => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }
}
