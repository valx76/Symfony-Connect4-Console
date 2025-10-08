<?php

namespace App\Tests\EventListener;

use App\Entity\Game;
use App\Entity\Grid;
use App\Entity\PlayerValue;
use App\Event\OnGameFull;
use App\EventListener\OnGameFullListener;
use App\Service\GameState;
use PHPUnit\Framework\TestCase;

class OnGameFullListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $grid = new Grid(3, 3);
        $game = new Game($grid, 3, PlayerValue::RED);

        $gameState = new GameState();
        $gameState->game = $game;

        $listener = new OnGameFullListener($gameState);

        $this->assertFalse($gameState->game->isFinished);
        $listener(new OnGameFull());
        $this->assertTrue($gameState->game->isFinished);
    }
}
