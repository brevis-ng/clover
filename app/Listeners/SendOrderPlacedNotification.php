<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class SendOrderPlacedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        try {
            Telegram::sendMessage(
                message("order-notify"),
                chat_id: $event->order->customer->id,
                parse_mode: ParseMode::HTML,
                reply_markup: ReplyKeyboardMarkup::make(
                    resize_keyboard: true,
                    one_time_keyboard: true,
                    input_field_placeholder: "/myorder",
                    selective: true
                )->addRow(KeyboardButton::make(__("order.check")))
            );
        } catch (\Throwable $e) {
            Log::error(self::class." Error in line ".$e->getLine().": ".$e->getMessage());
        }

        $users = User::whereNotNull("telegram_id")->get();
        if ($users) {
            foreach ($users as $user) {
                try {
                    Telegram::sendMessage(
                        message("order-detail", ["order" => $event->order]),
                        $user->telegram_id,
                        parse_mode: ParseMode::HTML
                    );
                } catch (\Throwable $e) {
                    Log::error(self::class." Error in line ".$e->getLine().": ".$e->getMessage());
                    continue;
                }
            }
        }
    }
}
