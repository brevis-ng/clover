<?php

namespace App\Enums;

enum Roles: string
{
    case ADMIN = "Administrator";
    case ASSISTANT = "Assistant";

    public static function all(): array
    {
        $res = [];
        foreach (self::cases() as $case) {
            $res[$case->value] = __("customer.roles." . $case->value);
        }
        return $res;
    }

    // public static function trans(self $status): string
    // {
    //     return __("order.s." . $status->value);
    // }
}
