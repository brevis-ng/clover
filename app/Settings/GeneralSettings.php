<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $bot_token;

    public static function group(): string
    {
        return 'general';
    }
}
