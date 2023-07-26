<?php

namespace App\Enums;

enum Units: string
{
    case NONE = "";
    case PIECE = "piece";
    case PAIR = "pair";
    case BOX = "box";
    case KG = "kg";
    case PACK = "pack";
    case SET = "set";

    public static function all(): array
    {
        $res = [];
        foreach (self::cases() as $case) {
            $res[$case->value] = __("product.units." . $case->value);
            if ($case == self::NONE) {
                $res[$case->value] = "None";
            };
        }
        return $res;
    }

    public static function getTranslation(Units $units): string
    {
        return __("product.units.{$units->value}");
    }
}
