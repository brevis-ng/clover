<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.bot_token', '5931240678:AAGE40ErCdxa7xzKxUr5sYHVq0RLSH4nTu0');
    }
};
