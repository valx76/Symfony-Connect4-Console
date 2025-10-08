<?php

namespace App\Validator;

use App\Service\GameState;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class GridColumnNotFullValidator extends ConstraintValidator
{
    public function __construct(
        private readonly GameState $gameState,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof GridColumnNotFull) {
            throw new UnexpectedTypeException($constraint, GridColumnNotFull::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_numeric($value)) {
            throw new UnexpectedValueException($value, 'int');
        }

        if (!$this->gameState->game->grid->isColumnFull((int) $value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ column }}', (string) $value)
            ->addViolation();
    }
}
