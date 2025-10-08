<?php

namespace App\Entity;

enum PlayerValue: int
{
    case RED = 1;
    case YELLOW = 2;

    public static function getFirst(): self
    {
        return self::RED;
    }
}
