<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\Roles;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommand;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommandScopeChat;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        try {
            if ($this->data["role"] == Roles::ADMIN->value) {
                Telegram::deleteMyCommands(
                    new BotCommandScopeChat($this->data["telegram_id"])
                );
                Telegram::setMyCommands(
                    [
                        BotCommand::make("order", "Manage order /order orderNo"),
                        BotCommand::make("cache", "Clear cache"),
                        BotCommand::make("help", "Help message"),
                        BotCommand::make("cancel", "Cancel"),
                    ],
                    new BotCommandScopeChat($this->data["telegram_id"])
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
