<?php

namespace App\Telegram\Conversations;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Settings\TelegramBotSettings;
use Illuminate\Support\Facades\Cache;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class OrderConversation extends InlineMenu
{
    protected Order $order;
    protected Customer $customer;
    protected bool $reopen = false;

    public function start(Nutgram $bot)
    {
        $this->customer = Cache::remember(
            "active_customer_" . $bot->userId(),
            300,
            fn() => Customer::whereTelegramId($bot->userId())->first()
        );

        $orders = $this->customer
            ->orders()
            ->whereIn("status", [OrderStatus::PENDING, OrderStatus::PROCESSING])
            ->get();

        $text = __("order.list", ["count" => $orders->count()]);

        $this->clearButtons()->menuText($text);

        $orders
            ->map(function ($order) {
                return InlineKeyboardButton::make(
                    $order->order_number,
                    callback_data: "$order->order_number@trackingOrder"
                );
            })
            ->chunk(3)
            ->each(fn($row) => $this->addButtonRow(...$row->values()));

        $this->addButtonRow(
            InlineKeyboardButton::make(
                __("order.close"),
                callback_data: "settings:cancel@end"
            )
        );

        $this->showMenu($this->reopen);
    }

    protected function trackingOrder(Nutgram $bot)
    {
        if ($bot->isCallbackQuery()) {
            $order_number = $bot->callbackQuery()?->data;

            $this->order = Order::where("order_number", $order_number)->first();
        }

        $text = message("order-detail", ["order" => $this->order]);

        $this->clearButtons()
            ->menuText($text, ["parse_mode" => ParseMode::HTML])
            ->addButtonRow(
                InlineKeyboardButton::make(
                    __("order.update_phone"),
                    callback_data: "order@updatePhone"
                ),
                InlineKeyboardButton::make(
                    __("order.update_address"),
                    callback_data: "order@updateAddress"
                )
            )
            ->addButtonRow(
                InlineKeyboardButton::make(
                    __("order.cancel"),
                    callback_data: "order@askCancelOrder"
                )
            );

        collect(app(TelegramBotSettings::class)->customers_support)
            ->map(
                fn($item, $key) => InlineKeyboardButton::make(
                    __("order.customer_service", ["name" => $key]),
                    "tg://user?id=$item"
                )
            )
            ->chunk(3)
            ->each(fn($row) => $this->addButtonRow(...$row->values()));

        $this->addButtonRow(
            InlineKeyboardButton::make(
                __("order.back"),
                callback_data: "orderk@start"
            )
        )->showMenu($this->reopen);

        $this->reopen = false;
    }

    protected function updatePhone(Nutgram $bot)
    {
        $this->clearButtons()
            ->menuText(__("order.update_phone_send"))
            ->orNext("setNewPhone");
        $this->showMenu(true);
    }

    protected function setNewPhone(Nutgram $bot)
    {
        if ($bot->message()?->text === null) {
            $bot->sendMessage(__("order.invalid_value"));
            $this->updatePhone($bot);
        }

        $this->customer->phone = $bot->message()->text;
        $this->customer->save();
        $this->order->refresh();

        $this->reopen = true;
        $this->trackingOrder($bot);
    }

    protected function updateAddress(Nutgram $bot)
    {
        $this->clearButtons()
            ->menuText(__("order.update_address_send"))
            ->orNext("setNewAddress");
        $this->showMenu(true);
    }

    protected function setNewAddress(Nutgram $bot)
    {
        if ($bot->message()?->text === null) {
            $bot->sendMessage(__("order.invalid_value"));
            $this->updatePhone($bot);
        }

        $this->order->address = $bot->message()?->text;
        $this->order->save();

        $this->reopen = true;
        $this->trackingOrder($bot);
    }

    protected function askCancelOrder(Nutgram $bot)
    {
        if ($this->canCancelOrder()) {
            $this->clearButtons()
                ->menuText(__("order.cancel_confirm"))
                ->orNext("cancelOrder")
                ->showMenu(true);
        }

        $bot->sendMessage(__("order.unable_to_cancel_order"));
        $this->end();
    }

    protected function canCancelOrder()
    {
        $this->order->refresh();

        if (
            in_array($this->order->status, [
                OrderStatus::PROCESSING,
                OrderStatus::SHIPPED,
                OrderStatus::CANCELLED,
            ])
        ) {
            return false;
        }

        return true;
    }

    protected function cancelOrder(Nutgram $bot)
    {
        $this->order->status = OrderStatus::CANCELLED;
        $this->order->save();

        $this->reopen = true;
        $this->start($bot);
    }
}
