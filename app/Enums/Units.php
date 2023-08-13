<?php

namespace App\Enums;

enum Units: string
{
    case NONE = "none";
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
        }
        return $res;
    }

    public function getTrans(): string
    {
        return $this->name == static::NONE
            ? ""
            : "/" . __("product.units.{$this->value}");
    }
}
