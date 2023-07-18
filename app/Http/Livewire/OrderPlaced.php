<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use App\Models\Customer;
use App\Settings\GeneralSettings;
use Livewire\Component;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class OrderPlaced extends Component
{
    public $name;
    public $phone;
    public $address;
    public $payment = "cod";
    public $notes;

    protected $rules = [
        "name" => "required|max:255",
        "phone" => "required|numeric|min_digits:10",
        "address" => "required|max:255",
        "payment" => "required",
        "notes" => "max:255",
    ];

    public function mount()
    {
        $this->name = CartManager::customer()?->name;
        $this->phone = CartManager::customer()?->phone;
    }

    public function submit()
    {
        $this->validate();

        $customer = CartManager::customer();
        if (!$customer) {
            $customer = Customer::create([
                "name" => $this->name,
                "phone" => $this->phone
            ]);
        } else {
            $customer->name = $this->name;
            $customer->phone = $this->phone;
            $customer->save();
        }

        $order = CartManager::order($customer->id);
        $order->address = $this->address;
        $order->payment_method = $this->payment;
        $order->notes = $this->notes;
        $order->save();

        foreach (CartManager::items() as $item) {
            $order->products()->attach($item->product["id"], [
                "quantity" => $item->quantity,
                "amount" => $item->amount,
            ]);
        }

        $this->emit("tg:orderPlaced", __("admin.order_placed_successfully"));

        $message = message("order", [
            "customer" => $customer,
            "items" => CartManager::items(),
            "order" => $order,
        ]);

        if ($customer?->telegram_id) {
            Telegram::sendMessage(
                text: $message,
                chat_id: $customer?->telegram_id,
                parse_mode: ParseMode::HTML,
                reply_markup: InlineKeyboardMarkup::make()->addRow(
                    InlineKeyboardButton::make(
                        text: "Chat với CSKH",
                        url: "tg://user?id=" . app(GeneralSettings::class)->shop_telegram_id
                    )
                ),
            );
        }
        if ($shop_id = app(GeneralSettings::class)->shop_telegram_id) {
            Telegram::sendMessage(
                text: $message,
                chat_id: $shop_id,
                parse_mode: ParseMode::HTML,
                reply_markup: InlineKeyboardMarkup::make()->addRow(
                    InlineKeyboardButton::make(
                        text: "Chat với Khách",
                        url: "tg://user?id=" . $customer?->telegram_id
                    )
                ),
            );
        }

        session()->flush();
    }

    public function render()
    {
        return view("livewire.order-placed", [
            "cart" => CartManager::items(),
            "subtotal" => CartManager::subtotal(),
        ]);
    }
}
