<?php

namespace App\Enums;

enum RelocationType: string{
    case MOVE_HOUSE = 'MOVE_HOUSE';
    case EMIGRATE = 'EMIGRATE';

    public static function options(): array
    {
        return [
            self::MOVE_HOUSE->value => self::MOVE_HOUSE->name,
            self::EMIGRATE->value => self::EMIGRATE->name,
        ];
    }
}
