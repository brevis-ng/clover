<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Customer;
use App\Settings\TelegramBotSettings;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function cancel(?Customer $user, Order $order): bool
    {
        $isAdmin = $user->id == app(TelegramBotSettings::class)->administrator;

        $orderOwner = $user->id === $order->customer_id;

        $openStateOrder = in_array($order->status, [
            OrderStatus::PENDING,
            OrderStatus::PROCESSING,
        ]);

        return $isAdmin || ($orderOwner && $openStateOrder);
    }
}
