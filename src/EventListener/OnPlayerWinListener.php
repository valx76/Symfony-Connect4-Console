<?php

namespace App\EventListener;

use App\Event\OnPlayerWin;
use App\Service\GameState;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final readonly class OnPlayerWinListener
{
    public function __construct(
        private GameState $gameState,
    ) {
    }

    public function __invoke(OnPlayerWin $event): void
    {
        $this->gameState->game->winner = $this->gameState->game->currentPlayer;
        $this->gameState->game->isFinished = true;
    }
}
