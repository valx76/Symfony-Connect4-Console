<?php

namespace App\Entity;

final class Grid
{
    /**
     * @var array<array<?PlayerValue>>
     */
    public private(set) array $values = [];

    public function __construct(
        public private(set) readonly int $width,
        public private(set) readonly int $height,
    ) {
        for ($rowIndex = 0; $rowIndex < $height; ++$rowIndex) {
            $this->values[$rowIndex] = array_fill(0, $width, null);
        }
    }

    public function isFull(): bool
    {
        return 0 === array_reduce(
            $this->values,
            /** @param array<?PlayerValue> $row */
            fn (int $carry, array $row) => $carry + array_sum(
                array_map(fn (?PlayerValue $value) => (null === $value) ? 1 : 0, $row)
            ),
            0
        );
    }

    public function isColumnFull(int $columnIndex): bool
    {
        if ($columnIndex < 0 || $columnIndex >= $this->width) {
            throw new \InvalidArgumentException('Invalid column index');
        }

        return null !== $this->values[0][$columnIndex];
    }

    public function setValueAtPosition(int $rowIndex, int $columnIndex, PlayerValue $value): void
    {
        if ($rowIndex < 0 || $rowIndex >= $this->height || $columnIndex < 0 || $columnIndex >= $this->width) {
            throw new \InvalidArgumentException('Invalid position');
        }

        $this->values[$rowIndex][$columnIndex] = $value;
    }
}
