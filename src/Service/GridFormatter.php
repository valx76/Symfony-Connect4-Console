<?php

namespace App\Service;

use App\Entity\Grid;
use App\Entity\PlayerValue;

class GridFormatter
{
    /**
     * @return array<array<string>>
     */
    public function format(Grid $grid, string $redTag, string $yellowTag): array
    {
        return array_map(
            /**
             * @param array<?PlayerValue> $row
             */
            fn (array $row) => array_map(
                fn (?PlayerValue $cell) => match ($cell) {
                    PlayerValue::RED => $redTag,
                    PlayerValue::YELLOW => $yellowTag,
                    default => '',
                },
                $row
            ),
            $grid->values
        );
    }
}
