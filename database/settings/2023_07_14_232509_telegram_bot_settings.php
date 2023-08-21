<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add("telegram.bot_token", "");
        $this->migrator->add("telegram.bot_username", "cloverbot");
        $this->migrator->add("telegram.webapp_inline_button", "Order Food");
        $this->migrator->add("telegram.webapp_menu_button", "Order Food");
        $this->migrator->add("telegram.webapp_url", route("frontend.index"));
        $this->migrator->add("telegram.start_msg_content", "Hello World!");
        $this->migrator->add("telegram.start_msg_photo", "");
    }
};
