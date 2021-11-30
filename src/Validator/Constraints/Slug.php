<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Slug extends Regex
{
    public $message = 'A slug can only contain lowercase letters, numbers and hyphens.';

    public $pattern = '/^([a-z0-9-]+)$/';

    public function __construct(string $pattern = null, string $message = null, string $htmlPattern = null, bool $match = null, callable $normalizer = null, array $groups = null, $payload = null, array $options = [])
    {
        parent::__construct($pattern ?? $this->pattern, $message ?? $this->message, $htmlPattern, $match, $normalizer, $groups, $payload, $options);
    }

    public function getRequiredOptions(): array
    {
        return [];
    }

    public function validatedBy(): string
    {
        return RegexValidator::class;
    }
}
