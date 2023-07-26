<?php

namespace App\Telegram\Middleware;

use App\Models\User;
use SergiX44\Nutgram\Nutgram;

class IsAdmin
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $admin = User::admin()->where("telegram_id", $bot->userId())->first();

        if (!$admin) {
            $bot->sendMessage("Sorry, I can't do that right now.");

            return;
        }

        $next($bot);
    }
}
