<?php

namespace App\Telegram\Middleware;

use App\Enums\OrderStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use SergiX44\Nutgram\Nutgram;

class HasOrder
{
    public function __invoke(Nutgram $bot, $next)
    {
        $hasOrder = false;

        $customer = Customer::where("telegram_id", $bot->userId())->first();

        if ($customer) {
            $orders = $customer
                ->orders()
                ->whereIn("status", [
                    OrderStatus::PENDING,
                    OrderStatus::PROCESSING,
                ])
                ->count();

            $hasOrder = $orders > 0;
        }

        if (!$hasOrder) {
            $bot->sendMessage("You don't have any orders yet!");

            return;
        }

        $next($bot);
    }
}
