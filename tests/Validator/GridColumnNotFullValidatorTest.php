<?php

namespace App\Tests\Validator;

use App\Entity\Game;
use App\Entity\Grid;
use App\Entity\PlayerValue;
use App\Service\GameState;
use App\Validator\GridColumnNotFull;
use App\Validator\GridColumnNotFullValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<GridColumnNotFullValidator>
 */
class GridColumnNotFullValidatorTest extends ConstraintValidatorTestCase
{
    private GameState $gameState;

    protected function setUp(): void
    {
        $this->gameState = new GameState();

        parent::setUp();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new GridColumnNotFullValidator($this->gameState);
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new GridColumnNotFull());

        $this->assertNoViolation();
    }

    public function testExceptionWhenNonNumericValue(): void
    {
        $constraint = new GridColumnNotFull();

        $this->expectException(UnexpectedValueException::class);
        $this->validator->validate('abc', $constraint);
    }

    public function testGridColumnIsFull(): void
    {
        $grid = new Grid(3, 3);
        $grid->setValueAtPosition(0, 0, PlayerValue::RED);
        $grid->setValueAtPosition(1, 0, PlayerValue::YELLOW);
        $grid->setValueAtPosition(2, 0, PlayerValue::RED);

        $game = new Game($grid, 3, PlayerValue::RED);
        $this->gameState->game = $game;

        $columnIndex = 0;
        $constraint = new GridColumnNotFull();

        $this->validator->validate($columnIndex, $constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('{{ column }}', (string) $columnIndex)
            ->assertRaised();
    }

    public function testGridColumnIsNotFull(): void
    {
        $grid = new Grid(3, 3);
        $grid->setValueAtPosition(1, 1, PlayerValue::YELLOW);
        $grid->setValueAtPosition(2, 1, PlayerValue::RED);

        $game = new Game($grid, 3, PlayerValue::RED);
        $this->gameState->game = $game;

        $columnIndex = 1;
        $constraint = new GridColumnNotFull();

        $this->validator->validate($columnIndex, $constraint);

        $this->assertNoViolation();
    }
}
