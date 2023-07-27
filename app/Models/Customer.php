<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    protected $casts = ["started_at" => "datetime", "blocked_at" => "datetime"];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, "chat_id", "id");
    }
}
