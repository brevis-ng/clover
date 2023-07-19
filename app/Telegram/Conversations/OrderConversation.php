<?php

namespace App\Telegram\Conversations;

use App\Enums\OrderStatus;
use App\Helpers\CartManager;
use App\Models\Customer;
use App\Models\Order;
use App\Settings\TelegramBotSettings;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class OrderConversation extends Conversation
{
    protected int $message_id;
    protected Order|null $order;
    protected Customer|null $customer;
    public function start(Nutgram $bot)
    {
        $this->customer = $bot->chatId()
            ? Customer::where("telegram_id", $bot->chatId())
            : CartManager::customer();

        $order_number = $bot->getUserData("order_number", $this->customer->telegram_id);
        $this->order = Order::where("order_number", $order_number)->first();

        if (! $this->order ) {
            $bot->sendMessage("You haven't order!");
            $this->end();
        }

        $text = message("order", ["order" => $this->order, "customer" => $this->customer]);

        $reply_markup = InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(__("order.update_phone"), callback_data: "order.updatePhone"),
                InlineKeyboardButton::make(__("order.update_address"), callback_data: "order.updateAddress")
            )
            ->addRow(
                InlineKeyboardButton::make(__("order.cancel"), callback_data: "order.cancelOrder")
            );

        collect(app(TelegramBotSettings::class)->customers_support)
            ->map(fn ($item, $key) => InlineKeyboardButton::make("Support $key", "tg://user?id=$item"))
            ->chunk(3)
            ->each(fn ($row) => $reply_markup->addRow(...$row->values()));

        $message = $bot->sendMessage(
            $text,
            $this->customer->telegram_id,
            parse_mode: ParseMode::HTML,
            reply_markup: $reply_markup
        );

        $this->message_id = $message->message_id;

        $this->next("getAction");
    }

    public function getAction(Nutgram $bot)
    {
        if ( $bot->isCallbackQuery() ) {
            $action = $bot->callbackQuery()->data;
            if ( $action === "order.updatePhone" ) {
                $bot->sendMessage(__("settings.order.update_phone"));
                $this->next("updatePhone");
            } else if ( $action === "order.updateAddress" ) {
                $bot->sendMessage(__("settings.order.update_address"));
                $this->next("updateAddress");
            } else if ( $action === "order.cancelOrder" ) {
                $this->order->status = OrderStatus::CANCELLED;
                $this->order->save();

                $this->end();
            }
        }
    }

    public function updatePhone(Nutgram $bot)
    {
        if ($bot->message()?->text === null) {
            $bot->sendMessage(__("settings.order.wrong"));
            $this->start($bot);
            return;
        }

        $this->customer->phone = $bot->message()?->text;
        $this->customer->save();

        $this->start($bot);
    }

    public function updateAddress(Nutgram $bot)
    {
        if ($bot->message()?->text === null) {
            $bot->sendMessage(__("settings.order.wrong"));
            $this->start($bot);
            return;
        }

        $this->order->address = $bot->message()?->text;
        $this->order->save();

        $this->start($bot);
    }
}
