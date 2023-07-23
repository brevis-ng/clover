<?php

namespace App\Telegram\Middleware;

use App\Settings\TelegramBotSettings;
use SergiX44\Nutgram\Nutgram;

class IsAdmin
{
    public function __invoke(Nutgram $bot, $next): void
    {
        if ($bot->userId() !== app(TelegramBotSettings::class)->administrator) {
            $bot->sendMessage("Sorry, I can't do that right now.");

            return;
        }

        $next($bot);
    }
}
