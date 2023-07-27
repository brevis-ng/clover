<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ["name", "content", "image", "cron"];

    public function chat()
    {
        return $this->belongsTo(Customer::class, "chat_id", "id");
    }
}
