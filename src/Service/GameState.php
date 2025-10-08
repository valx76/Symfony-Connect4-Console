<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\PlayerValue;

final class GameState
{
    public Game $game {
        get => $this->game ?? throw new \RuntimeException('Game not set');
    }

    public function isWinningLastMove(PlayerValue $player, int $columnIndex): bool
    {
        $width = $this->game->grid->width;
        $height = $this->game->grid->height;
        $winningCoinsCount = $this->game->winningCoinsCount;

        // Find the row of the move
        $rowIndex = 0;
        for ($currentRowIndex = 0; $currentRowIndex < count($this->game->grid->values); ++$currentRowIndex) {
            if (null !== $this->game->grid->values[$currentRowIndex][$columnIndex]) {
                $rowIndex = $currentRowIndex;
                break;
            }
        }

        // Horizontal
        $consecutiveValues = 1;
        for ($i = $columnIndex + 1; $i < $width && $consecutiveValues < $winningCoinsCount; ++$i) {
            if ($this->game->grid->values[$rowIndex][$i] !== $player) {
                break;
            }
            ++$consecutiveValues;
        }
        for ($i = $columnIndex - 1; $i >= 0 && $consecutiveValues < $winningCoinsCount; --$i) {
            if ($this->game->grid->values[$rowIndex][$i] !== $player) {
                break;
            }
            ++$consecutiveValues;
        }

        if ($consecutiveValues >= $winningCoinsCount) {
            return true;
        }

        // Vertical
        $consecutiveValues = 1;
        for ($i = $rowIndex + 1; $i < $height && $consecutiveValues < $winningCoinsCount; ++$i) {
            if ($this->game->grid->values[$i][$columnIndex] !== $player) {
                break;
            }
            ++$consecutiveValues;
        }
        for ($i = $rowIndex - 1; $i >= 0 && $consecutiveValues < $winningCoinsCount; --$i) {
            if ($this->game->grid->values[$i][$columnIndex] !== $player) {
                break;
            }
            ++$consecutiveValues;
        }

        if ($consecutiveValues >= $winningCoinsCount) {
            return true;
        }

        // Diagonal TL-BR
        $consecutiveValues = 1;
        for ($i = $rowIndex + 1; $i < $height && ($columnIndex + ($i - $rowIndex)) < $width && $consecutiveValues < $winningCoinsCount; ++$i) {
            if ($this->game->grid->values[$i][$columnIndex + ($i - $rowIndex)] !== $player) {
                break;
            }
            ++$consecutiveValues;
        }
        for ($i = $rowIndex - 1; $i >= 0 && ($columnIndex + ($i - $rowIndex)) >= 0 && $consecutiveValues < $winningCoinsCount; --$i) {
            if ($this->game->grid->values[$i][$columnIndex + ($i - $rowIndex)] !== $player) {
                break;
            }
            ++$consecutiveValues;
        }

        if ($consecutiveValues >= $winningCoinsCount) {
            return true;
        }

        // Diagonal BL-TR
        $consecutiveValues = 1;
        for ($i = $rowIndex - 1; $i >= 0 && ($columnIndex - ($i - $rowIndex)) < $width && $consecutiveValues < $winningCoinsCount; --$i) {
            if ($this->game->grid->values[$i][$columnIndex - ($i - $rowIndex)] !== $player) {
                break;
            }
            ++$consecutiveValues;
        }
        for ($i = $rowIndex + 1; $i < $height && ($columnIndex - ($i - $rowIndex)) >= 0 && $consecutiveValues < $winningCoinsCount; ++$i) {
            if ($this->game->grid->values[$i][$columnIndex - ($i - $rowIndex)] !== $player) {
                break;
            }
            ++$consecutiveValues;
        }

        if ($consecutiveValues >= $winningCoinsCount) {
            return true;
        }

        return false;
    }
}
