<?php

namespace App\Models;

use App\Enums\Units;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "code",
        "category_id",
        "price",
        "old_price",
        "cost",
        "unit",
        "image",
        "description",
        "is_visible",
        "remarks",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        "is_visible" => "boolean",
        "unit" => Units::class,
    ];

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
        static::deleted(function (Product $product) {
            // ...
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot([
            "quantity",
            "amount",
        ]);
    }
}
