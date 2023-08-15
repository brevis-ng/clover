<?php

namespace App\Telegram\Commands;

use App\Models\Customer;
use App\Settings\TelegramBotSettings;
use Illuminate\Support\Facades\Cache;
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
    protected string $key = "start_command_cache_key";

    public function handle(Nutgram $bot): void
    {
        try {
            $inline_button = InlineKeyboardMarkup::make()->addRow(
                InlineKeyboardButton::make(
                    app(TelegramBotSettings::class)->webapp_inline_button,
                    web_app: new WebAppInfo(app(TelegramBotSettings::class)->webapp_url)
                )
            );

            $msg_content = (string)Cache::remember($this->key, 3600, function () {
                $elements = ["<p>", "</p>"];
                $replaces = ["", "\n"];
                $msg = str_replace($elements, $replaces, app(TelegramBotSettings::class)->start_msg_content);
                return $msg;
            });
            info($msg_content);

            $image = app(TelegramBotSettings::class)->start_msg_photo;
            $image_url = null;
            if ($image && Storage::disk("tasks")->exists($image)) {
                $image_url = Storage::disk("tasks")->url($image);
            }

            if ($image_url) {
                $bot->sendPhoto(
                    $image_url,
                    caption: $msg_content,
                    parse_mode: ParseMode::HTML,
                    reply_markup: $inline_button
                );
            } else {
                $bot->sendMessage(
                    text: $msg_content,
                    parse_mode: ParseMode::HTML,
                    reply_markup: $inline_button
                );
            }
        } catch (\Throwable $e) {
            Log::critical("{class} Error in line {line}: {message}", [
                "class" => self::class,
                "line" => $e->getLine(),
                "message" => $e->getMessage(),
            ]);
        }
    }
}
