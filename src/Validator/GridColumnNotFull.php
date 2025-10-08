<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class GridColumnNotFull extends Constraint
{
    public function __construct(
        public readonly string $message = 'The column {{ column }} is full!',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }
}
