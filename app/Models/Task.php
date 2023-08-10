<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

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

    public function getImage(): InputFile|null
    {
        if ($this->image && Storage::disk("tasks")->exists($this->image)) {
            $img_data = fopen(Storage::disk("tasks")->path($this->image), "r+");

            return InputFile::make($img_data);
        }

        return null;
    }

    protected static function booted(): void
    {
        static::deleted(function (Product $product) {
            $image = $product->image;
            if (Storage::disk("tasks")->exists($image)) {
                Storage::disk("tasks")->delete($image);
            }
        });
        static::updating(function (Product $product) {
            if ($product->isDirty("image")) {
                $image = $product->getOriginal("image");
                if (Storage::disk("tasks")->exists($image)) {
                    Storage::disk("tasks")->delete($image);
                }
            }
        });
    }
}
