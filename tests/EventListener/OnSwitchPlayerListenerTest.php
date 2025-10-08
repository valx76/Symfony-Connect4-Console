<?php

namespace App\Tests\EventListener;

use App\Entity\Game;
use App\Entity\Grid;
use App\Entity\PlayerValue;
use App\Event\OnSwitchPlayer;
use App\EventListener\OnSwitchPlayerListener;
use App\Service\GameState;
use PHPUnit\Framework\TestCase;

class OnSwitchPlayerListenerTest extends TestCase
{
    public function testInvoke(): void
    {
        $grid = new Grid(3, 3);
        $game = new Game($grid, 3, PlayerValue::RED);

        $gameState = new GameState();
        $gameState->game = $game;

        $listener = new OnSwitchPlayerListener($gameState);

        $this->assertSame(PlayerValue::RED, $gameState->game->currentPlayer);
        $listener(new OnSwitchPlayer());
        $this->assertSame(PlayerValue::YELLOW, $gameState->game->currentPlayer);
        $listener(new OnSwitchPlayer());
        $this->assertSame(PlayerValue::RED, $gameState->game->currentPlayer);
    }
}
