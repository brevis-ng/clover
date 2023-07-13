<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "customer_id",
        "status",
        "address",
        "total_amount",
        "shipping_amount",
        "payment_method",
        "notes",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        "status" => OrderStatus::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot([
            "quantity",
            "amount",
        ]);
    }
}
