<?php

namespace App\EventListener;

use App\Entity\PlayerValue;
use App\Event\OnSwitchPlayer;
use App\Service\GameState;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final readonly class OnSwitchPlayerListener
{
    public function __construct(
        private GameState $gameState,
    ) {
    }

    public function __invoke(OnSwitchPlayer $event): void
    {
        $game = $this->gameState->game;

        $nextPlayer = PlayerValue::tryFrom($game->currentPlayer->value + 1) ?? PlayerValue::getFirst();
        $game->currentPlayer = $nextPlayer;
    }
}
