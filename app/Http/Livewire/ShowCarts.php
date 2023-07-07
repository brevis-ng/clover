<?php

namespace App\Http\Livewire;

use App\Helpers\CartManager;
use App\Models\Customer;
use App\Settings\GeneralSettings;
use Livewire\Component;

class ShowCarts extends Component
{
    protected $listeners = ['tg:initData' => 'telegramInitDataHandler'];

    public function telegramInitDataHandler($raw_data)
    {
        parse_str($raw_data, $data);

        if ($this->validateTelegramData($data)) {
            $user_data = json_decode($data['user'], true);

            CartManager::storeCustomer($user_data);
        }
    }

    protected function validateTelegramData($data)
    {
        asort($data);
        $data_check_str = [];
        foreach ($data as $key => $value) {
            if ($key === 'hash') {
                continue;
            }

            if (is_array($value)) {
                $data_check_str[] = $key . '=' . json_encode($value);
            } else {
                $data_check_str[] = $key . '=' . $value;
            }
        }

        $data_check_str = implode("\n", $data_check_str);
        $secret_key = hash_hmac('sha256', app(GeneralSettings::class)->bot_token, 'WebAppData', true);
        $sig = hash_hmac('sha256', $data_check_str, $secret_key);

        return $sig === $data['hash'];
    }

    public function clear()
    {
        CartManager::clear();
        CartManager::clearCustomer();

        return redirect()->route('frontend.index');
    }

    public function getSubtotal()
    {
        return CartManager::subtotal();
    }

    public function render()
    {
        return view('livewire.show-carts', [
            'cart' => CartManager::items(),
        ]);
    }
}
