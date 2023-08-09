<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SergiX44\Nutgram\Telegram\Properties\ChatType;

class Customer extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $fillable = [
        "id",
        "type",
        "name",
        "phone",
        "username",
        "language_code",
        "started_at",
        "blocked_at",
    ];
    protected $casts = [
        "started_at" => "datetime",
        "blocked_at" => "datetime",
        "type" => ChatType::class,
    ];

    public function scopeGroup($query): void
    {
        $query->whereIn("type", [ChatType::GROUP, ChatType::SUPERGROUP, ChatType::CHANNEL]);
    }

    public function getTelegramUrl(): ?string
    {
        if ($this->username) {
            return "https://t.me/" . $this->username;
        }

        return "tg://user?id=" . $this->id;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, "chat_id", "id");
    }
}
