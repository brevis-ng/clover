<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Settings\TelegramBotSettings;
use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Facades\Log;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class SendTelegramMessages extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "ramshop:send-telegram-messages";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send messages to groups, channel or members.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::enabled()->get();

        foreach ($tasks as $task) {
            if ($task->chat->blocked_at) {
                continue;
            }

            $cron = new CronExpression($task->cron);

            if ($cron->isDue()) {
                try {
                    $content = $task->getContent();
                    $image = $task->getImage();

                    $inline_button = InlineKeyboardMarkup::make()->addRow(
                        InlineKeyboardButton::make(
                            app(TelegramBotSettings::class)->webapp_inline_button,
                            web_app: new WebAppInfo(app(TelegramBotSettings::class)->webapp_url)
                        )
                    );

                    if ($image) {
                        Telegram::sendPhoto(
                            photo: $image,
                            chat_id: $task->chat_id,
                            caption: $content,
                            parse_mode: ParseMode::HTML,
                            reply_markup: $inline_button
                        );
                    } else {
                        Telegram::sendMessage(
                            text: $content,
                            chat_id: $task->chat_id,
                            parse_mode: ParseMode::HTML,
                            reply_markup: $inline_button
                        );
                    }

                    Log::info(
                        "Task {name} be sent successfully to {chat_name}({chat_id}).",
                        [
                            "name" => $task->name,
                            "chat_name" => $task->chat->name,
                            "chat_id" => $task->chat->id,
                        ]
                    );
                } catch (\Exception $e) {
                    Log::critical("{class} Error in line {line}: {message}", [
                        "class" => self::class,
                        "line" => $e->getLine(),
                        "message" => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
