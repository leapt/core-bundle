<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Slug extends Regex
{
    public function __construct(
        string|array|null $pattern = '/^([a-z0-9]+)(?:-[a-z0-9]+)*$/',
        string $message = 'A slug can only contain lowercase letters, numbers and hyphens.',
        ?string $htmlPattern = null,
        ?bool $match = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
        ?array $options = null,
    ) {
        if (null !== $options) {
            trigger_deprecation('leapt/core-bundle', '5.6.1', 'Passing an array of options to configure the "%s" constraint is deprecated, use named arguments instead.', static::class);
            parent::__construct($pattern, $message, $htmlPattern, $match, $normalizer, $groups, $payload, $options);
        } else {
            parent::__construct($pattern, $message, $htmlPattern, $match, $normalizer, $groups, $payload);
        }
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
