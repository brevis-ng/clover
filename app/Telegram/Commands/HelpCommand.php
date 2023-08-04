<?php

namespace App\Telegram\Commands;

use App\Enums\Roles;
use App\Models\User;
use App\Settings\TelegramBotSettings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class HelpCommand extends Command
{
    protected string $command = "help";

    protected ?string $description = "Help command";

    public function handle(Nutgram $bot): void
    {
        $inline_button = new InlineKeyboardMarkup();

        $assistant = Cache::remember("assistant", 3600, function () {
            return User::whereIn("role", [Roles::ADMIN, Roles::ASSISTANT])
                ->whereNotNull("telegram_id")
                ->get();
        });

        $assistant
            ->map(
                fn($user) => InlineKeyboardButton::make(
                    __("order.assistant", ["name" => $user->name]),
                    $user->getTelegramUrl()
                )
            )
            ->chunk(1)
            ->each(fn($row) => $inline_button->addRow(...$row->values()));

        $inline_button->addRow(
            InlineKeyboardButton::make(
                app(TelegramBotSettings::class)->webapp_inline_button,
                web_app: new WebAppInfo(
                    app(TelegramBotSettings::class)->webapp_url
                )
            )
        );

        try {
            $bot->sendMessage(
                __("frontend.help"),
                parse_mode: ParseMode::HTML,
                reply_markup: $inline_button
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
