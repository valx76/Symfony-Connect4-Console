<?php

namespace App\Tests\Service;

use App\Entity\Grid;
use App\Entity\PlayerValue;
use App\Service\GridFormatter;
use PHPUnit\Framework\TestCase;

class GridFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $grid = new Grid(3, 4);
        $grid->setValueAtPosition(0, 0, PlayerValue::RED);
        $grid->setValueAtPosition(1, 2, PlayerValue::YELLOW);

        $gridFormatter = new GridFormatter();
        $this->assertSame([
            ['R', '', ''],
            ['', '', 'Y'],
            ['', '', ''],
            ['', '', ''],
        ], $gridFormatter->format($grid, 'R', 'Y'));
    }
}
