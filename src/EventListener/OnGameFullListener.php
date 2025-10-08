<?php

namespace App\EventListener;

use App\Event\OnGameFull;
use App\Service\GameState;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final readonly class OnGameFullListener
{
    public function __construct(
        private GameState $gameState,
    ) {
    }

    public function __invoke(OnGameFull $event): void
    {
        $this->gameState->game->isFinished = true;
    }
}
