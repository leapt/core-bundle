<?php

namespace Snowcap\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordStrength extends Constraint
{
    /**
     * @var string
     */
    public $minMessage = 'This password is too short. It should have {{ limit }} characters or more.';
    /**
     * @var string
     */
    public $maxMessage = 'This password is too long. It should have {{ limit }} characters or less.';
    /**
     * @var string
     */
    public $scoreMessage = 'This password is not strong enough.';
    /**
     * @var int
     */
    public $min;
    /**
     * @var int
     */
    public $max;
    /**
     * @var int
     */
    public $score = 50;
}
