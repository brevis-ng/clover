<?php

namespace App\Telegram\Middleware;

use App\Enums\OrderStatus;
use App\Models\Order;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class VerifyOrder
{
    public function __invoke(Nutgram $bot, $next): void
    {
        if (!$bot->currentParameters()) {
            $bot->sendMessage(
                "❌ *Invalid Format\!*\nChecking order should be entered in the following format:\n `/order ABC123`",
                parse_mode: ParseMode::MARKDOWN
            );
            return;
        }

        [$order_number] = $bot->currentParameters();

        $order = Order::where("order_number", $order_number)->first();

        if ($order) {
            if (
                in_array($order->status, [
                    OrderStatus::FAILED,
                    OrderStatus::CANCELLED,
                    OrderStatus::COMPLETED,
                ])
            ) {
                $bot->sendMessage(
                    "⚠️ *Order not editable\!*\nOrder status is " .
                        $order->status->value .
                        "\. Please edit in admin panel\.",
                    parse_mode: ParseMode::MARKDOWN
                );
                return;
            }
        } else {
            $bot->sendMessage(
                "❌ *Order not found\!*",
                parse_mode: ParseMode::MARKDOWN
            );
            return;
        }

        $bot->set(Order::class, $order);

        $next($bot);
    }
}
