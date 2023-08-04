<?php

namespace App\Telegram\Conversations;

use App\Enums\OrderStatus;
use App\Jobs\UserUpdatedOrder;
use App\Models\Customer;
use App\Models\Order;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class OrderConversation extends InlineMenu
{
    protected Order $order;
    protected Customer $customer;

    public function start(Nutgram $bot)
    {
        $this->customer = $bot->get(
            Customer::class,
            Customer::find($bot->userId())
        );

        $orders = $this->customer
            ->orders()
            ->whereIn("status", [
                OrderStatus::PENDING,
                OrderStatus::PROCESSING,
                OrderStatus::SHIPPED,
            ])
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
                callback_data: "user:order@end"
            )
        );

        $this->showMenu();
    }

    protected function trackingOrder(Nutgram $bot)
    {
        $this->order = $bot->isCallbackQuery()
            ? Order::where("order_number", $bot->callbackQuery()->data)->first()
            : $this->order;

        $text = message("order-detail", ["order" => $this->order]);

        $this->clearButtons()
            ->menuText($text, ["parse_mode" => ParseMode::HTML])
            ->addButtonRow(
                InlineKeyboardButton::make(
                    __("order.update_phone"),
                    callback_data: "user:order@updatePhone"
                ),
                InlineKeyboardButton::make(
                    __("order.update_address"),
                    callback_data: "user:order@updateAddress"
                )
            )
            ->addButtonRow(
                InlineKeyboardButton::make(
                    __("order.cancel"),
                    callback_data: "user:order@askCancelOrder"
                )
            )
            ->addButtonRow(
                InlineKeyboardButton::make(
                    __("order.back"),
                    callback_data: "order@start"
                )
            )
            ->showMenu();
    }

    protected function updatePhone(Nutgram $bot)
    {
        $this->clearButtons()
            ->menuText(__("order.update_phone_send"))
            ->orNext("setNewPhone");
        $this->showMenu();
    }

    protected function setNewPhone(Nutgram $bot)
    {
        if ($bot->message()?->text === null) {
            $bot->sendMessage(__("order.invalid_value"));
            $this->updatePhone($bot);
        }

        $this->customer->phone = $bot->message()->text;
        $this->customer->save();

        UserUpdatedOrder::dispatch($this->order, null, __("customer.phone"));

        $this->trackingOrder($bot);
    }

    protected function updateAddress(Nutgram $bot)
    {
        $this->clearButtons()
            ->menuText(__("order.update_address_send"))
            ->orNext("setNewAddress");
        $this->showMenu();
    }

    protected function setNewAddress(Nutgram $bot)
    {
        if ($bot->message()?->text === null) {
            $bot->sendMessage(__("order.invalid_value"));
            $this->updatePhone($bot);
        }

        $this->order->address = $bot->message()?->text;
        $this->order->save();

        UserUpdatedOrder::dispatch($this->order, null, __("order.address"));

        $this->trackingOrder($bot);
    }

    protected function askCancelOrder(Nutgram $bot)
    {
        if (
            $this->customer->id == $this->order->customer_id &&
            in_array($this->order->status, [
                OrderStatus::PENDING,
                OrderStatus::PROCESSING,
            ])
        ) {
            $this->clearButtons()
                ->menuText(__("order.cancelled_ask"), [
                    "parse_mode" => ParseMode::HTML,
                ])
                ->orNext("cancelOrder")
                ->showMenu();
        }

        if ($this->customer->cannot("cancel", $this->order)) {
            $bot->sendMessage(__("order.unable_to_cancel_order"));
            $this->end();
            return null;
        }
    }

    protected function cancelOrder(Nutgram $bot)
    {
        $this->order->status = OrderStatus::CANCELLED;
        $this->order->save();

        UserUpdatedOrder::dispatch($this->order, $bot->messageId());

        $bot->sendMessage("Order cancelled");
        $this->start($bot);
    }
}
