<?php

namespace App\Enums;

enum ProductStatus: string
{
    case INSTOCK = "instock";
    case SOLDOUT = "soldout";
    case ARRIVALS = "arrivals";

    public static function all(): array
    {
        $res = [];
        foreach (self::cases() as $case) {
            $res[$case->value] = __('admin.'.$case->value);
        }
        return $res;
    }
}
