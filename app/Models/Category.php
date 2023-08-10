<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
     * Scope a query to only include active categories.
     */
    public function scopeVisibility(Builder $query): void
    {
        $query->where("is_visible", true);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleted(function (Category $category) {
            $image = $category->image;
            if ($image && Storage::disk("categories")->exists($image)) {
                Storage::disk("categories")->delete($image);
            }
        });
        static::updating(function (Category $category) {
            if ($category->isDirty("image")) {
                $image = $category->getOriginal("image");
                if (Storage::disk("categories")->exists($image)) {
                    Storage::disk("categories")->delete($image);
                }
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
