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
        $customer = Customer::where("telegram_id", $bot->chatId())->first();

        $orders = $customer
            ->orders()
            ->whereIn("status", [OrderStatus::PENDING, OrderStatus::PROCESSING])
            ->count();

        if ($orders == 0) {
            $bot->sendMessage("You don't have any orders yet!");

            return;
        }

        $next($bot);
    }
}
