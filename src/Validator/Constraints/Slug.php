<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Slug extends Regex
{
    public function __construct(
        string|array|null $pattern = '/^([a-z0-9-]+)$/',
        string $message = 'A slug can only contain lowercase letters, numbers and hyphens.',
        ?string $htmlPattern = null,
        ?bool $match = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ) {
        parent::__construct($pattern, $message, $htmlPattern, $match, $normalizer, $groups, $payload, $options);
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
