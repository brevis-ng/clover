<?php

namespace App\Telegram\Conversations;

use App\Enums\OrderStatus;
use App\Helpers\CartManager;
use App\Models\Customer;
use App\Models\Order;
use App\Settings\TelegramBotSettings;
use Illuminate\Support\Facades\Cache;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class OrderConversation extends InlineMenu
{
    protected ?Order $order;
    protected ?Customer $customer;
    protected bool $reopen = false;
    protected ?int $userId;

    protected function next(string $step): void
    {
        $this->step = $step;

        $this->bot->stepConversation(
            $this,
            $this->bot->userId() ?? $this->userId,
            $this->bot->chatId() ?? $this->chatId
        );
    }

    public function start(Nutgram $bot)
    {
        $this->customer = $bot->chatId()
            ? Customer::where("telegram_id", $bot->chatId())->first()
            : CartManager::customer();

        if (!$this->customer) {
            $bot->sendMessage("You haven't order!");
            $this->closeMenu();
            return null;
        }

        $order_number = $bot->getUserData("order_number", $this->customer->telegram_id);
        $this->order = Order::where("order_number", $order_number)->first();

        if (!$this->order || $this->order->status == OrderStatus::CANCELLED) {
            $bot->sendMessage("You haven't order!");
            $this->closeMenu();
            return null;
        }

        $this->chatId = $this->customer->telegram_id;
        $text = message("order", ["order" => $this->order, "customer" => $this->customer]);

        $this->clearButtons()->menuText($text, ["parse_mode" => ParseMode::HTML]);
        $this->addButtonRow(
            InlineKeyboardButton::make(__("order.update_phone"), callback_data: "order@updatePhone"),
            InlineKeyboardButton::make(__("order.update_address"), callback_data: "order@updateAddress")
        );
        $this->addButtonRow(InlineKeyboardButton::make(__("order.cancel"), callback_data: "order@cancelOrder"));

        collect(app(TelegramBotSettings::class)->customers_support)
            ->map(fn ($item, $key) => InlineKeyboardButton::make("Support $key", "tg://user?id=$item"))
            ->chunk(3)
            ->each(fn ($row) => $this->addButtonRow(...$row->values()));

        $this->showMenu($this->reopen);
    }

    protected function updatePhone(Nutgram $bot)
    {
        $this->clearButtons()->menuText(__("order.update_phone_send"))->orNext("setNewPhone");
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

        $this->reopen = true;
        $this->start($bot);
    }

    protected function updateAddress(Nutgram $bot)
    {
        $this->clearButtons()->menuText(__("order.update_address_send"))->orNext("setNewAddress");
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
        $this->start($bot);
    }

    protected function cancelOrder(Nutgram $bot)
    {
        $this->order->status = OrderStatus::CANCELLED;
        $this->order->save();

        $this->closeMenu();
    }
}
