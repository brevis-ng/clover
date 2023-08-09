<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Jobs\PanelUpdatedOrder;
use App\Models\Order;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        try {
            $order = Order::whereOrderNumber($data["order_number"])
                ->withSum("items", "amount")
                ->first();

            $amount = $order->items_sum_amount;
            $data["total_amount"] = $amount + $data["shipping_amount"];

            PanelUpdatedOrder::dispatch($order);
        } catch (\Throwable $e) {
            Log::critical("{class} Error in line {line}: {message}", [
                "class" => self::class,
                "line" => $e->getLine(),
                "message" => $e->getMessage(),
            ]);
        }

        return $data;
    }

    protected function getActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
