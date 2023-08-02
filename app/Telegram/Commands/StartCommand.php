<?php

namespace App\Telegram\Commands;

use App\Models\Customer;
use App\Settings\TelegramBotSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class StartCommand extends Command
{
    protected string $command = "start";

    protected ?string $description = "Welcome message";

    public function handle(Nutgram $bot): void
    {
        try {
            $inline_button = InlineKeyboardMarkup::make()->addRow(
                InlineKeyboardButton::make(
                    app(TelegramBotSettings::class)->webapp_inline_button,
                    web_app: new WebAppInfo(app(TelegramBotSettings::class)->webapp_url)
                )
            );
            if ($photo_name = app(TelegramBotSettings::class)->start_msg_photo) {
                $photo_data = fopen(Storage::path($photo_name), "r+");

                if ($photo_data) {
                    $bot->sendPhoto(
                        photo: InputFile::make($photo_data),
                        caption: app(TelegramBotSettings::class)->start_msg_content,
                        parse_mode: ParseMode::HTML,
                        reply_markup: $inline_button
                    );
                }
            } else {
                $bot->sendMessage(
                    text: app(TelegramBotSettings::class)->start_msg_content,
                    parse_mode: ParseMode::HTML,
                    reply_markup: $inline_button
                );
            }
        } catch (\Throwable $e) {
            Log::error(self::class." Error in line ".$e->getLine().": ".$e->getMessage());
        }
    }
}
