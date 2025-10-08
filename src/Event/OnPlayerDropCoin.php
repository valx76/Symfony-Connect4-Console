<?php

namespace App\Event;

use App\Entity\PlayerValue;
use Symfony\Contracts\EventDispatcher\Event;

final class OnPlayerDropCoin extends Event
{
    public function __construct(
        public readonly PlayerValue $player,
        public readonly int $columnIndex,
    ) {
    }
}
