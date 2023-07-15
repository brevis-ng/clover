<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add("telegram.bot_token", "5931240678:AAGE40ErCdxa7xzKxUr5sYHVq0RLSH4nTu0");
        $this->migrator->add("telegram.bot_username", "cloverbot");
        $this->migrator->add("telegram.inline_btn_title", "Order Food");
        $this->migrator->add("telegram.menu_btn_title", "Order Food");
        $this->migrator->add("telegram.webapp_url", route("frontend.index"));
        $this->migrator->add("telegram.admin_list", [6246702463]);
        $this->migrator->add("telegram.cskh_list", [6246702463]);
        $this->migrator->add("telegram.is_send_start_msg", false);
        $this->migrator->add("telegram.start_message_content", "");
        $this->migrator->add("telegram.start_message_image", "");
        $this->migrator->add("telegram.start_msg_photo_id", "");
    }
};
