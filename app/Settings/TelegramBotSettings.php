<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TelegramBotSettings extends Settings
{
    public string $bot_token;
    public string $bot_username;
    public string $webapp_inline_button;
    public string $webapp_menu_button;
    public string $webapp_url;
    public ?string $start_msg_content;
    public ?string $start_msg_photo;


    public static function group(): string
    {
        return 'telegram';
    }
}
