<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class LongitudeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_float($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ($value < Longitude::MIN || $value > Longitude::MAX) {
            /** @var Longitude $constraint */
            $this->context->buildViolation(message: $constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}