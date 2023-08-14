<?php

namespace App\Telegram\Middleware;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use SergiX44\Nutgram\Nutgram;

class CollectChat
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $chat = $bot->chat();

        if ($chat === null) {
            return;
        }

        $chat = DB::transaction(function () use ($chat, $bot) {
            $chat = Customer::updateOrCreate(
                [
                    "id" => $chat->id,
                ],
                [
                    "type" => $chat->type,
                    "name" =>
                        $chat->title ??
                        $chat->first_name . " " . $chat->last_name,
                    "username" => $chat->username,
                    "language_code" => $bot->user()?->language_code,
                    "blocked_at" => null,
                ]
            );

            if (!$chat->started_at) {
                $chat->started_at = now();
                $chat->save();
            }

            return $chat;
        });

        if ($chat->language_code) {
            $chat->language_code == "vi"
                ? session()->put("locale", "vi")
                : session()->put("locale", "zh");
        }

        $bot->set(Customer::class, $chat);

        $next($bot);
    }
}
