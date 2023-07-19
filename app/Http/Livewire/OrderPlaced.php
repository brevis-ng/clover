<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use App\Models\Customer;
use App\Settings\TelegramBotSettings;
use App\Telegram\Conversations\OrderConversation;
use Livewire\Component;
use SergiX44\Nutgram\Nutgram;

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
                "phone" => $this->phone,
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

        $bot = new Nutgram(app(TelegramBotSettings::class)->bot_token);
        $bot->setUserData("order_number", $order->order_number, $customer?->telegram_id);

        OrderConversation::begin($bot);

        CartManager::clear();
    }

    public function render()
    {
        return view("livewire.order-placed", [
            "cart" => CartManager::items(),
            "subtotal" => CartManager::subtotal(),
        ]);
    }
}
