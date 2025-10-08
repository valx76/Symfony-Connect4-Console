<?php

namespace App\Tests\Service;

use App\Entity\Game;
use App\Entity\Grid;
use App\Entity\PlayerValue;
use App\Service\GameState;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GameStateTest extends TestCase
{
    public function testIsWinningLastMoveExceptionWhenGameIsNull(): void
    {
        $gameState = new GameState();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Game not set');
        $gameState->isWinningLastMove(PlayerValue::RED, 0);
    }

    /**
     * @param array<array<?PlayerValue>> $state
     */
    #[DataProvider('isWinningLastMoveProvider')]
    public function testIsWinningLastMove(array $state, int $lastCol, bool $expected): void
    {
        $grid = new Grid(7, 6);
        $game = new Game($grid, 4, PlayerValue::RED);

        $gameState = new GameState();
        $gameState->game = $game;

        foreach ($state as $row => $rowValues) {
            foreach ($rowValues as $col => $value) {
                if (null === $value) {
                    continue;
                }

                $grid->setValueAtPosition($row, $col, $value);
            }
        }

        $this->assertSame($expected, $gameState->isWinningLastMove(PlayerValue::RED, $lastCol));
    }

    public static function isWinningLastMoveProvider(): \Generator
    {
        yield [[
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
        ], 0, false];
        yield [[
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [PlayerValue::RED, PlayerValue::RED, PlayerValue::RED, PlayerValue::YELLOW, null, null, null],
        ], 0, false];
        yield [[
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null],
            [PlayerValue::RED, PlayerValue::RED, PlayerValue::RED, PlayerValue::RED, null, null, null],
        ], 0, true];

        yield [[
            [null, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
            [PlayerValue::YELLOW, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
        ], 0, false];
        yield [[
            [null, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
        ], 0, true];

        yield [[
            [null, null, null, null, null, null, null],
            [null, null, null, PlayerValue::RED, null, null, null],
            [null, null, PlayerValue::YELLOW, null, null, null, null],
            [null, PlayerValue::RED, PlayerValue::YELLOW, null, null, null, null],
            [PlayerValue::RED, PlayerValue::YELLOW, null, null, null, null, null],
        ], 0, false];
        yield [[
            [null, null, null, null, null, null, null],
            [null, null, null, PlayerValue::RED, null, null, null],
            [null, null, PlayerValue::RED, null, null, null, null],
            [null, PlayerValue::RED, PlayerValue::YELLOW, null, null, null, null],
            [PlayerValue::RED, PlayerValue::YELLOW, PlayerValue::YELLOW, null, null, null, null],
        ], 0, true];

        yield [[
            [null, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
            [PlayerValue::YELLOW, PlayerValue::RED, null, null, null, null, null],
            [PlayerValue::YELLOW, PlayerValue::RED, PlayerValue::YELLOW, null, null, null, null],
            [PlayerValue::RED, PlayerValue::YELLOW, PlayerValue::YELLOW, PlayerValue::RED, null, null, null],
        ], 0, false];
        yield [[
            [null, null, null, null, null, null, null],
            [PlayerValue::RED, null, null, null, null, null, null],
            [PlayerValue::YELLOW, PlayerValue::RED, null, null, null, null, null],
            [PlayerValue::YELLOW, PlayerValue::RED, PlayerValue::RED, null, null, null, null],
            [PlayerValue::RED, PlayerValue::YELLOW, PlayerValue::YELLOW, PlayerValue::RED, PlayerValue::YELLOW, null, null],
        ], 0, true];
    }
}
