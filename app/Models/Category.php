<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ["name", "image", "is_visible"];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ["is_visible" => "boolean"];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleted(function (Category $category) {
            // ...
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
