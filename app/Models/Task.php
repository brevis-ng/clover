<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        "chat_id",
        "name",
        "content",
        "image",
        "cron",
        "enabled",
    ];

    protected $casts = ["enabled" => "boolean"];

    public function scopeEnabled($query): void
    {
        $query->where("enabled", true);
    }

    public function chat()
    {
        return $this->belongsTo(Customer::class, "chat_id", "id");
    }

    public function getContent(): string
    {
        $elements = ["<p>", "</p>"];
        $replaces = ["", "\n"];

        return str_replace($elements, $replaces, $this->content);
    }
}
