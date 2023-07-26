<?php

namespace App\Telegram\Commands;

use App\Models\User;
use App\Settings\TelegramBotSettings;
use Illuminate\Support\Facades\Cache;
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

        $assistant = Cache::get("assistant", User::assistant()->get());

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
            info($e->getMessage());
        }
    }
}
