<?php

namespace App\Telegram\Commands;

use Illuminate\Support\Facades\Artisan;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Handlers\Type\Command;

class ClearCacheCommand extends Command
{
    protected string $command = "cache";

    protected ?string $description = "Flush the application cache";

    public function handle(Nutgram $bot): void
    {
        $exit_code = Artisan::call("cache:clear");
        $bot->sendMessage("Cache is cleared successfully!");
    }
}
