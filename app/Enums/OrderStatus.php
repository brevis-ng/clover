<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = "pending";
    case PROCESSING = "processing";
    case SHIPPED = "shipped";
    case CANCELLED = "cancelled";
    case COMPLETED = "completed";
    case FAILED = "failed";

    public static function all(): array
    {
        $res = [];
        foreach (self::cases() as $case) {
            $res[$case->value] = __("order." . $case->value);
        }
        return $res;
    }
}
