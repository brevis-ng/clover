<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Roles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Nutgram\Laravel\Facades\Telegram;
use Laravel\Sanctum\HasApiTokens;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommand;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommandScopeChat;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ["name", "telegram_id", "email", "role", "password"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // protected $hidden = ["password", "remember_token"];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "email_verified_at" => "datetime",
        "password" => "hashed",
        "role" => Roles::class,
    ];

    public function canAccessFilament(): bool
    {
        return $this->role == Roles::ADMIN;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function getTelegramUrl(): ?string
    {
        return "tg://user?id=" . $this->telegram_id;
    }

    public function scopeAdmin($query): void
    {
        $query->where("role", Roles::ADMIN);
    }

    public function scopeAssistant($query): void
    {
        $query->where("role", Roles::ASSISTANT);
    }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            try {
                if ($user->role == Roles::ADMIN && $user->telegram_id) {
                    Telegram::deleteMyCommands(
                        new BotCommandScopeChat($user->telegram_id)
                    );
                    Telegram::setMyCommands(
                        [
                            BotCommand::make("order", "Manage order"),
                            BotCommand::make("cache", "Clear cache"),
                            BotCommand::make("help", "Help message"),
                            BotCommand::make("cancel", "Cancel"),
                        ],
                        new BotCommandScopeChat($user->telegram_id)
                    );
                }
            } catch (\Throwable $e) {
                Log::critical("{class} Error in line {line}: {message}", [
                    "class" => self::class,
                    "line" => $e->getLine(),
                    "message" => $e->getMessage(),
                ]);
            }
        });
        static::deleted(function (User $user) {
            try {
                if ($user->role == Roles::ADMIN && $user->telegram_id) {
                    Telegram::deleteMyCommands(
                        new BotCommandScopeChat($user->telegram_id)
                    );
                }
            } catch (\Throwable $e) {
                Log::critical("{class} Error in line {line}: {message}", [
                    "class" => self::class,
                    "line" => $e->getLine(),
                    "message" => $e->getMessage(),
                ]);
            }
        });
        static::updating(function (User $user) {
            try {
                if (
                    $user->isDirty("telegram_id") ||
                    $user->role == Roles::ASSISTANT
                ) {
                    $chat_id = $user->getOriginal("telegram_id") ?? $user->telegram_id;

                    Telegram::deleteMyCommands(new BotCommandScopeChat($chat_id));
                }

                if ($user->role == Roles::ADMIN) {
                    Telegram::setMyCommands(
                        [
                            BotCommand::make("order", "Manage order"),
                            BotCommand::make("cache", "Clear cache"),
                            BotCommand::make("help", "Help message"),
                            BotCommand::make("cancel", "Cancel"),
                        ],
                        new BotCommandScopeChat($user->telegram_id)
                    );
                }
            } catch (\Throwable $e) {
                Log::critical("{class} Error in line {line}: {message}", [
                    "class" => self::class,
                    "line" => $e->getLine(),
                    "message" => $e->getMessage(),
                ]);
            }
        });
    }
}
