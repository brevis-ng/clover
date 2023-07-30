<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = ["name", "price", "created_at", "updated_at"];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
