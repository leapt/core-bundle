<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PasswordStrength extends Constraint
{
    public string $minMessage = 'This password is too short. It should have {{ limit }} characters or more.';
    public string $maxMessage = 'This password is too long. It should have {{ limit }} characters or less.';
    public string $scoreMessage = 'This password is not strong enough.';
    public ?int $min = null;
    public ?int $max = null;
    public int $score = 50;

    public function __construct(
        array $options = null, int $min = null, string $minMessage = null, int $max = null, string $maxMessage = null,
        int $score = null, string $scoreMessage = null, array $groups = null, $payload = null,
    ) {
        parent::__construct($options ?? [], $groups, $payload);

        $this->min = $min ?? $this->min;
        $this->minMessage = $minMessage ?? $this->minMessage;
        $this->max = $max ?? $this->max;
        $this->maxMessage = $maxMessage ?? $this->maxMessage;
        $this->score = $score ?? $this->score;
        $this->scoreMessage = $scoreMessage ?? $this->scoreMessage;
    }
}
