<?php

namespace App\Entity;

final class Game
{
    public ?PlayerValue $winner = null;
    public bool $isFinished = false;

    public function __construct(
        public private(set) readonly Grid $grid,
        public private(set) readonly int $winningCoinsCount,
        public PlayerValue $currentPlayer,
    ) {
    }
}
