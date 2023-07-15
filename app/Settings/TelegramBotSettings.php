<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TelegramBotSettings extends Settings
{
    public string $bot_token;
    public string $bot_username;
    public string $inline_btn_title;
    public string $menu_btn_title;
    public string $webapp_url;
    public array $admin_list;
    public array $cskh_list;
    public bool $is_send_start_msg;
    public ?string $start_message_content;
    public ?string $start_message_image;
    public ?string $start_msg_photo_id;


    public static function group(): string
    {
        return 'telegram';
    }
}
