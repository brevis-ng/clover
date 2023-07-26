<?php

namespace App\Jobs;

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

class UserCancelledOrder implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    private ?int $chat_id;
    private ?int $message_id;
    private string $order_number;

    /**
     * Create a new job instance.
     */
    public function __construct(?int $chat_id, ?int $message_id, string $order_number)
    {
        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
        $this->order_number = $order_number;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $administrators = Cache::rememberForever("administrator", fn () => User::admin()->get());

        foreach ($administrators as $admin) {
            try {
                Telegram::sendMessage(
                    __("order.order_cancelled", ["order" => $this->order_number]),
                    $admin->telegram_id,
                    parse_mode: ParseMode::HTML,
                );
                Telegram::forwardMessage(
                    $admin->telegram_id,
                    $this->chat_id,
                    $this->message_id,
                );
            } catch (\Throwable $e) {
                Log::error(self::class." Error in line ".$e->getLine().": ".$e->getMessage());
            }
        }
    }
}
