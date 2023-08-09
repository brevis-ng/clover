<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class PanelUpdatedOrder implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    private Order $order;
    private ?string $was_changed;
    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, ?string $was_changed = null)
    {
        $this->order = $order;
        $this->was_changed = $was_changed;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Telegram::sendMessage(
                __("order.order_updated", [
                    "order" => $this->order->order_number,
                    "field" => $this->was_changed
                ]),
                $this->order->customer->id
            );
            Telegram::sendMessage(
                message("order-detail", ["order" => $this->order]),
                chat_id: $this->order->customer->id,
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
