<?php

namespace Snowcap\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Snowcap\CoreBundle\Util\PasswordStrengthChecker;


/**
 * Validator based on the Symfony1 snippet to validate password strength
 *
 * @link http://snippets.symfony-project.org/snippet/235
 */
class PasswordStrengthValidator extends ConstraintValidator
{
    /**
     * @param string                                  $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        if (null !== $constraint->min && strlen($value) < $constraint->min) {
            $this->context->addViolation($constraint->minMessage, array('{{ limit }}' => $constraint->min));
            return;
        }
        if (null !== $constraint->max && strlen($value) > $constraint->max) {
            $this->context->addViolation($constraint->maxMessage, array('{{ limit }}' => $constraint->max));
            return;
        }

        $checker = new PasswordStrengthChecker();
        $score = $checker->getStrength($value);

        if ($score < $constraint->score) {
            $this->context->addViolation($constraint->scoreMessage);
        }
    }
}
