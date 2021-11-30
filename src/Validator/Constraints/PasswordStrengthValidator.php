<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Validator\Constraints;

use Leapt\CoreBundle\Util\PasswordStrengthChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validator based on the Symfony1 snippet to validate password strength.
 *
 * @see http://snippets.symfony-project.org/snippet/235
 */
class PasswordStrengthValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        \assert($constraint instanceof PasswordStrength);

        if (null === $value || '' === $value) {
            return;
        }
        if (null !== $constraint->min && \strlen($value) < $constraint->min) {
            $this->context->addViolation($constraint->minMessage, ['{{ limit }}' => $constraint->min]);

            return;
        }
        if (null !== $constraint->max && \strlen($value) > $constraint->max) {
            $this->context->addViolation($constraint->maxMessage, ['{{ limit }}' => $constraint->max]);

            return;
        }

        $checker = new PasswordStrengthChecker();
        $score = $checker->getStrength($value);

        if ($score < $constraint->score) {
            $this->context->addViolation($constraint->scoreMessage);
        }
    }
}
