<?php

namespace App\Telegram\Conversations;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class NewOrderConversation extends InlineMenu
{
    protected Order $order;
    protected bool $reopen = false;
    protected OrderStatus $status;

    public function start(Nutgram $bot)
    {
        $orders = Cache::remember(
            "latest_five_orders",
            300,
            fn() => Order::whereIn("status", [
                OrderStatus::PENDING,
                OrderStatus::PROCESSING,
                OrderStatus::SHIPPED,
            ])
                ->latest()
                ->take(6)
                ->get()
        );

        $this->clearButtons()->menuText(
            __("order.latest_orders", ["count" => $orders->count()])
        );

        $orders
            ->map(
                fn($order) => InlineKeyboardButton::make(
                    $order->order_number,
                    callback_data: "$order->order_number@trackingOrder"
                )
            )
            ->chunk(3)
            ->each(fn($row) => $this->addButtonRow(...$row->values()));

        $this->addButtonRow(
            InlineKeyboardButton::make(
                __("order.close"),
                callback_data: "settings:cancel@end"
            )
        )->showMenu();
    }

    protected function trackingOrder(Nutgram $bot)
    {
        if ($bot->isCallbackQuery()) {
            $order_number = $bot->callbackQuery()?->data;

            $this->order = Order::where("order_number", $order_number)
                ->with(["customer", "products"])
                ->first();
            $this->status = $this->order->status;
        }

        $text = message("order-detail", ["order" => $this->order]);
        $this->clearButtons()
            ->menuText($text, ["parse_mode" => ParseMode::HTML])
            ->addButtonRow(
                InlineKeyboardButton::make(
                    __("order.update_shipping_amount"),
                    callback_data: "order@updateShippingAmount"
                ),
                InlineKeyboardButton::make(
                    __("order.update_status"),
                    callback_data: "order@updateStatus"
                )
            )
            ->addButtonRow(
                InlineKeyboardButton::make(
                    __("order.cancel"),
                    callback_data: "order@askCancelOrder"
                )
            )
            ->addButtonRow(
                InlineKeyboardButton::make(
                    __("order.back"),
                    callback_data: "order@start"
                )
            )
            ->showMenu($this->reopen);

        $this->reopen = false;
    }

    protected function updateShippingAmount(Nutgram $bot)
    {
        $this->clearButtons()
            ->menuText(__("order.update_shipping_amount_send"))
            ->orNext("setShippingAmount");

        $this->showMenu(true);
    }

    protected function setShippingAmount(Nutgram $bot)
    {
        if ($bot->message()?->text === null) {
            $bot->sendMessage(__("order.invalid_value"));
            $this->updateShippingAmount($bot);
        }

        $this->order->shipping_amount = intval($bot->message()->text);
        $this->order->total_amount += $this->order->shipping_amount;
        $this->order->save();
        $this->order->refresh();

        $this->reopen = true;
        $this->trackingOrder($bot);
    }

    protected function updateStatus(Nutgram $bot)
    {
        $text = __("order.update_status_send", [
            "status" => OrderStatus::trans($this->status),
            "new_status" => OrderStatus::trans($this->order->status),
        ]);
        $this->clearButtons()
            ->menuText($text, ["parse_mode" => ParseMode::HTML])
            ->addButtonRow(
                InlineKeyboardButton::make(
                    OrderStatus::trans(OrderStatus::PROCESSING),
                    callback_data: OrderStatus::PROCESSING->value .
                        "@setOrderStatus"
                ),
                InlineKeyboardButton::make(
                    OrderStatus::trans(OrderStatus::SHIPPED),
                    callback_data: OrderStatus::SHIPPED->value .
                        "@setOrderStatus"
                ),
                InlineKeyboardButton::make(
                    OrderStatus::trans(OrderStatus::COMPLETED),
                    callback_data: OrderStatus::COMPLETED->value .
                        "@setOrderStatus"
                )
            )
            ->addButtonRow(
                InlineKeyboardButton::make(
                    __("order.back"),
                    callback_data: $this->order->order_number . "@trackingOrder"
                )
            );

        $this->showMenu($this->reopen);
        $this->reopen = false;
    }

    protected function setOrderStatus(Nutgram $bot)
    {
        $this->order->status = OrderStatus::from($bot->callbackQuery()->data);
        $this->status = $this->order->getOriginal("status");
        $this->order->save();

        $this->updateStatus($bot);
    }

    protected function askCancelOrder(Nutgram $bot)
    {
        $this->clearButtons()
            ->menuText(__("order.explain_cancelled"))
            ->orNext("cancelOrder")
            ->showMenu(true);
    }

    protected function cancelOrder(Nutgram $bot)
    {
        if ($bot->message()?->text === null) {
            $bot->sendMessage(__("order.invalid_value"));
            $this->askCancelOrder($bot);
        }

        $explain = $bot->message()?->text;

        $this->order->status = OrderStatus::CANCELLED;
        $this->order->save();
        Cache::forget("latest_five_orders");

        $this->reopen = true;
        $this->start($bot);
    }
}
