<?php

namespace App\Telegram\Middleware;

use App\Models\Order;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class VerifyOrder
{
    public function __invoke(Nutgram $bot, $next): void
    {
        [$order_number] = $bot->currentParameters();

        $order = Order::where("order_number", $order_number)->first();

        if (! $order ) {
            $bot->sendMessage(
                "❌ *Order not found\!*\nChecking order should be entered in the following format:\n `/order ABC123`",
                parse_mode: ParseMode::MARKDOWN
            );

            return;
        }

        $bot->set(Order::class, $order);

        $next($bot);
    }
}
