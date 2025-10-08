<?php

namespace App\Tests\EventListener;

use App\Entity\Game;
use App\Entity\Grid;
use App\Entity\PlayerValue;
use App\Event\OnGameFull;
use App\Event\OnPlayerWin;
use App\EventListener\OnGameFullListener;
use App\EventListener\OnPlayerWinListener;
use App\Service\GameState;
use PHPUnit\Framework\TestCase;

class OnPlayerWinListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $grid = new Grid(3, 3);
        $game = new Game($grid, 3, PlayerValue::YELLOW);

        $gameState = new GameState();
        $gameState->game = $game;

        $listener = new OnPlayerWinListener($gameState);

        $this->assertNull($gameState->game->winner);
        $this->assertFalse($gameState->game->isFinished);
        $listener(new OnPlayerWin());
        $this->assertTrue($gameState->game->isFinished);
        $this->assertSame(PlayerValue::YELLOW, $gameState->game->winner);
    }
}
