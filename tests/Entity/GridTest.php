<?php

namespace App\Tests\Entity;

use App\Entity\Grid;
use App\Entity\PlayerValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{
    /**
     * @param array<array{row:int, col:int, value:PlayerValue}> $moves
     * @param array<array<?PlayerValue>>                        $expected
     */
    #[DataProvider('setValueAtPositionProvider')]
    public function testSetValueAtPosition(array $moves, array $expected): void
    {
        $grid = new Grid(3, 3);

        foreach ($moves as $move) {
            $grid->setValueAtPosition($move['row'], $move['col'], $move['value']);
        }

        $this->assertSame($expected, $grid->values);
    }

    public function testSetValueNegativeRowException(): void
    {
        $grid = new Grid(3, 3);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid position');
        $grid->setValueAtPosition(-1, 0, PlayerValue::RED);
    }

    public function testSetValueInvalidRowException(): void
    {
        $grid = new Grid(3, 3);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid position');
        $grid->setValueAtPosition(3, 0, PlayerValue::RED);
    }

    public function testSetValueNegativeColException(): void
    {
        $grid = new Grid(3, 3);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid position');
        $grid->setValueAtPosition(0, -1, PlayerValue::RED);
    }

    public function testSetValueInvalidColException(): void
    {
        $grid = new Grid(3, 3);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid position');
        $grid->setValueAtPosition(0, 3, PlayerValue::RED);
    }

    public function testColumnFullNegativeColException(): void
    {
        $grid = new Grid(3, 3);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid column index');
        $grid->isColumnFull(-1);
    }

    public function testColumnFullInvalidColException(): void
    {
        $grid = new Grid(3, 3);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid column index');
        $grid->isColumnFull(3);
    }

    /**
     * @param array<array<?PlayerValue>> $state
     * @param array<bool>                $expected
     */
    #[DataProvider('isColumnFullProvider')]
    public function testIsColumnFull(array $state, array $expected): void
    {
        $grid = new Grid(3, 3);

        foreach ($state as $row => $rowValues) {
            foreach ($rowValues as $col => $value) {
                if (null === $value) {
                    continue;
                }

                $grid->setValueAtPosition($row, $col, $value);
            }
        }

        foreach ($expected as $col => $expectedValue) {
            $this->assertSame($expectedValue, $grid->isColumnFull($col));
        }
    }

    /**
     * @param array<array<?PlayerValue>> $state
     */
    #[DataProvider('isFullProvider')]
    public function testIsFull(array $state, bool $expected): void
    {
        $grid = new Grid(3, 3);

        foreach ($state as $row => $rowValues) {
            foreach ($rowValues as $col => $value) {
                if (null === $value) {
                    continue;
                }

                $grid->setValueAtPosition($row, $col, $value);
            }
        }

        $this->assertSame($expected, $grid->isFull());
    }

    public static function setValueAtPositionProvider(): \Generator
    {
        yield [[], [[null, null, null], [null, null, null], [null, null, null]]];
        yield [[['row' => 0, 'col' => 1, 'value' => PlayerValue::RED]], [[null, PlayerValue::RED, null], [null, null, null], [null, null, null]]];
        yield [[['row' => 2, 'col' => 1, 'value' => PlayerValue::YELLOW]], [[null, null, null], [null, null, null], [null, PlayerValue::YELLOW, null]]];
        yield [[['row' => 1, 'col' => 1, 'value' => PlayerValue::YELLOW], ['row' => 0, 'col' => 2, 'value' => PlayerValue::RED]], [[null, null, PlayerValue::RED], [null, PlayerValue::YELLOW, null], [null, null, null]]];
    }

    public static function isColumnFullProvider(): \Generator
    {
        yield [[[null, null, null], [null, null, null], [null, null, null]], [false, false, false]];
        yield [[[null, null, null], [null, null, null], [PlayerValue::RED, null, null]], [false, false, false]];
        yield [[[null, null, null], [PlayerValue::RED, null, null], [PlayerValue::RED, null, null]], [false, false, false]];
        yield [[[PlayerValue::RED, null, null], [PlayerValue::RED, null, null], [PlayerValue::RED, null, null]], [true, false, false]];
        yield [[[PlayerValue::YELLOW, PlayerValue::YELLOW, null], [PlayerValue::RED, PlayerValue::YELLOW, null], [PlayerValue::RED, PlayerValue::YELLOW, null]], [true, true, false]];
    }

    public static function isFullProvider(): \Generator
    {
        yield [[[null, null, null], [null, null, null], [null, null, null]], false];
        yield [[[PlayerValue::RED, null, null], [PlayerValue::RED, null, null], [PlayerValue::RED, null, null]], false];
        yield [[[PlayerValue::RED, PlayerValue::YELLOW, null], [PlayerValue::RED, PlayerValue::YELLOW, null], [PlayerValue::RED, PlayerValue::YELLOW, null]], false];
        yield [[[PlayerValue::RED, PlayerValue::YELLOW, PlayerValue::YELLOW], [PlayerValue::RED, PlayerValue::YELLOW, PlayerValue::YELLOW], [PlayerValue::RED, PlayerValue::YELLOW, PlayerValue::YELLOW]], true];
    }
}
