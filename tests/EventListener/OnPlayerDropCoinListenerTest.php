<?php

namespace App\Tests\EventListener;

use App\Entity\Game;
use App\Entity\Grid;
use App\Entity\PlayerValue;
use App\Event\OnPlayerDropCoin;
use App\EventListener\OnPlayerDropCoinListener;
use App\Service\GameState;
use PHPUnit\Framework\TestCase;

class OnPlayerDropCoinListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $grid = new Grid(3, 3);
        $grid->setValueAtPosition(2, 1, PlayerValue::YELLOW);

        $game = new Game($grid, 3, PlayerValue::RED);

        $gameState = new GameState();
        $gameState->game = $game;

        $listener = new OnPlayerDropCoinListener($gameState);

        $this->assertSame(null, $grid->values[1][1]);
        $listener(new OnPlayerDropCoin(PlayerValue::RED, 1));
        $this->assertSame(PlayerValue::RED, $grid->values[1][1]);

        $this->assertSame(null, $grid->values[2][0]);
        $listener(new OnPlayerDropCoin(PlayerValue::YELLOW, 0));
        $this->assertSame(PlayerValue::YELLOW, $grid->values[2][0]);
    }
}
