<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add("telegram.bot_token", "5931240678:AAGE40ErCdxa7xzKxUr5sYHVq0RLSH4nTu0");
        $this->migrator->add("telegram.bot_username", "cloverbot");
        $this->migrator->add("telegram.webapp_inline_button", "Order Food");
        $this->migrator->add("telegram.webapp_menu_button", "Order Food");
        $this->migrator->add("telegram.webapp_url", route("frontend.index"));
        $this->migrator->add("telegram.administrators", [6246702463]);
        $this->migrator->add("telegram.customers_support", [6246702463]);
        $this->migrator->add("telegram.should_send_start_msg", true);
        $this->migrator->add("telegram.start_msg_content", "Hello World!");
        $this->migrator->add("telegram.start_msg_photo", "");
        $this->migrator->add("telegram.start_msg_photo_id", "");
    }
};
