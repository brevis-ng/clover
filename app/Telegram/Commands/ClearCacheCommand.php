<?php

namespace App\Telegram\Commands;

use Illuminate\Support\Facades\Artisan;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommandScopeChat;

class ClearCacheCommand extends Command
{
    protected string $command = "clear_cache";

    protected ?string $description = "Flush the application cache";

    public function scopes(): array
    {
        return [
            new BotCommandScopeChat(
                app(TelegramBotSettings::class)->administrator
            ),
        ];
    }

    public function handle(Nutgram $bot): void
    {
        $exit_code = Artisan::call("cache:clear");
        $bot->sendMessage("Cache is cleared successfully!");
    }
}
