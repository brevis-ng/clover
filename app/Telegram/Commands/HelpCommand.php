<?php

namespace App\Telegram\Commands;

use App\Settings\TelegramBotSettings;
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

        collect(app(TelegramBotSettings::class)->customers_support)
            ->map(
                fn($item, $key) => InlineKeyboardButton::make(
                    __("order.customer_service", ["name" => $key]),
                    "tg://user?id=$item"
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

        $bot->sendMessage(
            __("frontend.help"),
            parse_mode: ParseMode::HTML,
            reply_markup: $inline_button
        );
    }
}
