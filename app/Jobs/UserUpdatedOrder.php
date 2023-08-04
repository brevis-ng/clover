<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class UserUpdatedOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    private Order $order;
    private ?int $msg_id;
    private ?string $was_changed;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, ?int $msg_id, ?string $was_changed)
    {
        $this->order = $order;
        $this->msg_id = $msg_id;
        $this->was_changed = $was_changed;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $administrators = Cache::rememberForever(
            "administrator",
            fn() => User::admin()->get()
        );

        foreach ($administrators as $admin) {
            if ($this->order->status == OrderStatus::CANCELLED) {
                try {
                    Telegram::sendMessage(
                        __("order.order_cancelled", [
                            "order" => $this->order->order_number,
                        ]),
                        $admin->telegram_id,
                        parse_mode: ParseMode::HTML
                    );
                    Telegram::forwardMessage(
                        $admin->telegram_id,
                        $this->order->customer->id,
                        $this->msg_id
                    );
                } catch (\Throwable $e) {
                    Log::critical("{class} Error in line {line}: {message}", [
                        "class" => self::class,
                        "line" => $e->getLine(),
                        "message" => $e->getMessage(),
                    ]);
                }
            } else {
                try {
                    Telegram::sendMessage(
                        __("order.order_updated", [
                            "order" => $this->order->order_number,
                            "field" => $this->was_changed
                        ]),
                        $admin->telegram_id
                    );
                    Telegram::sendMessage(
                        message("order-detail", ["order" => $this->order]),
                        chat_id: $admin->telegram_id,
                        parse_mode: ParseMode::HTML
                    );
                } catch (\Throwable $e) {
                    Log::critical("{class} Error in line {line}: {message}", [
                        "class" => self::class,
                        "line" => $e->getLine(),
                        "message" => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
