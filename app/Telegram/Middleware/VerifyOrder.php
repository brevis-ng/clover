<?php

namespace App\Telegram\Middleware;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class VerifyOrder
{
    public function __invoke(Nutgram $bot, $next): void
    {
        try {
            $order_number = trim($bot->currentParameters()[0]);
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
                        "⚠️ *Order not editable\!*\nThis order just edited in admin panel only\.",
                        parse_mode: ParseMode::MARKDOWN
                    );
                    return;
                }

                $bot->setUserData(Order::class, $order, $bot->userId());

                $next($bot);
            } else {
                $bot->sendMessage(
                    "❌ *Order not found\!*\nPlease ensure that you have entered the correct order number and try again\.",
                    parse_mode: ParseMode::MARKDOWN
                );
                return;
            }
        } catch (\Throwable $e) {
            Log::critical("{class} Error in line {line}: {message}", [
                "class" => self::class,
                "line" => $e->getLine(),
                "message" => $e->getMessage(),
            ]);
        }

    }
}
