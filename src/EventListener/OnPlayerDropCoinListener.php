<?php

namespace App\EventListener;

use App\Event\OnPlayerDropCoin;
use App\Service\GameState;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final readonly class OnPlayerDropCoinListener
{
    public function __construct(
        private GameState $gameState,
    ) {
    }

    public function __invoke(OnPlayerDropCoin $event): void
    {
        $game = $this->gameState->game;

        // The column should not be full at this point because it has been checked before

        for ($rowIndex = $game->grid->height - 1; $rowIndex >= 0; --$rowIndex) {
            if (null === $game->grid->values[$rowIndex][$event->columnIndex]) {
                $game->grid->setValueAtPosition($rowIndex, $event->columnIndex, $event->player);

                break;
            }
        }
    }
}
